<?php

namespace App\Repositories\Classes;

use App\Models\OccupationModel;
use App\Repositories\Interfaces\IOccupationRepository;

class OccupationRepository extends BasicRepository implements IOccupationRepository
{
    public function __construct(OccupationModel $occupationModel)
    {
        parent::__construct($occupationModel, 'occupation');
    }
}
