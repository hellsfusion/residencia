<?php
include __DIR__ . '/../php/header.php';
$name = 'Apartamentos';
$page = 'aptos';
$table = 'apartamentos';
$rowConfig = null;
// var_dump($_GET);
if ($_GET['FAID'] != null) {
    $id = base64_decode($_GET['FAID']);
    $queryConfig = "SELECT * from $table where id = '$id' LIMIT 1";
    $resultConfig = mysqli_query($conn, $queryConfig);
    if (mysqli_num_rows($resultConfig) > 0) {
        $rowConfig = mysqli_fetch_assoc($resultConfig);
    } else {
        echo "<script>alert('No se encontró el registro.');</script>";
    }
}

// datos lista
$headers = [
    'No.',
    'Apto.',
    'Contacto',
    '',
];

// buscar datos de la tabla
$query = "SELECT * from $table
where 1=1
and activo = 1
";
$result = mysqli_query($conn, $query);
$rowData = [];
if ($result && mysqli_num_rows($result) > 0){
    while ($row = mysqli_fetch_assoc($result)) {
        $rowData[] = $row;
    }
}


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
                    <form id="configForm">
                        <div class="card card-secondary <?= ($rowConfig == null ? 'collapsed-card' : null) ?>">
                            <div class="card-header border-transparent">
                                <h3 class="card-title"><?= ($rowConfig != null ? 'Editar' : 'Registrar') ?></h3>
                                <div class="card-tools mt-2">
                                    <button type="button" class="btn btn-tool" data-card-widget="collapse">
                                        <i class="fas <?= ($rowConfig == null ? 'fa-plus' : 'fa-minus') ?>"></i>
                                    </button>
                                </div>
                            </div>
                            <div class="card-body">
                                <div class="row">
                                    <div class="col-md-3">
                                        <div class="form-group">
                                            <label for="nombre">Edificio</label>
                                            <select name="datos[idEdificio]" class="form-control" id="idEdificio">
                                                <?= selectMaster('where 1=1 and activo = 1', 'id', 'nombre', 'edificios') ?>
                                            </select>
                                        </div>
                                    </div>
                                    <div class="col-md-3">
                                        <div class="form-group">
                                            <label for="nombre">Apartamento</label>
                                            <input type="text" class="form-control" id="apartamento" name="datos[apartamento]" required value="<?= ($rowConfig != null ? $rowConfig['apartamento'] : null) ?>">
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="nombre">Propietario</label>
                                            <input type="text" class="form-control" id="propietario" name="datos[propietario]" required value="<?= ($rowConfig != null ? $rowConfig['propietario'] : null) ?>">
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label for="nombre">Nombre [Contacto]</label>
                                            <input type="text" class="form-control" id="contacto" name="datos[contacto]" required value="<?= ($rowConfig != null ? $rowConfig['contacto'] : null) ?>">
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label for="nombre">Teléfono [Contacto]</label>
                                            <input type="text" class="form-control" id="telefono" name="datos[telefono]" required value="<?= ($rowConfig != null ? $rowConfig['telefono'] : null) ?>">
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label for="nombre">Correo [Contacto]</label>
                                            <input type="text" class="form-control" id="correo" name="datos[correo]" required value="<?= ($rowConfig != null ? $rowConfig['correo'] : null) ?>">
                                        </div>
                                    </div>
                                    <div class="col-md-12">
                                        <div class="form-group">
                                            <label for="nombre">Notas / Observaciones</label>
                                            <textarea class="form-control" id="observaciones" name="datos[observaciones]"><?= ($rowConfig != null ? $rowConfig['observaciones'] : null) ?></textarea>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="card-footer">
                                <input type="hidden" name="datos[activo]" value="1">
                                <input type="hidden" name="datos[idUsuario]" value="<?= $_SESSION['id'] ?>">
                                <input type="hidden" name="datos[random]" value="<?= rand(0, 99999) ?>">
                                <button type="submit" class="btn btn-primary" onclick="$('#configForm').automaticForm({type:'<?= ($rowConfig != null ? 2 : 1) ?>', idUpdate:'<?= ($rowConfig != null ? $rowConfig['id'] : null) ?>', table:'<?= $table ?>', reload:'', page:'<?= $page ?>?FAID='});">
                                    <i class="fas fa-save"></i>
                                    Guardar
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
                                        <tr>
                                            <td><?= $row['id'] ?></td>
                                            <td><?= funcionMaster($row['idEdificio'],'id','nombre','edificios') ?> | <?=$row['apartamento']?></td>
                                            <td>
                                                <p class="m-0 p-0"><strong><?= $row['contacto'] ?></strong></p>
                                                <p class="m-0 p-0"><strong>Tel:</strong> <?= $row['telefono'] ?></p>
                                                <p class="m-0 p-0"><strong>Correo:</strong> <?= $row['correo'] ?></p>
                                                </td>
                                            <td class="text-center" style="width: 100px">
                                                <button class="btn btn-success btn-sm btn-block" onclick="window.open('<?= $Base ?>recibos?iA=<?= base64_encode($row['id']) ?>', '_self');" title="Recibos de Ingreso">
                                                    <i class="fas fa-file-invoice"></i>
                                                    Recibos de Ingreso
                                                </button>
                                                <button class="btn btn-primary btn-sm btn-block" onclick="window.open('<?= $page ?>?FAID=<?= base64_encode($row['id']) ?>', '_self');" title="Editar">
                                                    <i class="fas fa-edit"></i>
                                                    Editar
                                                </button>
                                                <button class="btn btn-danger btn-sm btn-block" onclick="automaticUpdateAlert('Eliminar este registro', '0', 'activo', '<?= $table ?>', '<?=$row['id']?>', '');" title="Eliminar">
                                                    <i class="fas fa-trash"></i>
                                                    Eliminar
                                                </button>
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
<?php include __DIR__ . '/../php/footer.php';?>