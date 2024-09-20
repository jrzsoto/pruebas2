<?php

namespace models\SQLQueries;

//include 'DBConnectionModel.php';
//use models\SQLQueries\DBConnectionModel;


/*public function mostrar(){
    $program = "select * from sga_programa where ";

    print_r($program);
}*/


class Catalogs {

    /**
     * Constructor
     */
    public function __construct() {
    }

    public function programCatalog($tipo_posgrado, $degree) {

        $program = $degree. " en " . $tipo_posgrado; //$degree+' en '+ $tipo_posgrado;
        if ($program === 'Maestría en Ciencias en la Especialidad de Astrofísica') {
            return '1';
        } elseif ($program === 'Maestría en Ciencias en la Especialidad de Electrónica') {
            return '3';
        } elseif ($program === 'Maestría en Ciencias en la Especialidad de Óptica') {
            return '5';
        }elseif ($program === 'Maestría en Ciencias en el Área de Ciencias Computacionales') {
            return '7';
        } elseif ($program === 'Maestría en Ciencias en el Área de Ciencia y Tecnología del Espacio') {
            return '9';
        }elseif ($program === 'Maestría en Ciencias y Tecnologías Biomédicas') {
            return '11';
        } elseif ($program === 'Maestría en Enseñanza de Ciencias Exactas') {
            return '12';
        }elseif ($program === 'Maestría en Ciencias y Tecnologías de Seguridad') {
            return '13';
        } elseif ($program === 'Doctorado en Ciencias en la Especialidad de Astrofísica') {
            return '2';
        }elseif ($program === 'Doctorado en Ciencias en la Especialidad de Electrónica') {
            return '4';
        } elseif ($program === 'Doctorado en Ciencias en la Especialidad de Óptica') {
            return '6';
        }elseif ($program === 'Doctorado en Ciencias en el Área de Ciencias Computacionales') {
            return '8';
        } elseif ($program === 'Doctorado en Ciencias en el Área de Ciencia y Tecnología del Espacio') {
            return '10';
        }elseif ($program === 'Doctorado en Ciencias y Tecnologías Biomédicas') {
            return '14';
        } 
         else {
            return '7'; // Valor por defecto si no coincide con ningún programa
        }
    }
    
    // Método para obtener el catálogo de estados
    public function birthPlaceCatalog($birthPlace) {
        if ($birthPlace === 'Puebla') {
            return '20';
        } else {
            return '20'; // Valor por defecto si no coincide con ningún estado
        }
    }
    
    //metodo para obtener el genero del aspirante 
    public function genderCatalog($gender){
        if($gender === 'Hombre'){
            return '1';
        }else {
            return '2';
        }
    }
    
    //metodo de catalago  de sga_cat_estado_proceso esto se le asigna en al tabla sga_estudiante_admision
    // campos estado_proceso_m2o_id y etapa_procesos_m2o_id
    

    // campos estado_proceso_m2o_id y etapa_procesos_m2o_id
        public function processStateCatalog($degree){
    
            if($degree === 'Maestría' || $degree === 'maestria')
            {           
                return '1'; 
            }else {              
                return'16';
            }
        }
    
    //metodo de sga_cat_estado_proceso esto se le asigna en al tabla sga_estudiante_admision
        public function processStageCatalog($degree){
            if ($degree==='Maestría'|| $degree === 'maestria')
            {
                return '5';
            }
            else {
                return '20';
            }
        }
    

