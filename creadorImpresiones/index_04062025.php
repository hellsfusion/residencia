<?php
include '../header.php';
include '../menu.php';

$table = 'configImpresiones';
$FAID = base64_decode($_GET['FAID']);
if (!is_numeric($FAID)) {
    $FAID = 0;
}
$resultConfig = null;
if ($FAID > 0){
    $queryConfig = "SELECT * FROM $table WHERE id = $FAID";
    $resultConfig = mysqli_query($conn3, $queryConfig);
}


if ($resultConfig) {
    $rowConfig = mysqli_fetch_assoc($resultConfig);
}

if ($_GET['FAID'] && $rowConfig != null) {
    $css = "

     * {
         print-color-adjust: exact !important;
         color-adjust: exact !important;
         text-rendering: optimizeLegibility !important;
         -webkit-print-color-adjust: exact !important;
         -webkit-color-adjust: exact !important;
         -moz-print-color-adjust: exact !important;
         -moz-color-adjust: exact !important;
     }
@import url('http://fonts.cdnfonts.com/css/tahoma');

@page {
    margin: 0cm 0cm !important;
    font-family: 'Tahoma', sans-serif !important;
    size: " . $rowConfig['AnchoPapel'] . "cm " . $rowConfig['AltoPapel'] . "cm " . $rowConfig['orientacion'] . ";
    " . (($rowConfig['orientacion'] == 'portrait'  && $rowConfig['impresion'] == 'M') ? 'margin-bottom: ' . (($rowConfig['AltoPapel'] / 2) - $rowConfig['MargenInferior']) . 'cm !important;' : (($rowConfig['orientacion'] == 'landscape' && $rowConfig['impresion'] == 'M') ? 'margin-right: ' . (($rowConfig['AltoPapel'] / 2) - $rowConfig['MargenDerecho']) . 'cm !important;'  : '')) . "
}

/** Defina ahora los márgenes reales de cada página en el PDF **/
body {
    margin-top: " . ($rowConfig['MargenSuperior'] + $rowConfig['encabezadoH']) . "cm !important;
    margin-left: " . $rowConfig['MargenIzquierdo'] . "cm !important;
    margin-right: " . $rowConfig['MargenDerecho'] . "cm !important;
    margin-bottom: " . ($rowConfig['MargenInferior'] + $rowConfig['pieH']) . "cm !important;
    font-family: 'Tahoma', sans-serif !important;
}

/** Definir las reglas del encabezado **/
#header {
    position: fixed !important;
    top: 0cm !important;
    left: 0cm !important;
    right: 0cm !important;
    height: " . ($rowConfig['encabezadoH']) . "cm !important;
    padding-top: " . ($rowConfig['MargenSuperior']) . "cm !important;
    padding-left: " . $rowConfig['MargenIzquierdo'] . "cm !important;
    padding-right: " . $rowConfig['MargenDerecho'] . "cm !important;
}

/** Definir las reglas del pie de página **/
#footer {
    position: fixed !important;
    bottom: 0cm !important;
    left: 0cm !important;
    right: 0cm !important;
    height: " . ($rowConfig['pieH']) . "cm !important;
    padding-bottom: " . ($rowConfig['MargenInferior']) . "cm !important;
    padding-left: " . $rowConfig['MargenIzquierdo'] . "cm !important;
    padding-right: " . $rowConfig['MargenDerecho'] . "cm !important;
}

#page {
    width: 100% !important;
}
    h1,
    h2,
    h3,
    h4,
    h5,
    h6,
    p,
    div,
    td {
        page-break-before: auto !important;
        page-break-after: auto !important;
        page-break-inside: auto !important;
    }
";
    if ($rowConfig['marcaAgua'] == 1) {
        // tiene marca de agua
        $marcaAgua = $rowConfig['marcaAgua']; // 1 si / 0 no tiene marca de agua
        $marcaAguaPosicion = $rowConfig['marcaAguaPosicion']; // 0 arriba / 1 centro / 2 abajo / 3 izquierda / 4 derecha / 5 mosaico
        $marcaAguaImg = $rowConfig['marcaAguaImg']; // ruta de la imagen

        $posicion = ($marcaAguaPosicion == 0 ? 'top center' : (($marcaAguaPosicion == 1) ? 'center' : (($marcaAguaPosicion == 2) ? 'bottom center' : (($marcaAguaPosicion == 3) ? 'left center' : (($marcaAguaPosicion == 4) ? 'right center' : 'center')))));

        $css .= "
    .marcaAgua {        
        width: 100% !important;
        height: 100% !important;
        opacity: 0.1;
        position: fixed;
        background-image: url('" . $Base . "/creadorImpresiones/config/marcas/" . $marcaAguaImg . "');
        background-repeat: " . ($marcaAguaPosicion <> 5 ? "no-repeat" : "repeat") . ";
        " . ($marcaAguaPosicion <> 5 ? "background-position: " . $posicion . ";"  : "background-position: top left;") . "        
        top: " . ($rowConfig['MargenSuperior'] + $rowConfig['encabezadoH']) . "cm !important;
        left: " . $rowConfig['MargenIzquierdo'] . "cm !important;
        right: " . $rowConfig['MargenDerecho'] . "cm !important;
        bottom: " . ($rowConfig['MargenInferior'] + $rowConfig['pieH']) . "cm !important;
    }    
    ";
    }

    // crear un archivo css si no existe
    file_put_contents('config/' . $rowConfig['id'] . '.css', $css);
}

