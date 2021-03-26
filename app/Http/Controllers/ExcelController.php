<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\base;
use App\Exports\UsersExport;
use App\Imports\UsersImport;
use Maatwebsite\Excel\Facades\Excel;

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
        $file = $request->file('file');
        try {
            //$rows = Excel::toArray(new UsersImport,$file);
            //return response()->json(["rows"=>$rows]);
            
            Excel::import(new UsersImport, $file, \Maatwebsite\Excel\Excel::XLSX);
            $rows = Excel::toArray(new UsersImport,$file);
            return response()->json(["rows"=>$rows]);
            //return back()->with('message', 'Importación exitosa');
        } catch (\Maatwebsite\Excel\Validators\ValidationException $e) {
             $failures = $e->failures();
             
             foreach ($failures as $failure) {
                 $failure->row(); // row that went wrong
                 $failure->attribute(); // either heading key (if using heading row concern) or column index
                 $failure->errors(); // Actual error messages from Laravel validator
                 $failure->values(); // The values of the row that has failed.
             }
             //var_dump($failure->errors(), $failure->row());
             return back()->with('message', $failure->errors(), 'filas', $failure->row());
        }
    }

    public function exportar(Request $request) 
    {
        return Excel::download(new UsersExport, 'lista.xlsx');
    }
}
