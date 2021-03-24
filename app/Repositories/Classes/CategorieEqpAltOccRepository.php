<?php

namespace App\Repositories\Classes;

use App\Models\CategorieEqpAltOccModel;
use App\Repositories\Interfaces\ICategorieEqpAltOccRepository;

class CategorieEqpAltOccRepository extends BasicRepository implements ICategorieEqpAltOccRepository
{
    public function __construct(CategorieEqpAltOccModel $categorieEqpAltOccModel)
    {
        parent::__construct($categorieEqpAltOccModel, 'categorie_eqp_alt_occ');
    }
}
