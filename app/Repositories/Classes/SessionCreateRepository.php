<?php

namespace App\Repositories\Classes;

use App\Fonctions;
use App\Repositories\Interfaces\ISessionCreateRepository;
use Exception;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Carbon\Carbon;

class SessionCreateRepository extends SessionGetRepository implements ISessionCreateRepository
{

    public function someThing(array $params)
    {
        $resp = ['data' => null];
        DB::beginTransaction();
        try {
            $validated = Validator::make($params, []);
            if ($validated->fails()) $resp = Fonctions::setError($resp, $validated->errors());
            else {
                $user = $this->checkAuthUser($params['uid']);
                if ($user) {
                } else {
                    $resp = Fonctions::setError($resp, 'Unauthorized');
                    DB::rollBack();
                }
            }
        } catch (Exception $ex) {
            $resp['error'] = $ex->getMessage();
            DB::rollBack();
        }
        return $resp;
    }
    public function newBox(array $params)
    {
        $resp = ['data' => null];
        DB::beginTransaction();
        try {
            $validated = Validator::make($params, [
                'uid' => 'required',
                'abreviation' => 'required',
                'libelle' => 'required',
                'id_batiment' => 'required',
                'activated' => 'present',
                'lock' => 'present',
                'ordre' => 'required',
                'code_parent' => 'present'
            ]);
            if ($validated->fails()) $resp = Fonctions::setError($resp, $validated->errors());
            else {
                $user = $this->checkAuthUser($params['uid']);
                if ($user) {
                    $batiment = $this->getEtab($params['uid'], $params['id_batiment']);
                    if ($batiment) {
                        $batiment = get_object_vars($batiment);
                        $box = $this->box->create([
                            'abreviation' => $params['abreviation'],
                            'libelle' => $params['libelle'],
                            'id_batiment' => $batiment['id'],
                            'ordre' => $params['ordre'],
                            'code_parent' => $params['code_parent']
                        ]);
                        if (!isset($box["error"])) {
                            $resp["data"] = [
                                "code" => $box["data"]["code_unique"],
                                "created_at" => $box["data"]["created_at"]
                            ];
                            DB::commit();
                        } else {
                            DB::rollBack();
                            $resp = $box;
                        }
                    } else {
                        $resp = Fonctions::setError($resp, 'Object not found');
                    }
                } else {
                    $resp = Fonctions::setError($resp, 'Unauthorized');
                    DB::rollBack();
                }
            }
        } catch (Exception $ex) {
            $resp['error'] = $ex->getMessage();
            DB::rollBack();
        }
        return $resp;
    }
    public function newCategorie(array $params)
    {
        $resp = ['data' => null];
        DB::beginTransaction();
        try {
            $validated = Validator::make($params, [
                'uid' => 'required',
                'reference' => 'required',
                'libelle' => 'required',
                'activated' => 'present',
                'lock' => 'present',
                'ordre' => 'required'
            ]);
            if ($validated->fails()) $resp = Fonctions::setError($resp, $validated->errors());
            else {
                $user = $this->checkAuthUser($params['uid']);
                if ($user) {

                    $categorie = $this->categorie->create([
                        'reference' => $params['reference'],
                        'libelle' => $params['libelle'],
                        'ordre' => $params['ordre']
                    ]);
                    if (!isset($categorie["error"])) {
                        $resp["data"] = [
                            "code" => $categorie["data"]["code_unique"],
                            "created_at" => $categorie["data"]["created_at"]
                        ];
                        DB::commit();
                    } else {
                        DB::rollBack();
                        $resp = $categorie;
                    }
                } else {
                    $resp = Fonctions::setError($resp, 'Unauthorized');
                    DB::rollBack();
                }
            }
        } catch (Exception $ex) {
            $resp['error'] = $ex->getMessage();
            DB::rollBack();
        }
        return $resp;
    }
    public function newOccupation(array $params)
    {
        $resp = ['data' => null];
        DB::beginTransaction();
        try {
            $validated = Validator::make($params, [
                'uid' => 'required',
                'id_box' => 'required|exists:box,code_unique',
                'id_categorie' => 'required|exists:categorie_eqp_alt_occ,code_unique',
                'id_personnel' => 'required',
                'id_patient' => 'nullable',
                'info_patient' => 'nullable',
                'type_occupation' => 'required',
                'date_entree' => 'required',
                'date_sortie' => 'required',
                'observation' => 'required',
                'code_parent' => 'present'
            ]);
            if ($validated->fails()) $resp = Fonctions::setError($resp, $validated->errors());
            else {
                $user = $this->checkAuthUser($params['uid']);
                if ($user) {
                    $box = $this->box->getByCode($params['id_box']);
                    $categorie = $this->categorie->getByCode($params['id_categorie']);
                    $personnel = $this->getPersonnel($params['uid'], $params['id_personnel']);
                    //$patient = $this->getPatient($params['uid'], $params['id_patient']);
                    if ($box && $categorie && $personnel) {
                        $personnel = get_object_vars($personnel);
                        $model = $this->occupation->getModel();
                        $occupations =
                            $model->where('cloturer', 0)
                            ->where('id_personnel', $personnel['id'])
                            ->get();
                        $filter = [];
                        $date_entree_new = Carbon::parse($params['date_entree']);
                        $date_sortie_new = Carbon::parse($params['date_sortie']);
                        foreach ($occupations as $occupation) {
                            $date_entree = Carbon::parse($occupation['date_entree']);
                            $date_sortie = Carbon::parse($occupation['date_sortie']);
                            if (
                                $date_entree_new->lte($date_sortie) ||
                                $date_sortie_new->lte($date_sortie)
                            ) {
                                array_push($filter, $occupation);
                            }
                        }
                        if (count($filter) > 0) {
                            $resp = Fonctions::setError($resp, 'Personel est deja une occupation a cette heure');
                        } else {
                            //$patient = get_object_vars($patient);
                            $occupation = $this->occupation->create([
                                'id_box' => $box['id'],
                                'id_categorie' => $categorie['id'],
                                'id_personnel' => $personnel['id'],
                                'type_occupation' => $params['type_occupation'],
                                'date_entree' => $params['date_entree'],
                                'date_sortie' => $params['date_sortie'],
                                'observation' => $params['observation'],
                                'code_parent' => $params['code_parent']
                            ]);
                            if (!isset($occupation["error"])) {
                                $resp["data"] = [
                                    "code" => $occupation["data"]["code_unique"],
                                    "created_at" => $occupation["data"]["created_at"]
                                ];
                                DB::commit();
                            } else {
                                DB::rollBack();
                                $resp = $occupation;
                            }
                        }
                    } else {
                        $resp = Fonctions::setError($resp, 'Object not found');
                    }
                } else {
                    $resp = Fonctions::setError($resp, 'Unauthorized');
                    DB::rollBack();
                }
            }
        } catch (Exception $ex) {
            $resp['error'] = $ex->getMessage();
            DB::rollBack();
        }
        return $resp;
    }
    public function newEquipementBox(array $params)
    {
        $resp = ['data' => null];
        DB::beginTransaction();
        try {
            $validated = Validator::make($params, [
                'uid' => 'required',
                'id_categorie' => 'required|exists:categorie_eqp_alt_occ,code_unique',
                'id_box' => 'required|exists:box,code_unique',
                'libelle' => 'required',
                'numero_serie' => 'required',
                'description' => 'required'
            ]);
            if ($validated->fails()) $resp = Fonctions::setError($resp, $validated->errors());
            else {
                $user = $this->checkAuthUser($params['uid']);
                if ($user) {
                    $box = $this->box->getByCode($params['id_box']);
                    $categorie = $this->categorie->getByCode($params['id_categorie']);
                    if ($box && $categorie) {
                        $equipementBox = $this->equipementBox->create([
                            'id_categorie' => $categorie['id'],
                            'id_box' => $box['id'],
                            'libelle' => $params['libelle'],
                            'numero_serie' => $params['numero_serie'],
                            'description' => $params['description']
                        ]);
                        if (!isset($equipementBox["error"])) {
                            $resp["data"] = [
                                "code" => $equipementBox["data"]["code_unique"],
                                "created_at" => $equipementBox["data"]["created_at"]
                            ];
                            DB::commit();
                        } else {
                            DB::rollBack();
                            $resp = $equipementBox;
                        }
                    } else {
                        $resp =  Fonctions::setError($resp, 'Object not found');
                    }
                } else {
                    $resp = Fonctions::setError($resp, 'Unauthorized');
                    DB::rollBack();
                }
            }
        } catch (Exception $ex) {
            $resp['error'] = $ex->getMessage();
            DB::rollBack();
        }
        return $resp;
    }
    public function newAlerteEquipement(array $params)
    {
        $resp = ['data' => null];
        DB::beginTransaction();
        try {
            $validated = Validator::make($params, [
                'uid' => 'required',
                'id_categorie' => 'required|exists:categorie_eqp_alt_occ,code_unique',
                'id_equipement' => 'required|exists:equipement_box,code_unique',
                'type_alerte' => 'required',
                'description' => 'required',
                'date_declaration' => 'required',
                'date_cloture' => 'required',
                'description_solution' => 'required',
                'code_parent' => 'present',
                'activated' => 'present',
                'lock' => 'present',
                'ordre' => 'required'
            ]);
            if ($validated->fails()) $resp = Fonctions::setError($resp, $validated->errors());
            else {
                $user = $this->checkAuthUser($params['uid']);
                if ($user) {
                    $categorie = $this->categorie->getByCode($params['id_categorie']);
                    $equipement = $this->equipementBox->getByCode($params['id_equipement']);
                    if ($categorie && $equipement) {
                        $alerteEquipement = $this->alertEquipement->create([
                            'id_categorie' => $categorie['id'],
                            'id_equipement' => $equipement['id'],
                            'type_alerte' => $params['type_alerte'],
                            'description' => $params['description'],
                            'date_declaration' => $params['date_declaration'],
                            'date_cloture' => $params['date_cloture'],
                            'description_solution' => $params['description_solution'],
                            'ordre' => $params['ordre']
                        ]);
                        if (!isset($alerteEquipement["error"])) {
                            $resp["data"] = [
                                "code" => $alerteEquipement["data"]["code_unique"],
                                "created_at" => $alerteEquipement["data"]["created_at"]
                            ];
                            DB::commit();
                        } else {
                            DB::rollBack();
                            $resp = $alerteEquipement;
                        }
                    } else {
                        $resp = Fonctions::setError($resp, 'Object not found');
                    }
                } else {
                    $resp = Fonctions::setError($resp, 'Unauthorized');
                    DB::rollBack();
                }
            }
        } catch (Exception $ex) {
            $resp['error'] = $ex->getMessage();
            DB::rollBack();
        }
        return $resp;
    }
}
