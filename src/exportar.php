<?php
include("consultas.php");
function guardar($dato, $nombrearchivo){
    $out = fopen('php://memory', 'w'); 
    foreach($dato as $item) fputcsv($out,$item);
    fseek($out, 0);
    header('Content-Type: text/csv');
    header('Content-Disposition: attachment; filename="'.$nombrearchivo.'";');
    fpassthru($out);
}
if ( isset($_POST['exportar_distribucion'])) {
    $nueva_distribucion=distribucion_balanceada();
    $nombrearchivo = "Nueva_Distribucion.csv";  
    guardar($nueva_distribucion,$nombrearchivo);
    }
if ( isset($_POST['exportar_alumnos'])) {
    $query=no_matriculados();
    $nombrearchivo = "Alumnos_no_matriculados.csv";  
    guardar($query,$nombrearchivo);
    }
if ( isset($_POST['exportar_sin_tutor'])) {
    $query=alumnos_sin_tutor();
    $nombrearchivo = "Alumnos_sin_tutor.csv";  
    guardar($query,$nombrearchivo);
    }
?>