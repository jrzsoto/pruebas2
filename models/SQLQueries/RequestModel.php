<?php

namespace models\SQLQueries;

include 'Catalogs.php';
include 'DBConnectionModel.php';

use models\SQLQueries\Catalogs;
use models\SQLQueries\DBConnectionModel;


class RequestModel {


    /**
     * Construct
     */
    public function __construct() {
    }

    /**
     * @param string $name
     * @param string $last_name
     * @param string $email
     * @param string $curp
     * @param string|null $second_last_name
     * 
     * @return $this
     */
    function InsertData(
        string $name,
        string $last_name,
        string $email,
        string $curp,
        string $second_last_name,
        string $id_general,
        string $gender,
        string $birth_date,
        string $birth_place,
        string $mobile_number,
        string $register_date,
        string $nationality,
        string $admission_period,
        string $academic_area,
        string $degree,
        string $country
    ) {

        try {

            // obtener código de los catálogos
            $catalog = new Catalogs();

            // Satos que requieren un código del catálogo

            //procesar nombre casos de tener un apostrofe en medio


            $name = trim($name);

            /*if(tiene_apostrofe($name)){
                $name = procesar_nombre($name);
            }else{
                $name = $name;
            }*/

            $name = mb_strtoupper($name);
            $last_name = trim($last_name);
            $last_name = mb_strtoupper($last_name);

            if ($second_last_name == "" || $second_last_name == null) {
                $second_last_name = "";
            } else {
                $second_last_name = mb_strtoupper($second_last_name);
            }

            $genderCode = $catalog->genderCatalog($gender);
            $birthPlaceCode = $catalog->birthPlaceCatalog($birth_place);
            $pogramCode = $catalog->programCatalog($academic_area, $degree);
            $contrasena = md5("2024#1N4031487");
            $countryCode = $catalog->countryCatalog($country);
            $admisionProcessCode = $catalog->admisionProcessCatalog($academic_area, $degree);
            $studentProgramPrefix = $catalog->studentProgramPrefix($academic_area, $degree);
            $registerDate = $register_date;
            $registerDate = "'" . $registerDate . "'";
            $personaId = null;

            $processStageCode = $catalog->processStageCatalog($degree);
            $processStateCode = $catalog->processStateCatalog($degree);


            $userName    = explode('@', $email);
            $userName    = $userName[0];
            //$userName    = $userName[0];
            //$userName = ftruncate($userName[0] , 6);

            //agregar validaciones de BD
            /*
            1.- duplicidad de correo electrónico 
            2.- curp para nacionales
            3.- usuario no duplicado y longitud 
            */

            $resultQ1 = $this->sga_persona($name, $last_name, $second_last_name, $genderCode, $email);

            if ($resultQ1['status']) {
                $personaId = $resultQ1['data']['id'];
                $id = $resultQ1['data']['id'];

                $resultQ2 = $this->sga_persona_sga_estudiante($id, $registerDate, $birth_date, $birthPlaceCode, $pogramCode);

                if ($resultQ2['status']) {
                    $id = $resultQ2['data']['id'];
                    $resultQ3 = $this->sga_nacionalidad_m2m_estudiante($id, $countryCode);

                    if ($resultQ3['status']) {
                        $id = $resultQ3['data']['id'];

                        $resultQ4 = $this->sga_estudiante_admision($id, $registerDate, $admisionProcessCode, $id_general, $studentProgramPrefix, $processStateCode, $processStageCode);

                        if ($resultQ4['status']) {
                            $id = $resultQ4['data']['id'];
                            $resultQ5 = $this->sga_bitacora_estudiante_admision($id, $registerDate);

                            if ($resultQ5['status']) {

                                $resultQ6 = $this->sga_usuario_sistema($personaId, $userName, $contrasena, $registerDate);

                                if ($resultQ6['status']) {
                                    $id = $resultQ6['data']['id'];
                                    $resultQ7 = $this->sga_usuario_m2m_rol($id);

                                    if ($resultQ7['status']) {
                                        $id = $resultQ7['data']['id'];
                                        $resultQ8 = $this->sga_usuario_m2m_grupo_permiso($id);

                                        if ($resultQ8['status']) {
                                            $id = $resultQ8['data']['id'];
                                            $resultQ9 = $this->sga_usuario_m2m_permiso($id);
                                        } else {

                                            $status = false;
                                            $data = "Error en Query 6";
                                            return compact('status', 'data');
                                        }
                                    } else {

                                        $status = false;
                                        $data = "Error en Query 6";
                                        return compact('status', 'data');
                                    }
                                } else {
                                    $status = false;
                                    $data = "Error en Query 6";
                                    return compact('status', 'data');
                                }
                            } else {
                                $status = false;
                                $data = "Error en Query 5";
                                return compact('status', 'data');
                            }
                        } else {
                            $status = false;
                            $data = "Error en Query 4";
                            return compact('status', 'data');
                        }
                    } else {
                        $status = false;
                        $data = "Error en Query 3";
                        return compact('status', 'data');
                    }
                } else {
                    $status = false;
                    $data = "Error en Query 2";
                    return compact('status', 'data');
                }
            } else {
                $status = false;
                $data = "Error en Query 1";
                return compact('status', 'data');
            }

            $status = true;
            $data = $id;

            return compact('status', 'data');
        } catch (Exception $e) {

            $status = false;
            $data = 'Error';

            return compact('status', 'data');
        }
    }

    function procesar_nombre($name) {
        // Escapar las comillas y otros caracteres especiales
        $name = addslashes($name);
        return $name;
    }

    function tiene_apostrofe($name) {
        // Buscar la posición del apóstrofe en el nombre
        $posicion = strpos($name, "'");

        // Si la posición es diferente de false, significa que se encontró el apóstrofe
        if ($posicion !== false) {
            return true;
        } else {
            return false;
        }
    }

