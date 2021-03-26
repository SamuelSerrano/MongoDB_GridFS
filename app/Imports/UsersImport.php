<?php

namespace App\Imports;

use App\Models\base;
use App\Models\validaciones;
use Illuminate\Validation\Rule;
use Maatwebsite\Excel\Concerns\Importable;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow; /* Uso para cabeceras en el documento Excel */
use Maatwebsite\Excel\Concerns\WithValidation;

class UsersImport implements ToModel, WithHeadingRow, WithValidation /* Penúltima parte es para las cabeceras del documento, última para validación de datos */
{

    use Importable;

    /**
     * @param array $row
     *
     * @return \Illuminate\Database\Eloquent\Model|null
     */
    public function model(array $row)
    {
        
        /*return new base([
            'id_file'  => $row['id_file'],
            'namefile' => $row['namefile']
        ]);*/
        //var_dump($response);
    }

    public function rules(): array
    {
        $validator = $this->validatorDB();        
        
        return [
            'consecutivo' => 'numeric|min:1',
            'id_file' => Rule::findOrFail($validator),

            // Above is alias for as it always validates in batches
            //'*.id_file' => Rule::in(['123456789', '987654321']),

            // Can also use callback validation rules
            /*'0' => function($attribute, $value, $onFailure) {
                  if ($value !== 'Patrick Brouwers') {
                       $onFailure('Name is not Patrick Brouwers');
                  }
              }*/
        ];
    }

    public function customValidationAttributes()
    {
        return [
            'id_file' => 'ID Archivo',
            'consecutivo' => 'Consecutivo'
        ];
    }

    public function customValidationMessages()
    {
        return [
            'id_file.in' => 'El campo :attribute. no tiene un valor válido',
            'id_file.numeric' => 'El campo :attribute. es únicamente numérico',
            'id_file.required' => 'El campo :attribute. es obligatorio y viene vacío en la fila :row',
            'consecutivo.numeric' => 'El campo :attribute debe ser numérico. Error en la fila :row()'
        ];
    }

    private function validatorDB() {
        $doc = [];
        $response = validaciones::all();
        foreach ($response as $type) {
            array_push($doc, $type['description']);
        }
        return $doc;
    }
}
