<?php
// include __DIR__ . '/../php/error_reporting.php';
$idApartamento = $_GET['idApartamento'] ?? 0;
$fechaInicio = $_GET['fechaInicio'] ?? 0;
$fechaFin = $_GET['fechaFin'] ?? 0;
$include = $_GET['include'] ?? 0;
// var_dump($idApartamento);

if ($include == 0) {
    include __DIR__ . '/../php/funciones.php';
}

// cuotas extra 
$queryCuotas = "SELECT * from cuotasExtra
where 1=1
and activo = 1
";
$resultCuotas = mysqli_query($conn, $queryCuotas);
$rowCuotas = [];
while ($row = mysqli_fetch_assoc($resultCuotas)) {
    $rowCuotas[] = $row;
}

// recibos 
// $idApartamento = $_SESSION['id'];
$queryRecibos = "SELECT * from ingresos
where 1=1
and activo = 1
" . ($idApartamento != 0 ? "and idApartamento = '$idApartamento'" : "") . "
";
$resultRecibos = mysqli_query($conn, $queryRecibos);
$rowRecibos = [];
while ($row = mysqli_fetch_assoc($resultRecibos)) {
    $rowRecibos[] = $row;
}

// apartamentos
$queryApartamentos = "SELECT * from apartamentos
where 1=1
and activo = 1
" . ($idApartamento != 0 ? "and id = '$idApartamento'" : "") . "
";
$resultApartamentos = mysqli_query($conn, $queryApartamentos);
$rowApartamentos = [];
while ($row = mysqli_fetch_assoc($resultApartamentos)) {
    $rowApartamentos[] = $row;
}




?>

<link rel="stylesheet" href="<?= $Base ?>/dist/css/table_print.css">
<style>
    .p-0 {
        padding: 0 !important;
    }
    .m-0 {
        margin: 0 !important;
    }
</style>

<?php foreach ($rowApartamentos as $apartamento) { ?>
    <p>Periodo de búsqueda: <strong><?= date('Y-m', strtotime($fechaInicio)) ?></strong> a <strong><?= date('Y-m', strtotime($fechaFin)) ?></strong></p>
    <p>Apartamento: <strong><?= $apartamento['apartamento'] ?> <?= $apartamento['propietario'] ?></strong></p>

    <table class="dltrc">
        <tbody>
            <tr class="dlheader">
                <td class="dlheader"><?= strtoupper('Cuotas Extra') ?></td>
            </tr>
        </tbody>
    </table>
    <table class="dltrc">
        <tbody>
            <tr class="dlheader">
                <td class="dlheader" style="width: 2cm;">Fecha</td>
                <td class="dlheader" style="width: 5cm;">Descripción</td>
                <td class="dlheader">Estado</td>
            </tr>
            </tr>
            <?php foreach ($rowCuotas as $cuota) {
                $rowEncontrado = [];
                foreach ($rowRecibos as $recibo) {
                    if ($recibo['idCuotaExtra'] == $cuota['id'] && $recibo['idApartamento'] == $apartamento['id']) {
                        $rowEncontrado[] = $recibo;
                    }
                }
            ?>
                <tr class="dlinfo">
                    <td class="dlinfo" style="width: 2cm; white-space: nowrap;"><?= date('Y-m', strtotime($cuota['fechaRegistro'])) ?></td>
                    <td class="dlinfo" style="width: 5cm;"><?= $cuota['descripcion'] ?></td>
                    <td class="dlinfo">
                        <?php foreach ($rowEncontrado as $reciboEncontrado) { ?>
                            <strong><?= $reciboEncontrado ? 'Pagado' : 'Sin Información' ?></strong>
                            <?php if ($reciboEncontrado) { ?>
                                <p class="m-0 p-0">Monto: <?= funcionMaster($reciboEncontrado['idMoneda'], 'id', 'prefijo', 'monedas') ?> <?= number_format($reciboEncontrado['monto'], 2) ?></p>
                                <p class="m-0 p-0">Referencia: <?= $reciboEncontrado['referencia'] ?></p>
                            <?php } ?>
                            <br>
                        <?php } ?>
                        <?php if (count($rowEncontrado) == 0) { ?>
                            <strong>Sin Información</strong>
                            <a href="<?= $Base ?>reportar_pago?iC=<?= base64_encode($cuota['id']) ?>" target="_blank" class="btn btn-default btn-sm ml-1">Reportar Pago</a>
                        <?php } ?>
                    </td>
                </tr>
            <?php } ?>
        </tbody>
    </table>

    <table class="dltrc">
        <tbody>
            <tr class="dlheader">
                <td class="dlheader"><?= strtoupper('Recibos de Pago - Condominio') ?></td>
            </tr>
        </tbody>
    </table>
    <table class="dltrc">
        <tbody>
            <tr class="dlheader">
                <td class="dlheader" style="width: 2cm;">Fecha</td>
                <td class="dlheader" style="width: 5cm;">Descripción</td>
                <td class="dlheader">Estado</td>
            </tr>
            </tr>
            <?php
            $mesesCalculados = calcularMesesEntreFechas($fechaInicio, $fechaFin);

            for ($i = 0; $i < ($mesesCalculados); $i++) {
                $anoActual = date('Y', strtotime($fechaInicio . " +$i month"));
                $mesActual = date('m', strtotime($fechaInicio . " +$i month"));

                $rowEncontrado = [];
                foreach ($rowRecibos as $recibo) {
                    if ($recibo['idApartamento'] == $apartamento['id'] && $recibo['mes'] == $mesActual && date('Y', strtotime($recibo['fecha'])) == $anoActual) {
                        $rowEncontrado[] = $recibo;
                    }
                }


            ?>
                <tr class="dlinfo">
                    <td class="dlinfo" style="width: 2cm; white-space: nowrap;"><?= date('Y-m', strtotime($anoActual . '-' . $mesActual . '-01')) ?></td>
                    <td class="dlinfo" style="width: 5cm;"> Condominio <?= $meses[intval($mesActual)] ?> <?= $anoActual ?></td>
                    <td class="dlinfo">
                        <?php foreach ($rowEncontrado as $reciboEncontrado) { ?>
                            <strong><?= $reciboEncontrado ? 'Pagado' : 'Sin Información' ?></strong>
                            <?php if ($reciboEncontrado) { ?>
                                <p class="m-0 p-0">Monto: <?= funcionMaster($reciboEncontrado['idMoneda'], 'id', 'prefijo', 'monedas') ?> <?= number_format($reciboEncontrado['monto'], 2) ?></p>
                                <p class="m-0 p-0">Referencia: <?= $reciboEncontrado['referencia'] ?></p>
                            <?php } ?>
                            <br>
                        <?php } ?>
                        <?php if (count($rowEncontrado) == 0) { ?>
                            <strong>Sin Información</strong>
                            <a href="<?= $Base ?>reportar_pago?f=<?= base64_encode($anoActual . '-' . $mesActual . '-' . $apartamento['id']) ?>" target="_blank" class="btn btn-default btn-sm ml-1">Reportar Pago</a>
                        <?php } ?>

                    </td>
                </tr>
            <?php } ?>
        </tbody>
    </table>
<?php } ?>