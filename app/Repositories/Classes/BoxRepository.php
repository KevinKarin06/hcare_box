<?php

namespace App\Repositories\Classes;

use App\Models\BoxModel;
use App\Repositories\Interfaces\IBoxRepository;

class BoxRepository extends BasicRepository implements IBoxRepository
{
    public function __construct(BoxModel $BoxModel)
    {
        parent::__construct($BoxModel, 'box');
    }
}
