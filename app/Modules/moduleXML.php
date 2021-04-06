<?php

namespace App\Modules;
use App\Models\Nomina;
use \MongoDB;
use SimpleXMLElement;

class moduleXML 
{
    
    public function generateNominaXML($json)
    {
        
        // SDSE - 25/03/2021
        // Lógica para generar 
        //header('Content-Type: application/json');
        //echo  $json_string = json_encode($json, JSON_PRETTY_PRINT);

        $contenido = json_decode($json,true);
        //var_dump($contenido);
        foreach($contenido[0] as $value)
        {
           /* 
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
           */

          //return response()->view('header', compact('value'))->header('Content-Type', 'text/xml');
          /*$output = \View::make('header')->with(compact('value'))->render();
          $xml = "<?xml version=\"1.0\" ?>\n" . $output;*/
          /*$params =[
            'xmlstr' => $xml_nomina->asXML(),
            'idpgsql' => 5,
            'cune' => 'CUNE_'.time()
          ];*/
          //$datass = response()->view('header', compact('value'))->header('Content-Type', 'text/xml');
          $output = \View::make('template.xml')->with(compact('value'))->render();
          $xml = "<?xml version=\"1.0\" ?>\n" . $output;
          $params =[
            'xmlstr' => $xml,
            'idpgsql' => 5,
            'cune' => 'CUNE_'.time()
          ];
          //return $datass;
          $this->moduleXML = new moduleXML();
          echo $this->moduleXML->insertXML($params);
          //return view('header', compact('value'));
        }
        
        
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
    
    public function insertXML($params)
    {
        $fh_date = time(); 
        $file_name = "nomina_$fh_date.xml";
        //$fh = fopen($file_name, 'w') or die("Se produjo un error al crear el archivo");
        //fwrite($fh, $params['xmlstr']) or die("No se pudo escribir en el archivo"); 
        //fclose($fh);
        
        $stream = fopen('php://memory','r+');
        fwrite($stream, $params['xmlstr']);
        rewind($stream);

        $bucket = (new MongoDB\Client)->test->selectGridFSBucket();
        //$file = fopen($file_name, 'rb');
        $id_bucket = $bucket->uploadFromStream($file_name,  $stream);
        
        
        

        // SDSE - 10/03/2021
        // Se almacena en MariaDB
        $nomina = new Nomina;
        $nomina->cune = $params['cune'];        
        $nomina->idfile = $id_bucket;        
        $nomina->filename = $file_name; 
        $nomina->save();

        return back()->with('message3', 'Generación de XML realizada exitosamente');
    }
}