<?php
include __DIR__ . '/../php/header.php';
include __DIR__ . '/../php/error_reporting.php';
include __DIR__ . '/maestros_list.php'; // lista de maestros (tablas de datos estaticos o generales)

// array de maestros
// ver pantos.php
// array de maestros


?>
<div class="content-wrapper p-3">
    <!-- Main content -->
    <section class="content">
        <div class="">
            <div class="col-xs-12">

                <div class="row">
                    <div class="col-md-12">
                        <div class="card card-secondary">
                            <div class="card-header">
                                <div class="float-left">
                                    <h4>Maestros internos</h4>
                                </div>
                            </div>

                            <div class="card-body">
                                <div class="row">
                                    <?php foreach ($arrayMaestros as $maestro) { ?>
                                        <div class="col-lg-4 col-6">
                                            <div class="small-box bg-light">
                                                <div class="inner">
                                                    <p style="text-overflow: ellipsis; white-space: nowrap; overflow: hidden;" title="<?= $maestro['nombre'] ?>">
                                                        <span><?= $maestro['nombre'] ?></span>
                                                    </p>
                                                    <h3><?= 0 + floatval(funcionMaster('1', ' activo = 1 and 1', 'count(*)', $maestro['tabla'])) ?></h3>
                                                </div>
                                                <div class="icon">
                                                    <i class="<?= $maestro['icono'] ?> text-danger" style="opacity: 0.30;"></i>
                                                </div>
                                                <a href="#" data-toggle="modal" data-target="#modalConfigOption_<?= $maestro['tabla'] ?>" class="small-box-footer">Ver / Agregar <i class="fas fa-arrow-circle-right"></i></a>
                                            </div>
                                        </div>
                                    <?php } ?>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

            </div>
            <!-- /.col -->
        </div>
        <!-- /.row -->
    </section>
    <!-- /.content -->

</div>
<?php include __DIR__ . '/../php/footer.php'; ?>
<script>
    // maestro
    const maestros = <?= json_encode($arrayMaestros) ?>;
    for (i = 0; i < maestros.length; i++) {        
        configOptions(maestros[i].tabla, maestros[i].formulario,0 , maestros[i].nombre);
    }
</script>