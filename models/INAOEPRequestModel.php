
<?php

use models\SQLQueries\RequestModel;

require '../models/SQLQueries/RequestModel.php';

header('Content-Type: application/json; charset=utf-8');

$solicitudes = json_decode(getResponses(), true);

//Acomodo de información
$processedData = [];
foreach ($solicitudes['Solicitudes'] as $key => $value) {
    $processedData[] = $value['Solicitud'];
}

//Se remueven los ID generales duplicados
$filteredData = removeDuplicates($processedData);

$final_data = [];
foreach ($filteredData as $key => $value) {
    
    $rm = new RequestModel();
    $generalID = $rm->getGeneralIdById($value['IDgenerales']);

    $value['control'] = $generalID['data'];

    $final_data[] = $value;
}

exit(json_encode($final_data));

/**
 * @return Mixed
 */
function getResponses() {

    $data = [
        'email' => 'info_sistema',
        'contrasenia' => 'Ch4NS3O24@+/--'
    ];

    $apiUrl = 'https://www.inaoep.mx/tramites/serviciosweb/posgrados/?formato=json';

    $curl = curl_init();
    curl_setopt_array($curl, [
        CURLOPT_URL => $apiUrl,
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_ENCODING => '',
        CURLOPT_MAXREDIRS => 10,
        CURLOPT_TIMEOUT => 0,
        CURLOPT_FOLLOWLOCATION => true,
        CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
        CURLOPT_COOKIEFILE => "/tmp/cookie.txt",
        CURLOPT_POSTFIELDS => http_build_query($data)
    ]);

    $response = curl_exec($curl);
    curl_close($curl);
    return $response;
}

/**
 * Limpia los IDGenerales duplicados
 * @param mixed $array
 * 
 * @return Array
 */
function removeDuplicates($array) {
    $finalArr = [];

    $tempID = null;
    foreach ($array as $key => $value) {

        if ($value['IDgenerales'] == $tempID) {
            continue;
        }

        $tempID = $value['IDgenerales'];
        $finalArr[] = $value;
    }

    return $finalArr;
}