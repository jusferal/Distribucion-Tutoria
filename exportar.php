<?php
include("conexion.php");
function get_pref_cod($codigo){
    if(strlen($codigo)==6) return $codigo[0]."".$codigo[1];
    else if(strlen($codigo)==5) return "0".$codigo[0];
    return "00";
}
if ( isset($_POST['exportar_distribucion'])) {
    $con=conectar();
    $sql1="SELECT DISTINCT * FROM matriculados_2022  WHERE cod_estudiante not in( SELECT cod_estudiante FROM distribucion_tutoria ); ";
    $query_alumnos_nuevos=mysqli_query($con,$sql1);
    $sql2=$sql="SELECT DISTINCT * FROM distribucion_tutoria D WHERE D.cod_estudiante  in( SELECT cod_estudiante FROM matriculados_2022 ); ";
    $query_alumnos_con_tutor=mysqli_query($con,$sql2);
    $sql3="SELECT DISTINCT * FROM  docentes";
    $query_docente=mysqli_query($con,$sql3);
    $sql4=$sql="SELECT DISTINCT * FROM matriculados_2022 ";
    $query_alumnos=mysqli_query($con,$sql4);

    $total_alumnos=$query_alumnos->num_rows;
    $total_docentes=$query_docente->num_rows;
    $num_max_alum=floor($total_alumnos/$total_docentes);
    $resto=$total_alumnos%$total_docentes;
    $i=1;

    $alumnos_sin_tutor=mysqli_fetch_all($query_alumnos_nuevos);
    $data=array();
    $nuevos_alumnos=[];
    $nueva_distribucion=[];
    while($row = mysqli_fetch_assoc($query_alumnos_con_tutor)) { 
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
        $i++;
    }
    $i=0;
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
    $nombrearchivo = "Nueva_Distribucion.csv";  
    $out = fopen('php://memory', 'w'); 
    foreach($nueva_distribucion as $item){
        fputcsv($out,$item);
    }
    fseek($out, 0);
    header('Content-Type: text/csv');
    header('Content-Disposition: attachment; filename="'.$nombrearchivo.'";');
    fpassthru($out);
    }
if ( isset($_POST['exportar_alumnos'])) {
    $con=conectar();
    $sql="SELECT DISTINCT * FROM distribucion_tutoria D WHERE D.cod_estudiante not in( SELECT cod_estudiante FROM matriculados_2022 ); ";
    $query=mysqli_query($con,$sql);
    $i=1;
    $nombrearchivo = "Alumnos_no_matriculados.csv";  
    $out = fopen('php://memory', 'w'); 
    while($row = mysqli_fetch_assoc($query)) { 
        fputcsv($out,[$row['cod_estudiante'],$row['nombres_apellidos']]);
    }       
    fseek($out, 0);
    header('Content-Type: text/csv');
    header('Content-Disposition: attachment; filename="'.$nombrearchivo.'";');
    fpassthru($out);
    }
?>