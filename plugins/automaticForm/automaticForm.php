<?php
// version 0.5.9\
session_start();
include '../../php/conn.php';
include '../comprimir/comprimirImagen.php';
date_default_timezone_set($_POST['datos']["timezone"]);

// conexion a otra base
$dbHost = base64_decode($_GET['dbHost']);
$dbUser = base64_decode($_GET['dbUser']);
$dbPass = base64_decode($_GET['dbPass']);
$db = base64_decode($_GET['db']);
if (!empty($db) && !empty($dbHost) && !empty($dbUser) && !empty($dbPass)) {
    $host = $dbHost;
    $user = $dbUser;
    $pass = $dbPass;
    $dbname = $db;
    $conn = mysqli_connect($host, $user, $pass, $dbname);
}
// mysqli_set_charset($conn, "utf8");


$datos = [];
$q = "CREATE table if not exists {$_GET['table']} (id int auto_increment, fechaRegistro datetime default current_timestamp, primary key(id))";
mysqli_query($conn, $q) or array_push($datos, ["Error" => "$q -> " . mysqli_error($conn)]);
$valores = '';
if (!empty($_POST['datos'])) {
    // var_dump($_POST['datos']);
    foreach ($_POST['datos'] as $key => $value) {
        $q = "ALTER table {$_GET['table']} add column if not exists $key text null default null";
        mysqli_query($conn, $q) or array_push($datos, ["Error" => "$q -> " . mysqli_error($conn)]);
        if (is_array($value)) {
            $valoresArray = "";
            foreach ($value as $key1 => $value1) {
                $valoresArray .= "$value1|/|";
            }
            $valoresArray = substr($valoresArray, 0, -3);
            $valores .= "$key = '$valoresArray', ";
        } else {
            $valores .= "$key = '$value', ";
        }
    }
}
$valores = substr($valores, 0, -2);
$valoresFile = '';
$countSize = 0;
$carpetas = '';
if (!empty($_FILES['archivos'])) {
    foreach ($_FILES['archivos']['name'] as $key => $value) {
        if ($_FILES['archivos']['size'][$key] > 0) {
            $llave = explode("|", $key);
            $q = "ALTER table {$_GET['table']} add column if not exists $llave[0] text default null";
            mysqli_query($conn, $q) or array_push($datos, ["Error" => "$q -> " . mysqli_error($conn)]);
            $valoresFile .= "$llave[0] = '" . str_replace("", "_", rand(date("Y"), date("YHis")) . $value) . "', ";
            $countSize += $_FILES['archivos']['size'][$key];
            $carpetas .= $llave[1] . "|//|";
        }
    }
}
$valoresFile = substr($valoresFile, 0, -2);
$q = "SHOW COLUMNS FROM {$_GET['table']} where `Key` like '%PRI%'";
$queryInfo = mysqli_query($conn, $q) or array_push($datos, ["Error" => "$q -> " . mysqli_error($conn)]);
$fetchInfo = mysqli_fetch_array($queryInfo);
$q = "SELECT * from {$_GET['table']} where " . str_replace(", ", " and ", $valores) . "" . (!empty($countSize) ? " and " . str_replace(", ", " and ", $valoresFile) . "" : "");
$queryValidar = mysqli_query($conn, $q) or array_push($datos, ["Error" => "$q -> " . mysqli_error($conn)]);
if (empty(mysqli_num_rows($queryValidar))) {
    $q = ($_GET['type'] == 2 ? "UPDATE" : "INSERT INTO") . " {$_GET['table']} set $valores " . (!empty($countSize) ? ", $valoresFile" : "") . " " . ($_GET['type'] == 2 ? "where {$fetchInfo['Field']} = {$_GET["idUpdate"]}" : "");
    $auditorQuery = $q;
    $validar = mysqli_query($conn, $q) or array_push($datos, ["Error" => "$q -> " . mysqli_error($conn)]);

    $idInsert = ($_GET['type'] == 2 ? $_GET["idUpdate"] : mysqli_insert_id($conn));
    if (!empty($countSize)) {
        $carpetas = explode("|//|", substr($carpetas, 0, -4));
        foreach ($carpetas as $key => $value) {
            if (!file_exists('../../' . $value . "/")) {
                mkdir('../../' . $value . "/", 0777, true);
            }
        }
        $valoresFile = explode(", ", $valoresFile);
        $conntador = -1;
        foreach ($_FILES['archivos']['name'] as $key => $value) {
            if ($_FILES['archivos']['size'][$key] > 0) {
                $llave = explode("|", $key);
                $valoresFile2 = explode("= ", $valoresFile[$conntador += 1]);
                // move_uploaded_file($_FILES['archivos']['tmp_name'][$key], '../../' . $llave[1] . "/" . str_replace("'", "", str_replace("", "", $valoresFile2[1])));

                // 23 10 2023 - JRodrigues
                // antes de subir el archivo primero se valida si es imagen / video / otros
                $ext = pathinfo($_FILES['archivos']['name'][$key], PATHINFO_EXTENSION);
                if ($ext == "jpg" || $ext == "png" || $ext == "jpeg" || $ext == "gif") {
                    // imagen 
                    // las imagenes se comprimen y se suben

                    // comprimirImagen($rutaImagen, $rutaDestino, $calidad)
                    comprimirImagen($_FILES['archivos']['tmp_name'][$key], '../../' . $llave[1] . "/" . str_replace("'", "", str_replace("", "", $valoresFile2[1])), 70);
                } else {
                    // otros
                    // si es otros no hacemos nada y continua
                    move_uploaded_file($_FILES['archivos']['tmp_name'][$key], '../../' . $llave[1] . "/" . str_replace("'", "", str_replace("", "", $valoresFile2[1])));
                }
                // var_dump('<br> ../../' . $llave[1] . "/" . str_replace("'", "", str_replace("", "", $valoresFile2[1])));



            }
        }
    }
} else {
    $validar = false;
}

$datos["status"] = ($validar ? true : false);
$datos["FAID"] = (!empty($idInsert) ? base64_encode($idInsert) : "0");
echo json_encode($datos);

// auditor
$_GET['q'] = base64_encode(base64_encode($auditorQuery));
// include '../../auditor.php';