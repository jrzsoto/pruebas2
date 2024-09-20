<?php

//curp validacion
function validate_curp($curp) {     
     if(strlen($curp)==18){         
        $letras     = substr($curp, 0, 4);
        $numeros    = substr($curp, 4, 6);         
        $sexo       = substr($curp, 10, 1);
        $mxState    = substr($curp, 11, 2); 
        $letras2    = substr($curp, 13, 3); 
        $homoclave  = substr($curp, 16, 2);
          if(ctype_alpha($letras) && ctype_alpha($letras2) && ctype_digit($numeros) && ctype_digit($homoclave) && is_mx_state($mxState) && is_sexo_curp($sexo)){ 
            return true; 
        }         
    return false;
     }else{
         return false; 
    } 
}

// ... validacion de estados de mexico 

function is_mx_state($state){     
    $mxStates = [         
        'AS','BS','CL','CS','DF','GT',         
        'HG','MC','MS','NL','PL','QR',         
        'SL','TC','TL','YN','NE','BC',         
        'CC','CM','CH','DG','GR','JC',         
        'MN','NT','OC','QT','SP','SR',         
        'TS','VZ','ZS'     
    ];     
    if(in_array(strtoupper($state),$mxStates)){         
        return true;     
    }     
    return false; 
}

// ...validacion de sexo en el curp 

function is_sexo_curp($sexo){     
    $sexoCurp = ['H','M'];     
    if(in_array(strtoupper($sexo),$sexoCurp)){         
       return true;     
       
    }     
    return false; 
}

//Validar la Curp
/*if(validate_curp($la_curp)){
    echo '
   La CURP '.$la_curp.' es válida
   
   ';
   }else{
    echo '
   La CURP '.$la_curp.' es inválida
   
   ';
   }*/