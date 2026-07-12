<?php
// este archivo recibe como parametro get la url en base64 que queramos imprimir
// $urlImprimir = ($_GET['urlImprimir']);
include __DIR__ .'/../php/conn.php';
// include '../error_reporting.php';

// se consultan las plantillas registradas en el sistema
// $queryPlantillas = "SELECT id,nombrePlantilla FROM configImpresiones where estado = 1";
// $resultPlantillas = mysqli_query($conn3, $queryPlantillas);
// $plantillas = [];
// while ($rowPlantillas = mysqli_fetch_assoc($resultPlantillas)) {
//     $plantillas[] = $rowPlantillas;
// }
$plantillas[] = ['id' => 0, 'nombrePlantilla' => 'Sin plantilla'];
?>
<?php
if ($_GET['botones']) {
    $botones = json_decode(base64_decode($_GET['botones']));
    // var_dump($botones);
?>
    <?php for ($i = 0; $i < count($botones); $i++) : ?>
        <div class="center text-center align-items-center" align="center">
            <button type="button" class="btn btn-outline-info btn-sm btn-block" onclick="metodosImpresion(1,'<?= $botones[$i][1] ?>','<?= $botones[$i][2] ?>');">
                <i class="fas fa-print"></i>
                Imprimir <strong><?= $botones[$i][0] ?></strong>
            </button>
            <button type="button" class="btn btn-outline-danger btn-sm btn-block" onclick="metodosImpresion(0,'<?= $botones[$i][1] ?>','<?= $botones[$i][2] ?>');">
                <i class="fas fa-file-pdf"></i>
                <strong><?= $botones[$i][0] ?></strong> PDF
            </button>
        </div>
    <?php endfor ?>
<?php } ?>



<script>
    function metodosImpresion(pre, url, ticket = 0) {
        const text = (pre == 0 ? 'Descargar PDF' : 'Imprimir');
        // alert('Metodos de Impresion');
        swal.fire({
            title: 'Seleccione una plantilla para imprimir el documento',
            text: 'Estas plantillas las puede crear y editar en el sistema desde el botón "Agregar plantilla"',
            icon: 'info',
            input: 'select',
            inputOptions: {
                <?php foreach ($plantillas as $plantilla) { ?> '<?php echo $plantilla['id']; ?>': '<?php echo $plantilla['id']; ?> | <?php echo $plantilla['nombrePlantilla']; ?>',
                <?php } ?>
            },
            showCancelButton: true,
            confirmButtonText: text,
            cancelButtonText: 'Cancelar',
            confirmButtonColor: '#3085d6',
            inputValidator: (value) => {
                if (!value) {
                    return 'Por favor seleccione una plantilla'
                } else {
                    return null
                }
            },
        }).then((result) => {
            console.log(result);
            if (result.isConfirmed) {
                window.open('visualizarDocumento?i=' + btoa(result.value) + '&uZ=' + url + '&pre=' + pre + '&ticket=' + ticket, '_blank');
            }
        })
    }
</script>