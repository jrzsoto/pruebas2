<?php

namespace models\SQLQueries;


include 'DBConnectionModel.php';
include 'Catalogs.php';

use Exception;
use models\SQLQueries\DBConnectionModel;
use models\SQLQueries\Catalogs;

class VerifyModel {
    /**
     * Construct
     */
    public function __construct() {
    }
    
    /**
     * 
     * @return $this
     */
  
        function VerifyData(string $name, string $last_name, string $email, string $curp, string $second_last_name, 
                            string $id_general, string $gender, string $birth_date, string $birth_place, string $mobile_number, string $register_date,
                            string $nationality, string $admission_period, string $academic_area, string $degree, string $country) {

        try { 
            $catalogo = new Catalogs();   
            $country = trim($country);      
            /* 
            1.- duplicidad de correo electrónico y
            2.- curp para nacionales 
            3.- usuario longitud = 30
            4.- fecha validad
            */
            //$name = trim($name);
            //$Nation = $catalogo ->countryCatalog($country); 
            $resultEmailVerification = $this-> verifyEmail( $email );
            
           if ($resultEmailVerification['status']) 
           {   

             $status= true;
             $data = $resultEmailVerification['data']['count'];
              
             $nacionalidad = $catalogo->countryCatalog($country);
             switch($nacionalidad){
                     case 76:
                         $resultCurpVerification = $this-> verifyCURP($curp);
                         if($resultCurpVerification['status'] && $curp !== 'null'){            
                            $status = true;

                            $resultAgeVerification=$this->verifyAge($birth_date);
                            if($resultAgeVerification['status']){
                                 $status=true;
                            }
                            else{
                                 $data=$resultAgeVerification['data']['count'];
                                 return compact('status', 'data');

                            }

                         } else if($curp === null) {
                             $data = $resultCurpVerification['data']['count'];
                             return compact('status', 'data');
                         }else {
                             $data = $resultCurpVerification['data']['count'];
                             return compact('status', 'data');
                         }
                     break;
                     default:
                         $status = true;
                         $data = $resultEmailVerification['data']['count'];
                         
                             $resultAgeVerification=$this->verifyAge($birth_date);
                                 if($resultAgeVerification['status']){
                                         $status=true;
                                 }
                                 else{
                                        $data=$resultAgeVerification['data']['count'];
                                         return compact('status', 'data');
                                 }
                         return compact('status', 'data');
                 }
            }else{ 
                $status = false;
                $data = "Error ";
                return compact('status', 'data');
            }

            
            //$data=1;
            return compact('status', 'data');

        } catch (Exception $e) {

            $status = false;
            $data = 'Error';

            return compact('status', 'data');
        }


    }

    function verifySolicitudAdmision(string $email = null){
        try{
            $dbc = new DBConnectionModel();
            $dbc->createConnection();
            $conn1 = $dbc->getConnection();

            $sql = "SELECT pe.folio_aspirante, p.nombre_completo,p.correo_electronico
                    FROM sga_persona as p
                    inner join sga_persona_sga_estudiante as pe
                    on p.id=pe.id where p.correo_electronico like '$email'";

        }catch(Exception $e)
        {
            $status = false;
            $data = 'Error';

            return compact('status', 'data');
        }


    }

    function verifyEmail(string $email = null) {

        try {

            $dbc = new DBConnectionModel();
            $dbc->createConnection();
            $conn = $dbc->getConnection();

             $tsql = "SELECT COUNT(*) AS count FROM sga_persona WHERE correo_electronico = '$email'";

            //select query
            $selectReview = sqlsrv_query($conn, $tsql);
            if ($selectReview == FALSE) {
                $status = false;
                $data = ['errors'=>sqlsrv_errors()];
                die(print_r(compact('status', 'data')));
            }

            $count = null;
            while ($row = sqlsrv_fetch_array($selectReview, SQLSRV_FETCH_ASSOC)) {
                $count = ($row['count']);
            }
            sqlsrv_free_stmt($selectReview);
            sqlsrv_close($conn);

            // Obtenemos el status de la inserción y el data_id
            $status = true;
            $data = ['count' => $count];
      
            return compact('status', 'data');

        } catch (Exception $e) {

            $status = false;
            $data = 'Error';

            return compact('status', 'data');
        }
    }
    
    function verifyCURP(string $curp = null) {
                    
        try {
            
            
            // Expresión regular para validar CURP
            $patron = '/^([A-Z][AEIOUX][A-Z]{2}\d{2}(?:0[1-9]|1[0-2])(?:0[1-9]|[12]\d|3[01])[HM](?:AS|B[CS]|C[CLMSH]|D[FG]|G[TR]|HG|JC|M[CNS]|N[ETL]|OC|PL|Q[TR]|S[PLR]|T[CSL]|VZ|YN|ZS)[B-DF-HJ-NP-TV-Z]{3}[A-Z\d])(\d)$/';
            // Verificar si el CURP coincide con el patrón
            //$count=0;
            if (!preg_match($patron, $curp)) {  
                $status = false;
                   
            } else{
                $status=true;
            }

            switch($status){
                case false:
                    $count=4;
                    $status=false;
                    $data = ['count' => $count];
                    return compact('status','data');
                 break;
                case true:
                    $count=0;
                    $status=true;
                    $data = ['count' => $count];
                    return compact('status','data');
                 break;
                default:
                    $count=4;
                    $status=false;
                    $data = ['count' => $count];
                    return compact('status','data');
                break;

            }
        }
        catch(Exception $e){
            $status = false;
            $data = 1;
            return compact('status','data');
        }
       

    }

 
    function verifyAge(string $birth_date) {

        try{
          // Usando la función date() con una fecha específica
            //$fechaNacimiento = new DateTime($birth_date);
            $añoNacimiento = substr($birth_date,0,4);
            // Obtener la fecha actual
            //$fechaActual = new DateTime();
            $añoActual=2024;

            $edad = ($añoActual-$añoNacimiento);

            //Calculamos usando diff y la bi$birth_date actual
            //$diferencia = $fechaNacimiento ->diff($fechaActual);

            if ($edad > 18) 
            {
                //echo "Usted es menor de edad. Su edad es: $diferencia\n";
                $status = true;
                $count= 0;
                
             }else{
                //echo "Usted es mayor de edad. Su edad es: $diferencia\n";
                $status = false;
                $count= 17;              
                
            }
            $data = ['count' => $count];
            return  compact('status','data');
        } catch(Exception $e){
            $status = false;
            $data = "error";
            return compact('status','data');
        }
       
    }
    

}