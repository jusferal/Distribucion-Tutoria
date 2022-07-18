<?php
 //require('librerias/php-excel-reader/excel_reader2.php');
 //require('librerias/SpreadsheetReader.php');
 //require('PHPExcel/Classes/PHPExcel.php');
 include("conexion.php");
 require 'vendor/autoload.php';
 use PhpOffice\PhpSpreadsheet\Spreadsheet;
 $reader_excel = new \PhpOffice\PhpSpreadsheet\Reader\Xlsx();
 $reader_csv = new \PhpOffice\PhpSpreadsheet\Reader\Csv();
 $Matriculados_2022=[];
 $Tutores_Almunos=[];
 function LeerMatriculados_2022($dir){
    global $reader_excel;
    global $reader_csv;
    $ext = pathinfo($dir, PATHINFO_EXTENSION);
    $con=conectar();
    $sql="INSERT INTO  matriculados_2022  VALUES";
    if($ext=='csv'){
        $reader_csv->setInputEncoding('CP1252');
        $reader_csv->setSheetIndex(0);
        $spreadsheet = $reader_csv->load($dir);
        $rows = $spreadsheet->getActiveSheet()->toArray();
        $total=0;
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
    global $reader_excel;
    global $reader_csv;
    $ext = pathinfo($dir, PATHINFO_EXTENSION);
    $con=conectar();
    $sql="INSERT INTO  distribucion_tutoria  VALUES";
    if($ext=='csv'){
        $reader_csv->setInputEncoding('CP1252');
        $reader_csv->setSheetIndex(0);
        $spreadsheet = $reader_csv->load($dir);
        $rows = $spreadsheet->getActiveSheet()->toArray();
        for($i=0;$i<count($rows) ;$i++){
            if($rows[$i][0]==""||!is_numeric($rows[$i][0]))continue;
            $codigo=$rows[$i][0];
            $nombres=$rows[$i][1];
            $nombres=str_replace("'","''",$nombres);
            $sql.="('$codigo','$nombres')";
            $sql.=",";
        }
    }
    else {
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

if ( isset($_POST['submit_files'])) {
    $direccion1='archivos/';
    $direccion2='archivos/';
    if ( isset($_FILES['alumnos_antiguos']['name']) && $_FILES['alumnos_antiguos']['name'] != "" ) {
        $direccion1 = 'archivos/'.basename($_FILES['alumnos_antiguos']['name']);
        move_uploaded_file($_FILES['alumnos_antiguos']['tmp_name'], $direccion1);
    }
    if ( isset($_FILES['alumnos_nuevos']['name']) && $_FILES['alumnos_nuevos']['name'] != "" ) {
        $direccion2 = 'archivos/'.basename($_FILES['alumnos_nuevos']['name']);
        move_uploaded_file($_FILES['alumnos_nuevos']['tmp_name'], $direccion2);
    }
    LeerMatriculados_2022($direccion2);
    LeerTutores_Alumnos($direccion1);
    header('Location: alumnos.php');
    }
?>