  // Método para obtener el catálogo de estados
     public function countryCatalog($country) {
    
        switch ($country){
            case "Afganistán":
                return'1';
            break;
            case "Albania":
                return'2';
            break;
            case "Alemania":
                return'3';
            break;
            case "Andorra":
                return'4';
            break;
            case "Angola":
                return'5';
            break;
            case "Anguilla":
                return'76';
            break;
            case "Antártida":
                return'76';
            break;
            case "Antigua y Barbuda":
                return'76';
            break;
            case "Arabia Saudita":
                return'6';
            break;
            case "Argelia":
                return'7';
            break;
            case "Argentina":
                return'8';
            break;
            case "Armenia":
                return'9';
            break;
            case "Aruba":
                return'10';
            break;
            case "Australia":
                return'11';
            break;
             case "Austria":
                return'20';
            break;
             case "Azerbaiyán":
                return'76';
            break;
            case "Bahamas":
                return'12';
            break;
            case "Bangladesh":
                return'76';
            break;
            case "Barbados":
                return'13';
            break;
            case "Baréin":
                return'76';
            break;
            case "Bélgica":
                return'14';
            break;
            case "Belice":
                return'15';
            break;
            case "Benín":
                return'76';
            break;
            case "Bermudas":
                return'42';
            break;
            case "Bielorrusia":
                return'16';
            break;
            case "Bolivia":
                return'17';
            break;
            case "Bonaire":
                return'18';
            break;
            case "Bosnia-Herzegovina":
                return'18';
            break;
            case "Botsuana":
                return'76';
            break;
            case "Brasil":
                return'19';
            break;
            case "Brunéi Darussalam":
                return'76';
            break;
            case "Bulgaria":
                return'21';
            break;
            case "Burkina Faso":
                return'21';
            break;
            case "Burundi":
                return'76';
            break;
            case "Bután":
                return'76';
            break;
            case "Cabo Verde":
                return'76';
            break;
            case "Camboya":
                return'22';
            break;
            case "Camerún":
                return'23';
            break;
            case "Canadá":
                return'24';
            break;
            case "Chad":
                return'76';
            break;
            case "Chile":
                return'26';
            break;
            case "China":
                return'27';
            break;
            case "Chipre":
                return'76';
            break;
            case "Colombia":
                return'28';
            break;
            case "Comoras":
                return'76';
            break;
            case "Congo":
                return'114';
            break;
            case "Congo RD":
                return'114';
            break;
            case "Corea del Norte":
                return'29';
            break;
            case "Corea del Sur":
                return'29';
            break;
            case "Costa de Marfil":
                return'76';
            break;
            case "Costa Rica":
                return'30';
            break;
            case "Croacia":
                return'31';
            break;
            case "Cuba":
                return'32';
            break;
            case "Curazao":
                return'76';
            break;
            case "Dinamarca":
                return'33';
            break;
            case "Dominica":
                return'34';
            break;
            case "Ecuador":
                return'36';
            break;
            case "Egipto":
                return'37';
            break;
            case "El Salvador":
                return'';
            break;
            case "Emiratos Árabes Unidos":
                return'6';
            break;
            case "Eritrea":
                return'76';
            break;
            case "Eslovaquia":
                return'39';
            break;
            case "Eslovenia":
                return'40';
            break;
            case "España":
                return'41';
            break;
            case "Estados Unidos":
                return'42';
            break;
            case "Estonia":
                return'43';
            break;
            case "Etiopía":
                return'44';
            break;
            case "Federación Rusa":
                return'92';
            break;
            case "Filipinas":
                return'45';
            break;
            case "Finlandia":
                return'46';
            break;
            case "Fiyi":
                return'76';
            break;
            case "Francia":
                return'47';
            break;
            case "Gabón":
                return'76';
            break;
            case "Gambia":
                return'76';
            break;
            case "Georgia":
                return'76';
            break;
            case "Ghana":
                return'';
            break;
            case "Gibraltar":
                return'76';
            break;
            case "Granada":
                return'76';
            break;
            case "Grecia":
                return'49';
            break;
            case "Groenlandia":
                return'42';
            break;
            case "Guadalupe":
                return'76';
            break;
            case "Guam":
                return'76';
            break;
            case "Guatemala":
                return'50';
            break;
            case "Guayana Francesa":
                return'47';
            break;
            case "Guernsey":
                return'76';
            break;
            case "Guinea":
                return'76';
            break;
            case "Guinea Ecuatorial":
                return'76';
            break;
            case "Guinea-Bissau":
                return'76';
            break;
            case "Guyana":
                return'51';
            break;
            case "Haití":
                return'52';
            break;
            case "Honduras":
                return'54';
            break;
            case "Hong Kong":
                return'27';
            break;
            case "Hungría":
                return'55';
            break;
            case "India":
                return'115';
            break;
            case "Indonesia":
                return'56';
            break;
            case "Irán":
                return'58';
            break;
            case "Irlanda":
                return'60';
            break;
            case "Isla Bouvet":
                return'56';
            break;
            case "Isla de Man":
                return'76';
            break;
            case "Isla De Navidad, Isla Christmas":
                return'42';
            break;
            case "Isla Norfolk":
                return'53';
            break;
            case "Islandia":
                return'56';
            break;
            case "Islas Áland":
                return'76';
            break;
            case "Islas Caimán":
                return'42';
            break;
            case "Islas Cocos":
                return'42';
            break;
            case "Islas Cook":
                return'42';
            break;
            case "Islas Falkland":
                return'42';
            break;
            case "Islas Feroe":
                return'42';
            break;
            case "Islas Georgias del Sur y Sándwich del Sur":
                return'42';
            break;
            case "Islas Heard y Mcdonald":
                return'42';
            break;
            case "Islas Marianas de Norte":
                return'42';
            break;
            case "Islas Marshall":
                return'42';
            break;
            case "Islas Salomón":
                return'42';
            break;
            case "Islas Turcas y Caicos":
                return'107';
            break;
            case "Islas Ultramarinas Menores de EU":
                return'42';
            break;
            case "Islas Vírgenes Británicas":
                return'20';
            break;
            case "Islas Vírgenes de EE.UU.":
                return'42';
            break;
            case "Israel":
                return'61';
            break;
            case "Italia":
                return'62';
            break;
            case "Jamaica":
                return'63';
            break;
            case "Japón":
                return'64';
            break;
            case "Jersey":
                return'42';
            break;
            case "Jordania":
                return'65';
            break;
            case "Kazajistán":
                return'92';
            break;
            case "Kenia":
                return'';
            break;
            case "Kirguistán":
                return'92';
            break;
            case "Kiribati":
                return'92';
            break;
            case "Kuwait":
                return'';
            break;
            case "Lesoto":
                return'68';
            break;
            case "Letonia":
                return'68';
            break;
            case "Líbano":
                return'';
            break;
            case "Liberia":
                return'69';
            break;
            case "Libia":
                return'70';
            break;
            case "Liechtenstein":
                return'71';
            break;
            case "Lituania":
                return'71';
            break;
            case "Luxemburgo":
                return'20';
            break;
            case "Macao":
                return'76';
            break;
            case "Macedonia":
                return'49';
            break;
            case "Madagascar":
                return'';
            break;
            case "Malasia":
                return'73';
            break;
            case "Malawi":
                return'73';
            break;
            case "Maldivas":
                return'73';
            break;
            case "Malí":
                return'73';
            break;
            case "Malta":
                return'73';
            break;
            case "Marruecos":
                return'75';
            break;
            case "Martinica":
                return'76';
            break;
            case "Mauricio":
                return'76';
            break;
            case "Mauritania":
                return'76';
            break;
            case "Mayotte":
                return'';
            break;
            case "México":
                return'76';
            break;
            case "Micronesia":
                return'';
            break;
            case "Moldavia":
                return'77';
            break;
            case "Mónaco":
                return'47';
            break;
            case "Mongolia":
                return'78';
            break;
            case "Montenegro":
                return'79';
            break;
            case "Montserrat":
                return'79';
            break;
            case "Mozambique":
                return'80';
            break;
            case "Myanmar":
                return'80';
            break;
            case "Namibia":
                return'80';
            break;
            case "Nauru":
                return'80';
            break;
            case "Nepal":
                return'83';
            break;
            case "Nicaragua":
                return'76';
            break;
            case "Níger":
                return'83';
            break;
            case "Nigeria":
                return'83';
            break;
            case "Niue":
                return'76';
            break;
            case "Noruega":
                return'84';
            break;
            case "Nueva Caledonia":
                return'76';
            break;
            case "Nueva Zelanda":
                return'81';
            break;
            case "Omán":
                return'76';
            break;
            case "Países Bajos, Holanda":
                return'53';
            break;
            case "Pakistán":
                return'59';
            break;
            case "Palaos":
                return'76';
            break;
            case "Palestina":
                return'59';
            break;
            case "Panamá":
                return'85';
            break;
            case "Papúa Nueva Guinea":
                return'';
            break;
            case "Paraguay":
                return'86';
            break;
            case "Perú":
                return'87';
            break;
            case "Pitcairn":
                return'76';
            break;
            case "Polinesia Francesa":
                return'47';
            break;
            case "Polonia":
                return'88';
            break;
            case "Portugal":
                return'89';
            break;
            case "Puerto Rico":
                return'90';
            break;
            case "Qatar":
                return'6';
            break;
            case "Reino Unido":
                return'20';
            break;
            case "República Centroafricana":
                return'100';
            break;
            case "República Checa":
                return'25';
            break;
            case "República Democrática Popular Lao":
                return'76';
            break;
            case "":
            case "República Dominicana":
                return'Dominicano';
            break;
            case "Reunión":
                return'20';
            break;
            case "Ruanda":
                return'91';
            break;
            case "Rumanía":
                return'91';
            break;
            case "Sáhara Occidental":
                return'6';
            break;
            case "Samoa":
                return'100';
            break;
            case "Samoa Americana":
                return'42';
            break;
            case "San Bartolomé":
                return'76';
            break;
            case "San Cristóbal y Nieves":
                return'95';
            break;
            case "San Marino":
                return'76';
            break;
            case "San Martin":
                return'';
            break;
            case "San Pedro y Miquelón":
                return'76';
            break;
            case "San Vicente y Las Granadinas":
                return'76';
            break;
            case "Santa Helena":
                return'76';
            break;
            case "Santa Lucía":
                return'76';
            break;
            case "Santa Sede (Ciudad Estado Vaticano)":
                return'62';
            break;
            case "Santo Tomé y Príncipe":
                return'62';
            break;
            case "Senegal":
                return'76';
            break;
            case "Serbia":
                return'98';
            break;
            case "Seychelles":
                return'76';
            break;
            case "Sierra Leona":
                return'76';
            break;
            case "Singapur":
                return'27';
            break;
            case "Sint Maarten":
                return'42';
            break;
            case "Siria":
                return'59';
            break;
            case "Somalia":
                return'59';
            break;
            case "Sri Lanka":
                return'59';
            break;
            case "Suazilandia":
                return'76';
            break;
            case "Sudáfrica":
                return'100';
            break;
            case "Sudán":
                return'100';
            break;
            case "Sudán del Sur":
                return'100';
            break;
            case "Suecia":
                return'101';
            break;
            case "Suiza":
                return'102';
            break;
            case "Surinam":
                return'104';
            break;
            case "Svalbard y Jan Mayen":
                return'104';
            break;
            case "Tailandia":
                return'104';
            break;
            case "Taiwán":
                return'102';
            break;
            case "Tanzania":
                return'113';
            break;
            case "Tayikistán":
                return'92';
            break;
            case "Territorio Británico del Océano Índico.":
                return'20';
            break;
            case "Territorios Australes Franceses":
                return'47';
            break;
            case "Timor-Leste":
                return'47';
            break;
            case "Togo":
                return'76';
            break;
            case "Tonga":
                return'76';
            break;
            case "Trinidad y Tobago":
                return'94';
            break;
            case "Túnez":
                return'94';
            break;
            case "Turkmenistán":
                return'92';
            break;
            case "Turquía":
                return'107';
            break;
            case "Tuvalu":
                return'76';
            break;
            case "Ucrania":
                return'108';
            break;
            case "Uganda":
                return'';
            break;
            case "Uruguay":
                return'109';
            break;
            case "Uzbekistán":
                return'92';
            break;
            case "Vanuatu":
                return'92';
            break;
            case "Venezuela":
                return'110';
            break;
            case "Vietnam":
                return'110';
            break;
            case "Wallis y Futuna":
                return'42';
            break;
            case "Yemen":
                return'59';
            break;
            case "Yibuti":
                return'76';
            break;
            case "Zambia":
                return'76';
            break;
            case "Zimbabue":
                return'76';
            break;
            default :
                return '76';
             break;

            }
    }
  