    function sga_persona(string $name, string $last_name, string $second_last_name, string $genderCode, string $email = null) {

        try {

            $dbc = new DBConnectionModel();
            $dbc->createConnection();
            $conn = $dbc->getConnection();



            $tsql = "INSERT dbo.sga_persona (nombre, primer_apellido, segundo_apellido, nombre_completo, genero, numero_cvu, correo_electronico, tipo_persona, estado) OUTPUT"
                . " INSERTED.id VALUES ('" . $name . "', '" . $last_name . "', '" . $second_last_name . "', '" . $name . ' ' . $last_name . ' ' . $second_last_name . "', '" . $genderCode . "', '', '" . $email . "', '1', '1')";

            //Insert query
            $insertReview = sqlsrv_query($conn, $tsql);
            if ($insertReview == FALSE) {
                $status = false;
                $data = ['errors' => sqlsrv_errors()];
                die(print_r(compact('status', 'data')));
            }

            $id = null;
            while ($row = sqlsrv_fetch_array($insertReview, SQLSRV_FETCH_ASSOC)) {
                $id = ($row['id']);
            }
            sqlsrv_free_stmt($insertReview);
            sqlsrv_close($conn);

            // Obtenemos el status de la inserción y el data_id
            $status = true;
            $data = ['id' => $id];

            return compact('status', 'data');
        } catch (Exception $e) {

            $status = false;
            $data = 'Error';

            return compact('status', 'data');
        }
    }

    function sga_persona_sga_estudiante(string $id, string $registerDate,  string $birth_date, string $birthPlaceCode, string $programCode) {

        try {

            $dbc = new DBConnectionModel();
            $dbc->createConnection();
            $conn = $dbc->getConnection();

            //$registerDate =date("Y-m-d H:i:s");
            // Query de inserción
            //$tsql = "INSERT INTO sga_persona_sga_estudiante (folio_aspirante, matricula_alumno, numero_registro, fecha_registro_aspirante, curp, dni, rfc, fecha_nacimiento, registro_datos_biometricos, estado_estudiante, estudiante_m2o_id, estado_civil_m2o_id, estado_r1_m2o_id, programa_m2o_id, id) 
            //VALUES (null, null, null, '$registerDate', '$curp', null, null, null, null, '1', null, null, '$birthPlaceCode', '$programCode', '$id')";
            //CONVERT(datetime, '2023-04-17 00:03:38'
            //$registerDate = "'". $registerDate . "'";
            // Query de inserción
            $tsql = "INSERT INTO sga_persona_sga_estudiante (folio_aspirante, matricula_alumno, numero_registro, fecha_registro_aspirante, curp, dni, rfc, fecha_nacimiento, registro_datos_biometricos, estado_estudiante, estudiante_m2o_id, estado_civil_m2o_id, estado_r1_m2o_id, programa_m2o_id, id) 
            VALUES (null, null, null, CONVERT(datetime,$registerDate, 20), null, null, null, null, null, '1', null, null, '$birthPlaceCode', '$programCode','$id')";
            //Insert query
            $insertReview = sqlsrv_query($conn, $tsql);
            if ($insertReview == FALSE) {
                $status = false;
                $data = ['errors' => sqlsrv_errors()];
                die(print_r(compact('status', 'data')));
            }

            sqlsrv_free_stmt($insertReview);
            sqlsrv_close($conn);

            // Obtenemos el status de la inserción y el data_id
            $status = true;
            $data = ['id' => $id];

            return compact('status', 'data');
        } catch (Exception $e) {

            $status = false;
            $data = 'Error';

            return compact('status', 'data');
        }
    }


    function sga_nacionalidad_m2m_estudiante(string $id, string $countryCode = null) {
        try {

            $dbc = new DBConnectionModel();
            $dbc->createConnection();
            $conn = $dbc->getConnection();


            // Query de inserción
            $tsql = "INSERT INTO sga_nacionalidad_m2m_estudiante (sga_estudiante_id, sga_cat_nacionalidad_id) 
            VALUES ('$id', '$countryCode')";

            //Insert query
            $insertReview = sqlsrv_query($conn, $tsql);
            if ($insertReview == FALSE) {
                $status = false;
                $data = ['errors' => sqlsrv_errors()];
                die(print_r(compact('status', 'data')));
            }

            sqlsrv_free_stmt($insertReview);
            sqlsrv_close($conn);

            // Obtenemos el status de la inserción y el data_id
            $status = true;
            $data = ['id' => $id];

            return compact('status', 'data');
        } catch (Exception $e) {

            $status = false;
            $data = 'Error';

            return compact('status', 'data');
        }
    }


