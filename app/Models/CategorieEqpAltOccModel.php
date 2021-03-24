<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CategorieEqpAltOccModel extends Model
{
    use HasFactory;

    protected $table = 'categorie_eqp_alt_occ';

    protected $fillable = [
        'code_unique',
        'reference',
        'libelle',
        'activated',
        'lock',
        'ordre'
    ];
}
