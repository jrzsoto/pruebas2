<?php

use models\SQLQueries\RequestModel;
use controllers\SendMailController;

require '../models/SQLQueries/RequestModel.php';
require '../controllers/SendMailController.php';

header('Content-Type: application/json; charset=utf-8');

//! 1. Se recuperan los ID para descartar registros
$rm = new RequestModel();
$request = $rm->getIDGenerales('id_generales');
$registeredIds = array_column($request['data'], 'id_generales');

$solicitudes = json_decode(getSolicitudes(), true);

//Acomodo de información
$processedData = [];
foreach ($solicitudes['Solicitudes'] as $key => $value) {
    $processedData[] = $value['Solicitud'];
}

//Con esta funcion se filtra si los IDs ya estan registrados
$filteredData = array_filter($processedData, function ($var) use ($registeredIds) {
    return ($var['IDgenerales'] == !in_array($var['IDgenerales'], $registeredIds));
});

//Se remueven los ID generales duplicados
$filteredData = removeDuplicates($filteredData);

/**
 * ! 2. Una vez filtrada la información, se procesa. Magia
 */
$data = [];
foreach ($filteredData as $key => $value) {

    $registerIdGeneral = null;
    // 1. Comprueba si el email ya ha sido registrado
    $rm = new RequestModel();
    $user = $rm->getUserByEmail($value['Email']);

    // 2. Si el email ya ha sido registrado, vamos a comprobar sus registros en control
    if ($user['data'] !== null) {
        $data[] = validateAndRegister($value['IDgenerales'],$user['data']['id']);
    }
  
    // 2. Si el email no ha sido registrado se hacen cosas
    if ($user['data'] === null) {

        // Se limpia doble por apostrofes en medio del nombre
        $nombre = str_replace("'", ' ', $value['Nombres']);
        $nombres = trim($nombre);

        $rm = new RequestModel();
        $user = $rm->InsertData($nombres,$value['Paterno'],$value['Email'],(string) $value['CURP'],(string)$value['Materno'],$value['IDgenerales'],$value['Sexo'],$value['FechaNacimiento'],$value['LugarNacimiento'],$value['TelMovil'],$value['FechaRegistro'],$value['Nacionalidad'],$value['PeriodoIngreso'],(string) $value['tipoPosgrado'],$value['Posgrado'],$value['Pais']);

        if ($user['status']) {

            $data[] = validateAndRegister($value['IDgenerales'],$user['data']);
            
            $fullName = $nombres." ".$value['Paterno']." ".(string)$value['Materno'];

            $ms = new SendMailController();
            $mail = $ms->send($value['Email'],$fullName);
        }
    }
}

//----------------> Resultado
exit(json_encode($data));

################### FUNCIONES ADICIONALES ###########################
/**
 * Se obtienenen las solicitudes
 * @return Mixed
 */
function getSolicitudes() {

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


/**
 * Esta funcion es la que hace todo el proceso para registrar el ID en control
 * @param string $id_generales
 * @param int $user_id
 * 
 * @return Array|Bool
 */
function validateAndRegister(string $id_generales, int $user_id){

    $data = null;
    $rm = new RequestModel();
    $IDgeneralesReq = $rm->getUserGeneralIdControl($user_id);

    //2.1 Sino contiene registros se registra
    if ($IDgeneralesReq['data'] === null) {
        $rm = new RequestModel();
        $registerIdGeneral = $rm->registerIDGenerales($id_generales, $user_id, 'Registrado correctamente');
        $data = $registerIdGeneral;
    }

    //2.2 En caso de que exista algo asignado, se debe comprobar que el generalId no haya sido registrado ya
    if ($IDgeneralesReq['data'] !== null) {

        $rm = new RequestModel();
        $generalID = $rm->getGeneralIdById($id_generales);

        if ($generalID['data'] !== null && $generalID['data']['id_generales'] === $id_generales) {
            $data[] = 'No cuenta';
            return;
        }

        $rm = new RequestModel();
        $registerIdGeneral = $rm->registerIDGenerales($id_generales, $user_id, 'Correo electrónico repetido');
        $data = $registerIdGeneral;
    }

    return $data;
}