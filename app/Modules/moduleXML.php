<?php

namespace App\Modules;
use App\Models\Nomina;
use \MongoDB;

class moduleXML 
{
    public function insertXML($params)
    {
        $fh_date = time(); 
        $file_name = "nomina_$fh_date.xml";
        $fh = fopen($file_name, 'w') or die("Se produjo un error al crear el archivo");
        fwrite($fh, $params['xmlstr']) or die("No se pudo escribir en el archivo"); 
        fclose($fh);       
        $bucket = (new MongoDB\Client)->test->selectGridFSBucket();
        $file = fopen($file_name, 'rb');
        $id_bucket = $bucket->uploadFromStream($file_name, $file);
        
        
        

        // SDSE - 10/03/2021
        // Se almacena en MariaDB
        $nomina = new Nomina;
        $nomina->cune = $params['cune'];        
        $nomina->idfile = $id_bucket;        
        $nomina->filename = $file_name; 
        $nomina->save();

        return 'Proceso Realizado con exito';
    }
}