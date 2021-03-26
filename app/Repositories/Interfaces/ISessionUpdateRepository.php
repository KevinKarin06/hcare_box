<?php

namespace App\Repositories\Interfaces;

interface ISessionUpdateRepository extends ISessionCreateRepository
{
    public function updateCategorie(array $params,string $code);
    public function updateAlerteEquipement(array $params,string $code);
    public function updateOccupation(array $params,string $code);
    public function updateBox(array $params,string $code);
    public function updateEquipement(array $params,string $code);
    public function closeOccupation(array $params,string $code);
    public function affecterPatient(array $params,string $code);
}
