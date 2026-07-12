<?php
include __DIR__ . '/../php/header.php';
// include __DIR__ . '/../php/error_reporting.php';
$rowConfig = null;

// datos lista
$headers = [
    // 'No.',
    'Apto.',
    'Info',
    '',
];
// var_dump($_GET);

// buscar datos de la tabla
$query = "SELECT ap.* from apartamentos ap
where 1=1
and ap.activo = 1
" . ($_GET['datos']['idEdificio'] != null ? "and ap.idEdificio = '{$_GET['datos']['idEdificio']}'" : "") . "
" . ($_GET['datos']['idApartamento'] != 0 ? "and ap.id = '{$_GET['datos']['idApartamento']}'" : "") . "
";
// var_dump($query);
$result = mysqli_query($conn, $query);
$rowData = [];
if ($result && mysqli_num_rows($result) > 0) {
    while ($row = mysqli_fetch_assoc($result)) {
        $rowData[] = $row;
    }
}

// var_dump($rowData);

// buscar datos de los ingresos

if ($_GET['datos']['idCuotaExtra'] != 0) {
    $query = "SELECT * from ingresos
    where 1=1
    and activo = 1
    " . ($_GET['datos']['idEdificio'] != null ? "and idEdificio = '{$_GET['datos']['idEdificio']}'" : "") . "
    " . ($_GET['datos']['idApartamento'] != 0 ? "and idApartamento = '{$_GET['datos']['idApartamento']}'" : "") . "
    " . ($_GET['datos']['idCategoria'] != 0 ? "and idCategoria = '{$_GET['datos']['idCategoria']}'" : "") . "
    " . ($_GET['datos']['idCuotaExtra'] != 0 ? "and idCuotaExtra = '{$_GET['datos']['idCuotaExtra']}'" : "") . "
    ";
} else {
    $query = "SELECT * from ingresos
    where 1=1
    and activo = 1
    " . ($_GET['datos']['idEdificio'] != null ? "and idEdificio = '{$_GET['datos']['idEdificio']}'" : "") . "
    " . ($_GET['datos']['idApartamento'] != 0 ? "and idApartamento = '{$_GET['datos']['idApartamento']}'" : "") . "
    " . ($_GET['datos']['mes'] != null ? "and mes = '{$_GET['datos']['mes']}'" : "") . "
    " . ($_GET['datos']['idCategoria'] != 0 ? "and idCategoria = '{$_GET['datos']['idCategoria']}'" : "") . "
    " . ($_GET['datos']['idCuotaExtra'] != 0 ? "and idCuotaExtra = '{$_GET['datos']['idCuotaExtra']}'" : "") . "
    " . ($_GET['datos']['anio'] != 0 ? "and (anio like '%{$_GET['datos']['anio']}%')" : "") . "
    ";
}


var_dump($query);
$result = mysqli_query($conn, $query);
$rowDataIngresos = [];
if ($result && mysqli_num_rows($result) > 0) {
    while ($row = mysqli_fetch_assoc($result)) {
        $rowDataIngresos[] = $row;
    }
}
// var_dump($rowDataIngresos);


