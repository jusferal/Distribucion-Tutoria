<?php
include("conexion.php");
function get_pref_cod($codigo){
    if(strlen($codigo)==6) return $codigo[0]."".$codigo[1];
    else if(strlen($codigo)==5) return "0".$codigo[0];
    return "00";
}
function no_matriculados(){
    $con=conectar();
    $sql="SELECT DISTINCT cod_estudiante,nombres_apellidos FROM distribucion_tutoria D WHERE D.cod_estudiante not in( SELECT cod_estudiante FROM matriculados_2022 ); ";
    $query=mysqli_query($con,$sql);
    return mysqli_fetch_all($query);
}
function alumnos_sin_tutor(){
    $con=conectar();
    $sql="SELECT DISTINCT * FROM matriculados_2022  WHERE cod_estudiante not in( SELECT cod_estudiante FROM distribucion_tutoria ); ";
    $query=mysqli_query($con,$sql);
    return mysqli_fetch_all($query);
}
function distribucion_balanceada(){
    $con=conectar();
    $sql="SELECT DISTINCT * FROM distribucion_tutoria D WHERE D.cod_estudiante  in( SELECT cod_estudiante FROM matriculados_2022 ); ";
    $alumnos_con_tutor=mysqli_query($con,$sql);
    $sql="SELECT DISTINCT * FROM  docentes";
    $docentes=mysqli_query($con,$sql);
    $sql="SELECT DISTINCT * FROM matriculados_2022 ";
    $alumnos=mysqli_query($con,$sql);

    $total_alumnos=$alumnos->num_rows;
    $total_docentes=$docentes->num_rows;
    $num_max_alum=floor($total_alumnos/$total_docentes);
    $resto=$total_alumnos%$total_docentes;
    
    $data=array();
    $nuevos_alumnos=[];
    $nueva_distribucion=[];
    while($row = mysqli_fetch_assoc($alumnos_con_tutor)) { 
        $cod_alumno=get_pref_cod($row['cod_estudiante']);
        $docente=$row['nombres_apellidos_docente'];
        if(count($data)==0 OR !array_key_exists($docente,$data) OR !array_key_exists($cod_alumno,$data[$docente])){
            $data[$docente][$cod_alumno]=0;
        }
        if($data[ $row['nombres_apellidos_docente'] ][$cod_alumno]+1>$num_max_alum){
            array_push($nuevos_alumnos,[$cod_alumno,$row['nombres_apellidos'],$docente]);
        }
        else {
            array_push($nueva_distribucion,[$row['cod_estudiante'],$row['nombres_apellidos'],$docente]);
            $data[ $row['nombres_apellidos_docente'] ][$cod_alumno]+=1;
        }
    }
    $i=0;
    $alumnos_sin_tutor=alumnos_sin_tutor();
    while( $i<count($alumnos_sin_tutor)) { 
        foreach($data as $docente =>$codigos){
            $cantidad=0;
            if($resto>0)$add=1;
            else $add=0;
            foreach($codigos as $cod=>$len)$cantidad+=$len;
            while($i<count($alumnos_sin_tutor) AND $cantidad<$num_max_alum+$add){
                if (!array_key_exists($alumnos_sin_tutor[$i][0],$codigos)){
                    $codigos+=[$alumnos_sin_tutor[$i][0]=>0];
                }
                $codigos[$alumnos_sin_tutor[$i][0]]++;
                $cantidad++;
                array_push($nueva_distribucion,[$alumnos_sin_tutor[$i][0],$alumnos_sin_tutor[$i][1],$docente]);
                $i++;
            }
            if($cantidad==$num_max_alum+$add)$resto--;
        }
    }
    return $nueva_distribucion;
}
function mostrar_datos($data){
    for($i=0;$i<count($data);$i++){
        echo "<tr>";
        echo "<td>".($i+1)."</td>";
        foreach($data[$i] as $valor)echo "<td>".$valor."</td>";
        echo "</tr>";
    }
}
?>