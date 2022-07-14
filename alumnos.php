<?php
 include("conexion.php");
 include('includes/header.php');

?>
<?php
?>
<div class="container p-4">
  <div class="row">
    <div class="col-md-12 mx-auto">
      <div class="card card-body">
      <form action="alumnos.php" method="POST">
        <div class="form-group ">
            <h2>Mostrar Datos:</h2>
            <input type="radio" name="tipo_mostrar" value="alumnos"> Mostrar Alumnos que no seran tutorados en 2022-1 <br>
            <input type="radio" name="tipo_mostrar" value="tutor"> Mostrar Alumnos nuevos para tutoria
        </div>
        <button class="btn-success" name="mostrar"> Mostrar
        </button>
      </form>
      </div>
    </div>
    <div class="col-md-12">
      <table class="table table-bordered">
        <thead>
          <tr>
            <th>Numero</th>
            <th>Codigo</th>
            <th>Nombres y Apellidos</th>
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
    }
    ?>
        </tbody>
      </table>
    </div>
  </div>
</div>
<?php include('includes/footer.php'); ?>