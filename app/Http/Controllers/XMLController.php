<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use ArchivoController;
use SimpleXMLElement;
use App\Modules\moduleXML;

class XMLController extends Controller
{

    public function obtainJson()
    {
        $data = file_get_contents("data.json");
        $data_json = json_decode($data, true);
        $respuesta = $this->generateNominaXML($data);
        
 
        return $respuesta;
    }

    public function generateNominaXML($json)
    {

        // SDSE - 25/03/2021
        // Lógica para generar 
        //header('Content-Type: application/json');
        //echo  $json_string = json_encode($json, JSON_PRETTY_PRINT);

        $contenido = json_decode($json,true);        
        foreach($contenido['rows'][0] as $value)
        {
            // SDSE - 25/03/2021
            // Se recorre el json para armar el XML
           $template = "<?xml version=\"1.0\"?><NominaIndividual></NominaIndividual>";
           $xml_template = new SimpleXMLElement($template);
           $xml_nomina = $this->jsontoxml($value,$xml_template);
           //var_dump($xml_nomina);
           $params =[
               'xmlstr' => $xml_nomina->asXML(),
               'idpgsql' => 5,
               'cune' => 'CUNE_'.time()
           ];

           $this->moduleXML = new moduleXML();
           echo $this->moduleXML->insertXML($params);
           
        }
        
    }

    public function generateNominaAjusteXML()
    {
        // SDSE - 25/03/2021
        // Lógica para generar 
    }

    public function jsontoxml($array, &$xml)
    {        
        
        foreach($array  as $key=>$line){
            if(!is_array($line)){                               
                $data = $xml->addChild($key, htmlspecialchars($line));  
            }else{
                $obj = $xml->addChild($key);
    
                if(!empty($line['attribute'])){
    
                    $attr = explode(":",$line['attribute']);
                    $obj->addAttribute($attr[0],$attr[1]);
                    unset($line['attribute']);
                }
                convert($line, $obj);
            }
        }
        return $xml;
    }
}