    public function sga_estudiante_admision(string $studentId, string $registerDate, string $admisionProcessCode, string $id_general, string $studentProgramPrefix, string $processStateCode, string $processStageCode = null) {

        try {


            $dbc = new DBConnectionModel();
            $dbc->createConnection();
            $conn = $dbc->getConnection();

            //$registerDate = date("Y-m-d H:i:s");
            $tsql = "INSERT INTO sga_estudiante_admision (criterio_aprobacion_total, motivo_ingreso, fecha_registro, observaciones, modalidad_ingreso, beca_propedeutico, beca_comedor, estado, estudiante_m2o_id, proceso_admision_m2o_id, estado_proceso_m2o_id, etapa_proceso_m2o_id, idgenerales, especifique) 
              VALUES ('0', '',  CONVERT(datetime,$registerDate, 20), null, '3', null, null, '1', '$studentId', '$admisionProcessCode', '$processStateCode', '$processStageCode', '$id_general', null)";


            //Insert query
            $insertReview = sqlsrv_query($conn, $tsql);
            if ($insertReview == FALSE) {
                $status = false;
                $data = ['errors' => sqlsrv_errors()];
                die(print_r(compact('status', 'data')));
            }


            $idQuery = sqlsrv_query($conn, "SELECT SCOPE_IDENTITY() AS id");
            $id = sqlsrv_fetch_array($idQuery, SQLSRV_FETCH_ASSOC)['id'];


            // se actualiza la tabla de persona_estudiante           

            $applicantFolio = $studentProgramPrefix . "" . $id;
            $tsql = "UPDATE sga_persona_sga_estudiante SET folio_aspirante = '$applicantFolio' WHERE id = $studentId";

            //Insert query
            $insertReview = sqlsrv_query($conn, $tsql);
            if ($insertReview == FALSE) {
                $status = false;
                $data = ['errors' => sqlsrv_errors()];
                die(print_r(compact('status', 'data')));
            }

            sqlsrv_free_stmt($insertReview);
            sqlsrv_close($conn);

            // Obtenemos el status de la inserción y el data_id
            $status = true;
            $data = ['id' => $id];

            return compact('status', 'data');
        } catch (Exception $e) {

            $status = false;
            $data = 'Error';

            return compact('status', 'data');
        }
    }

    public function sga_bitacora_estudiante_admision(string $studiantId = null, string $registerDate) {

        $actionDate  = date('Y-m-d H:i:s'); // Obtener la fecha y hora actual
        $actionDate = "'" . $actionDate . "'";
        try {


            $dbc = new DBConnectionModel();
            $dbc->createConnection();
            $conn = $dbc->getConnection();

            $tsql = "INSERT INTO sga_bitacora_estudiante_admision (accion, fecha_accion, descripcion, estado, estudiante_admision_m2o_id, empleado_m2o_id, estado_proceso_m2o_id) 
            VALUES ('creacion', CONVERT(datetime,$registerDate, 20), '', '1', '$studiantId', null, '1')";

            //Insert query
            $insertReview = sqlsrv_query($conn, $tsql);
            if ($insertReview == FALSE) {
                $status = false;
                $data = ['errors' => sqlsrv_errors()];
                die(print_r(compact('status', 'data')));
            }

            $id = $studiantId;

            sqlsrv_free_stmt($insertReview);
            sqlsrv_close($conn);

            // Obtenemos el status de la inserción y el data_id
            $status = true;
            $data = ['id' => $id];

            return compact('status', 'data');
        } catch (Exception $e) {

            $status = false;
            $data = 'Error';

            return compact('status', 'data');
        }
    }


    public function sga_usuario_sistema(string $personId, string $userName, string $contrasena, string $registerDate) {

        try {


            $dbc = new DBConnectionModel();
            $dbc->createConnection();
            $conn = $dbc->getConnection();

            // $registerDate = date("Y-m-d H:i:s");
            //print_r($registerDate);

            $tsql = "INSERT INTO sga_usuario_sistema (nombre_usuario, contrasena, fecha_registro, estado, persona_m2o_id) 
          VALUES ('$userName', '$contrasena', CONVERT(datetime,$registerDate, 20), '1', '$personId')";


            //Insert query
            $insertReview = sqlsrv_query($conn, $tsql);
            if ($insertReview == FALSE) {
                $status = false;
                $data = ['errors' => sqlsrv_errors()];
                die(print_r(compact('status', 'data')));
            }

            $idQuery = sqlsrv_query($conn, "SELECT SCOPE_IDENTITY() AS id");
            $id = sqlsrv_fetch_array($idQuery, SQLSRV_FETCH_ASSOC)['id'];

            sqlsrv_free_stmt($insertReview);
            sqlsrv_close($conn);

            // Obtenemos el status de la inserción y el data_id
            $status = true;
            $data = ['id' => $id];

            return compact('status', 'data');
        } catch (Exception $e) {

            $status = false;
            $data = 'Error';

            return compact('status', 'data');
        }
    }

    public function sga_usuario_m2m_rol(string $userId = null) {

        // Definir los valores a insertar
        try {
            $dbc = new DBConnectionModel();
            $dbc->createConnection();
            $conn = $dbc->getConnection();

            $tsql = "INSERT INTO sga_usuario_m2m_rol (sga_cat_rol_id, sga_usuario_sistema_id) 
            VALUES ('14', '$userId')";

            //Insert query
            $insertReview = sqlsrv_query($conn, $tsql);
            if ($insertReview == FALSE) {
                $status = false;
                $data = ['errors' => sqlsrv_errors()];
                die(print_r(compact('status', 'data')));
            }

            $id = $userId;

            sqlsrv_free_stmt($insertReview);
            sqlsrv_close($conn);

            $status = true;
            $data = ['id' => $id];

            return compact('status', 'data');
        } catch (Exception $e) {

            $status = false;
            $data = 'Error';

            return compact('status', 'data');
        }
    }


    public function sga_usuario_m2m_grupo_permiso(string $userId = null) {

        // Definir los valores a insertar
        try {
            $dbc = new DBConnectionModel();
            $dbc->createConnection();
            $conn = $dbc->getConnection();


            $tsql = "INSERT INTO sga_usuario_m2m_grupo_permiso (sga_grupo_permiso_id, sga_usuario_sistema_id) 
            VALUES ('14', '$userId') , ('15', '$userId') ";


            //Insert query
            $insertReview = sqlsrv_query($conn, $tsql);
            if ($insertReview == FALSE) {
                $status = false;
                $data = ['errors' => sqlsrv_errors()];
                die(print_r(compact('status', 'data')));
            }

            $id = $userId;

            sqlsrv_free_stmt($insertReview);
            sqlsrv_close($conn);

            // Obtenemos el status de la inserción y el data_id
            $status = true;
            $data = ['id' => $id];

            return compact('status', 'data');
        } catch (Exception $e) {

            $status = false;
            $data = 'Error';

            return compact('status', 'data');
        }
    }


