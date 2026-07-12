<?php
include __DIR__ . '/../php/header.php';
$name = 'Gastos / Egresos';
$page = 'gastos';
$table = 'gastos';
$rowConfig = null;

// 21 12 2025
// añadir comision por transacción
$porcentaje_comision = 0;
// buscamos el ultimo registro activo
$queryPorcentaje = "SELECT * from comisiones 
where 1=1
and activo = 1 
order by id desc
limit 1
";
$resultqueryPorcentaje = mysqli_query($conn, $queryPorcentaje);
if (mysqli_num_rows($resultqueryPorcentaje) > 0) {
    $rowPorcentaje = mysqli_fetch_assoc($resultqueryPorcentaje);
    $porcentaje_comision = $rowPorcentaje['porcentaje'];
}
// var_dump($porcentaje_comision);


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
                                    <div class="col-md-12">
                                        <div class="form-group">
                                            <label for="nombre">Categoria</label>
                                            <select name="datos[idCategoria]" class="form-control" id="idCategoria">
                                                <?= selectMaster('where 1=1 and activo = 1', 'id', 'nombre', 'cat_gastos') ?>
                                            </select>
                                        </div>
                                    </div>
                                    <div class="col-md-2">
                                        <div class="form-group">
                                            <label for="nombre">Fecha</label>
                                            <input type="date" class="form-control" id="fecha" name="datos[fecha]" required value="<?= ($rowConfig != null ? $rowConfig['fecha'] : date('Y-m-d')) ?>">
                                        </div>
                                    </div>
                                    <div class="col-md-10">
                                        <div class="form-group">
                                            <label for="nombre">Beneficiario</label>
                                            <input type="text" class="form-control" id="beneficiario" name="datos[beneficiario]" required value="<?= ($rowConfig != null ? $rowConfig['beneficiario'] : null) ?>">
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label for="nombre">Monto</label>
                                            <input type="number" step="0.01" class="form-control" id="monto" name="datos[monto]" required value="<?= ($rowConfig != null ? $rowConfig['monto'] : null) ?>">
                                        </div>
                                    </div>
                                    <div class="col-md-2">
                                        <div class="form-group">
                                            <label for="nombre">Comisión</label>
                                            <input type="number" step="0.01" class="form-control" id="comision" name="datos[comision]" required value="<?= ($rowConfig != null ? $rowConfig['comision'] : null) ?>">
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
                                            <label for="nombre">Referencia [Bancaria]</label>
                                            <input type="text" class="form-control" id="referencia" name="datos[referencia]" required value="<?= ($rowConfig != null ? $rowConfig['referencia'] : null) ?>">
                                        </div>
                                    </div>
                                    <div class="col-md-12">
                                        <div class="form-group">
                                            <label for="nombre">Descripción</label>
                                            <textarea class="form-control" id="descripcion" name="datos[descripcion]"><?= ($rowConfig != null ? $rowConfig['descripcion'] : null) ?></textarea>
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

<script>
    $(document).ready(function() {
        // calcular comisión al cambiar el monto
        $('#monto').on('input', function() {
            let monto = parseFloat($(this).val());
            let porcentaje_comision = <?= $porcentaje_comision ?>;
            let comision = (monto * porcentaje_comision) / 100;
            $('#comision').val(comision.toFixed(2));
        });
    });
</script>