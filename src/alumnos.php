<?php
 include("consultas.php");
 include('includes/header.php');

?>
<div class="container p-4">
  <div class="row">
    <div class="col-md-12 mx-auto">
      <div class="card card-body">
      <h2>Mostrar Datos</h2>
      <form action="alumnos.php" method="POST">
        <div class="form-group ">
            <input type="radio" class="form-check-input" name="tipo_mostrar" value="no_matriculados"> Mostrar Alumnos que no seran tutorados en 2022-1 <br>
            <input type="radio" class="form-check-input" name="tipo_mostrar" value="sin_tutor"> Mostrar Alumnos nuevos para tutoria <br> 
            <input type="radio" class="form-check-input" name="tipo_mostrar" value="distribucion"> Mostrar Distribución balanceada de tutorías para el presente semestre
        </div>
        <button class="btn btn-success my-3" name="mostrar"> Mostrar
        </button>
      </form>
      </div>
    </div>
    <div class="col-md-12 mt-5">
    <?php 
      if (isset($_POST['mostrar']) AND isset($_POST['tipo_mostrar'])){
        if($_POST['tipo_mostrar']=='no_matriculados'){
          ?> <h3 class="text-center">Alumnos no matriculados en el semestre 2022</h3> <?php
        }
        if($_POST['tipo_mostrar']=='sin_tutor'){
          ?> <h3 class="text-center"> Alumnos sin tutor</h3> <?php
        }
        if($_POST['tipo_mostrar']=='distribucion'){
          ?> <h3 class="text-center"> Distribución balanceada de tutorías</h3> <?php
        }
        if($_POST['tipo_mostrar']=='distribucion' OR $_POST['tipo_mostrar']=='no_matriculados' OR $_POST['tipo_mostrar']=='sin_tutor'){ ?>
          <div class="form-group">
            <form action="exportar.php" method="POST" class="form-inline">
              <?php if($_POST['tipo_mostrar']=='distribucion'){?>
                <button class="btn btn-primary my-3" name="exportar_distribucion"> Exportar en formato CSV</button>
              <?php } ?>
              <?php if($_POST['tipo_mostrar']=='no_matriculados'){?>
                <button class="btn btn-primary my-3" name="exportar_alumnos"> Exportar en  formato CSV</button>
              <?php } ?>
              <?php if($_POST['tipo_mostrar']=='sin_tutor'){?>
                <button class="btn btn-primary my-3" name="exportar_sin_tutor"> Exportar en  formato CSV</button>
              <?php } ?>
            </form>
          </div>
        <?php }?>
      <table class="table table-bordered  table-dark table-hover">
        <thead >
            <tr class="table-active">
              <th scope="col">Numero</th>
              <th scope="col">Código</th>
              <th scope="col">Nombres y Apellidos</th>
              <?php if (isset($_POST['mostrar']) AND ($_POST['tipo_mostrar']=='distribucion')){ ?>
              <th scope="col">Docente Asignado</th>
              <?php }?>
            </tr>
          
        </thead>
        <tbody>
        <?php }?>
        <?php
        if (isset($_POST['mostrar']) AND isset($_POST['tipo_mostrar'])) {
            if($_POST['tipo_mostrar']=='no_matriculados'){
                $query=no_matriculados();
                mostrar_datos($query);
            }
            else if($_POST['tipo_mostrar']=='sin_tutor'){
                $query=alumnos_sin_tutor();
                mostrar_datos($query);
            }
            else if($_POST['tipo_mostrar']=='distribucion'){
              $nueva_distribucion=distribucion_balanceada();
              mostrar_datos($nueva_distribucion);
          }
        }
        ?>
        </tbody>
      </table>
    </div>
  </div>
</div>
<?php include('includes/footer.php'); ?>