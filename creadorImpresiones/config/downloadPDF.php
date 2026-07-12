<?php
require_once './creadorImpresiones/dompdf/autoload.inc.php';

// obtenemos el presupuesto del arch
$idPresupuesto = 0;
$documento = base64_decode($idPresupuesto);
// esto hace que depende del botón que presionemos corra la función y se vea
// o que corra y explote y entonces descarga el pdf todo bien
$pre = ($_GET['pre'] == 1 ? false : 'paqueserompa');

use Dompdf\Dompdf;

// $html   = 'prueba';
$html = (file_get_contents_curl('https://medicalsoftplus.com/co1098/creadorImpresiones/prueba.php'));
$dompdf = new DOMPDF();
// echo $html;

$dompdf->set_option('isRemoteEnabled', true);

$dompdf->loadHtml(($html));
// $dompdf->setPaper('A4', 'portrait');
$dompdf->render();

// // malayo footer
// $x          = 250;
// $y          = 800;
// $text       = "Página {PAGE_NUM} de {PAGE_COUNT}";
// $font       = $dompdf->getFontMetrics()->get_font('Helvetica', 'normal');
// $size       = 10;
// $color      = array(0, 0, 0);
// $word_space = 0.0;
// $char_space = 0.0;
// $angle      = 0.0;

// $dompdf->getCanvas()->page_text(
//     $x,
//     $y,
//     $text,
//     $font,
//     $size,
//     $color,
//     $word_space,
//     $char_space,
//     $angle
// );

// stream
$dompdf->stream('Documento_' . $documento . '.pdf', array('Attachment' => $pre));

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