<?php

getFileResponses();

function getFileResponses(): void {
    
    $json_data = file_get_contents('solicitudes.json');
    $response = json_decode($json_data, true);
    echo json_encode($response);

    
}
