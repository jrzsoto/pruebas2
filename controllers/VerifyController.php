<?php

use models\SQLQueries\VerifyModel;


require '../models/SQLQueries/VerifyModel.php';
//require 'RequestController.php';


$data = json_decode(file_get_contents('php://input'), true);

$rm = new VerifyModel();

$result = $rm->VerifyData($data['names'],$data['last_name'],$data['email'],$data['curp'],$data['second_last_name'],
                          $data['id_general'],$data['gender'],$data['birth_date'],$data['birth_place'],$data['mobile_number'],$data['register_date'],
                          $data['nationality'],$data['admission_period'],$data['academic_area'],$data['degree'],$data['country']
                        );

                      
if ($result['status']) {

    try {        
        $result['status'] = true;    

    }
    catch (Exception $e)
    {
        $result['error'] = "error";
    }

    header('Content-Type: application/json; charset=utf-8');   
    exit(json_encode($result));


if (!$result['status']) {
    header('Content-Type: application/json; charset=utf-8');  
    exit(json_encode($result));
}
}

