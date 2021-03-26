<?php

namespace App\Repositories\Classes;

use App\Fonctions;
use App\Repositories\Interfaces\IAlerteEquipementRepository;
use App\Repositories\Interfaces\IBoxRepository;
use App\Repositories\Interfaces\ICategorieEqpAltOccRepository;
use App\Repositories\Interfaces\iEquipementBoxRepository;
use App\Repositories\Interfaces\IOccupationRepository;
use App\Repositories\Interfaces\ISessionGetRepository;
use Exception;
use GuzzleHttp\Client;
use Illuminate\Support\Facades\Validator;

require_once __DIR__ . "../../../constantes.php";

class SessionGetRepository implements ISessionGetRepository
{
    protected $alertEquipement;
    protected $box;
    protected $categorie;
    protected $equipementBox;
    protected $occupation;
    public function __construct(
        IAlerteEquipementRepository $iAlerteEquipementRepository,
        IBoxRepository $iBoxRepository,
        ICategorieEqpAltOccRepository $iCategorieEqpAltOccRepository,
        IEquipementBoxRepository $iEquipementBoxRepository,
        IOccupationRepository $iOccupationRepository
    ) {
        $this->alertEquipement = $iAlerteEquipementRepository;
        $this->box = $iBoxRepository;
        $this->categorie = $iCategorieEqpAltOccRepository;
        $this->equipementBox = $iEquipementBoxRepository;
        $this->occupation = $iOccupationRepository;
    }

    public function checkAuthUser($_uid)
    {
        $resp = false;
        $client = new Client();
        $response = $client->request("GET", __HCARE_SERVER__ . __AUTHENTICATION_ROUTE__ . "?uid=" . $_uid, []);
        $body = json_decode((string)$response->getBody());
        $body = get_object_vars($body);
        if ($body["code"] === 200 && $body["record"] !== null) {
            $resp = $body["record"];
        }
        return $resp;
    }

    public function getPatient($_uid, $_code)
    {
        $resp = false;
        $client = new Client();
        $response = $client->request("GET", __HCARE_SERVER__ . __PATIENT_ROUTE__ . "?uid=" . $_uid . "&code=" . $_code, []);
        $body = json_decode((string)$response->getBody());
        $body = get_object_vars($body);
        if ($body["code"] === 200 && $body["record"] !== null) {
            $resp = $body["record"];
        }
        return $resp;
    }

    public function getPersonnel($_uid, $_code)
    {
        $resp = false;
        $client = new Client();
        $response = $client->request("GET", __HCARE_SERVER__ . __PERSONNEL_ROUTE__ . "?uid=" . $_uid . "&code=" . $_code, []);
        $body = json_decode((string)$response->getBody());
        $body = get_object_vars($body);
        if ($body["code"] === 200 && $body["record"] !== null) {
            $resp = $body["record"];
        }
        return $resp;
    }

    public function getEtab($_uid, $_code)
    {
        $resp = false;
        $client = new Client();
        $response = $client->request("GET", __HCARE_SERVER__ . __ETAB_ROUTE__ . "?uid=" . $_uid . "&code=" . $_code, []);
        $body = json_decode((string)$response->getBody());
        $body = get_object_vars($body);
        if ($body["code"] === 200 && $body["record"] !== null) {
            $resp = $body["record"];
        }
        return $resp;
    }

    public function getPrestation($_uid, $_code)
    {
        $resp = false;
        $client = new Client();
        $response = $client->request("GET", __PRESTATION_SERVER__ . __PRESTATION_ROUTE__ . "?uid=" . $_uid . "&code=" . $_code, []);
        $body = json_decode((string)$response->getBody());
        $body = get_object_vars($body);
        if ($body["code"] === 200 && $body["record"] !== null) {
            $resp = $body["record"];
        }
        return $resp;
    }

    public function formatDataWithoutId($iterable)
    {
        $data = null;
        foreach ($iterable->getAttributes() as $key => $value) {
            if ($key != 'id') {
                $data[$key] = $value;
            }
        }
        return $data;
    }