    //metodo para obtener el proceso de admision que se inserta en el campo proceso_m2o_id de la table 
    // sga_estudainte_admision 
    public function admisionProcessCatalog($program, $degree){
        
        $program = $degree. " en " . $program;
        
        if ($program === 'Maestría en Ciencias en la Especialidad de Astrofísica') {
            return '129';
        } elseif ($program === 'Maestría en Ciencias en la Especialidad de Electrónica') {
            return'131';
        } elseif ($program === 'Maestría en Ciencias en la Especialidad de Óptica') {
            return '130';
        }elseif ($program === 'Maestría en Ciencias en el Área de Ciencias Computacionales') {
            return '132';
        } elseif ($program === 'Maestría en Ciencias en el Área de Ciencia y Tecnología del Espacio') {
            return '133';
        }elseif ($program === 'Maestría en Ciencias y Tecnologías Biomédicas') {
            return '134';
        } elseif ($program === 'Maestría en Enseñanza de Ciencias Exactas') {
            return '136';
        }elseif ($program === 'Maestría en Ciencias y Tecnologías de Seguridad') {
            return '137';
        } elseif ($program === 'Doctorado en Ciencias en la Especialidad de Astrofísica') {
            return '116';
        }elseif ($program === 'Doctorado en Ciencias en la Especialidad de Electrónica') {
            return '118';
        } elseif ($program === 'Doctorado en Ciencias en la Especialidad de Óptica') {
            return '117';
        }elseif ($program === 'Doctorado en Ciencias en el Área de Ciencias Computacionales') {
            return '119';
        } elseif ($program === 'Doctorado en Ciencias en el Área de Ciencia y Tecnología del Espacio') {
            return '121';
        }elseif ($program === 'Doctorado en Ciencias y Tecnologías Biomédicas') {
            return '120';
        } 
         else {
            return '129'; // Valor por defecto si no coincide con ningún programa
        }

    }
    public function studentProgramPrefix($program, $degree) {

        $year = "2024";

        $program = $degree. " en " . $program;
       
        if ($program === 'Maestría en Ciencias en la Especialidad de Astrofísica') {
            return $year.'MA'."XX";
        } elseif ($program === 'Maestría en Ciencias en la Especialidad de Electrónica') {
            return $year.'ME'."XX";
        } elseif ($program === 'Maestría en Ciencias en la Especialidad de Óptica') {
            return $year.'MO'."XX";
        }elseif ($program === 'Maestría en Ciencias en el Área de Ciencias Computacionales') {
            return $year.'MC'."XX";
        } elseif ($program === 'Maestría en Ciencias en el Área de Ciencia y Tecnología del Espacio') {
            return $year.'MT'."XX";
        }elseif ($program === 'Maestría en Ciencias y Tecnologías Biomédicas') {
            return $year.'MB'."XX";
        } elseif ($program === 'Maestría en Enseñanza de Ciencias Exactas') {
            return $year.'MZ'."XX";
        }elseif ($program === 'Maestría en Ciencias y Tecnologías de Seguridad') {
            return $year.'MS'."XX";
        } elseif ($program === 'Doctorado en Ciencias en la Especialidad de Astrofísica') {
            return $year.'DA'."XX";
        }elseif ($program === 'Doctorado en Ciencias en la Especialidad de Electrónica') {
            return $year.'DE'."XX";
        } elseif ($program === 'Doctorado en Ciencias en la Especialidad de Óptica') {
            return $year.'DO'."XX";
        }elseif ($program === 'Doctorado en Ciencias en el Área de Ciencias Computacionales') {
            return $year.'DC'."XX";
        } elseif ($program === 'Doctorado en Ciencias en el Área de Ciencia y Tecnología del Espacio') {
            return $year.'DT'."XX";
        }elseif ($program === 'Doctorado en Ciencias y Tecnologías Biomédicas') {
            return $year.'DB'."XX";
        } elseif ($program === 'Maestría en Electrónica') {
            return $year.'ME'."XX";
        }
         else {
            return $year.'MM'."XX"; // Valor por defecto si no coincide con ningún programa
        }
    }

}
