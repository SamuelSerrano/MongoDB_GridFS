<?php

namespace App\Modules;
use App\Models\Nomina;
use \MongoDB;
use SimpleXMLElement;

/*Use new class calculosNomina */
use DateTime;  
use DateTimeZone;
use Throwable;
use number_format;
use hash;
class moduleXML 
{
    // SDSE 12/04/2021
    // Función que calcula el tiempo laborado el trabajador en la empresa.
    // $fechaingreso : Fecha de Ingreso del trabajador a la empresa, en formato AAAA-MM‐DD    
    // $fechaliquidacioninicio: Fecha de Inicio del Periodo de liquidación del documento, en formato AAAA-MM‐DD
    // $fechaliquidacionfin: Fecha de fin del Periodo de liquidación del documento, en formato AAAA-MM‐DD
    // $fecharetiro (optional): Fecha de Retiro del trabajador a la empresa, en formato AAAA-MM‐DD
    public function SetTiempoLaborado($fechaingreso, $fechaliquidacioninicio, $fechaliquidacionfin, $fecharetiro=null){
        try
        {                      
            // SDSE 14/04/2021
            // Se inicializan las variables 
            $fechainicio = new DateTime($fechaingreso);    
            $fechafin = (!is_null($fecharetiro)) ?  new DateTime($fecharetiro) :  new DateTime($fechaliquidacionfin);
            
            // Se aplica el la función datediff
            $diff = $fechainicio->diff($fechafin);

            // Se obtienen los años, meses y días de la diferencia de fechas
            $year = $diff->y;
            $month = $diff->m;
            $day = $diff->d;

            // Se aplica la formula del anexo técnico 8.3.1. Cálculo de Tiempo Laborado
            $tiempolaborado = ($year*360)+($month*30)+$day;

            //return "Año ".$year." Mes: ".$month." Dias: ".$day." (".$tiempolaborado.")";
            return $tiempolaborado;
        }
        catch(Throwable $e)
        {
           throw $e;
        }
    }

    //SDSE - 12/04/2021
    // Funcion encargada de crear el Numero de consecutivo NIE012 (NIE010 + NIE011)        
    public function SetNumeroSecuencia($consecutivo,$prefijo="")
    {
        $numero = $prefijo.(string)$consecutivo;
        return $numero;
    }

    // SDSE - 12/04/2021
    // Se calcula la fecha actual dependiendo el formato especificado para la Zona Horaria de Colombia
    // $format Ymd  = YYYY-MM-DD 
    //         Ymdh = YYYY‐MM‐DDTHH:MM:SS
    //         h    = HH:MM:SS
    public function getDate($format)
    {        
        switch($format)
        {
            case 'ymd': $formato = 'Y-m-d';
            break;
            case 'ymdh': $formato = 'Y-m-d\TH:i:s';
            break;
            case 'h': $formato = 'H:i:s';
            break;
            case 'hgmt': $formato = 'H:i:sP';
            break;
            case 'y': $formato = 'y';
            break;
            default: $formato = 'Y-m-d';
            break;
        }
        $date = new DateTime();
        $date->setTimezone(new DateTimeZone("America/Bogota"));
        return  $date->format($formato);
    }

    // SDSE - 04/12/2021
    // Método para armar el CUNE según anexo técnico
    // $fecne: usar metodo getDate('ymd')
    // $horne: usar metodo getDate('hgmt')
    // $valdev: Total Devengos
    // $valded:Total Deducciones
    public function getCUNE($numne,$fecne,$horne,$valdev,$valded,$nitne,$docemp,$tipoxml,$pin,$tipoamb)
    {
        try
        {
            //Consultar tipo ambiente desde model
            //Consultar pin desde model


            // Se realiza la conversión al formato solicitado por el anexo
            $valdev_format = number_format($valdev,2,'.','');
            $valded_format = number_format($valded,2,'.','');
            $valtolne = number_format($valdev_format - $valded_format,2,'.','');

            // Se realiza la concatenacion segun anexo tecnico
            $cune = (string)$numne.(string)$fecne.(string)$horne.(string)$valdev_format.(string)$valded_format.(string)$valtolne.(string)$nitne.(string)$docemp.(string)$tipoxml.(string)$pin.(string)$tipoamb; 

            // Se realiza la encripcion con SHA384
            $cunesha = hash('sha384',$cune);
            return $cunesha;
        }
        catch(Throwable $e)
        {
           throw $e;
        }
    }

    // SDSE - 13/04/2021
    // Método encargado de armar el nombre de los archivos necesarios según anexo técnico.
    // $tipoarchivo: 1=nie, 2=niae, 3=zip
    // $nit: Nit empleador
    // $consecutivo: Consecutivo interno para el tipo de archivo. (entero).
    public function ArmarNombreArchivo($tipoarchivo,$nit,$consecutivo)
    {
        try{

            // SDSE - 13/04/2021
            // Se convierte el consecutivo a HEX
            $hexConsecutivo = substr("00000000".dechex($consecutivo),-8);
            $nitformat = (strlen($nit)<=10) ? substr("0000000000".$nit,-10) : substr($nit,0,10);
            $year = $this->getDate('y');
            
            switch($tipoarchivo)
            {
                case 1:
                    $nombrearchivo = 'nie'.$nitformat.$year.$hexConsecutivo.'.xml';
                    break;
                case 2:
                    $nombrearchivo = 'niae'.$nitformat.$year.$hexConsecutivo.'.xml';
                    break;
                case 3:
                    $nombrearchivo = 'z'.$nitformat.$year.$hexConsecutivo.'.zip';
                    break;
                default:
                    $nombrearchivo = 'nie'.$nitformat.$year.$hexConsecutivo.'.xml';
                    break;
            }
            return  $nombrearchivo;
        }
        catch(Throwable $e)
        {
           throw $e;
        }
    }

    

    /*
    Fin Metodos de calculo
    */

    public function exceldatetounix($exceldate)      
    {
        $newdate = ($exceldate - 25569) * 86400;
           return gmdate("Y-m-d",$newdate);
    }
    
    public function generateNominaXML($json)
    {
        
        // SDSE - 25/03/2021
        // Lógica para generar 
        //header('Content-Type: application/json');
        //echo  $json_string = json_encode($json, JSON_PRETTY_PRINT);

        $contenido = json_decode($json,true);
        //var_dump($contenido);
        //die;
        $i = 0;
        foreach($contenido['Hoja1'] as $value)
        {
            
          $validar = $this->fechas();
          foreach($validar as $val) {
            if (array_key_exists($val, $value)) {
                $value[$val] = $this->exceldatetounix($value[$val]);
            }
          }
            //$value['nie002'] = $this->exceldatetounix($value['nie002']);
            //var_dump(array_keys($value));
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
          //return view('header', compact('value'));*/
          //echo var_dump(array_keys($value)).'<br>';
          
        }
        
    }

    private function fechas() {
        $campos = ['nie002', 'nie004', 'nie005', 'nie203'];
        return $campos;
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