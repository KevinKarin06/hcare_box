<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AlerteEquipementModel extends Model
{
    use HasFactory;

    protected $table = 'alerte_equipement';

    protected $fillable = [
        'code_unique',
        'id_categorie',
        'id_equipement',
        'type_alerte',
        'description',
        'date_declaration',
        'date_cloture',
        'description_solution',
        'code_parent',
        'activated',
        'lock',
        'ordre'
    ];
}
