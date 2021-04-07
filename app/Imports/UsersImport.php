<?php

namespace App\Imports;

use App\Models\base;
use App\Models\validaciones;
use Exception;
use Illuminate\Validation\Rule;
use Maatwebsite\Excel\Concerns\Importable;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow; /* Uso para cabeceras en el documento Excel */
use Maatwebsite\Excel\Concerns\WithValidation;
use Maatwebsite\Excel\Concerns\WithCalculatedFormulas;
use Maatwebsite\Excel\Concerns\WithChunkReading;
use Maatwebsite\Excel\Concerns\RemembersRowNumber;
use Maatwebsite\Excel\Concerns\WithMultipleSheets;
use Maatwebsite\Excel\Concerns\SkipsErrors;
use Maatwebsite\Excel\Concerns\WithBatchInserts;
use Maatwebsite\Excel\Concerns\SkipsOnFailure;
use Maatwebsite\Excel\Concerns\SkipsFailures;
use Maatwebsite\Excel\Validators\Failure;

class UsersImport implements ToModel, WithHeadingRow, WithValidation, WithCalculatedFormulas, WithChunkReading, WithBatchInserts, WithMultipleSheets  /* Penúltima parte es para las cabeceras del documento, última para validación de datos */
{

    use Importable;
    use RemembersRowNumber;

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
        $validar = $this->fechas();
          foreach($validar as $val) {
            if (array_key_exists($val, $row)) {
                $row[$val] = \PhpOffice\PhpSpreadsheet\Shared\Date::excelToDateTimeObject($row[$val]);
                $row[$val] = $row[$val]->format('Y-m-d');
            }
          }
        
        var_dump($row);
        
    }

    public function rules(): array
    {
        $validator = $this->validatorDB();        
        
        return [
            'nie011' => 'numeric|min:1',
            'nie044' => Rule::in($validator),
            'nie044' => 'min:1',
            'nie048' => 'min:1|alpha'

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
        $currentRowNumber = $this->getRowNumber();

        return [
            'id_file.in' => 'El campo :attribute. no tiene un valor válido',
            'id_file.numeric' => 'El campo :attribute. es únicamente numérico',
            'id_file.required' => 'El campo :attribute. es obligatorio y viene vacío en la fila :row',
            'consecutivo.numeric' => 'El campo :attribute debe ser numérico. Error en la fila '.$currentRowNumber,
            'tipo_de_documento.min' => 'El tipo de documento es un campo obligatorio, revisar la fila '. $currentRowNumber,
            'primer_nombre.alpha' => 'El campo primer nombre debe contener solo letras'
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

    public function chunkSize(): int
    {
        return 10;
    }

    public function batchSize(): int
    {
        return 9;
    }

    /*public function onFailure(Failure ...$failures)
    {
        // Handle the failures how you'd like.
    }*/

    public function headingRow(): int
    {
        return 2;
    }

    public function onFailure(Failure ...$failures)
    {
        // Handle the failures how you'd like.
    }

    public function sheets(): array
    {
        return [
            'Hoja1' => new UsersImport(),
            //0 => new FirstSheetImport()
        ];
    }

    //Lista de campos que debe aplicarse el cambio de formato a fecha
    //Se sugiere generar el listado desde base de datos para no tener que aplicar cambios al código cuando deba incluirse o eliminarse un campo
    private function fechas() {
        $campos = ['nie002', 'nie004', 'nie005', 'nie203'];
        return $campos;
    }
}


