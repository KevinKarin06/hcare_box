<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class OccupationModel extends Model
{
    use HasFactory;

    protected $table = 'occupation';

    protected $fillable = [
        'code_unique',
        'id_box',
        'id_categorie',
        'id_personnel',
        'id_patient',
        'type_occupation',
        'date_entree',
        'date_sortie',
        'observation',
        'code_parent'
    ];
}
