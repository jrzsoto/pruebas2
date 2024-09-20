
<?php

use models\SQLQueries\RequestModel;

require '../models/SQLQueries/RequestModel.php';

header('Content-Type: application/json; charset=utf-8');

    
$rm = new RequestModel();
$data = $rm->getTemporalWithdrawnStudents();


//$dataBD = json_encode($data);
//echo "datos: ". $dataBD;


exit(json_encode($data));