    public function sga_usuario_m2m_permiso(string $userId = null) {

        // Definir los valores a insertar
        try {
            $dbc = new DBConnectionModel();
            $dbc->createConnection();
            $conn = $dbc->getConnection();


            $tsql = "INSERT INTO sga_usuario_m2m_permiso (sga_permiso_id, sga_usuario_sistema_id) 
             VALUES ('31', '$userId') , ('33', '$userId') , ('81', '$userId') , ('82', '$userId') , ('83', '$userId'), 
             ('84', '$userId') , ('87', '$userId') , ('89', '$userId') , ('90', '$userId') , ('91', '$userId'),
             ('92', '$userId') , ('95', '$userId') 
             ";

            //Insert query
            $insertReview = sqlsrv_query($conn, $tsql);
            if ($insertReview == FALSE) {
                $status = false;
                $data = ['errors' => sqlsrv_errors()];
                die(print_r(compact('status', 'data')));
            }

            $id = $userId;

            sqlsrv_free_stmt($insertReview);
            sqlsrv_close($conn);

            $status = true;
            $data = ['id' => $id];

            return compact('status', 'data');
        } catch (Exception $e) {

            $status = false;
            $data = 'Error';

            return compact('status', 'data');
        }
    }



    //########## Adrian ############

    /**
     * @return Mixed
     */
    function getIDGenerales(string $fields = '*') {
        // Definir los valores a insertar
        try {
            $dbc = new DBConnectionModel();
            $dbc->createConnection();
            $conn = $dbc->getConnection();

            $query = "SELECT ".$fields." FROM dbo.id_generales_control";

            //Try query
            $queryConn = sqlsrv_query($conn, $query);
            if ($queryConn == FALSE) {
                $status = false;
                $data = ['errors' => sqlsrv_errors()];
                die(print_r(compact('status', 'data')));
            }

            $id = [];
            while ($row = sqlsrv_fetch_array($queryConn, SQLSRV_FETCH_ASSOC)) {
                $id[] = ($row);
            }
            sqlsrv_free_stmt($queryConn);
            sqlsrv_close($conn);


            // Obtenemos el status de la inserción y el data_id
            $status = true;
            $data = $id;

            return compact('status', 'data');
        } catch (Exception $e) {

            $status = false;
            $data = 'Error';

            return compact('status', 'data');
        }
    }


    /**
     * @param string $IDGenerales
     * @param int $user_id
     * @param string $estado
     * 
     * @return Mixed
     */
    function registerIDGenerales(string $IDGenerales,int $user_id = null, string $estado = ''){
    
        try {

            $dbc = new DBConnectionModel();
            $dbc->createConnection();
            $conn = $dbc->getConnection();

            $tsql = "INSERT dbo.id_generales_control (id_generales, estado,user_id) OUTPUT"
                . " INSERTED.id VALUES ('" . $IDGenerales . "', '" . $estado ."',".$user_id.")";

            //Insert query
            $insertReview = sqlsrv_query($conn, $tsql);
            if ($insertReview == FALSE) {
                $status = false;
                $data = ['errors' => sqlsrv_errors()];
                die(print_r(compact('status', 'data')));
            }

            $id = null;
            while ($row = sqlsrv_fetch_array($insertReview, SQLSRV_FETCH_ASSOC)) {
                $id = ($row['id']);
            }
            sqlsrv_free_stmt($insertReview);
            sqlsrv_close($conn);

            // Obtenemos el status de la inserción y el data_id
            $status = true;
            $data = ['id' => $id];

            return compact('status', 'data');
        } catch (Exception $e) {

            $status = false;
            $data = 'Error';

            return compact('status', 'data');
        }
    }

    /**
     * @return Mixed
     */
    function getUserByEmail(string $email) {
        // Definir los valores a insertar
        try {
            $dbc = new DBConnectionModel();
            $dbc->createConnection();
            $conn = $dbc->getConnection();

            $query = "SELECT TOP 1 * FROM sga_persona WHERE correo_electronico = '".$email."'";

            //Try query
            $queryConn = sqlsrv_query($conn, $query);
            if ($queryConn == FALSE) {
                $status = false;
                $data = ['errors' => sqlsrv_errors()];
                die(print_r(compact('status', 'data')));
            }

            $register = null;
            while ($row = sqlsrv_fetch_array($queryConn, SQLSRV_FETCH_ASSOC)) {
                $register = ($row);
            }
            sqlsrv_free_stmt($queryConn);
            sqlsrv_close($conn);

            // Obtenemos el status de la inserción y el data_id
            $status = true;
            $data = $register;

            return compact('status', 'data');
        } catch (Exception $e) {

            $status = false;
            $data = 'Error';

            return compact('status', 'data');
        }
    }

    /**
      * Agregué el id del usuario en la tabla de control, con eso se puede asociar muchas solicitudes a una persona, por si
     * @return Mixed
     */
    function getUserGeneralIdControl(int $user_id) {
        // Definir los valores a insertar
        try {
            $dbc = new DBConnectionModel();
            $dbc->createConnection();
            $conn = $dbc->getConnection();

            $query = "SELECT * FROM dbo.id_generales_control WHERE user_id = ".$user_id;

            //Try query
            $queryConn = sqlsrv_query($conn, $query);
            if ($queryConn == FALSE) {
                $status = false;
                $data = ['errors' => sqlsrv_errors()];
                die(print_r(compact('status', 'data')));
            }

            $register = null;
            while ($row = sqlsrv_fetch_array($queryConn, SQLSRV_FETCH_ASSOC)) {
                $register[] = ($row);
            }
            sqlsrv_free_stmt($queryConn);
            sqlsrv_close($conn);

            // Obtenemos el status de la inserción y el data_id
            $status = true;
            $data = $register;

            return compact('status', 'data');
        } catch (Exception $e) {

            $status = false;
            $data = 'Error';

            return compact('status', 'data');
        }
    }

