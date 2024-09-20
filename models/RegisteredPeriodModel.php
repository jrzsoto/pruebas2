
<?php

use models\SQLQueries\RequestModel;

require '../models/SQLQueries/RequestModel.php';

header('Content-Type: application/json; charset=utf-8');

    
$rm = new RequestModel();
$data = $rm->getRegisteredPeriod();

exit(json_encode($data));
