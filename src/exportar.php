<?php
include("consultas.php");
if ( isset($_POST['exportar_distribucion'])) {
    $nueva_distribucion=distribucion_balanceada();
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
    $query=no_matriculados();
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