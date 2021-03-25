<?php

namespace App\Imports;

use App\Models\base;
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
        return new base([
            'id_file'  => $row['id_file'],
            'namefile' => $row['namefile']
        ]);
    }

    public function rules(): array
    {
        return [            
            'id_file' => Rule::in(['123456789', '987654321', '']),

            // Above is alias for as it always validates in batches
            '*.id_file' => Rule::in(['123456789', '987654321']),

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
        return ['id_file' => 'ID Archivo'];
    }

    public function customValidationMessages()
{
    return [
        'id_file.in' => 'El campo :attribute. no tiene un valor válido',
        'id_file.numeric' => 'El campo :attribute. es únicamente numérico',
        'id_file.required' => 'El campo :attribute. es obligatorio y viene vacío en la fila :row',
    ];
}
}
