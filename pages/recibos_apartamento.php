<?php
include __DIR__ . '/../php/header.php';
// include __DIR__ . '/../php/error_reporting.php';
$name = 'Recibos de Pago';
$page = 'recibos';

// datos lista
$headers = [
    '#',
    // 'Fecha Registro',
    //  'Fecha de Pago',
    'Detalles',
    // '',
];

$idApartamento = $_SESSION['id'];

$idCuotaExtra = ($_GET['icE'] ? base64_decode($_GET['icE']) : 0);
$idApartamentosAfectados = ($_GET['iaF'] ? base64_decode($_GET['iaF']) : 0);


// buscar datos de la tabla
$query = "SELECT * from ingresos
where 1=1
and activo = 1
" . ($idApartamento != 0 ? "and idApartamento = '$idApartamento'" : "") . "
" . ($idCuotaExtra != 0 ? "and idCuotaExtra = '$idCuotaExtra'" : "and (idCuotaExtra is null or idCuotaExtra = '' or idCuotaExtra = 0) ") . "
" . ($idApartamentosAfectados != 0 ? "and idApartamento in (" . implode(',', explode("|/|", $idApartamentosAfectados)) . ")" : "") . "
";
// var_dump($query);
$result = mysqli_query($conn, $query);
$rowData = [];
if ($result && mysqli_num_rows($result) > 0) {
    while ($row = mysqli_fetch_assoc($result)) {
        $rowData[] = $row;
    }
}

$meses = array(1 => 'Enero', 2 => 'Febrero', 3 => 'Marzo', 4 => 'Abril', 5 => 'Mayo', 6 => 'Junio', 7 => 'Julio', 8 => 'Agosto', 9 => 'Septiembre', 10 => 'Octubre', 11 => 'Noviembre', 12 => 'Diciembre');


?>
<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <div class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1 class="m-0"><?= $name ?></h1>
                </div><!-- /.col -->
            </div><!-- /.row -->
        </div><!-- /.container-fluid -->
    </div>
    <!-- /.content-header -->

    <!-- Main content -->
    <section class="content">
        <div class="container-fluid">

            <!-- Main row -->
            <div class="row">
                <!-- Left col -->
                <div class="col-md-12">

                    <div class="card card-secondary">
                        <div class="card-header border-transparent">
                            <h3 class="card-title"></h3>
                        </div>
                        <div class="card-body">
                            <table class="table table-striped">
                                <thead>
                                    <tr>
                                        <?php foreach ($headers as $value) { ?>
                                            <th><?= $value ?></th>
                                        <?php } ?>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php foreach ($rowData as $row) { ?>
                                        <tr>
                                            <p class="d-none"><?= number_format($row['monto'], 2, '.', '') ?></p>
                                            <p class="d-none"><?= $row['monto'] ?></p>
                                            <td style="width:2cm;"><?= $row['id'] ?></td>
                                            <!-- <td style="width:2cm;"><?= date('Y-m-d', strtotime($row['fechaRegistro'])) ?></td> -->
                                            <!-- <td style="width:2cm;"><?= $row['fecha'] ?></td> -->
                                            <td>
                                                <?= $row['fecha'] ?>
                                                <p class="m-0 p-0"><?= funcionMaster($row['idMoneda'], 'id', 'prefijo', 'monedas') ?> <?= number_format($row['monto'], 2) ?></p>
                                                <p class="m-0 p-0"><strong>Apto:</strong> <?= funcionMaster($row['idApartamento'], 'id', 'concat(apartamento," - ",propietario)', 'apartamentos') ?></p>
                                                <p class="m-0 p-0"><strong>Mes:</strong> <?= $row['mes'] ?> - <?= $meses[$row['mes']] ?> </p>
                                                <p class="m-0 p-0"><?= $row['referencia'] ?></p>
                                            
                                                <?php
                                                $botonesImprimir = [
                                                    ['', base64_encode($Base . 'prints/recibo_ingreso.php?i=' . base64_encode(base64_encode($row['id'])) . ''), 0],
                                                ];
                                                $_GET['botones'] = base64_encode(json_encode($botonesImprimir));
                                                include __DIR__ . '/../creadorImpresiones/seleccionarMetodoImpresion.php';
                                                ?>
                                            </td>
                                        </tr>
                                    <?php } ?>
                                </tbody>
                                <tfoot>
                                    <tr>
                                        <?php foreach ($headers as $value) { ?>
                                            <th><?= $value ?></th>
                                        <?php } ?>
                                    </tr>
                                </tfoot>
                            </table>

                        </div>
                        <div class="card-footer">

                        </div>
                    </div>

                </div>
                <!-- /.col -->
            </div>
            <!-- /.row -->
        </div><!--/. container-fluid -->
    </section>
    <!-- /.content -->
</div>
<!-- /.content-wrapper -->
<?php include __DIR__ . '/../php/footer.php'; ?>