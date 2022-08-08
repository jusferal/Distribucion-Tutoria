<?php
 //require('librerias/php-excel-reader/excel_reader2.php');
 //require('librerias/SpreadsheetReader.php');
 //require('PHPExcel/Classes/PHPExcel.php');
 include("conexion.php");
 require 'vendor/autoload.php';
 use PhpOffice\PhpSpreadsheet\Spreadsheet;
 
 function LeerMatriculados_2022($dir){
    $ext = pathinfo($dir, PATHINFO_EXTENSION);
    $con=conectar();
    $sql="INSERT INTO  matriculados_2022  VALUES";
    if($ext=='csv'){
        $reader_csv = new \PhpOffice\PhpSpreadsheet\Reader\Csv();
        $reader_csv->setInputEncoding('CP1252');
        $reader_csv->setSheetIndex(0);
        $spreadsheet = $reader_csv->load($dir);
        $rows = $spreadsheet->getActiveSheet()->toArray();
        for($i=0;$i<count($rows) ;$i++){
            if($rows[$i][1]==""||!is_numeric($rows[$i][1]))continue;
            $codigo=$rows[$i][1];
            $nombres=$rows[$i][2];
            $nombres=str_replace("'","''",$nombres);
            $sql.="('$codigo','$nombres')";
            $sql.=",";
        }
    }
    else {
        $reader_excel = new \PhpOffice\PhpSpreadsheet\Reader\Xlsx();
        $spreadsheet = $reader_excel->load($dir);
        $rows=$spreadsheet->getSheet(0)->toArray();
        for($i=0;$i<count($rows) ;$i++){
            if($rows[$i][1]==""||!is_numeric($rows[$i][1]))continue;
            $codigo=$rows[$i][1];
            $nombres=$rows[$i][2];
            $nombres=str_replace("'","''",$nombres);
            $sql.="('$codigo','$nombres')";
            $sql.=",";
        }
    }
    $sql=substr($sql, 0, -1);
    $query= mysqli_query($con,$sql);
 }
 function LeerTutores_Alumnos($dir){
    $ext = pathinfo($dir, PATHINFO_EXTENSION);
    $con=conectar();
    $sql="INSERT INTO  distribucion_tutoria  VALUES";
    if($ext=='csv'){
        $reader_csv = new \PhpOffice\PhpSpreadsheet\Reader\Csv();
        $reader_csv->setInputEncoding('CP1252');
        $reader_csv->setSheetIndex(0);
        $spreadsheet = $reader_csv->load($dir);
        $rows = $spreadsheet->getActiveSheet()->toArray();
        for($i=0;$i<count($rows) ;$i++){
            if(str_contains($rows[$i][0],'Docente')){
                $docente=$rows[$i][1];
            }
            if($rows[$i][0]==""||!is_numeric($rows[$i][0]))continue;
            $codigo=$rows[$i][0];
            $nombres=$rows[$i][1];
            $nombres=str_replace("'","''",$nombres);
            $docente=str_replace("'","''",$docente);
            $sql.="('$codigo','$nombres','$docente')";
            $sql.=",";
        }
    }
    else {
        $reader_excel = new \PhpOffice\PhpSpreadsheet\Reader\Xlsx();
        $spreadsheet = $reader_excel->load($dir);
        $rows=$spreadsheet->getSheet(0)->toArray();
        for($i=0;$i<count($rows) ;$i++){
            if($rows[$i][0]==""||!is_numeric($rows[$i][0]))continue;
            $codigo=$rows[$i][0];
            $nombres=$rows[$i][1];
            $nombres=str_replace("'","''",$nombres);
            $sql.="('$codigo','$nombres')";
            $sql.=",";
        }
    }
    $sql=substr($sql, 0, -1);
    $query= mysqli_query($con,$sql);
 }
 function LeerDocentes_2022($dir){
    $ext = pathinfo($dir, PATHINFO_EXTENSION);
    $con=conectar();
    $sql="INSERT INTO  docentes  VALUES";
    if($ext=='csv'){
        $reader_csv = new \PhpOffice\PhpSpreadsheet\Reader\Csv();
        $reader_csv->setInputEncoding('CP1252');
        $reader_csv->setSheetIndex(0);
        $spreadsheet = $reader_csv->load($dir);
        $rows = $spreadsheet->getActiveSheet()->toArray();
        for($i=0;$i<count($rows) ;$i++){
            if($rows[$i][1]=="")continue;
            $nombres=$rows[$i][1];
            $categoria=$rows[$i][2];
            $nombres=str_replace("'","''",$nombres);
            $sql.="('$nombres','$categoria')";
            $sql.=",";
        }
    }
    else {
        $reader_excel = new \PhpOffice\PhpSpreadsheet\Reader\Xlsx();
        $spreadsheet = $reader_excel->load($dir);
        $rows=$spreadsheet->getSheet(0)->toArray();
        for($i=0;$i<count($rows) ;$i++){
            if($rows[$i][1]=="")continue;
            $nombres=$rows[$i][1];
            $categoria=$rows[$i][2];
            $nombres=str_replace("'","''",$nombres);
            $sql.="('$nombres','$categoria')";
            $sql.=",";
        }
    }
    $sql=substr($sql, 0, -1);
    $query= mysqli_query($con,$sql);
 }
if ( isset($_POST['submit_files'])) {
    $direccion1='archivos/';
    $direccion2='archivos/';
    $direccion3='archivos/';
    if ( isset($_FILES['alumnos_antiguos']['name']) && $_FILES['alumnos_antiguos']['name'] != "" ) {
        $direccion1 = 'archivos/'.basename($_FILES['alumnos_antiguos']['name']);
        move_uploaded_file($_FILES['alumnos_antiguos']['tmp_name'], $direccion1);
    }
    if ( isset($_FILES['alumnos_nuevos']['name']) && $_FILES['alumnos_nuevos']['name'] != "" ) {
        $direccion2 = 'archivos/'.basename($_FILES['alumnos_nuevos']['name']);
        move_uploaded_file($_FILES['alumnos_nuevos']['tmp_name'], $direccion2);
    }
    if ( isset($_FILES['docentes']['name']) && $_FILES['docentes']['name'] != "" ) {
        $direccion3 = 'archivos/'.basename($_FILES['docentes']['name']);
        move_uploaded_file($_FILES['docentes']['tmp_name'], $direccion3);
    }
    LeerMatriculados_2022($direccion2);
    LeerTutores_Alumnos($direccion1);
    LeerDocentes_2022($direccion3);
    header('Location: alumnos.php');
    }
?>
