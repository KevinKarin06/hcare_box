<?php

namespace App\Repositories\Classes;

use App\Models\AlerteEquipementModel;
use App\Repositories\Classes\BasicRepository;
use App\Repositories\Interfaces\IAlerteEquipementRepository;

class AlerteEquipementRepository extends BasicRepository implements IAlerteEquipementRepository
{
    public function __construct(AlerteEquipementModel $alerteEquipementModel)
    {
        parent::__construct($alerteEquipementModel, 'alerte_equipement');
    }
}
