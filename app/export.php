<?php

namespace App\export;

use App\Models\base;
use Maatwebsite\Excel\Concerns\FromCollection;

class UsersExport implements FromCollection
{
    public function collection()
    {
        return base::all();
    }
}