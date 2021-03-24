<?php

namespace App\Repositories\Interfaces;

interface ISessionCreateRepository extends ISessionGetRepository
{
    public function newBox(array $params);
    public function newCategorie(array $params);
    public function newOccupation(array $params);
    public function newEquipementBox(array $params);
    public function newAlerteEquipement(array $params);
}
