<?php 
session_start();
include "../funciones/funciones.php";

$estilo = base64_decode($_GET['FAID']);
$queryEstilo = "SELECT * FROM configImpresiones WHERE id = $estilo LIMIT 1";
// var_dump($queryEstilo);
$resultadoEstilo = mysqli_query($conn3, $queryEstilo);
$rowEstilo = mysqli_fetch_assoc($resultadoEstilo);


function file_get_contents_curl($url)
{
    $crl = curl_init();
    $timeout = 8;
    curl_setopt($crl, CURLOPT_URL, $url);
    curl_setopt($crl, CURLOPT_RETURNTRANSFER, 1);
    curl_setopt($crl, CURLOPT_CONNECTTIMEOUT, $timeout);
    $ret = curl_exec($crl);
    curl_close($crl);
    return $ret;
}

// disable cache
header('Cache-Control: no-cache, no-store, must-revalidate');
header('Pragma: no-cache');
header('Expires: 0');

?>

<!DOCTYPE html>
<html lang="es">

<style>
    /* vamos a poner una imagen como marca de agua en el centro de la hoja */
    .marcaAgua {        
        width: 100% !important;
        height: 100% !important;
        opacity: 0.3;
        position: fixed;
        background-image: url("<?=$Base?>/creadorImpresiones/config/marcas/<?= $marcaAguaImg ?>");
        background-repeat: no-repeat;
        background-position: top center;
    }

</style>

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="<?=$Base?>creadorImpresiones/config/<?= $estilo ?>.css">
    <title>Document</title>
    <!-- disable cache -->
    <meta http-equiv="Cache-Control" content="no-cache, no-store, must-revalidate">
    <meta http-equiv="Pragma" content="no-cache">
    <meta http-equiv="Expires" content="0">
</head>



<body>
    <div class="marcaAgua"></div>
    <div id="header" style="background:rgba(0,0,255,0.5);">
        <?=$rowEstilo['encabezado']?>
    </div>
    <div id="footer" style="background:rgba(0,255,0,0.5);">
        <?=$rowEstilo['piePagina']?>
    </div>
    <div id="page">    
        <?php for ($i = 1; $i <= 5; $i++) : ?>
            <p>Lorem ipsum dolor sit amet consectetur adipisicing elit. Vero quasi enim quae ipsum tempore. Facilis numquam voluptate assumenda velit vero laudantium vitae, voluptatem harum veritatis eveniet asperiores eligendi aperiam beatae.</p>
        <?php endfor; ?>
    </div>
</body>


</html>