<?php

use models\SQLQueries\RequestModel;

require '../models/SQLQueries/RequestModel.php';

//require 'VerifyController.php';
$data = json_decode(file_get_contents('php://input'), true);

$rm = new RequestModel();
$result = $rm->login($data['username'],$data['password']);


header('Content-Type: application/json; charset=utf-8');  
exit(json_encode($result));

//exit('Error');