    /**
      * Agregué el id del usuario en la tabla de control, con eso se puede asociar muchas solicitudes a una persona, por si
     * @return Mixed
     */
    function getGeneralIdById(string $general_id) {
        // Definir los valores a insertar
        try {
            $dbc = new DBConnectionModel();
            $dbc->createConnection();
            $conn = $dbc->getConnection();

            $query = "SELECT TOP 1 * FROM dbo.id_generales_control WHERE id_generales = '".$general_id."'";

            //Try query
            $queryConn = sqlsrv_query($conn, $query);
            if ($queryConn == FALSE) {
                $status = false;
                $data = ['errors' => sqlsrv_errors()];
                die(print_r(compact('status', 'data')));
            }

            $register = null;
            while ($row = sqlsrv_fetch_array($queryConn, SQLSRV_FETCH_ASSOC)) {
                $register = ($row);
            }
            sqlsrv_free_stmt($queryConn);
            sqlsrv_close($conn);

            // Obtenemos el status de la inserción y el data_id
            $status = true;
            $data = $register;

            return compact('status', 'data');
        } catch (Exception $e) {

            $status = false;
            $data = 'Error';

            return compact('status', 'data');
        }
    }


    /**
     * @param string $user
     * @param string $password
     * 
     * @return Mixed
     */
    function login(string $username,string $password) {
        // Definir los valores a insertar
        try {
            $dbc = new DBConnectionModel();
            $dbc->createConnection();
            $conn = $dbc->getConnection();

            $query = "SELECT TOP 1 id, username FROM dbo.users WHERE username = '".$username."' AND password ='".$password."'";

            //Try query
            $queryConn = sqlsrv_query($conn, $query);
            if ($queryConn == FALSE) {
                $status = false;
                $data = ['errors' => sqlsrv_errors()];
                die(print_r(compact('status', 'data')));
            }

            $register = null;
            while ($row = sqlsrv_fetch_array($queryConn, SQLSRV_FETCH_ASSOC)) {
                $register = ($row);
            }
            sqlsrv_free_stmt($queryConn);
            sqlsrv_close($conn);

            // Obtenemos el status de la inserción y el data_id
            $status = true;
            $data = $register;

            return compact('status', 'data');
        } catch (Exception $e) {

            $status = false;
            $data = 'Error';

            return compact('status', 'data');
        }
    }


    
    /**
     * @return Mixed
     */
    function getRegisteredPeriod() {
        // Definir los valores a insertar
        try {
            $dbc = new DBConnectionModel();
            $dbc->createConnection();
            $conn = $dbc->getConnection();

            $query = 
            "SELECT p.nombre, p.primer_apellido, p.segundo_apellido, p.correo_institucional, p.correo_electronico, 
               pe.matricula_alumno AS matricula_estudiante, cte.descripcion AS estado_actual_estudiante, 
               pro.nombre_programa, ctg.tipo_grado, pe.rfc, pe.curp, ei.periodo_escolar_m2o_id
            FROM sga_persona AS p
            INNER JOIN sga_persona_sga_estudiante AS pe ON p.id = pe.id
            INNER JOIN sga_estudiante_admision AS ea ON pe.id = ea.estudiante_m2o_id
            INNER JOIN sga_bitacora_estudiante AS be ON pe.id = be.estudiante_m2o_id
            INNER JOIN sga_cat_tipo_estudiante AS cte ON be.tipo_estudiante_m2o_id = cte.id
            INNER JOIN sga_programa AS pro ON pe.programa_m2o_id = pro.id
            INNER JOIN sga_cat_tipo_grado AS ctg ON pro.tipo_grado_m2o_id = ctg.id
            INNER JOIN sga_estudiante_inscripcion AS ei ON ei.estudiante_m2o_id = pe.id
            INNER JOIN sga_periodo_escolar AS pescolar ON ei.periodo_escolar_m2o_id = pescolar.id
            LEFT OUTER JOIN sga_programa AS programa ON pe.programa_m2o_id = programa.id
            WHERE pe.matricula_alumno NOT LIKE 'NULL'
            AND ei.estado = '3'
            AND pe.matricula_alumno IS NOT NULL
            AND p.estado <> 0
            AND pe.estado_estudiante <> 0
            AND pescolar.id = 20
            AND ea.criterio_aprobacion_total = 1
            AND ea.estado = 3
            AND be.fecha = (SELECT MAX(fecha) AS Expr1 FROM dbo.sga_bitacora_estudiante AS BBE2 WHERE estudiante_m2o_id = pe.id)
            ORDER BY p.primer_apellido";


            //Try query
            $queryConn = sqlsrv_query($conn, $query);
            if ($queryConn == FALSE) {
                $status = false;
                $data = ['errors' => sqlsrv_errors()];
                die(print_r(compact('status', 'data')));
            }

            $id = [];
            while ($row = sqlsrv_fetch_array($queryConn, SQLSRV_FETCH_ASSOC)) {
                $id[] = ($row);
            }
            sqlsrv_free_stmt($queryConn);
            sqlsrv_close($conn);


            // Obtenemos el status de la inserción y el data_id
            $status = true;
            $data = $id;

            return compact('status', 'data');
        } catch (Exception $e) {

            $status = false;
            $data = 'Error';

            return compact('status', 'data');
        }
    }

    /// start graduados
        
