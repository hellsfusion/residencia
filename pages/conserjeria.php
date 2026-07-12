<?php
include __DIR__ . '/../php/header.php';

$name = 'Pago Conserjeria';
$page = 'conserjeria';
$table = 'pagos_conserjeria';
$rowConfig = null;

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
                        <div class="card card-secondary">
                            <div class="card-header border-transparent">
                            </div>
                            <div class="card-body">
                                <div class="row">
                                    <div class="col-md-2">
                                        <div class="form-group">
                                            <label for="nombre">Fecha</label>
                                            <input type="date" class="form-control" id="fecha" name="datos[fecha]" required value="<?= ($rowConfig != null ? $rowConfig['fecha'] : date('Y-m-d')) ?>">
                                        </div>
                                    </div>
                                    <div class="col-md-2">
                                        <div class="form-group">
                                            <label for="nombre">Mes cancelado</label>
                                            <select name="datos[mes]" class="form-control" id="mes">
                                                <?php for ($i = 0; $i <= 11; $i++) { ?>
                                                    <option value="<?= $meses[$i] ?>"><?= $meses[$i] ?></option>
                                                <?php } ?>
                                            </select>
                                        </div>
                                    </div>
                                    <div class="col-md-2">
                                        <div class="form-group">
                                            <label for="nombre">Monto</label>
                                            <input type="number" step="0.01" class="form-control" id="monto" name="datos[monto]" required value="<?= ($rowConfig != null ? $rowConfig['monto'] : null) ?>">
                                        </div>
                                    </div>
                                    <div class="col-md-2">
                                        <div class="form-group">
                                            <label for="nombre">Moneda</label>
                                            <select name="datos[idMoneda]" class="form-control" id="idMoneda">
                                                <?= selectMaster('where 1=1 and activo = 1', 'id', 'nombre,prefijo', 'monedas') ?>
                                            </select>
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label for="nombre">Recibido por:</label>
                                            <input type="text" class="form-control" id="recibidoPor" name="datos[recibidoPor]" required value="<?= ($rowConfig != null ? $rowConfig['recibidoPor'] : null) ?>">
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
        </div><!--/. container-fluid -->
    </section>
    <!-- /.content -->
</div>
<!-- /.content-wrapper -->
<?php
include __DIR__ . '/../php/footer.php';
?>