    public function getAllBoxes(array $params)
    {
        $resp = ['data' => []];
        try {
            $validated = Validator::make($params, [
                'uid' => 'required'
            ]);
            if ($validated->fails()) $resp = Fonctions::setError($resp, $validated->errors());
            else {
                $user = $this->checkAuthUser($params['uid']);
                if ($user) {
                    $datas = ['data' => []];
                    $boxes = $this->box->getAll();
                    if (count($boxes) > 0) {
                        foreach ($boxes as $box) {
                            $temp = $this->formatDataWithoutId($box);
                            array_push($datas['data'], $temp);
                        }
                        $resp = $datas;
                    } else {
                        $resp = Fonctions::setError($resp, 'No record found');
                    }
                } else {
                    $resp = Fonctions::setError($resp, 'User Unauthorize');
                }
            }
        } catch (Exception $ex) {
            $resp['error'] = $ex->getMessage();
        }
        return $resp;
    }
    public function getBox(array $params, string $code)
    {
        $resp = ['data' => []];
        try {
            $validated = Validator::make($params, [
                'uid' => 'required'
            ]);
            if ($validated->fails()) $resp = Fonctions::setError($resp, $validated->errors());
            else {
                $user = $this->checkAuthUser($params['uid']);
                if ($user) {
                    $box = $this->box->getByCode($code);
                    if ($box) {
                        $resp['data'] = $this->formatDataWithoutId($box);
                    } else {
                        $resp = Fonctions::setError($resp, 'No box found');
                    }
                } else {
                    $resp = Fonctions::setError($resp, 'User Unauthorize');
                }
            }
        } catch (Exception $ex) {
            $resp['error'] = $ex->getMessage();
        }
        return $resp;
    }
    public function getCategorie(array $params, string $code)
    {
        $resp = ['data' => []];
        try {
            $validated = Validator::make($params, [
                'uid' => 'required'
            ]);
            if ($validated->fails()) $resp = Fonctions::setError($resp, $validated->errors());
            else {
                $user = $this->checkAuthUser($params['uid']);
                if ($user) {
                    $categorie = $this->categorie->getByCode($code);
                    if ($categorie) {
                        $resp['data'] = $this->formatDataWithoutId($categorie);
                    } else {
                        $resp = Fonctions::setError($resp, 'No categorie found');
                    }
                } else {
                    $resp = Fonctions::setError($resp, 'User Unauthorize');
                }
            }
        } catch (Exception $ex) {
            $resp['error'] = $ex->getMessage();
        }
        return $resp;
    }
    public function getAllCategorie(array $params)
    {
        $resp = ['data' => []];
        try {
            $validated = Validator::make($params, [
                'uid' => 'required'
            ]);
            if ($validated->fails()) $resp = Fonctions::setError($resp, $validated->errors());
            else {
                $user = $this->checkAuthUser($params['uid']);
                if ($user) {
                    $datas = ['data' => []];
                    $categories = $this->categorie->getAll();
                    if (count($categories) > 0) {
                        foreach ($categories as $categorie) {
                            $temp = $this->formatDataWithoutId($categorie);
                            array_push($datas['data'], $temp);
                        }
                        $resp = $datas;
                    } else {
                        $resp = Fonctions::setError($resp, 'No record found');
                    }
                } else {
                    $resp = Fonctions::setError($resp, 'User Unauthorize');
                }
            }
        } catch (Exception $ex) {
            $resp['error'] = $ex->getMessage();
        }
        return $resp;
    }
    public function getAllOccupations(array $params)
    {
        $resp = ['data' => []];
        try {
            $validated = Validator::make($params, [
                'uid' => 'required'
            ]);
            if ($validated->fails()) $resp = Fonctions::setError($resp, $validated->errors());
            else {
                $user = $this->checkAuthUser($params['uid']);
                if ($user) {
                    $datas = ['data' => []];
                    $occupations = $this->occupation->getAll();
                    if (count($occupations) > 0) {
                        foreach ($occupations as $occupation) {
                            $temp = $this->formatDataWithoutId($occupation);
                            array_push($datas['data'], $temp);
                        }
                        $resp = $datas;
                    } else {
                        $resp = Fonctions::setError($resp, 'No record found');
                    }
                } else {
                    $resp = Fonctions::setError($resp, 'User Unauthorize');
                }
            }
        } catch (Exception $ex) {
            $resp['error'] = $ex->getMessage();
        }
        return $resp;
    }
    public function getOccupation(array $params, string $code)
    {
        $resp = ['data' => []];
        try {
            $validated = Validator::make($params, [
                'uid' => 'required'
            ]);
            if ($validated->fails()) $resp = Fonctions::setError($resp, $validated->errors());
            else {
                $user = $this->checkAuthUser($params['uid']);
                if ($user) {
                    $occupation = $this->occupation->getByCode($code);
                    if ($occupation) {
                        $resp['data'] = $this->formatDataWithoutId($occupation);
                    } else {
                        $resp = Fonctions::setError($resp, 'No occupation found');
                    }
                } else {
                    $resp = Fonctions::setError($resp, 'User Unauthorize');
                }
            }
        } catch (Exception $ex) {
            $resp['error'] = $ex->getMessage();
        }
        return $resp;
    }
    public function getAllEquipementBox(array $params)
    {
        $resp = ['data' => []];
        try {
            $validated = Validator::make($params, [
                'uid' => 'required'
            ]);
            if ($validated->fails()) $resp = Fonctions::setError($resp, $validated->errors());
            else {
                $user = $this->checkAuthUser($params['uid']);
                if ($user) {
                    $datas = ['data' => []];
                    $equipementBoxes = $this->equipementBox->getAll();
                    if (count($equipementBoxes) > 0) {
                        foreach ($equipementBoxes as $equipementBox) {
                            $temp = $this->formatDataWithoutId($equipementBox);
                            array_push($datas['data'], $temp);
                        }
                        $resp = $datas;
                    } else {
                        $resp = Fonctions::setError($resp, 'No record found');
                    }
                } else {
                    $resp = Fonctions::setError($resp, 'User Unauthorize');
                }
            }
        } catch (Exception $ex) {
            $resp['error'] = $ex->getMessage();
        }
        return $resp;
    }
    public function getEquipementBox(array $params, string $code)
    {
        $resp = ['data' => []];
        try {
            $validated = Validator::make($params, [
                'uid' => 'required'
            ]);
            if ($validated->fails()) $resp = Fonctions::setError($resp, $validated->errors());
            else {
                $user = $this->checkAuthUser($params['uid']);
                if ($user) {
                    $equipementBox = $this->equipementBox->getByCode($code);
                    if ($equipementBox) {
                        $resp['data'] = $this->formatDataWithoutId($equipementBox);
                    } else {
                        $resp = Fonctions::setError($resp, 'No equipementBox found');
                    }
                } else {
                    $resp = Fonctions::setError($resp, 'User Unauthorize');
                }
            }
        } catch (Exception $ex) {
            $resp['error'] = $ex->getMessage();
        }
        return $resp;
    }
    public function getAllAlerteEquipement(array $params)
    {
        $resp = ['data' => []];
        try {
            $validated = Validator::make($params, [
                'uid' => 'required'
            ]);
            if ($validated->fails()) $resp = Fonctions::setError($resp, $validated->errors());
            else {
                $user = $this->checkAuthUser($params['uid']);
                if ($user) {
                    $datas = ['data' => []];
                    $alertEquipements = $this->alertEquipement->getAll();
                    if (count($alertEquipements) > 0) {
                        foreach ($alertEquipements as $alertEquipement) {
                            $temp = $this->formatDataWithoutId($alertEquipement);
                            array_push($datas['data'], $temp);
                        }
                        $resp = $datas;
                    } else {
                        $resp = Fonctions::setError($resp, 'No record found');
                    }
                } else {
                    $resp = Fonctions::setError($resp, 'User Unauthorize');
                }
            }
        } catch (Exception $ex) {
            $resp['error'] = $ex->getMessage();
        }
        return $resp;
    }
    public function getAlerteEquipement(array $params, string $code)
    {
        $resp = ['data' => []];
        try {
            $validated = Validator::make($params, [
                'uid' => 'required'
            ]);
            if ($validated->fails()) $resp = Fonctions::setError($resp, $validated->errors());
            else {
                $user = $this->checkAuthUser($params['uid']);
                if ($user) {
                    $alertEquipement = $this->alertEquipement->getByCode($code);
                    if ($alertEquipement) {
                        $resp['data'] = $this->formatDataWithoutId($alertEquipement);
                    } else {
                        $resp = Fonctions::setError($resp, 'No alertEquipement found');
                    }
                } else {
                    $resp = Fonctions::setError($resp, 'User Unauthorize');
                }
            }
        } catch (Exception $ex) {
            $resp['error'] = $ex->getMessage();
        }
        return $resp;
    }
    public function getEquipementByBox(array $params, string $code)
    {
        $resp = ['data' => null];
        try {
            $validated = Validator::make($params, [
                'uid' => 'required'
            ]);
            if ($validated->fails()) $resp = Fonctions::setError($resp, $validated->errors());
            else {
                $user = $this->checkAuthUser($params['uid']);
                if ($user) {
                    $box = $this->box->getByCode($code);
                    if ($box) {
                        $model = $this->equipementBox->getModel();
                        $equipementBoxes = $model->where('id_box', $box['id'])->get();
                        if (count($equipementBoxes) > 0) {
                            $datas = ['data' => []];
                            foreach ($equipementBoxes as $equipementBox) {
                                $temp = $this->formatDataWithoutId($equipementBox);
                                array_push($datas['data'], $temp);
                            }
                            $resp = $datas;
                        } else {
                            $resp = Fonctions::setError($resp, 'No Equipement found in that Box');
                        }
                    } else {
                        $resp = Fonctions::setError($resp, 'No Box with that id');
                    }
                } else {
                    $resp = Fonctions::setError($resp, 'Unauthrozed');
                }
            }
        } catch (Exception $ex) {
            $resp = Fonctions::setError($resp, $ex->getMessage());
        }
        return $resp;
    }
    public function getEquipementByCategorie(array $params, string $code)
    {
        $resp = ['data' => null];
        try {
            $validated = Validator::make($params, [
                'uid' => 'required'
            ]);
            if ($validated->fails()) $resp = Fonctions::setError($resp, $validated->errors());
            else {
                $user = $this->checkAuthUser($params['uid']);
                if ($user) {
                    $categorie = $this->categorie->getByCode($code);
                    if ($categorie) {
                        $model = $this->equipementBox->getModel();
                        $equipementBoxes = $model->where('id_categorie', $categorie['id'])->get();
                        if (count($equipementBoxes) > 0) {
                            $datas = ['data' => []];
                            foreach ($equipementBoxes as $equipementBox) {
                                $temp = $this->formatDataWithoutId($equipementBox);
                                array_push($datas['data'], $temp);
                            }
                            $resp = $datas;
                        } else {
                            $resp = Fonctions::setError($resp, 'No Equipement found in that Categorie');
                        }
                    } else {
                        $resp = Fonctions::setError($resp, 'No Categorie with that id');
                    }
                } else {
                    $resp = Fonctions::setError($resp, 'Unauthrozed');
                }
            }
        } catch (Exception $ex) {
            $resp = Fonctions::setError($resp, $ex->getMessage());
        }
        return $resp;
    }
    public function getAlerteEquipementByCategorie(array $params, string $code)
    {
        $resp = ['data' => null];
        try {
            $validated = Validator::make($params, [
                'uid' => 'required'
            ]);
            if ($validated->fails()) $resp = Fonctions::setError($resp, $validated->errors());
            else {
                $user = $this->checkAuthUser($params['uid']);
                if ($user) {
                    $categorie = $this->categorie->getByCode($code);
                    if ($categorie) {
                        $model = $this->alertEquipement->getModel();
                        $alertEquipements = $model->where('id_categorie', $categorie['id'])->get();
                        if (count($alertEquipements) > 0) {
                            $datas = ['data' => []];
                            foreach ($alertEquipements as $alertEquipement) {
                                $temp = $this->formatDataWithoutId($alertEquipement);
                                array_push($datas['data'], $temp);
                            }
                            $resp = $datas;
                        } else {
                            $resp = Fonctions::setError($resp, 'No AlerteEquipement found in that Categorie');
                        }
                    } else {
                        $resp = Fonctions::setError($resp, 'No Categorie with that id');
                    }
                } else {
                    $resp = Fonctions::setError($resp, 'Unauthrozed');
                }
            }
        } catch (Exception $ex) {
            $resp = Fonctions::setError($resp, $ex->getMessage());
        }
        return $resp;
    }
    public function getOccupationByCategorie(array $params, string $code)
    {
        $resp = ['data' => null];
        try {
            $validated = Validator::make($params, [
                'uid' => 'required'
            ]);
            if ($validated->fails()) $resp = Fonctions::setError($resp, $validated->errors());
            else {
                $user = $this->checkAuthUser($params['uid']);
                if ($user) {
                    $categorie = $this->categorie->getByCode($code);
                    if ($categorie) {
                        $model = $this->occupation->getModel();
                        $occupations = $model->where('id_categorie', $categorie['id'])->get();
                        if (count($occupations) > 0) {
                            $datas = ['data' => []];
                            foreach ($occupations as $occupation) {
                                $temp = $this->formatDataWithoutId($occupation);
                                array_push($datas['data'], $temp);
                            }
                            $resp = $datas;
                        } else {
                            $resp = Fonctions::setError($resp, 'No Occupation found in that Categorie');
                        }
                    } else {
                        $resp = Fonctions::setError($resp, 'No Categorie with that id');
                    }
                } else {
                    $resp = Fonctions::setError($resp, 'Unauthrozed');
                }
            }
        } catch (Exception $ex) {
            $resp = Fonctions::setError($resp, $ex->getMessage());
        }
        return $resp;
    }
    public function getOccupationByBox(array $params, string $code)
    {
        $resp = ['data' => null];
        try {
            $validated = Validator::make($params, [
                'uid' => 'required'
            ]);
            if ($validated->fails()) $resp = Fonctions::setError($resp, $validated->errors());
            else {
                $user = $this->checkAuthUser($params['uid']);
                if ($user) {
                    $box = $this->box->getByCode($code);
                    if ($box) {
                        $model = $this->occupation->getModel();
                        $occupations = $model->where('id_box', $box['id'])->get();
                        if (count($occupations) > 0) {
                            $datas = ['data' => []];
                            foreach ($occupations as $occupation) {
                                $temp = $this->formatDataWithoutId($occupation);
                                array_push($datas['data'], $temp);
                            }
                            $resp = $datas;
                        } else {
                            $resp = Fonctions::setError($resp, 'No Occupation found for that Box');
                        }
                    } else {
                        $resp = Fonctions::setError($resp, 'No Box with that id');
                    }
                } else {
                    $resp = Fonctions::setError($resp, 'Unauthrozed');
                }
            }
        } catch (Exception $ex) {
            $resp = Fonctions::setError($resp, $ex->getMessage());
        }
        return $resp;
    }
    public function getAlerteEquipementByEquipement(array $params, string $code)
    {
        $resp = ['data' => null];
        try {
            $validated = Validator::make($params, [
                'uid' => 'required'
            ]);
            if ($validated->fails()) $resp = Fonctions::setError($resp, $validated->errors());
            else {
                $user = $this->checkAuthUser($params['uid']);
                if ($user) {
                    $equipement = $this->equipementBox->getByCode($code);
                    if ($equipement) {
                        $model = $this->alertEquipement->getModel();
                        $alertEquipements = $model->where('id_equipement', $equipement['id'])->get();
                        if (count($alertEquipements) > 0) {
                            $datas = ['data' => []];
                            foreach ($alertEquipements as $alertEquipement) {
                                $temp = $this->formatDataWithoutId($alertEquipement);
                                array_push($datas['data'], $temp);
                            }
                            $resp = $datas;
                        } else {
                            $resp = Fonctions::setError($resp, 'No AlertEquipement found for that Equipement');
                        }
                    } else {
                        $resp = Fonctions::setError($resp, 'No Equipement with that id');
                    }
                } else {
                    $resp = Fonctions::setError($resp, 'Unauthrozed');
                }
            }
        } catch (Exception $ex) {
            $resp = Fonctions::setError($resp, $ex->getMessage());
        }
        return $resp;
    }
    public function getBoxOccuper(array $params)
    {
        $resp = ['data' => null];
        try {
            $validated = Validator::make($params, [
                'uid' => 'required'
            ]);
            if ($validated->fails()) $resp = Fonctions::setError($resp, $validated->errors());
            else {
                $user = $this->checkAuthUser($params['uid']);
                if ($user) {
                } else {
                    $resp = Fonctions::setError($resp, 'Unauthrozed');
                }
            }
        } catch (Exception $ex) {
            $resp = Fonctions::setError($resp, $ex->getMessage());
        }
        return $resp;
    }
    public function getPatientByBox(array $params, string $code)
    {
        $resp = ['data' => null];
        try {
            $validated = Validator::make($params, [
                'uid' => 'required'
            ]);
            if ($validated->fails()) $resp = Fonctions::setError($resp, $validated->errors());
            else {
                $user = $this->checkAuthUser($params['uid']);
                if ($user) {
                    $box = $this->box->getByCode($code);
                    if ($box) {
                        $occupations = $this->getOccupationByBox(['uid' => $params['uid']], $code);
                        if (isset($occupations['data'])) {
                            $occupations = collect($occupations['data']);
                            $occupations = $occupations->whereNotNull('id_patient');
                           if(count($occupations) > 0){
                            $datas = ['data' => []];
                            foreach ($occupations as $occupation) {
                                $temp = $occupation['info_patient'];
                                array_push($datas['data'], $temp);
                            }
                            $resp = $datas;
                           }else{
                               $resp = Fonctions::setError($resp,'No patients in that box');
                           }
                        } else {
                            $resp = Fonctions::setError($resp, 'Object not found');
                        }
                    } else {
                        $resp = Fonctions::setError($resp, 'Invalid Box Id');
                    }
                } else {
                    $resp = Fonctions::setError($resp, 'Unauthrozed');
                }
            }
        } catch (Exception $ex) {
            $resp = Fonctions::setError($resp, $ex->getMessage());
        }
        return $resp;
    }
}
