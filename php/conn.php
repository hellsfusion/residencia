<?php 
// conexión a la base de datos
$db_host = "sql109.infinityfree.com";
$db_user = "if0_39588119";
$db_pass = "O2TCXhdjVkayWK";
$db_name = "if0_39588119_residencia";
$conn = mysqli_connect($db_host, $db_user, $db_pass, $db_name) or die("Error " . mysqli_error($conn));
mysqli_set_charset($conn, "utf8mb4");
if (!$conn) {
    die("Murió la flor: " . mysqli_connect_error());
}
?>