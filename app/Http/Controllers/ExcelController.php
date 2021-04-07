<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\base;
use App\Exports\UsersExport;
use App\Imports\UsersImport;
use Maatwebsite\Excel\Facades\Excel;
use App\Modules\moduleXML;
use Maatwebsite\Excel\Validators\Failure;
use Maatwebsite\Excel\Validators\ValidationException;
class ExcelController extends Controller 
{

    private $excel;

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
    }    

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
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
        //
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

    public function importar(Request $request) 
    {        
        $ext = $file = $request->file('file')->extension();
        if($ext === 'xlsx' || $ext === 'xls') {
            $file = $request->file('file');
            try {
                //$rows = Excel::toArray(new UsersImport,$file);
                //return response()->json(["rows"=>$rows]);

                    $import = Excel::import(new UsersImport, $file, \Maatwebsite\Excel\Excel::XLSX);
                } catch (\Maatwebsite\Excel\Validators\ValidationException $e) {
                    $failures = $e->failures();
                    $message = array();
                    foreach ($failures as $failure) {
                    $failure->row(); // row that went wrong
                    $failure->attribute(); // either heading key (if using heading row concern) or column index
                    $failure->errors(); // Actual error messages from Laravel validator
                    $failure->values(); // The values of the row that has failed.                     
                    array_push($message,$failure->errors()[0]);
                    }
                    
                    return back()->with('message', $message);
                }
                $rows = Excel::toArray(new UsersImport,$file);
<<<<<<< HEAD
                $datas = json_encode($rows);
                
=======

                $datas = json_encode($rows);
                //var_dump($datas);
>>>>>>> 0cdf9219cfa640d3ded51f098a5c956c7d33ab0c
                //$datas = response()->json(["rows"=>$rows]);
                //return $data;
                //$this->XMLController->generateNominaXML($data);
                $this->moduleXML = new moduleXML();
<<<<<<< HEAD
                $this->moduleXML->generateNominaXML($datas);
                return back()->with('message3', 'Importación exitosa');
=======
                return $this->moduleXML->generateNominaXML($datas);
>>>>>>> 0cdf9219cfa640d3ded51f098a5c956c7d33ab0c
                
            
                 //var_dump($failure->errors(), $failure->row());
                 
        }
        else {
            return back()->with('message2', 'El archivo no contiene un formato válido, debe cargar únicamente el formato XLSX entregado');
        }
    }

    public function exportar(Request $request) 
    {
        return Excel::download(new UsersExport, 'lista.xlsx');
    }
}
