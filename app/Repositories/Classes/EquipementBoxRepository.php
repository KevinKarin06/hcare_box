<?php

namespace App\Repositories\Classes;

use App\Models\EquipementBoxModel;
use App\Repositories\Interfaces\iEquipementBoxRepository;

class EquipementBoxRepository extends BasicRepository implements iEquipementBoxRepository
{
    public function __construct(EquipementBoxModel $equipementBoxModel)
    {
        parent::__construct($equipementBoxModel, 'equipement_box');
    }
}