?>

<div class="content-wrapper p-3">
    <!-- Content Header (Page header) -->
    <section class="content-header">
        <ol class="breadcrumb">
            <li><a href="portada"><i class="fa fa-dashboard"></i> Escritorio</a></li>
            <li><a href="#">Configuración de impresión</a></li>
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
                                    <?php if ($rowConfig != null) : ?>
                                        <h4>
                                            Editar plantilla - <strong><?= $rowConfig['nombrePlantilla'] ?></strong>
                                            <!-- <button onclick="window.location.href='configImpresiones'" type="button" class="btn btn-light rounded-pill">
                                                <i class="far fa-copy"></i>
                                                <span>Crear nueva Plantilla</span>
                                            </button> -->
                                        </h4>
                                    <?php else : ?>
                                        <h4>Creando una nueva plantilla de impresión</h4>
                                    <?php endif ?>

                                </div>
                                <!-- botón a la derecha -->
                                <div class="float-right">
                                    <button onclick="window.location.href='configImpresionesLista'" type="button" class="btn btn-light rounded-pill">
                                        <i class="far fa-copy"></i>
                                        <span>Mis plantillas</span>
                                    </button>
                                    <button class="btn btn-light rounded-pill" data-toggle="modal" data-target="#modalImpresion">
                                        <i class="fas fa-question"></i>
                                    </button>
                                </div>
                            </div>
                            <form id="papelForm" class="row">
                                <div class="col-md-4 center text-center align-items-center pt-3">
                                    <label for="">Hoja de referencia</label>
                                    <br>
                                    <!-- <i id="hojaFalsa" class="far fa-file" style="font-size: 20rem !important;"></i> -->
                                    <div id="hojaFalsa" style="height:18rem;
                                    width:14rem;
                                    background-color: rgba(0, 0, 0, 0.5);
                                    padding-left: 10PX;
                                    padding-right: 10PX;
                                    padding-top: 10PX;
                                    padding-bottom: 10PX;
                                    border-radius: 0.5rem;
                                    display: inline-block;
                                    ">
                                        <div style="height: 100%; width: 100%; background-color: #fff; border-radius: 0.5rem; border: 1px solid #000">
                                            <p>.......</p>
                                        </div>
                                    </div>
                                    <p style="font-size: 0.8rem;" class="mt-2 p-3 text-muted">*Esta es una representación estimada de la hoja real, no es una representación precisa o exacta para tomar en cuenta los margenes y detalles de la misma*</p>
                                    <?php if ($rowConfig != null) : ?>
                                        <a href="configImpresionesTest?t=1&i=<?= base64_encode($FAID) ?>&pre=1" target="_blank" class="btn btn-outline-info  rounded-pill">
                                            <i class="fas fa-copy"></i>
                                            Probar la plantilla
                                        </a>
                                    <?php endif ?>

                                </div>
                                <div class="col-md-8">
                                    <div class="card-body row">
                                        <div class="form-group col-sm-12">
                                            <label for="">Nombre de la plantilla</label>
                                            <input type="text" class="form-control" id="nombrePlantilla" required name="datos[nombrePlantilla]" value="<?= $rowConfig['nombrePlantilla'] ?>">
                                        </div>
                                        <div class="form-group col-sm-12">
                                            <label for="">Tamaño de la hoja</label>
                                            <select name="datos[tipoPapel]" class="form-control" id="tipoPapel" onchange="cambiarTipoPapel()">
                                                <?php
                                                // tipo de papel ['nombre', 'Ancho', 'Alto'] en cm
                                                $tipos = explode('|', $rowConfig['tipoPapel']);
                                                $tipoPapel = [
                                                    ['Carta', 21.6, 27.9],
                                                    ['Oficio', 22.0, 34.0],
                                                    ['A4', 21.0, 29.7],
                                                    ['A5', 14.8, 21.0],
                                                    ['A6', 10.5, 14.8],
                                                    ['Personalizado', 0, 0],
                                                    [$tipos[0], $rowConfig['AnchoPapel'], $rowConfig['AltoPapel']],
                                                ];
                                                ?>
                                                <?php foreach ($tipoPapel as $key => $value) : ?>
                                                    <option value="<?= $value[0] ?>|<?= $value[1] ?>|<?= $value[2] ?>" <?= $tipos[0] == $value[0] ? 'selected' : '' ?>><?= $value[0] ?></option>
                                                <?php endforeach ?>
                                            </select>
                                        </div>
                                        <div class="form-group col-sm-6">
                                            <label for="">Ancho</label>
                                            <input type="number" class="form-control" id="AnchoPapel" min="0" step="0.1" required name="datos[AnchoPapel]" value="<?= $rowConfig['AnchoPapel'] ?>" oninput="verificarTamano()">
                                            <label for="" class="text-muted font-italic">Tamaño en cm</label>
                                        </div>
                                        <div class="form-group col-sm-6">
                                            <label for="">Alto</label>
                                            <input type="number" class="form-control" id="AltoPapel" min="0" step="0.1" required name="datos[AltoPapel]" value="<?= $rowConfig['AltoPapel'] ?>" oninput="verificarTamano()">
                                            <label for="" class="text-muted font-italic">Tamaño en cm</label>
                                        </div>
                                        <div class="form-group col-sm-12">
                                            <hr>
                                        </div>

                                        <div class="form-group col-sm-6">
                                            <label for="">Margen Superior</label>
                                            <input type="number" class="form-control" id="MargenSuperior" min="0" max="9" step="1" required name="datos[MargenSuperior]" value="<?= ($rowConfig['MargenSuperior'] > 0 ? $rowConfig['MargenSuperior'] : 0) ?>">
                                            <label for="" class="text-muted font-italic">Tamaño en cm</label>
                                        </div>
                                        <div class="form-group col-sm-6">
                                            <label for="">Margen Inferior</label>
                                            <input type="number" class="form-control" id="MargenInferior" min="0" max="9" step="1" required name="datos[MargenInferior]" value="<?= ($rowConfig['MargenInferior'] > 0 ? $rowConfig['MargenInferior'] : 0) ?>">
                                            <label for="" class="text-muted font-italic">Tamaño en cm</label>
                                        </div>
                                        <div class="form-group col-sm-6">
                                            <label for="">Margen Izquierdo</label>
                                            <input type="number" class="form-control" id="MargenIzquierdo" min="0" max="8" step="1" required name="datos[MargenIzquierdo]" value="<?= ($rowConfig['MargenIzquierdo'] > 0 ? $rowConfig['MargenIzquierdo'] : 0) ?>">
                                            <label for="" class="text-muted font-italic">Tamaño en cm</label>
                                        </div>
                                        <div class="form-group col-sm-6">
                                            <label for="">Margen Derecho</label>
                                            <input type="number" class="form-control" id="MargenDerecho" min="0" max="8" step="1" required name="datos[MargenDerecho]" value="<?= ($rowConfig['MargenDerecho'] > 0 ? $rowConfig['MargenDerecho'] : 0) ?>">
                                            <label for="" class="text-muted font-italic">Tamaño en cm</label>
                                        </div>
                                        
                                        <div class="form-group col-sm-12">
                                            <hr>
                                        </div>

                                        <div class="form-group col-sm-6">
                                            <label for="">Orientación</label>
                                            <select name="datos[orientacion]" class="form-control" id="orientacion">
                                                <option value="portrait" <?= $rowConfig['orientacion'] == 'portrait' ? 'selected' : '' ?>>Vertical</option>
                                                <option value="landscape" <?= $rowConfig['orientacion'] == 'landscape' ? 'selected' : '' ?>>Horizontal</option>
                                            </select>
                                        </div>
                                        <div class="form-group col-sm-6">
                                            <label for="">Tipo de impresión</label>
                                            <select name="datos[impresion]" class="form-control" id="impresion">
                                                <option value="C" <?= $rowConfig['impresion'] == 'C' ? 'selected' : '' ?>>Pagina completa</option>
                                                <option value="M" <?= $rowConfig['impresion'] == 'M' ? 'selected' : '' ?>>Media Pagina</option>
                                            </select>
                                        </div>

                                    </div>
                                </div>
                                <div class="col-md-12">
                                    <div class="card-body">
                                        <div class="form-group">
                                            <label for="">Encabezado</label>
                                            <textarea name="datos[encabezado]" class="editorJR" id="encabezado"><?= $rowConfig['encabezado'] ?></textarea>
                                            <input type="number" class="form-control mt-2" id="encabezadoH" min="0" step="1" required name="datos[encabezadoH]" value="<?= ($rowConfig['encabezadoH'] > 0 ? $rowConfig['encabezadoH'] : 2) ?>">
                                            <label for="" class="text-muted font-italic">Altura del encabezado en cm</label>
                                        </div>
                                        <div class="form-group">
                                            <label for="">Pie de página</label>
                                            <textarea name="datos[piePagina]" class="editorJR" id="piePagina"><?= $rowConfig['piePagina'] ?></textarea>
                                            <input type="number" class="form-control mt-2" id="pieH" min="0" step="1" required name="datos[pieH]" value="<?= ($rowConfig['pieH'] > 0 ? $rowConfig['pieH'] : 2) ?>">
                                            <label for="" class="text-muted font-italic">Altura del pie de página en cm</label>
                                        </div>
                                    </div>
                                </div>

                                <div class="card-body row">
                                    <div class="form-group col-sm-4">
                                        <label for="">Marca de agua</label>
                                        <select name="datos[marcaAgua]" class="form-control" id="marcaAgua">
                                            <option value="1" <?= ($rowConfig['marcaAgua'] == 1 ? 'selected' : '') ?>>Si</option>
                                            <option value="0" <?= ($rowConfig['marcaAgua'] == 0 ? 'selected' : '') ?>>No</option>
                                        </select>
                                    </div>
                                    <div class="form-group col-sm-4">
                                        <label for="">Imagen</label>
                                        <input type="file" class="form-control" id="imagen" name="archivos[marcaAguaImg|./creadorImpresiones/config/marcas/]" accept="image/*">
                                        <?php if ($rowConfig['marcaAguaImg'] != '' && $rowConfig['marcaAgua'] == 1) : ?>
                                            <img src="<?= $Base ?>/creadorImpresiones/config/marcas/<?= $rowConfig['marcaAguaImg'] ?>" alt="" class="img-fluid w-100 p-4" style="width:100%; height:auto; opacity:0.1">
                                        <?php endif ?>
                                    </div>
                                    <div class="form-group col-sm-4">
                                        <label for="">Posición</label>
                                        <select name="datos[marcaAguaPosicion]" class="form-control" id="marcaAguaPosicion">
                                            <option value="0" <?= ($rowConfig['marcaAguaPosicion'] == 0 ? 'selected' : '') ?>>Arriba</option>
                                            <option value="1" <?= ($rowConfig['marcaAguaPosicion'] == 1 ? 'selected' : '') ?>>Centro</option>
                                            <option value="2" <?= ($rowConfig['marcaAguaPosicion'] == 2 ? 'selected' : '') ?>>Abajo</option>
                                            <option value="3" <?= ($rowConfig['marcaAguaPosicion'] == 3 ? 'selected' : '') ?>>Izquierda</option>
                                            <option value="4" <?= ($rowConfig['marcaAguaPosicion'] == 4 ? 'selected' : '') ?>>Derecha</option>
                                            <option value="5" <?= ($rowConfig['marcaAguaPosicion'] == 5 ? 'selected' : '') ?>>Mosaico</option>
                                        </select>
                                    </div>
                                </div>





                                <div class="card-footer w-100">
                                    <input type="hidden" name="datos[usuario_id]" value="<?= $_SESSION['ID'] ?>">
                                    <input type="hidden" name="datos[fecha]" value="<?= date('y-m-d H:i:s') ?>">
                                    <input type="hidden" name="datos[estado]" value="1">
                                    <button type="submit" class="btn btn-outline-info rounded-pill" onclick="$('#papelForm').automaticForm({type:<?= ($rowConfig == null ? 1 : 2) ?>, table:'configImpresiones',idUpdate:'<?= ($rowConfig == null ? 0 : $rowConfig['id']) ?>',reload:'',page:'configImpresiones'});">
                                        <i class="fa fa-save mr-1"></i>
                                        Guardar
                                    </button>
                                </div>
                            </form>
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

