<?php
// 1.1

include '../../php/conn.php';
// include 'funciones/funciones.php';
date_default_timezone_set($_POST["timezone"]);

$tabla = $_POST['tabla'];
$dataType = $_POST['dataType'];

$replace = [];
$queryprimary = mysqli_query($conn, "show columns from `{$tabla}` where `Key` = 'PRI'");
$fetchprimary = mysqli_fetch_array($queryprimary);

$replace = [ "@primary" => $fetchprimary['Field'] ];

$condicion = $_POST['condicion'];

foreach ($replace as $key => $value) {
    $condicion = str_replace($key, $value, $condicion);
}

$datos = [];
$querycolumns = mysqli_query($conn, "SHOW columns from {$tabla}") or array_push($datos["Error"], "Columns: " . mysqli_error($con));
$querycliente = mysqli_query($conn, "SELECT * from {$tabla} ".(!empty($condicion) ? "where {$condicion}" : "")) or array_push($datos["Error"], "Query: ". mysqli_error($con));


foreach ($querycliente as $datcliente) {
    foreach ($querycolumns as $datcolumns) {        
        $datos[base64_encode(base64_encode($datcolumns['Field']))] = (base64_encode(base64_encode(utf8_decode($datcliente[$datcolumns['Field']]))) == "null" || base64_encode(base64_encode(utf8_decode($datcliente[$datcolumns['Field']]))) == ""  ? base64_encode(base64_encode(utf8_decode(0))) : base64_encode(base64_encode(utf8_decode(($dataType == 1 ? base64_decode(substr($datcliente[$datcolumns['Field']], 10)) : $datcliente[$datcolumns['Field']])))));
    }
}

echo json_encode($datos);