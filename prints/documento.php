<?php
include __DIR__ . '/../php/funciones.php';
$idDocumento = 10;

$idDocumento = base64_decode(base64_decode($_GET['i'])) ?? 0;

if ($idDocumento == 0) {
 echo "Documento no encontrado";
 exit;
}

// datos del ingreso
$queryIngreso = "SELECT * from documentos_generados where id = '$idDocumento' LIMIT 1";
$resultIngreso = mysqli_query($conn, $queryIngreso);
$row = null;
if (mysqli_num_rows($resultIngreso) > 0) {
 $row = mysqli_fetch_assoc($resultIngreso);
}



?>
<link rel="stylesheet" href="<?= $Base ?>/dist/css/table_print.css">





<table class="">
 <tbody>
  <tr class="">
   <td class="center"><strong><?= strtoupper(funcionMaster($row['idDoc'], 'id', 'titulo', 'documentos_generados_plantillas')) ?></strong></td>
  </tr>
  <tr class="">
   <td class="" >
    <p><?= $row['contenido'] ?></p>
   </td>
  </tr>
 </tbody>
</table>
