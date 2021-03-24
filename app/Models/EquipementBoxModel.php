<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class EquipementBoxModel extends Model
{
    use HasFactory;

    protected $table = 'equipement_box';

    protected $fillable = [
        'code_unique',
        'id_categorie',
        'id_box',
        'libelle',
        'numero_serie',
        'description'
    ];
}