    /**
     * @return Mixed
     */
    function getGraduatedStudents() {
        // Definir los valores a insertar
        try {
            $dbc = new DBConnectionModel();
            $dbc->createConnection();
            $conn = $dbc->getConnection();

            $query = 
            "SELECT p.nombre, p.primer_apellido, p.segundo_apellido, p.correo_institucional, p.correo_electronico, 
               pe.matricula_alumno AS matricula_estudiante, cte.descripcion AS estado_actual_estudiante, 
               pro.nombre_programa, ctg.tipo_grado, pe.rfc, pe.curp, be.fecha AS fecha_graduado
            FROM sga_persona AS p
            INNER JOIN sga_persona_sga_estudiante AS pe ON p.id = pe.id
            INNER JOIN sga_bitacora_estudiante AS be ON pe.id = be.estudiante_m2o_id
            INNER JOIN sga_cat_tipo_estudiante AS cte ON be.tipo_estudiante_m2o_id = cte.id
            INNER JOIN sga_programa AS pro ON pe.programa_m2o_id = pro.id
            INNER JOIN sga_cat_tipo_grado AS ctg ON pro.tipo_grado_m2o_id = ctg.id
            WHERE pe.matricula_alumno IS NOT NULL
            AND be.fecha = (SELECT MAX(fecha) AS Expr1 FROM dbo.sga_bitacora_estudiante AS BBE2 WHERE estudiante_m2o_id = pe.id)
            AND be.tipo_estudiante_m2o_id NOT IN (9, 13, 1, 2, 4, 10)
            ORDER BY be.fecha DESC";
          

            //Try query
            $queryConn = sqlsrv_query($conn, $query);
            if ($queryConn == FALSE) {
                $status = false;
                $data = ['errors' => sqlsrv_errors()];
                die(print_r(compact('status', 'data')));
            }

            $id = [];
            while ($row = sqlsrv_fetch_array($queryConn, SQLSRV_FETCH_ASSOC)) {
             
                if ($row['fecha_graduado']) {
                    $row['fecha_graduado'] = $row['fecha_graduado']->format('Y-m-d');
                }
                $id[] = ($row);

             
               
            }
            sqlsrv_free_stmt($queryConn);
            sqlsrv_close($conn);


            // Obtenemos el status de la inserción y el data_id
            $status = true;
            $data = $id;

            return compact('status', 'data');
        } catch (Exception $e) {

            $status = false;
            $data = 'Error';

            return compact('status', 'data');
        }

	}
    
    /// end graduados


    /// start bajas

    function getWithdrawnStudents() {
        // Definir los valores a insertar
        try {
            $dbc = new DBConnectionModel();
            $dbc->createConnection();
            $conn = $dbc->getConnection();

            $query = "SELECT p.nombre, p.primer_apellido, p.segundo_apellido, p.correo_institucional, p.correo_electronico, pe.matricula_alumno AS matricula_estudiante,
            cte.descripcion AS estado_actual_estudiante, pro.nombre_programa, ctg.tipo_grado, bj.id AS baja_id, bj.fecha_baja, p.id AS persona_id, be.id AS bitacora_id, p.id AS persona_id2
            FROM sga_persona p
            INNER JOIN sga_persona_sga_estudiante pe ON p.id = pe.id
            INNER JOIN sga_bitacora_estudiante be ON pe.id = be.estudiante_m2o_id
            INNER JOIN sga_cat_tipo_estudiante cte ON be.tipo_estudiante_m2o_id = cte.id
            INNER JOIN sga_programa pro ON pe.programa_m2o_id = pro.id
            INNER JOIN sga_cat_tipo_grado ctg ON pro.tipo_grado_m2o_id = ctg.id
            INNER JOIN sga_usuario_sistema sis ON p.id = sis.persona_m2o_id
            INNER JOIN sga_baja_estudiante bj ON pe.id = bj.estudiante_m2o_id
            INNER JOIN sga_estudiante_inscripcion ei ON pe.id = ei.estudiante_m2o_id
            INNER JOIN sga_periodo_escolar pes ON ei.periodo_escolar_m2o_id = pes.id
            WHERE (pe.matricula_alumno IS NOT NULL)
            AND (ei.fecha_inscripcion = (SELECT MAX(fecha_inscripcion) AS Expr1 FROM dbo.sga_estudiante_inscripcion AS BBE2 WHERE (estudiante_m2o_id = pe.id)))
            AND pe.matricula_alumno IS NOT NULL
            AND BJ.tipo_baja_m2o_id = 2
            AND (be.fecha = (SELECT MAX(fecha) AS Expr1 FROM dbo.sga_bitacora_estudiante AS BBE2 WHERE (estudiante_m2o_id = pe.id)))
            AND be.tipo_estudiante_m2o_id = 10
            AND bj.estado = 3
            ORDER BY bj.id DESC";
          

            //Try query
            $queryConn = sqlsrv_query($conn, $query);
            if ($queryConn == FALSE) {
                $status = false;
                $data = ['errors' => sqlsrv_errors()];
                die(print_r(compact('status', 'data')));
            }

            $id = [];
            while ($row = sqlsrv_fetch_array($queryConn, SQLSRV_FETCH_ASSOC)) {
                

                if ($row['fecha_baja']) {
                    $row['fecha_baja'] = $row['fecha_baja']->format('Y-m-d');
                }
                $id[] = ($row);

            }
            sqlsrv_free_stmt($queryConn);
            sqlsrv_close($conn);


            // Obtenemos el status de la inserción y el data_id
            $status = true;
            $data = $id;

            return compact('status', 'data');
        } catch (Exception $e) {

            $status = false;
            $data = 'Error';

            return compact('status', 'data');
        }

    }

    /// end bajas

    /// start Permisos getTemporalWithdrawnStudents


