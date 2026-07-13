<?php
include __DIR__ . '/../php/funciones.php';
$idDocumentoGenerado = 10;

$idDocumentoGenerado = base64_decode(base64_decode($_GET['i'])) ?? 0;

if ($idDocumentoGenerado == 0) {
 echo "Documento no encontrado";
 exit;
}

// datos del ingreso
$queryIngreso = "SELECT * from ingresos where id = '$idDocumentoGenerado' LIMIT 1";
$resultIngreso = mysqli_query($conn, $queryIngreso);
$rowIngreso = null;
if (mysqli_num_rows($resultIngreso) > 0) {
 $rowIngreso = mysqli_fetch_assoc($resultIngreso);
}

// datos del edificio
$queryEdificio = "SELECT * from edificios where id = '{$rowIngreso['idEdificio']}' LIMIT 1";
$resultEdificio = mysqli_query($conn, $queryEdificio);
$rowEdificio = null;
if (mysqli_num_rows($resultEdificio) > 0) {
 $rowEdificio = mysqli_fetch_assoc($resultEdificio);
}

// categorias de ingreso
$queryCat = "SELECT * from cat_ingresos
where 1=1
and activo = 1
";
$resultCat = mysqli_query($conn, $queryCat);
$rowCat = [];
if (mysqli_num_rows($resultCat) > 0) {
 while ($row = mysqli_fetch_assoc($resultCat)) {
  $rowCat[] = $row;
 }
}

// var_dump($rowIngreso);
// echo "<br>";
// var_dump($rowEdificio);

$link = (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on' ? "https" : "http") . "://$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]";

// qr base64
$qr = generarQR($link);


?>
<link rel="stylesheet" href="<?= $Base ?>/dist/css/table_print.css">

<table class="dltrc">
 <tbody>
  <tr class="dlheader">
   <td class="dlheader"><?= strtoupper('Conjunto Residencial ' . $rowEdificio['conjunto']) ?></td>
  </tr>
  <tr class="dlheader">
   <td class="dlheader"><?= strtoupper('Condominio ' . $rowEdificio['nombre']) ?></td>
  </tr>
  <tr class="dlheader">
   <td class="dlheader" style="font-weight: normal;">RIF <?= strtoupper($rowEdificio['rif']) ?></td>
  </tr>
  <tr class="dlheader">
   <td class="dlheader" style="font-weight: normal;"><?= strtoupper($rowEdificio['direccion']) ?></td>
  </tr>
 </tbody>
</table>
<table class="dltrc">
 <tbody>
  <tr class="dlheader">
   <td class="dlheader"><?= strtoupper('Recibo de Ingreso') ?></td>
  </tr>
 </tbody>
</table>

<table class="dltrc">
 <tbody>
  <tr class="dlinfo">
   <td class="dlinfo" style="width: 5cm;"><strong>Recibo de Ingreso No.</strong></td>
   <td class="dlinfo">I-<?= str_pad($rowIngreso['id'], 6, "0", STR_PAD_LEFT) ?></td>
  </tr>
  <tr class="dlinfo">
   <td class="dlinfo" rowspan="2" style="width: 5cm;"><strong>Por</strong></td>
   <td class="dlinfo"><strong><?= funcionMaster($rowIngreso['idMoneda'], 'id', 'prefijo', 'monedas') ?> <?= number_format($rowIngreso['monto'], 2) ?></strong></td>
  </tr>
  <tr class="dlinfo">
   <td class="dlinfo"><strong><?= strtoupper(numero_letra($rowIngreso['monto'], funcionMaster($rowIngreso['idMoneda'], 'id', 'nombre', 'monedas'))) ?></strong></td>
  </tr>
  <tr class="dlinfo">
   <td class="dlinfo" style="width: 5cm;"><strong>Fecha Emisión</strong></td>
   <td class="dlinfo"><?= date('d/m/Y', strtotime($rowIngreso['fechaRegistro'])) ?></td>
  </tr>
  <tr class="dlinfo">
   <td class="dlinfo" style="width: 5cm;"><strong>Fecha de Pago</strong></td>
   <td class="dlinfo"><?= date('d/m/Y', strtotime($rowIngreso['fecha'])) ?></td>
  </tr>
  <tr class="dlinfo">
   <td class="dlinfo" style="width: 5cm;"><strong>Apartamento</strong></td>
   <td class="dlinfo"><?= funcionMaster($rowIngreso['idApartamento'], 'id', 'concat(apartamento," - ",propietario)', 'apartamentos') ?></td>
  </tr>
 </tbody>
</table>

<table class="dltrc">
 <tbody>
  <?php foreach ($rowCat as $cat) { ?>
   <tr class="dlinfo">
    <td class="dlinfo" style="width: 5cm;"><?= $cat['nombre'] ?></td>
    <td class="dlinfo"><strong><?= ($cat['id'] == $rowIngreso['idCategoria'] ? 'X' : '') ?></strong></td>
   </tr>
  <?php } ?>
 </tbody>
</table>

<table class="dltrc">
 <tbody>
  <tr class="dlinfo">
   <td class="dlinfo" style="width: 5cm;">Referencia [Bancaria]</td>
   <td class="dlinfo"><strong><?= $rowIngreso['referencia'] ?></strong></td>
  </tr>
  <tr class="dlinfo">
   <td class="dlinfo" colspan="2">
    Observaciones
    <p><?= $rowIngreso['descripcion'] ?></p>
    <?php if ($rowIngreso['idCategoria'] == 2) { ?>
     <p><?= funcionMaster($rowIngreso['idCuotaExtra'], 'id', 'concat(descripcion, " - ", monto)', 'cuotasExtra') ?> <?= funcionMaster(funcionMaster($rowIngreso['idCuotaExtra'], 'id', 'idMoneda', 'cuotasExtra'), 'id', 'prefijo', 'monedas')  ?></p>
    <?php } ?>
   </td>
  </tr>
 </tbody>
</table>

<table class="">
 <tbody>
  <tr class="">
   <td class="" style="width: 5cm; text-align: center;">
    <img src="<?= $qr ?>" style="height: 3cm;">
   </td>
   <td class="">
    <p>Recibo digital:</p>
    <?= $link ?>
   </td>
  </tr>
 </tbody>
</table>