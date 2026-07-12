<?php
include __DIR__ . '/../php/header.php';
// include __DIR__ . '/../php/error_reporting.php';
$name = 'Generar Documentos';
$page = 'crearDocumentos';
$table = 'documentos_internos';
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
    'Nombre',
    '',
];

// buscar datos de la tabla
// $query = "SELECT * from $table
// where 1=1
// and activo = 1
// ";
// $result = mysqli_query($conn, $query);
// $rowData = [];
// if ($result && mysqli_num_rows($result) > 0) {
//     while ($row = mysqli_fetch_assoc($result)) {
//         $rowData[] = $row;
//     }
// }


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
                                    <div class="col-md-12">
                                        <div class="form-group">
                                            <label for="nombre">Nombre</label>
                                            <input type="text" class="form-control" id="nombre" name="datos[nombre]" required value="<?= ($rowConfig != null ? $rowConfig['nombre'] : null) ?>">
                                        </div>
                                    </div>
                                    <div class="col-md-12">
                                        <div class="form-group">
                                            <label for="nombre">Documento</label>
                                            <textarea name="datos[documento]" class="editorJR" placeholder="Documento" id="documento"><?= $rowConfig['documento'] ?></textarea>
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
                                            <td><?= $row['nombre'] ?></td>
                                            <td style="width: 10em;">
                                                <a href="<?= $Base ?>uploads/documentos/<?= $row['archivo'] ?>" target="_blank" class="btn btn-default">
                                                    <i class="fas fa-download"></i>
                                                    Ver / Descargar
                                                </a>
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