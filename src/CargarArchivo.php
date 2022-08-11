<?php
 include("conexion.php");
 require '../vendor/autoload.php';
 use PhpOffice\PhpSpreadsheet\Spreadsheet;
 
 function LeerMatriculados_2022($archivo,$extension){
    if($extension=='csv') $reader = new \PhpOffice\PhpSpreadsheet\Reader\Csv();
    else  $reader = new \PhpOffice\PhpSpreadsheet\Reader\Xlsx();
    $spreadsheet = $reader->load($archivo);
    $rows=$spreadsheet->getSheet(0)->toArray();

    $sql="INSERT INTO  matriculados_2022  VALUES";
    for($i=0;$i<count($rows) ;$i++){
        if($rows[$i][1]==""||!is_numeric($rows[$i][1]))continue;
        $codigo=$rows[$i][1];
        $nombres=$rows[$i][2];
        $nombres=str_replace("'","''",$nombres);
        $sql.="('$codigo','$nombres')";
        $sql.=",";
    }
    $sql=substr($sql, 0, -1);
    $con=conectar();
    $query= mysqli_query($con,$sql);
 }
 function LeerTutores_Alumnos($archivo,$extension){
    if($extension=='csv') $reader = new \PhpOffice\PhpSpreadsheet\Reader\Csv();
    else  $reader = new \PhpOffice\PhpSpreadsheet\Reader\Xlsx();
    $spreadsheet = $reader->load($archivo);
    $rows=$spreadsheet->getSheet(0)->toArray();

    $sql="INSERT INTO  distribucion_tutoria  VALUES";
    for($i=0;$i<count($rows) ;$i++){
        if(str_contains($rows[$i][0],'Docente')) $docente=$rows[$i][1];
        if($rows[$i][0]==""||!is_numeric($rows[$i][0]))continue;
        $codigo=$rows[$i][0];
        $nombres=$rows[$i][1];
        $nombres=str_replace("'","''",$nombres);
        $docente=str_replace("'","''",$docente);
        $sql.="('$codigo','$nombres','$docente')";
        $sql.=",";
    }
    $sql=substr($sql, 0, -1);
    $con=conectar();
    $query= mysqli_query($con,$sql);
 }
 function LeerDocentes_2022($archivo,$extension){
    if($extension=='csv') $reader = new \PhpOffice\PhpSpreadsheet\Reader\Csv();
    else  $reader = new \PhpOffice\PhpSpreadsheet\Reader\Xlsx();
    $spreadsheet = $reader->load($archivo);
    $rows=$spreadsheet->getSheet(0)->toArray();

    $sql="INSERT INTO  docentes  VALUES";
    for($i=0;$i<count($rows) ;$i++){
        if($rows[$i][1]=="")continue;
        $nombres=$rows[$i][1];
        $categoria=$rows[$i][2];
        $nombres=str_replace("'","''",$nombres);
        $sql.="('$nombres','$categoria')";
        $sql.=",";
    }
    
    $sql=substr($sql, 0, -1);
    $con=conectar();
    $query= mysqli_query($con,$sql);
 }
if ( isset($_POST['submit_files'])) {
    if ( isset($_FILES['alumnos_antiguos']['name']) && $_FILES['alumnos_antiguos']['name'] != "" ) {
        $archivo=$_FILES['alumnos_antiguos']['tmp_name'];
        $extension = pathinfo($_FILES['alumnos_antiguos']['name'], PATHINFO_EXTENSION);
        LeerTutores_Alumnos($archivo,$extension);
    }
    if ( isset($_FILES['alumnos_nuevos']['name']) && $_FILES['alumnos_nuevos']['name'] != "" ) {
        $archivo=$_FILES['alumnos_nuevos']['tmp_name'];
        $extension = pathinfo($_FILES['alumnos_nuevos']['name'], PATHINFO_EXTENSION);
        LeerMatriculados_2022($archivo,$extension);
    }
    if ( isset($_FILES['docentes']['name']) && $_FILES['docentes']['name'] != "" ) {
        $archivo=$_FILES['docentes']['tmp_name'];
        $extension = pathinfo($_FILES['docentes']['name'], PATHINFO_EXTENSION);
        LeerDocentes_2022($archivo,$extension);
    }
    header('Location: alumnos.php');
    }
?>
