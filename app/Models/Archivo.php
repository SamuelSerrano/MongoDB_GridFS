<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Jenssegers\Mongodb\Eloquent\Model as MongoModel;

class Archivo extends MongoModel
{
    use HasFactory;
    protected $connection = 'mongodb';
    protected $collection = 'xml_nomina';

    protected $fillable = [
        'idpgsql', 'cune','idfile','filename' 
    ];
}
