<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ArchivoController;
use App\Http\Controllers\ExcelController;
use App\Http\Controllers\XMLController;
/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', function () {
    return view('index');
});

Route::resource('archivos', ArchivoController::class);
Route::resource('excel', ExcelController::class);
Route::get('comprimir',[ArchivoController::class,'comprimir']);
Route::post('list_import', [ExcelController::class,'importar'])->name('users.import.excel');
Route::get('list_export', [ExcelController::class,'exportar'])->name('users.export');

Route::get('generarXML', [XMLController::class,'obtainJson'])->name('generarXML');