    function getTemporalWithdrawnStudents() {
        // Definir los valores a insertar
        try {
            $dbc = new DBConnectionModel();
            $dbc->createConnection();
            $conn = $dbc->getConnection();

            $query = 
            "SELECT p.nombre, p.primer_apellido, p.segundo_apellido, p.correo_institucional, p.correo_electronico, 
            pe.matricula_alumno AS matricula_estudiante, cte.descripcion AS estado_actual_estudiante, 
            pro.nombre_programa, ctg.tipo_grado, pe.rfc, pe.curp, bj.fecha_baja, bj.fecha_regreso 
            FROM sga_persona p
            INNER JOIN sga_persona_sga_estudiante pe ON p.id = pe.id 
            INNER JOIN sga_bitacora_estudiante be ON pe.id = be.estudiante_m2o_id 
            INNER JOIN sga_cat_tipo_estudiante cte ON be.tipo_estudiante_m2o_id = cte.id 
            INNER JOIN sga_programa pro ON pe.programa_m2o_id = pro.id
            INNER JOIN sga_cat_tipo_grado ctg ON pro.tipo_grado_m2o_id = ctg.id
            INNER JOIN sga_usuario_sistema sis ON p.id = sis.persona_m2o_id
            INNER JOIN sga_baja_estudiante bj ON pe.id = bj.estudiante_m2o_id 
            WHERE (pe.matricula_alumno IS NOT NULL)
            AND (be.fecha = (SELECT MAX(fecha) AS Expr1 FROM dbo.sga_bitacora_estudiante AS BBE2 WHERE (estudiante_m2o_id = pe.id)))
            AND pe.matricula_alumno IS NOT NULL
            AND BJ.tipo_baja_m2o_id = 1
            AND be.tipo_estudiante_m2o_id = 9
            AND bj.estado = 3
            ORDER BY cte.id ASC";
          

            //Try query
            $queryConn = sqlsrv_query($conn, $query);
            if ($queryConn == FALSE) {
                $status = false;
                $data = ['errors' => sqlsrv_errors()];
                die(print_r(compact('status', 'data')));
            }

            $id = [];
            while ($row = sqlsrv_fetch_array($queryConn, SQLSRV_FETCH_ASSOC)) {
                

                if ($row['fecha_baja']) {
                    $row['fecha_baja'] = $row['fecha_baja']->format('Y-m-d');
                }
                
                if ($row['fecha_regreso']) {
                    $row['fecha_regreso'] = $row['fecha_regreso']->format('Y-m-d');
                }


                $id[] = ($row);    



            }
            sqlsrv_free_stmt($queryConn);
            sqlsrv_close($conn);


            // Obtenemos el status de la inserción y el data_id
            $status = true;
            $data = $id;

            return compact('status', 'data');
        } catch (Exception $e) {

            $status = false;
            $data = 'Error';

            return compact('status', 'data');
        }

    }

    /// end Permisos


    /// start Activos del CENEW

    function getActiveEnrolledStudentCenew() {
        // Definir los valores a insertar
        try {
            $dbc = new DBConnectionModel();
            $dbc->createConnection();
            $conn = $dbc->getConnection();

            $query = 
            "SELECT nombre, primer_apellido, segundo_apellido, correo_electronico, correo_institucional, matricula_estudiante, 
               estado_actual_estudiante, nombre_programa, tipo_grado, rfc, curp, fecha_ultimo_registro_cenew, fecha_graduacion 
            FROM Activos_cenew";
          

            //Try query
            $queryConn = sqlsrv_query($conn, $query);
            if ($queryConn == FALSE) {
                $status = false;
                $data = ['errors' => sqlsrv_errors()];
                die(print_r(compact('status', 'data')));
            }

            $id = [];
            while ($row = sqlsrv_fetch_array($queryConn, SQLSRV_FETCH_ASSOC)) {
                $id[] = ($row);
            }
            sqlsrv_free_stmt($queryConn);
            sqlsrv_close($conn);


            // Obtenemos el status de la inserción y el data_id
            $status = true;
            $data = $id;

            return compact('status', 'data');
        } catch (Exception $e) {

            $status = false;
            $data = 'Error';

            return compact('status', 'data');
        }

    }

    /// end Activos del CENEW

    /// start Inconsistencia 

