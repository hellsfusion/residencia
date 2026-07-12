<?php 
session_start();
// include "../funciones/funciones.php";

// $estilo = base64_decode($_GET['FAID']);
// $queryEstilo = "SELECT * FROM configImpresiones WHERE id = $estilo LIMIT 1";
// $resultadoEstilo = mysqli_query($conn3, $queryEstilo);
// $rowEstilo = mysqli_fetch_assoc($resultadoEstilo);
$rowEstilo = null;


function file_get_contents_curl($url)
{
    $crl = curl_init();

    curl_setopt_array($crl, [
        CURLOPT_URL => $url,
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_FOLLOWLOCATION => true,  // sigue redirecciones
        CURLOPT_CONNECTTIMEOUT => 8,
        CURLOPT_TIMEOUT => 15,
        CURLOPT_SSL_VERIFYPEER => false, // no verificar SSL (por si es https)
    ]);

    $ret = curl_exec($crl);

    if (curl_errno($crl)) {
        // Si hubo error, puedes loguearlo o mostrarlo
        $error_msg = curl_error($crl);
        curl_close($crl);
        return 'Curl error: ' . $error_msg;
    }

    curl_close($crl);
    return $ret;
}
?>

<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="<?=$Base?>creadorImpresiones/config/<?= $estilo ?>.css">
    <title>Documento</title>
</head>

<body>
    <div class="marcaAgua"></div>
    <div id="header">
        <?=$rowEstilo['encabezado']?>
    </div>
    <div id="footer">
        <?=$rowEstilo['piePagina']?>
    </div>
    <div id="page">
        <?php 
        $url = base64_decode($_GET['url']);
        // echo $url;
        $content = file_get_contents_curl($url);
        $content = ($content);
        echo $content;
        ?>
    </div>
</body>


</html>