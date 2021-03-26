<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class validaciones extends Model
{
    use HasFactory;
    protected $connection = 'mysql';
    protected $table = 'document_type';
    protected $guarded = [];
}
