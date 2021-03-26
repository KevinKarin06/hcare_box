<?php

namespace App\Repositories\Classes;

use App\Fonctions;
use App\Repositories\Interfaces\ISessionUpdateRepository;
use Carbon\Carbon;
use Exception;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class SessionUpdateRepository extends SessionCreateRepository implements ISessionUpdateRepository
{
    public function updateCategorie(array $params, string $code)
    {
        $resp = ["data" => null];
        DB::beginTransaction();
        try {
            $validated = Validator::make($params, [
                "uid" => "required",
                'reference' => 'required',
                'libelle' => 'required',
                'activated' => 'present',
                'lock' => 'present',
                'ordre' => 'required'
            ]);
            if ($validated->fails()) $resp = Fonctions::setError($resp, $validated->errors());
            else {
                //$user = $this->checkAuthUser($params['uid']);
                if (true) {
                    $categorie = $this->categorie->getByCode($code);
                    if ($categorie) {
                        $updated = $categorie->update([
                            'reference' => $params['reference'],
                            'libelle' => $params['libelle'],
                            'ordre' => $params['ordre']
                        ]);
                        if ($updated) {
                            $resp["data"] = [
                                "code" => $categorie["code_unique"],
                                "updated_at" => $categorie["updated_at"]
                            ];
                            DB::commit();
                        } else {
                            DB::rollBack();
                            $resp = $updated;
                        }
                    } else {
                        DB::rollBack();
                        $resp = Fonctions::setError($resp, 'Invalid Categorie code');
                    }
                } else {
                    DB::rollBack();
                    $resp = Fonctions::setError($resp, 'User not authoriwed');
                }
            }
        } catch (Exception $ex) {
            DB::rollBack();
            $resp["error"] = $ex->getMessage();
        }
        return $resp;
    }
    public function updateAlerteEquipement(array $params, string $code)
    {
        $resp = ["data" => null];
        DB::beginTransaction();
        try {
            $validated = Validator::make($params, [
                "uid" => "required",
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
                //$user = $this->checkAuthUser($params['uid']);
                if (true) {
                    $categorie = $this->categorie->getByCode($params['id_categorie']);
                    $equipement = $this->equipementBox->getByCode($params['id_equipement']);
                    $alerteEquipement = $this->alertEquipement->getByCode($code);
                    if ($alerteEquipement && $categorie && $equipement) {
                        $updated = $alerteEquipement->update([
                            'id_categorie' => $categorie['id'],
                            'id_equipement' => $equipement['id'],
                            'type_alerte' => $params['type_alerte'],
                            'description' => $params['description'],
                            'date_declaration' => $params['date_declaration'],
                            'date_cloture' => $params['date_cloture'],
                            'description_solution' => $params['description_solution'],
                            'ordre' => $params['ordre']
                        ]);
                        if ($updated) {
                            $resp["data"] = [
                                "code" => $alerteEquipement["code_unique"],
                                "updated_at" => $alerteEquipement["updated_at"]
                            ];
                            DB::commit();
                        } else {
                            DB::rollBack();
                            $resp = $updated;
                        }
                    } else {
                        DB::rollBack();
                        $resp = Fonctions::setError($resp, 'Object not found');
                    }
                } else {
                    DB::rollBack();
                    $resp = Fonctions::setError($resp, 'User not authoriwed');
                }
            }
        } catch (Exception $ex) {
            DB::rollBack();
            $resp["error"] = $ex->getMessage();
        }
        return $resp;
    }
    public function updateBox(array $params, string $code)
    {
        $resp = ["data" => null];
        DB::beginTransaction();
        try {
            $validated = Validator::make($params, [
                "uid" => "required",
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
                //$user = $this->checkAuthUser($params['uid']);
                if (true) {
                    $batiment = $this->getEtab($params['uid'], $params['id_batiment']);
                    $box = $this->box->getByCode($code);
                    if ($box && $batiment) {
                        $batiment = get_object_vars($batiment);
                        $updated = $box->update([
                            'abreviation' => $params['abreviation'],
                            'libelle' => $params['libelle'],
                            'id_batiment' => $batiment['id'],
                            'ordre' => $params['ordre'],
                            'code_parent' => $params['code_parent']
                        ]);
                        if ($updated) {
                            $resp["data"] = [
                                "code" => $box["code_unique"],
                                "updated_at" => $box["updated_at"]
                            ];
                            DB::commit();
                        } else {
                            DB::rollBack();
                            $resp = $updated;
                        }
                    } else {
                        DB::rollBack();
                        $resp = Fonctions::setError($resp, 'Object not found');
                    }
                } else {
                    DB::rollBack();
                    $resp = Fonctions::setError($resp, 'User not authoriwed');
                }
            }
        } catch (Exception $ex) {
            DB::rollBack();
            $resp["error"] = $ex->getMessage();
        }
        return $resp;
    }
    public function updateEquipement(array $params, string $code)
    {
        $resp = ["data" => null];
        DB::beginTransaction();
        try {
            $validated = Validator::make($params, [
                "uid" => "required",
                'id_categorie' => 'required|exists:categorie_eqp_alt_occ,code_unique',
                'id_box' => 'required|exists:box,code_unique',
                'libelle' => 'required',
                'numero_serie' => 'required',
                'description' => 'required'
            ]);
            if ($validated->fails()) $resp = Fonctions::setError($resp, $validated->errors());
            else {
                //$user = $this->checkAuthUser($params['uid']);
                if (true) {
                    $box = $this->box->getByCode($params['id_box']);
                    $categorie = $this->categorie->getByCode($params['id_categorie']);
                    $equipementBox = $this->equipementBox->getByCode($code);
                    if ($equipementBox && $box && $categorie) {
                        $updated = $equipementBox->update([
                            'id_categorie' => $categorie['id'],
                            'id_box' => $box['id'],
                            'libelle' => $params['libelle'],
                            'numero_serie' => $params['numero_serie'],
                            'description' => $params['description']
                        ]);
                        if ($updated) {
                            $resp["data"] = [
                                "code" => $equipementBox["code_unique"],
                                "updated_at" => $equipementBox["updated_at"]
                            ];
                            DB::commit();
                        } else {
                            DB::rollBack();
                            $resp = $updated;
                        }
                    } else {
                        DB::rollBack();
                        $resp = Fonctions::setError($resp, 'Object not found');
                    }
                } else {
                    DB::rollBack();
                    $resp = Fonctions::setError($resp, 'User not authoriwed');
                }
            }
        } catch (Exception $ex) {
            DB::rollBack();
            $resp["error"] = $ex->getMessage();
        }
        return $resp;
    }
    public function closeOccupation(array $params, string $code)
    {
        $resp = ["data" => null];
        DB::beginTransaction();
        try {
            $validated = Validator::make($params, [
                "uid" => "required",
            ]);
            if ($validated->fails()) $resp = Fonctions::setError($resp, $validated->errors());
            else {
                $user = $this->checkAuthUser($params['uid']);
                if ($user) {
                    $occupation = $this->occupation->getByCode($code);
                    if ($occupation) {
                        $updated = $occupation->update([
                            'cloturer' => 1,
                        ]);
                        if ($updated) {
                            $resp["data"] = [
                                "code" => $occupation["code_unique"],
                                "updated_at" => $occupation["updated_at"]
                            ];
                            DB::commit();
                        } else {
                            DB::rollBack();
                            $resp = $updated;
                        }
                    } else {
                        DB::rollBack();
                        $resp = Fonctions::setError($resp, 'Invalid Occupation code');
                    }
                } else {
                    DB::rollBack();
                    $resp = Fonctions::setError($resp, 'User not authoriwed');
                }
            }
        } catch (Exception $ex) {
            DB::rollBack();
            $resp["error"] = $ex->getMessage();
        }
        return $resp;
    }
    public function affecterPatient(array $params, string $code)
    {
        $resp = ["data" => null];
        DB::beginTransaction();
        try {
            $validated = Validator::make($params, [
                "uid" => "required",
                'id_patient' => 'required',

            ]);
            if ($validated->fails()) $resp = Fonctions::setError($resp, $validated->errors());
            else {
                $user = $this->checkAuthUser($params['uid']);
                if ($user) {
                    $patient = $this->getPatient($params['uid'], $params['id_patient']);
                    $occupation = $this->occupation->getByCode($code);
                    if ($occupation && $patient) {
                        $patient = get_object_vars($patient);
                        $nom = $patient['first_name'] . ' ' . $patient['last_name'];
                        $updated = $occupation->update([
                            'id_patient' => $patient['id'],
                            'info_patient' => $nom,
                        ]);
                        if ($updated) {
                            $resp["data"] = [
                                "code" => $occupation["code_unique"],
                                "updated_at" => $occupation["updated_at"]
                            ];
                            DB::commit();
                        } else {
                            DB::rollBack();
                            $resp = $updated;
                        }
                    } else {
                        DB::rollBack();
                        $resp = Fonctions::setError($resp, 'Invalid Occupation code');
                    }
                } else {
                    DB::rollBack();
                    $resp = Fonctions::setError($resp, 'User not authoriwed');
                }
            }
        } catch (Exception $ex) {
            DB::rollBack();
            $resp["error"] = $ex->getMessage();
        }
        return $resp;
    }
    public function updateOccupation(array $params, string $code)
    {
        $resp = ["data" => null];
        DB::beginTransaction();
        try {
            $validated = Validator::make($params, [
                "uid" => "required",
                'id_box' => 'required|exists:box,code_unique',
                'id_categorie' => 'required|exists:categorie_eqp_alt_occ,code_unique',
                'id_personnel' => 'required',
                //'id_patient' => 'present',
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
                    $occupationToUpdate = $this->occupation->getByCode($code);
                    if ($box && $categorie && $personnel && $occupationToUpdate) {
                        $personnel = get_object_vars($personnel);
                        $date_entree_new = Carbon::parse($params['date_entree']);
                        $date_sortie_new = Carbon::parse($params['date_sortie']);
                        $filter = [];

                        $model = $this->occupation->getModel();
                        $occupations =
                            $model->where('cloturer', 0)
                            ->where('id_personnel', $personnel['id'])
                            ->where('id','!=', $occupationToUpdate['id'])
                            ->get();
                        foreach ($occupations as $occupation) {
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
                            $updated = $occupationToUpdate->update([
                                'id_box' => $box['id'],
                                'id_categorie' => $categorie['id'],
                                'id_personnel' => $personnel['id'],
                                //'id_patient' => $patient['id'],
                                'type_occupation' => $params['type_occupation'],
                                'date_entree' => $params['date_entree'],
                                'date_sortie' => $params['date_sortie'],
                                'observation' => $params['observation'],
                                'code_parent' => $params['code_parent']
                            ]);
                            if ($updated) {
                                $resp["data"] = [
                                    "code" => $occupationToUpdate["code_unique"],
                                    "updated_at" => $occupationToUpdate["updated_at"]
                                ];
                                DB::commit();
                            } else {
                                DB::rollBack();
                                $resp = $updated;
                            }
                        }
                    } else {
                        DB::rollBack();
                        $resp = Fonctions::setError($resp, 'Invalid Occupation code');
                    }
                } else {
                    DB::rollBack();
                    $resp = Fonctions::setError($resp, 'User not authoriwed');
                }
            }
        } catch (Exception $ex) {
            DB::rollBack();
            $resp["error"] = $ex->getMessage();
        }
        return $resp;
    }
}