    function getInconsistentRecords() {
        // Definir los valores a insertar
        try {
            $dbc = new DBConnectionModel();
            $dbc->createConnection();
            $conn = $dbc->getConnection();

            $query =" 
        SELECT [id],
               [primer_apellido],
               [segundo_apellido],
               [nombre],
               [nombre_completo],
               [genero],
               [correo_electronico],
               [matricula_alumno],
               [fecha_registro_aspirante],
               [curp],
               [rfc],
               [fecha_nacimiento],
               [nombre_programa],
               [estado_estudiante],
               [tipo_estudiante_m2o_id],
               [descripcion]
        FROM [DbSga].[dbo].[datos_estudiante]
        WHERE matricula_alumno NOT IN (
            SELECT pe.matricula_alumno
            FROM sga_persona AS p
            INNER JOIN sga_persona_sga_estudiante AS pe ON p.id = pe.id
            INNER JOIN sga_estudiante_admision AS ea ON pe.id = ea.estudiante_m2o_id
            INNER JOIN sga_bitacora_estudiante AS be ON pe.id = be.estudiante_m2o_id
            INNER JOIN sga_cat_tipo_estudiante AS cte ON be.tipo_estudiante_m2o_id = cte.id
            INNER JOIN sga_programa AS pro ON pe.programa_m2o_id = pro.id
            INNER JOIN sga_cat_tipo_grado AS ctg ON pro.tipo_grado_m2o_id = ctg.id
            INNER JOIN sga_estudiante_inscripcion AS ei ON ei.estudiante_m2o_id = pe.id
            INNER JOIN sga_periodo_escolar AS pescolar ON ei.periodo_escolar_m2o_id = pescolar.id
            LEFT OUTER JOIN sga_programa AS programa ON pe.programa_m2o_id = programa.id
            WHERE pe.matricula_alumno NOT LIKE 'NULL'
            AND ei.estado = '3'
            AND pe.matricula_alumno IS NOT NULL
            AND p.estado <> 0
            AND pe.estado_estudiante <> 0
            AND pescolar.id = 20
            AND ea.criterio_aprobacion_total = 1
            AND ea.estado = 3
            AND be.fecha = (SELECT MAX(fecha) AS Expr1 FROM dbo.sga_bitacora_estudiante AS BBE2 WHERE estudiante_m2o_id = pe.id)
        ) AND matricula_alumno NOT IN (
            SELECT pe.matricula_alumno
            FROM sga_persona AS p
            INNER JOIN sga_persona_sga_estudiante AS pe ON p.id = pe.id
            INNER JOIN sga_bitacora_estudiante AS be ON pe.id = be.estudiante_m2o_id
            INNER JOIN sga_cat_tipo_estudiante AS cte ON be.tipo_estudiante_m2o_id = cte.id
            INNER JOIN sga_programa AS pro ON pe.programa_m2o_id = pro.id
            INNER JOIN sga_cat_tipo_grado AS ctg ON pro.tipo_grado_m2o_id = ctg.id
            WHERE pe.matricula_alumno IS NOT NULL
            AND be.fecha = (SELECT MAX(fecha) AS Expr1 FROM dbo.sga_bitacora_estudiante AS BBE2 WHERE estudiante_m2o_id = pe.id)
            AND be.tipo_estudiante_m2o_id NOT IN (9, 13, 1, 2, 4, 10)
        ) AND matricula_alumno NOT IN (
            SELECT pe.matricula_alumno
            FROM sga_persona AS p
            INNER JOIN sga_persona_sga_estudiante AS pe ON p.id = pe.id
            INNER JOIN sga_bitacora_estudiante AS be ON pe.id = be.estudiante_m2o_id
            INNER JOIN sga_cat_tipo_estudiante AS cte ON be.tipo_estudiante_m2o_id = cte.id
            INNER JOIN sga_programa AS pro ON pe.programa_m2o_id = pro.id
            INNER JOIN sga_cat_tipo_grado AS ctg ON pro.tipo_grado_m2o_id = ctg.id
            INNER JOIN sga_usuario_sistema AS sis ON p.id = sis.persona_m2o_id
            INNER JOIN sga_baja_estudiante AS bj ON pe.id = bj.estudiante_m2o_id
            INNER JOIN sga_estudiante_inscripcion AS ei ON pe.id = ei.estudiante_m2o_id
            INNER JOIN sga_periodo_escolar AS pes ON ei.periodo_escolar_m2o_id = pes.id
            WHERE pe.matricula_alumno IS NOT NULL
            AND ei.fecha_inscripcion = (SELECT MAX(fecha_inscripcion) AS Expr1 FROM dbo.sga_estudiante_inscripcion AS BBE2 WHERE estudiante_m2o_id = pe.id)
            AND pe.matricula_alumno IS NOT NULL
            AND bj.tipo_baja_m2o_id = 2
            AND be.fecha = (SELECT MAX(fecha) AS Expr1 FROM dbo.sga_bitacora_estudiante AS BBE2 WHERE estudiante_m2o_id = pe.id)
            AND be.tipo_estudiante_m2o_id = 10
            AND bj.estado = 3
        ) AND matricula_alumno NOT IN (
            SELECT pe.matricula_alumno
            FROM sga_persona AS p
            INNER JOIN sga_persona_sga_estudiante AS pe ON p.id = pe.id 
            INNER JOIN sga_bitacora_estudiante AS be ON pe.id = be.estudiante_m2o_id 
            INNER JOIN sga_cat_tipo_estudiante AS cte ON be.tipo_estudiante_m2o_id = cte.id 
            INNER JOIN sga_programa AS pro ON pe.programa_m2o_id = pro.id
            INNER JOIN sga_cat_tipo_grado AS ctg ON pro.tipo_grado_m2o_id = ctg.id
            INNER JOIN sga_usuario_sistema AS sis ON p.id = sis.persona_m2o_id
            INNER JOIN sga_baja_estudiante AS bj ON pe.id = bj.estudiante_m2o_id 
            WHERE pe.matricula_alumno IS NOT NULL
            AND be.fecha = (SELECT MAX(fecha) AS Expr1 FROM dbo.sga_bitacora_estudiante AS BBE2 WHERE estudiante_m2o_id = pe.id)
            AND pe.matricula_alumno IS NOT NULL
            AND bj.tipo_baja_m2o_id = 1
            AND be.tipo_estudiante_m2o_id = 9
            AND bj.estado = 3
        )
        ORDER BY primer_apellido;
    ";

          

            //Try query
            $queryConn = sqlsrv_query($conn, $query);
            if ($queryConn == FALSE) {
                $status = false;
                $data = ['errors' => sqlsrv_errors()];
                die(print_r(compact('status', 'data')));
            }

            $id = [];
            while ($row = sqlsrv_fetch_array($queryConn, SQLSRV_FETCH_ASSOC)) {
                $id[] = ($row);
            }
            sqlsrv_free_stmt($queryConn);
            sqlsrv_close($conn);


            // Obtenemos el status de la inserción y el data_id
            $status = true;
            $data = $id;

            return compact('status', 'data');
        } catch (Exception $e) {

            $status = false;
            $data = 'Error';

            return compact('status', 'data');
        }

    }





    /// end Inconsistencia 





}
