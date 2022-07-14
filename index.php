<?php
include('includes/header.php');

?>
<div class="container p-4">
  <div class="row">
    <div class="col-md-12 mx-auto">
      <div class="card card-body">
      <form enctype="multipart/form-data" method="POST" action="CargarArchivo.php" role="form"> 
        <h2>Cargar Datos</h2>
        <div class="form-group row">
          <label for="upload" class="col-sm-4 col-form-label">Cargar alumnos antiguos</label> 
          <div class="col-sm-8">
            <input type="file" name="alumnos_antiguos" accept=".xls,.xlsx,.csv"> 
          </div>
        </div>
        <div class="form-group row">
        <label for="upload" class="col-sm-4 col-form-label">Cargar alumnos matriculados en el semestre 2022-1</label> 
        <div class="col-sm-8">
          <input type="file" name="alumnos_nuevos" accept=".xls,.xlsx,.csv"> 
        </div>
        </div>
        <input class="btn btn-success my-2 my-sm-0" type="submit" name="submit_files" value="Subir"> 
      </form>
      </div>
    </div>
  </div>
</div>
<?php include('includes/footer.php'); ?> 
 
</body>
</html>