<!-- Modal -->
<div class="modal fade" id="modalImpresion" tabindex="-1" role="dialog" aria-labelledby="modelTitleId" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-body">
                <p>En este panel puedes crear y editar hojas de impresión para tus documentos de acuerdo a tus necesidades de manera fácil y rápida. </p>
                <p>Ajusta el Tamaño de la hoja de impresión, los margenes y la orientación asi como textos de cabecera y pie de página para tus documentos.</p>
                <div class="row center text-center align-items-center">
                    <div class="col-sm-12">
                        <i class="fas fa-print fa-4x text-info m-1"></i>
                        <i class="far fa-file-alt fa-4x text-maroon m-1"></i>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-outline-secondary rounded-pill" data-dismiss="modal">Cerrar</button>
            </div>
        </div>
    </div>
</div>

<?php
include '../footer.php';
?>

<script>
    $(document).ready(function() {
        setTimeout(() => {
            cambiarTipoPapel();
            mueveloMuevelo();
        }, 500);
    });

    function cambiarTipoPapel() {
        let tipoPapel = document.getElementById('tipoPapel').value;
        tipoPapel = tipoPapel.split('|');
        let AnchoPapel = tipoPapel[1];
        let AltoPapel = tipoPapel[2];
        if (AnchoPapel[0] != 'Personalizado') {
            document.getElementById('AltoPapel').value = AltoPapel;
            document.getElementById('AnchoPapel').value = AnchoPapel;
        }
    }

    function verificarTamano() {
        let tipoPapel = document.getElementById('tipoPapel').value;
        tipoPapel = tipoPapel.split('|');
        let AnchoPapel = tipoPapel[1];
        let AltoPapel = tipoPapel[2];
        // -------------------------------
        let AnchoInput = document.getElementById('AnchoPapel').value;
        let AltoInput = document.getElementById('AltoPapel').value;

        if (AnchoInput != AnchoPapel || AltoInput != AltoPapel) {
            // si el tamaño es diferente a la base del papel entonces se cambia a personalizado
            document.getElementById('tipoPapel').value = 'Personalizado|0|0';
        }
    }

    function mueveloMuevelo() {
        // para la hoja falsa
        let orientacion = document.getElementById('orientacion').value;
        let MargenSuperior = (document.getElementById('MargenSuperior').value > 25 ? 25 * 10 : document.getElementById('MargenSuperior').value * 10);
        let MargenInferior = (document.getElementById('MargenInferior').value > 25 ? 25 * 10 : document.getElementById('MargenInferior').value * 10);
        let MargenIzquierdo = (document.getElementById('MargenIzquierdo').value > 8 ? 8 * 10 : document.getElementById('MargenIzquierdo').value * 10);
        let MargenDerecho = (document.getElementById('MargenDerecho').value > 8 ? 8 * 10 : document.getElementById('MargenDerecho').value * 10);


        if (orientacion === 'portrait') {
            orientacion = 0;
        } else {
            orientacion = 90;
        }

        let hojaFalsa = document.getElementById('hojaFalsa');
        hojaFalsa.style.transform = `rotate(${orientacion}deg)`;
        hojaFalsa.style.paddingTop = `${MargenSuperior}px`;
        hojaFalsa.style.paddingBottom = `${MargenInferior}px`;
        hojaFalsa.style.paddingLeft = `${MargenIzquierdo}px`;
        hojaFalsa.style.paddingRight = `${MargenDerecho}px`;

        // mas validaciones
        // el encabezado + el pie de pagina no pueden ser mayor a la altura de la hoja si es vertical o a la ancho si es horizontal
        let sumaEncabezado = parseInt(document.getElementById('encabezadoH').value) + parseInt(document.getElementById('pieH').value);
        if (orientacion === 'portrait') {
            // vertical
            if (sumaEncabezado >= parseInt(document.getElementById('AltoPapel').value) - parseInt(document.getElementById('MargenSuperior').value) - parseInt(document.getElementById('MargenInferior').value)) {
                alert('El encabezado + el pie de pagina no puede ser mayor a la altura de la hoja');
                document.getElementById('encabezadoH').value = 0;
                document.getElementById('pieH').value = 0;
            }
        } else {
            // horizontal
            if (sumaEncabezado >= parseInt(document.getElementById('AnchoPapel').value) - parseInt(document.getElementById('MargenSuperior').value) - parseInt(document.getElementById('MargenInferior').value)) {
                alert('El encabezado + el pie de pagina no puede ser mayor a la altura de la hoja');
                document.getElementById('encabezadoH').value = 0;
                document.getElementById('pieH').value = 0;
            }
        }

    }

    // escuchamos cualquier cambio en el formulario para saber la orientación, margenes y tipo de la hoja
    $('#papelForm').on('change', function(e) {
        mueveloMuevelo();
    });
</script>