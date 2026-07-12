<?php
include __DIR__ . '/../php/header.php';

$name = 'Ingresos';
$page = 'ingresos';
$table = 'ingresos';
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
                                    <div class="col-md-12">
                                        <div class="form-group">
                                            <label for="nombre">Edificio</label>
                                            <select name="datos[idEdificio]" class="form-control" id="idEdificio">
                                                <?= selectMaster('where 1=1 and activo = 1', 'id', 'nombre', 'edificios') ?>
                                            </select>
                                        </div>
                                    </div>
                                    <div class="col-md-8">
                                        <div class="form-group">
                                            <label for="nombre">Categoria</label>
                                            <select name="datos[idCategoria]" class="form-control" id="idCategoria">
                                                <?= selectMaster('where 1=1 and activo = 1', 'id', 'nombre', 'cat_ingresos') ?>
                                            </select>
                                        </div>
                                    </div>
                                    <div class="col-md-2">
                                        <div class="form-group">
                                            <label for="nombre">Mes</label>
                                            <select name="datos[mes]" class="form-control" id="mes">
                                                <option value="0"> - </option>
                                                <?php
                                                $meses = array(1 => 'Enero', 2 => 'Febrero', 3 => 'Marzo', 4 => 'Abril', 5 => 'Mayo', 6 => 'Junio', 7 => 'Julio', 8 => 'Agosto', 9 => 'Septiembre', 10 => 'Octubre', 11 => 'Noviembre', 12 => 'Diciembre');
                                                for ($m = 1; $m <= 12; $m++) {
                                                    echo '<option value="' . $m . '">' . $m . ' - ' . $meses[$m] . '</option>';
                                                }
                                                ?>
                                            </select>
                                        </div>
                                    </div>
                                    <div class="col-md-2">
                                        <div class="form-group">
                                            <label for="nombre">Año</label>
                                            <input type="number" class="form-control" id="anio" name="datos[anio]" required value="<?= ($rowConfig != null ? $rowConfig['anio'] : date('Y')) ?>">
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
                                            <label for="nombre">Propietario / Beneficiario</label>
                                            <select name="datos[idApartamento]" class="form-control" id="idApartamento">
                                                <option value="0"> - </option>
                                                <?= selectMaster('where 1=1 and activo = 1', 'id', 'apartamento,propietario', 'apartamentos') ?>
                                            </select>
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
                                            <label for="nombre">Moneda</label>
                                            <select name="datos[idMoneda]" class="form-control" id="idMoneda">
                                                <?= selectMaster('where 1=1 and activo = 1', 'id', 'nombre,prefijo', 'monedas') ?>
                                            </select>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="nombre">Referencia [Bancaria]</label>
                                            <input type="text" class="form-control" id="referencia" name="datos[referencia]" required value="<?= ($rowConfig != null ? $rowConfig['referencia'] : null) ?>">
                                        </div>
                                    </div>
                                    <div class="col-md-12">
                                        <div class="form-group">
                                            <label for="nombre">Cuota Extra</label>
                                            <select name="datos[idCuotaExtra]" class="form-control" id="idCuotaExtra">
                                                <option value="0"> - </option>
                                                <?= selectMaster('where 1=1 and activo = 1 order by id desc', 'id', 'fechaRegistro,descripcion', 'cuotasExtra') ?>
                                            </select>
                                        </div>
                                    </div>
                                    <div class="col-md-12">
                                        <div class="form-group">
                                            <label for="nombre">Observaciones</label>
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
    // codigo para llenar automaticamente las notas del recibo
    // por ejemplo: Pago Condominio 7B Febrero 2026
    // escuchar el cambio de los inputs Categoria para saber si es un pago condominio o de cuota extra
    let inputs = [{
            id: 'idCategoria',
            name: 'Categoria'
        },
        {
            id: 'mes',
            name: 'mes de pago'
        },
        {
            id: 'idApartamento',
            name: 'propietario apartamento'
        }
    ];
    // escuchar cambio de cada uno de estos y ejecutar la funcion
    for (let i = 0; i < inputs.length; i++) {
        document.getElementById(inputs[i].id).addEventListener('change', function() {

            let categoria = document.getElementById(inputs[0].id).value;

            let mesSelect = document.getElementById(inputs[1].id);
            let apartamentoSelect = document.getElementById(inputs[2].id);

            let mes = mesSelect.selectedOptions[0].text;
            let apartamento = apartamentoSelect.selectedOptions[0].text;

            let anio = new Date().getFullYear();

            let descripcion = '';

            if (categoria == 1) {
                descripcion = 'Pago Condominio ' + apartamento + ' ' + mes + ' ' + anio;
            } else if (categoria == 2) {
                descripcion = 'Pago Cuota Extra ' + apartamento + ' ' + mes + ' ' + anio;
            }else if (categoria == 3) {
                descripcion = 'Pago ' + apartamento + ' ' + mes + ' ' + anio;
            }

            document.getElementById('descripcion').value = descripcion;
        });
    }
</script>