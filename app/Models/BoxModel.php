<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BoxModel extends Model
{
    use HasFactory;

    protected $table = 'box';

    protected $fillable = [
        'code_unique',
        'abreviation',
        'libelle',
        'id_batiment',
        'activated',
        'lock',
        'ordre',
        'code_parent',
    ];
}
