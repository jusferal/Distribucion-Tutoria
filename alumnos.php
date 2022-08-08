<?php
 include("conexion.php");
 include('includes/header.php');

?>
<?php
function get_pref_cod($codigo){
    if(strlen($codigo)==6) return $codigo[0]."".$codigo[1];
    else if(strlen($codigo)==5) return "0".$codigo[0];
    return "00";
}
?>
<div class="container p-4">
  <div class="row">
    <div class="col-md-12 mx-auto">
      <div class="card card-body">
      <form action="alumnos.php" method="POST">
        <div class="form-group ">
            <h2>Mostrar Datos:</h2>
            <input type="radio" name="tipo_mostrar" value="alumnos"> Mostrar Alumnos que no seran tutorados en 2022-1 <br>
            <!--<input type="radio" name="tipo_mostrar" value="tutor"> Mostrar Alumnos nuevos para tutoria <br> -->
            <input type="radio" name="tipo_mostrar" value="distribucion"> Mostrar Distribución balanceada de tutorías para el presente semestre
        </div>
        <button class="btn btn-success" name="mostrar"> Mostrar
        </button>
      </form>
      </div>
    </div>
    <div class="col-md-12">
      <table class="table table b  -bordered">
        <thead>
          <?php if (isset($_POST['mostrar']) AND ($_POST['tipo_mostrar']=='distribucion' OR $_POST['tipo_mostrar']=='alumnos')){ ?>
            <br>
            <tr>
            <form action="exportar.php" method="POST">
            <?php if($_POST['tipo_mostrar']=='distribucion'){?>
              <button class="btn btn-primary" name="exportar_distribucion"> Exportar en CSV</button>
            <?php } ?>
            <?php if($_POST['tipo_mostrar']=='alumnos'){?>
              <button class="btn btn-primary" name="exportar_alumnos"> Exportar en CSV</button>
            <?php } ?>
            </form>
            </tr>
          <?php }
          ?>
          <tr>
            <th>Numero</th>
            <th>Codigo</th>
            <th>Nombres y Apellidos</th>
            <?php if (isset($_POST['mostrar']) AND ($_POST['tipo_mostrar']=='distribucion')){ ?>
            <th>Docente Asignado</th>
            <?php }?>
          </tr>
        </thead>
        <tbody>
    <?php
    if (isset($_POST['mostrar'])) {
        if($_POST['tipo_mostrar']=='alumnos'){
            $con=conectar();
            $sql="SELECT DISTINCT * FROM distribucion_tutoria D WHERE D.cod_estudiante not in( SELECT cod_estudiante FROM matriculados_2022 ); ";
            $query=mysqli_query($con,$sql);
            $i=1;
            while($row = mysqli_fetch_assoc($query)) { ?>
            <tr>
                <td><?php echo $i; ?></td>
                <td><?php echo $row['cod_estudiante']; ?></td>
                <td><?php echo $row['nombres_apellidos']; ?></td>
            </tr>
            <?php $i++;}       
        }
        else if($_POST['tipo_mostrar']=='tutor'){
            $con=conectar();
            $sql="SELECT DISTINCT * FROM matriculados_2022  WHERE cod_estudiante not in( SELECT cod_estudiante FROM distribucion_tutoria ); ";
            $query=mysqli_query($con,$sql);
            $i=1;
            while($row = mysqli_fetch_assoc($query)) { ?>
            <tr>
                <td><?php echo $i; ?></td>
                <td><?php echo $row['cod_estudiante']; ?></td>
                <td><?php echo $row['nombres_apellidos']; ?></td>
            </tr>
            <?php $i++;}
        }
        else if($_POST['tipo_mostrar']=='distribucion'){
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
          for($i=0;$i<count($nueva_distribucion);$i++){
            echo "<tr>";
            echo "<td>".($i+1)."</td>";
            echo "<td>".$nueva_distribucion[$i][0]."</td>";
            echo "<td>".$nueva_distribucion[$i][1]."</td>";
            echo "<td>".$nueva_distribucion[$i][2]."</td>";
            echo "</tr>";
          }
          if (isset($_POST['exportar'])) {
            $nombrearchivo = "Nueva_Distribucion" . ".csv";  
            foreach($nueva_distribucion as $item){
              fputscsv($nombrearchivo,$item);
            }
            header("Content-Disposition: attachment; filename=$nombrearchivo");
          }
      }
    }
    ?>
        </tbody>
      </table>
    </div>
  </div>
</div>
<?php include('includes/footer.php'); ?>