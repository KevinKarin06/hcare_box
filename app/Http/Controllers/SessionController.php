<?php

namespace App\Http\Controllers;

use App\Fonctions;
use App\Repositories\Interfaces\ISessionRepository;
use Illuminate\Http\Request;

class SessionController extends Controller
{
    protected $repository = null;
    public function __construct(ISessionRepository $repository)
    {
        $this->repository = $repository;
    }

    public function worker($record, $code)
    {
        $resp = Fonctions::setResponse($record, $code);
        return response()->json($resp, $resp["code"]);
    }

    public function createBox(Request $request)
    {
        return $this->worker($this->repository->newBox($request->all()), 201);
    }
    public function createCategorie(Request $request)
    {
        return $this->worker($this->repository->newCategorie($request->all()), 201);
    }
    public function createOccupation(Request $request)
    {
        return $this->worker($this->repository->newOccupation($request->all()), 201);
    }
    public function createEquipementBox(Request $request)
    {
        return $this->worker($this->repository->newEquipementBox($request->all()), 201);
    }
    public function createAlerteEquipement(Request $request)
    {
        return $this->worker($this->repository->newAlerteEquipement($request->all()), 201);
    }
    public function getAllBoxes(Request $request)
    {
        return $this->worker($this->repository->getAllBoxes($request->all()), 200);
    }
    public function getBox(Request $request, string $code)
    {
        return $this->worker($this->repository->getBox($request->all(), $code), 200);
    }
    public function getAllCategorie(Request $request)
    {
        return $this->worker($this->repository->getAllCategorie($request->all()), 200);
    }
    public function getCategorie(Request $request, string $code)
    {
        return $this->worker($this->repository->getCategorie($request->all(), $code), 200);
    }
    public function getAllOccupations(Request $request)
    {
        return $this->worker($this->repository->getAllOccupations($request->all()), 200);
    }
    public function getOccupation(Request $request, string $code)
    {
        return $this->worker($this->repository->getOccupation($request->all(), $code), 200);
    }
    public function getAllEquipementBox(Request $request)
    {
        return $this->worker($this->repository->getAllEquipementBox($request->all()), 200);
    }
    public function getEquipementBox(Request $request, string $code)
    {
        return $this->worker($this->repository->getEquipementBox($request->all(), $code), 200);
    }
    public function getAllAlerteEquipement(Request $request)
    {
        return $this->worker($this->repository->getAllAlerteEquipement($request->all()), 200);
    }
    public function getAlerteEquipement(Request $request, string $code)
    {
        return $this->worker($this->repository->getAlerteEquipement($request->all(), $code), 200);
    }
    public function getEquipementByCategorie(Request $request, string $code)
    {
        return $this->worker($this->repository->getEquipementByCategorie($request->all(), $code), 200);
    }
    public function getEquipementByBox(Request $request, string $code)
    {
        return $this->worker($this->repository->getEquipementByBox($request->all(), $code), 200);
    }
    public function getAlerteEquipementByCategorie(Request $request, string $code)
    {
        return $this->worker($this->repository->getAlerteEquipementByCategorie($request->all(), $code), 200);
    }
    public function getOccupationByCategorie(Request $request, string $code)
    {
        return $this->worker($this->repository->getOccupationByCategorie($request->all(), $code), 200);
    }
    public function getOccupationByBox(Request $request, string $code)
    {
        return $this->worker($this->repository->getOccupationByBox($request->all(), $code), 200);
    }
    public function getAlerteEquipementByEquipement(Request $request, string $code)
    {
        return $this->worker($this->repository->getAlerteEquipementByEquipement($request->all(), $code), 200);
    }
}
