<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use ArchivoController;
use SimpleXMLElement;
use App\Modules\moduleXML;

use RobRichards\XMLSecLibs\XMLSecurityDSig;
use RobRichards\XMLSecLibs\XMLSecurityKey;
use DOMDocument;
use DOMXPath;

class XMLController extends Controller
{

    public function obtainJson()
    {
        /*$data = file_get_contents("data.json");
        $data_json = json_decode($data, true);
        $respuesta = $this->generateNominaXML($data);
        */
        try
        {
        //$fechaingreso="AEIOU"; 
        //$fecharetiro="2020-05-23";
       // $fechaliquidacioninicio="2021-04-01"; 
       // $fechaliquidacionfin="2021-04-30"; 
       // $this->moduleXML = new moduleXML();        
        //$respuesta = $this->moduleXML->SetTiempoLaborado($fechaingreso, $fechaliquidacioninicio,$fechaliquidacionfin,$fecharetiro);
        //$respuesta = $this->moduleXML->SetNumeroSecuencia(1001,"XYZ");
        //$respuesta = $this->moduleXML->getDate('hgmt');
        //$respuesta = $this->moduleXML->getCUNE("N00001",$this->moduleXML->getDate('ymd'),$this->moduleXML->getDate('hgmt'),5000450.25,1000000.75,'700085371','800199436',102,693,1);
        //$respuesta = $this->moduleXML-> ArmarNombreArchivo(2,80044123456 ,1616161616);
        
        
        $doc = new DOMDocument();
        $doc->load('xmlsignature/xmlgenerated.xml');
        //si se tiene el string xml se utiliza
        //  $doc->loadXml("stringXml");
        $xpath = new DOMXPath($doc);
        $nodeset = $xpath->query("/NominaIndividual/ext:UBLExtensions/ext:UBLExtension/ext:ExtensionContent")->item(0);        
        // Create a new Security object 
        $objDSig = new XMLSecurityDSig();
        // Use the c14n exclusive canonicalization
        $objDSig->setCanonicalMethod(XMLSecurityDSig::EXC_C14N);
        // Sign using SHA-256
        
        $objDSig->addReference(
            $doc, 
            XMLSecurityDSig::SHA256, 
            array('http://www.w3.org/2000/09/xmldsig#enveloped-signature')
        );

        // Create a new (private) Security key
        $objKey = new XMLSecurityKey(XMLSecurityKey::RSA_SHA256, array('type'=>'private'));
        $objKey->passphrase = '123456';
        // Load the private key
        $objKey->loadKey('xmlsignature/nominademo.pem', TRUE);
        // Sign the XML file
        $objDSig->sign($objKey);

        // Add the associated public key to the signature
        $objDSig->add509Cert(file_get_contents('xmlsignature/certnomina.cer'));

        // Append the signature to the XML
        $objDSig->appendSignature($nodeset);
        // Save the signed XML
        $doc->save('xmlsignature/signed.xml');

        return "xml firmado";
        }
        catch(\Throwable $error)
        {
            return $error->getMessage();
        }
        
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
                foreach($line as $k=>$l)
                {                    
                    if (str_contains($k, '@')) $obj->addAttribute(str_replace('@','',$k),htmlspecialchars($l));
                    else
                    {
                        if($key == $k) $obj[0] = htmlspecialchars($l);
                        else $obj->addChild($k,$l);
                    } 
                }
                                                  
            }
        }
        return $xml;
    }
}
