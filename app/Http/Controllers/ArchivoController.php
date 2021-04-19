<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Archivo;
use App\Models\base;
use App\Models\Nomina;
use \MongoDB;
use ZipStream;
use ZipStream\Option\Archive;


class ArchivoController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //return  base::all();
        //return Archivo::all();
        $pages = Archivo::all();

        return response()->view('header', compact('pages'))->header('Content-Type', 'text/xml');
    }

    public function comprimir()
    {
        //$zip_object = (function() {
                // SDSE - 16/03/2021
                // Se consultan tres archivos desde MongoDB
                $archivos = Nomina::where('id','>=',91)
                ->get();



                $options = new Archive();
                $options_out = $options->getOutputStream();
                $options->setOutputStream($options_out);
                $options->setSendHttpHeaders(false);
                $zip = new ZipStream\ZipStream('example2.zip', $options);


                $bucket = (new MongoDB\Client)->repositorioNomina->selectGridFSBucket();             
                foreach($archivos as $archivo)
                {
                $mongoidfile = new MongoDB\BSON\ObjectId($archivo->idfile);
                $stream = $bucket->openDownloadStream($mongoidfile);
                $zip->addFileFromStream($archivo->filename, $stream);            
                }                  
                //application/octet-stream
                $zip->finish();
                $contents = stream_get_contents($options_out); 
                $header = ['Content-Type' => 'application/zip',            
                'Content-Disposition' => 'inline; filename=demo.zip'];
                 return response()->make($contents, 200,$header);

                
               
                

        //});
        
        //dd($zip_object);
        return "function comprimir";
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        // SDSE 10/03/2021
        // Se almacena un registro en MongoDB
        $idpgsql = 70;
        $cune ="cune-demo-ejemplo";
        $xmlstring = "<?xml version='1.0' encoding='UTF-8' ?>
        <NominaIndividual>
            <Periodo FechaIngreso='2021-02-22' FechaLiquidacionInicio='2021-03-01' FechaLiquidacionFin='2021-03-31' TiempoLaborado='30' FechaGen='2021-04-05'></Periodo>
            <NumeroSecuenciaXML Consecutivo='0001' Numero='0001'></NumeroSecuenciaXML>
            <LugarGeneracionXML Pais='01' DepartamentoEstado='08' MunicipioCiudad='08001' Idioma='es'></LugarGeneracionXML>
            <Trabajador 
            TipoTrabajador='02' SubTipoTrabajador='05' AltoRiesgoPension='false' 
            TipoDocumento='01' NumeroDocumento='72278903' 
            PrimerApellido='Serrano' SegundoApellido='Escorcia' PrimerNombre='Samuel' OtrosNombres='David'
            LugarTrabajoPais='01' LugarDepartamentoEstado='08' LugarTrabajoMunicipioCiudad='08001' LugarTrabajoDireccion='TV 3b No 22 - 246'
            SalarioIntegral='false' TipoContrato='2' Sueldo='1000000'>
            </Trabajador>
            <Redondeo>0</Redondeo>
            <DevengosTotal>1000000</DevengosTotal>
            <DeduccionesTotal>200000</DeduccionesTotal>
            <ComprobanteTotal>800000</ComprobanteTotal>
        </NominaIndividual>";

        //SDSE - 10/03/2021
        // Prueba fileObject
        /*$newsXML = new SimpleXMLElement("<news></news>");
        $newsXML->addAttribute('newsPagePrefix', 'value goes here');
        $newsIntro = $newsXML->addChild('content');
        $newsIntro->addAttribute('type', 'latest');
        Header('Content-type: text/xml');
        $object = $newsXML->asXML();*/


        //SDSE 12/03/2021
        // Prueba GridFS Creando Archivo
        /*$fh_date = time(); 
        $file_name = "nomina_$fh_date.xml";
        $fh = fopen($file_name, 'w') or die("Se produjo un error al crear el archivo");
        fwrite($fh, $req->$xmlstring) or die("No se pudo escribir en el archivo");
        $bucket = (new MongoDB\Client)->test->selectGridFSBucket();
        $id_bucket = $bucket->uploadFromStream($file_name, $file);
        fclose($fh);

        
        /*$bucket = (new MongoDB\Client)->test->selectGridFSBucket();
        $file = fopen($file_name, 'rb');
        $id_bucket = $bucket->uploadFromStream($file_name, $file);
        var_dump($id_bucket);
        fclose($file);*/

        // SDSE 15/03/2021
        // Prueba Accediendo a un archivo para almacenarlo en la BD.
        $file_name = "data.json";
        $bucket = (new MongoDB\Client)->repositorioNomina->selectGridFSBucket();
        $file = fopen($file_name, 'rb');
        $id_bucket = $bucket->uploadFromStream($file_name, $file);
        var_dump($id_bucket);
        fclose($file);

        // SDSE - 10/03/2021
        // Se almacena en MongoDB
        $archivo = new Nomina;
        $archivo->cune = $cune;
        //$archivo->xml = $newsXML;        
        //$archivo->xml = $bucket;        
        $archivo->idfile = $id_bucket;        
        $archivo->filename = $file_name;        
        $archivo->save();
        
        return 'Archivo creado';
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {   
        /*
        Base de datos Mongo
        $archivo = Archivo::where('_id',$id)
                    ->get();
        */
        $archivo = Nomina::where('id',$id)
                    ->get();

        
       // SDSE SE arma el stream para descargar
       $mongoidfile = new MongoDB\BSON\ObjectId($archivo[0]->idfile);

       $bucket = (new MongoDB\Client)->repositorioNomina->selectGridFSBucket();
       $stream = $bucket->openDownloadStream($mongoidfile);
       $contents = stream_get_contents($stream); 

       if(str_contains($archivo[0]->filename,".xml"))
       {
           $header = ['Content-Type' => 'application/xml',            
           'Content-Disposition' => 'inline; filename="'.$archivo[0]->namefile.'"'];
       }

       if(str_contains($archivo[0]->filename,".pdf"))
       {
           $header = ['Content-Type' => 'application/pdf',            
           'Content-Disposition' => 'inline; filename="'.$archivo[0]->namefile.'"'];
       }
       if(str_contains($archivo[0]->filename,".jpg"))
       {
           $header = ['Content-Type' => 'image/jpg',            
           'Content-Disposition' => 'inline; filename="'.$archivo[0]->filename.'"'];
       }
        
        
        return response()->make($contents, 200,  $header);
        /*var_dump($stream);
        return response()->streamDownload(function () {

        }, $archivo[0]->namefile); */

    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
