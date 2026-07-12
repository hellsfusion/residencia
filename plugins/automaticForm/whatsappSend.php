<?php
include '../../../masterFunciones.php';
include '../../funciones/funciones.php';
if ($_POST['phone'] && $_POST['body']) {
    

    $phone = base64_decode(base64_decode($_POST['phone']));
    $body = base64_decode(base64_decode($_POST['body']));
    // var_dump($phone);
    // var_dump($body);
    // var_dump($linkkey);

    $phone = explode('|/|', $phone);

    for ($i = 0; $i < count($phone); $i++) {

        $data = [
            'phone' => $phone[$i], // Receivers phone
            'body' => $body, // Message
        ];
    
        $json = json_encode($data);
    
        // Make a POST request
        $options = stream_context_create([
            'http' => [
                'method'  => 'POST',
                'header'  => 'Content-type: application/json',
                'content' => $json
            ]
        ]);
        // Send a request
        $result = file_get_contents($linkkey, false, $options);
        
    }    
}
