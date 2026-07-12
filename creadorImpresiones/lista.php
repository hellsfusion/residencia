<?php
include '../header.php';
include '../menu.php';

?>

<div class="content-wrapper p-3">
    <!-- Content Header (Page header) -->
    <section class="content-header">
        <ol class="breadcrumb">
            <li><a href="portada"><i class="fa fa-dashboard"></i> Escritorio</a></li>
            <li><a href="#">Lista de plantillas de impresión</a></li>
        </ol>
    </section>
    <!-- Main content -->
    <section class="content">
        <div class="copyPaste"></div>
        <div class="">
            <div class="col-xs-12">

                <div class="row">
                    <div class="col-md-12">
                        <div class="card card-info">
                            <div class="card-header">
                                <div class="float-left">
                                    <h4>Lista de plantillas de impresión</h4>
                                </div>
                                <!-- botón a la derecha -->
                                <div class="float-right">
                                    <button onclick="window.location.href='configImpresiones'" type="button" class="btn btn-light rounded-pill">
                                        <i class="far fa-copy"></i>
                                        <span>Crear nueva Plantilla</span>
                                    </button>
                                </div>
                            </div>
                            <div class="col-md-12">
                                <div class="card-body">

                                    <div class="table-responsive no-padding">
                                        <table id="Tabla_Rapida_AJAX" class="table table-bordered table-striped" style="font-size:18px">
                                        <?php 
                                                $header = [
                                                    'Nombre',
                                                    'Tipo de Papel',
                                                    'Tamaño',
                                                    '',
                                                ];
                                                ?>
                                            <thead>                                                
                                                <tr>
                                                    <?php foreach ($header as $key => $value) : ?>
                                                        <th><?php echo $value ?></th>
                                                    <?php endforeach ?>
                                                </tr>
                                            </thead>
                                            <tfoot>                                                
                                                <tr>
                                                    <?php foreach ($header as $key => $value) : ?>
                                                        <th><?php echo $value ?></th>
                                                    <?php endforeach ?>
                                                </tr>
                                            </tfoot>
                                        </table>
                                    </div>


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
<!-- /.content-wrapper -->

<?php
include '../footer.php';
?>

<script>
    //version 2 tabla rapida id="Tabla_Rapida_AJAX"
    var titulo_tabla = "Citas";
    query_tabla_ajax = "<?= "SELECT * FROM  configImpresiones where estado = 1"; ?>";

    columnas = ['id', 'fechaRegistro', 'nombrePlantilla', 'tipoPapel', 'AnchoPapel', 'AltoPapel', 'orientacion', 'MargenSuperior', 'MargenInferior', 'MargenIzquierdo', 'MargenDerecho', 'encabezado', 'piePagina', 'usuario_id', 'fecha'];

    columnastablas = [
        {
            "data": "nombrePlantilla"
        },
        {
            "data": function(row, type, set) {
                let tipoPapel = row.tipoPapel.split('|');
                datos = ``;
                datos += `${tipoPapel[0]}`;
                if (row.orientacion == 'portrait') {
                    datos += `<br>Vertical`;
                }else{
                    datos += `<br>Horizontal`;
                }
                return datos;
            }
        },
        {
            "data": function(row, type, set) {
                datos = ``;
                datos += `${row.AnchoPapel}cm x ${row.AltoPapel}cm`;
                return datos;
            }
        },
        {
            "data": function(row, type, set) {
                datos = ``;
                datos += `
                <a href="configImpresiones?FAID=${btoa(row.id)}">
                    <button type="button" class="m-2 btn btn-block btn-outline-info rounded-pill">
                        <i class="far fa-edit"></i>
                        Modificar Plantilla
                    </button>
                </a>`;
                datos += `
                <a onclick="automaticUpdate('0','estado','configImpresiones',${row.id},'configImpresionesLista')">
                    <button type="button" class="m-2 btn btn-block btn-outline-danger rounded-pill">
                        <i class="far fa-trash-alt"></i>
                        Desactivar
                    </button>
                </a>`;
                return datos;
            }
        },
    ];
</script>