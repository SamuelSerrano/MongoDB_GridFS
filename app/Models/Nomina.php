<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Nomina extends Model
{
    use HasFactory;
    protected $connection = 'mysql';
    protected $table = 'nominas';

    protected $fillable = [
        'cune','idfile','filename' 
    ];
}
