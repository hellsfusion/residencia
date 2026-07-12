<?php
session_start();
require __DIR__ . '/../php/funciones.php';
require_once __DIR__ . '/dompdf/autoload.inc.php';

use Dompdf\Dompdf;
use Dompdf\Options;
// disable cache
header("Cache-Control: no-store, no-cache, must-revalidate, max-age=0");
header("Cache-Control: post-check=0, pre-check=0", false);
header("Pragma: no-cache");


// obtenemos el presupuesto del arch
$idCss = $_GET['i'];
// esto hace que depende del botón que presionemos corra la función y se vea
// o que corra y explote y entonces descarga el pdf todo bien
$pre = ($_GET['pre'] == 1 ? false : 'paqueserompa');

// $html   = 'prueba';
if ($_GET['t'] == 1) {
    $html = file_get_contents_curl($Base . 'creadorImpresiones/prueba.php?FAID=' . $idCss);
} else {
    $url = $_GET['uZ'];
    $html = file_get_contents_curl($Base . 'creadorImpresiones/plantilla.php?FAID=' . $idCss . '&url=' . $url);
    // var_dump($Base . 'creadorImpresiones/plantilla.php?FAID=' . $idCss . '&url=' . $url);
}

if (!empty($Base)) {
    if (stripos($html, '<base') === false) {
        $baseTag = '<base href="' . htmlspecialchars($Base, ENT_QUOTES, 'UTF-8') . '">';
        if (preg_match('/<head[^>]*>/i', $html)) {
            $html = preg_replace('/(<head[^>]*>)/i', '$1' . $baseTag, $html, 1);
        } else {
            $html = $baseTag . $html;
        }
    }
}

ini_set('allow_url_fopen', '1');

$options = new Options();
$options->set('isRemoteEnabled', true);
$options->set('isHtml5ParserEnabled', true);
$options->set('debugKeepTemp', true);

$dompdf = new Dompdf($options);
if (!empty($Base)) {
    $dompdf->setBasePath(rtrim($Base, '/') . '/');
}
$dompdf->loadHtml($html);
// $dompdf->setPaper('A4', 'portrait');
$dompdf->render();
// stream
$dompdf->stream('Documento_' . $idCss . '.pdf', array('Attachment' => $pre));

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
