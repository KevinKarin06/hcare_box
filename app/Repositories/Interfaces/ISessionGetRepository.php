<?php

namespace App\Repositories\Interfaces;

interface ISessionGetRepository
{
    public function getAllBoxes(array $params);
    public function getBoxOccuper(array $params);
    public function getBox(array $params,string $code);
    public function getEquipementByBox(array $params,string $code);
    public function getOccupationByBox(array $params,string $code);
    public function getPatientByBox(array $params,string $code);
    public function getCategorie(array $params, string $code);
    public function getEquipementByCategorie(array $params, string $code);
    public function getAlerteEquipementByCategorie(array $params, string $code);
    public function getOccupationByCategorie(array $params, string $code);
    public function getAllCategorie(array $params);
    public function getAllOccupations(array $params);
    public function getOccupation(array $params, string $code);
    public function getAllEquipementBox(array $params);
    public function getEquipementBox(array $params, string $code);
    public function getAlerteEquipementByEquipement(array $params, string $code);
    public function getAllAlerteEquipement(array $params);
    public function getAlerteEquipement(array $params, string $code);
}