?>
<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <div class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1 class="m-0">Reporte de apartamentos</h1>
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
                    <form id="configForm" method="GET">
                        <div class="card card-secondary">
                            <div class="card-header border-transparent">
                                <h3 class="card-title">Filtros</h3>
                            </div>
                            <div class="card-body">
                                <div class="row">

                                    <div class="col-md-3">
                                        <div class="form-group">
                                            <label for="nombre">Categoria</label>
                                            <select name="datos[idCategoria]" class="form-control" id="idCategoria">
                                                <?php if (isset($_GET['datos']['idCategoria'])) {
                                                    echo '<option selected value="' . $_GET['datos']['idCategoria'] . '">' . funcionMaster($_GET['datos']['idCategoria'], 'id', 'nombre', 'cat_ingresos') . ' - ACTUAL</option>';
                                                } ?>
                                                <?= selectMaster('where 1=1 and activo = 1', 'id', 'nombre', 'cat_ingresos') ?>
                                            </select>
                                        </div>
                                    </div>
                                    <div class="col-md-3">
                                        <div class="form-group">
                                            <label for="nombre">Edificio</label>
                                            <select name="datos[idEdificio]" class="form-control" id="idEdificio">
                                                <?php if (isset($_GET['datos']['idEdificio'])) {
                                                    echo '<option selected value="' . $_GET['datos']['idEdificio'] . '">' . funcionMaster($_GET['datos']['idEdificio'], 'id', 'nombre', 'edificios') . ' - ACTUAL</option>';
                                                } ?>
                                                <?= selectMaster('where 1=1 and activo = 1', 'id', 'nombre', 'edificios') ?>
                                            </select>
                                        </div>
                                    </div>

                                    <div class="col-md-3">
                                        <div class="form-group">
                                            <label for="nombre">Mes</label>
                                            <select name="datos[mes]" class="form-control" id="mes">
                                                <?php
                                                $meses = array(1 => 'Enero', 2 => 'Febrero', 3 => 'Marzo', 4 => 'Abril', 5 => 'Mayo', 6 => 'Junio', 7 => 'Julio', 8 => 'Agosto', 9 => 'Septiembre', 10 => 'Octubre', 11 => 'Noviembre', 12 => 'Diciembre');
                                                for ($m = 1; $m <= 12; $m++) {
                                                    echo '<option value="' . $m . '" ' . ($_GET['datos']['mes'] == $m ? 'selected' : '') . ' >' . $m . ' - ' . $meses[$m] . '</option>';
                                                }
                                                ?>
                                            </select>
                                        </div>
                                    </div>
                                    <div class="col-md-3">
                                        <div class="form-group">
                                            <label for="nombre">Año</label>
                                            <input type="number" class="form-control" id="anio" name="datos[anio]" value="<?= ($_GET['datos']['anio'] != null ? $_GET['datos']['anio'] : date('Y')) ?>">
                                        </div>
                                    </div>
                                    <div class="col-md-3">
                                        <div class="form-group">
                                            <label for="nombre">Cuota Extra</label>
                                            <select name="datos[idCuotaExtra]" class="form-control" id="idCuotaExtra">
                                                <option value="0"> - </option>
                                                <?php if (isset($_GET['datos']['idCuotaExtra'])) {
                                                    echo '<option selected value="' . $_GET['datos']['idCuotaExtra'] . '">' . funcionMaster($_GET['datos']['idCuotaExtra'], 'id', 'descripcion', 'cuotasExtra') . ' - ACTUAL</option>';
                                                } ?>
                                                <?= selectMaster('where 1=1 and activo = 1', 'id', 'fechaRegistro,descripcion', 'cuotasExtra') ?>
                                            </select>
                                        </div>
                                    </div>
                                    <div class="col-md-9">
                                        <div class="form-group">
                                            <label for="nombre">Propietario / Beneficiario</label>
                                            <select name="datos[idApartamento]" class="form-control" id="idApartamento">
                                                <option value="0"> TODOS </option>
                                                <?php if (isset($_GET['datos']['idApartamento'])) {
                                                    echo '<option selected value="' . $_GET['datos']['idApartamento'] . '">' . funcionMaster($_GET['datos']['idApartamento'], 'id', 'propietario', 'apartamentos') . ' - ACTUAL</option>';
                                                } ?>
                                                <?= selectMaster('where 1=1 and activo = 1', 'id', 'apartamento,propietario', 'apartamentos') ?>
                                            </select>
                                        </div>
                                    </div>

                                </div>
                            </div>
                            <div class="card-footer">
                                <button type="submit" class="btn btn-primary">
                                    <i class="fas fa-save"></i>
                                    Filtrar
                                </button>
                            </div>
                        </div>
                    </form>
                </div>
                <!-- /.col -->
            </div>
            <!-- /.row -->


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
                                        <?php
                                        // recorrer los ingresos para ver si conseguimos el de este apartamento
                                        $datosIngreso = null;
                                        foreach ($rowDataIngresos as $ingreso) {
                                            if ($ingreso['idApartamento'] == $row['id']) {
                                                $datosIngreso = $ingreso;
                                                break;
                                            }
                                        }
                                        ?>
                                        <tr class="<?= ($datosIngreso == null ? 'table-danger' : '') ?>">
                                            <td>
                                                <?= funcionMaster($row['idEdificio'], 'id', 'nombre', 'edificios') ?> | <?= $row['apartamento'] ?>
                                                <p class="m-0 p-0"><strong><?= $row['contacto'] ?></strong></p>
                                                <p class="m-0 p-0"><strong>Tel:</strong> <?= $row['telefono'] ?></p>
                                                <p class="m-0 p-0"><strong>Correo:</strong> <?= $row['correo'] ?></p>
                                            </td>
                                            <td>

                                                <?php if ($datosIngreso != null) { ?>
                                                    <p class="m-0 p-0"><strong>No:</strong> <?= $datosIngreso['id'] ?></p>
                                                    <p class="m-0 p-0"><strong>Monto:</strong> <?= $datosIngreso['monto'] ?> <?= funcionMaster($datosIngreso['idMoneda'], 'id', 'prefijo', 'monedas') ?> </p>
                                                    <p class="m-0 p-0"><strong>Fecha de ingreso:</strong> <?= $datosIngreso['fecha'] ?></p>
                                                <?php } else { ?>
                                                    <p class="m-0 p-0">Sin Información</p>
                                                <?php } ?>
                                            </td>
                                            <td class="text-center" style="width: 100px">

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