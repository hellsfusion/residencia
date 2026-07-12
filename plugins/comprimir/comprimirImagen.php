<?php 
function comprimirImagen($rutaImagen, $rutaDestino, $calidad) {
    $info = getimagesize($rutaImagen);
    if ($info['mime'] == 'image/jpeg') {
        $imagen = imagecreatefromjpeg($rutaImagen);
    } elseif ($info['mime'] == 'image/png') {
        $imagen = imagecreatefrompng($rutaImagen);
    } else {
        return false;
    }
    // corregir orientacion 
    $exif = exif_read_data($rutaImagen);
    if (!empty($exif['Orientation'])) {
        switch ($exif['Orientation']) {
            case 1:
                // Sin rotación (orientación normal)
                break;
            case 2:
                // Rotación horizontal (tipo espejo)
                $imagen = imageflip($imagen, IMG_FLIP_HORIZONTAL);
                break;
            case 3:
                // Rotación de 180 grados
                $imagen = imagerotate($imagen, 180, 0);
                break;
            case 4:
                // Rotación vertical
                $imagen = imageflip($imagen, IMG_FLIP_VERTICAL);
                break;
            case 5:
                // Rotación de 90 grados y espejo
                $imagen = imagerotate($imagen, -90, 0);
                $imagen = imageflip($imagen, IMG_FLIP_HORIZONTAL);
                break;
            case 6:
                // Rotación de 90 grados
                $imagen = imagerotate($imagen, -90, 0);
                break;
            case 7:
                // Rotación de 90 grados en sentido horario y espejo horizontal
                $imagen = imagerotate($imagen, 90, 0);
                $imagen = imageflip($imagen, IMG_FLIP_HORIZONTAL);
                break;
            case 8:
                // Rotación de 90 grados en sentido antihorario
                $imagen = imagerotate($imagen, 90, 0);
                break;
        }
    }
    imagejpeg($imagen, $rutaDestino, $calidad);    
    imagedestroy($imagen);    
    return true;
}


// // test
// $rutaImagenOriginal = 'original/foto.jpg';
// $rutaDestino = 'compri/comprimida.jpg';
// $calidad = 80; // Porcentaje de calidad de compresión (de 0 a 100)

// if (comprimirImagen($rutaImagenOriginal, $rutaDestino, $calidad)) {
//     echo '¡Imagen comprimida y guardada con éxito!';
// } else {
//     echo 'No se pudo comprimir la imagen.';
// }

?>
