<?php

namespace App\Repositories\Classes;

use App\Fonctions;
use App\Repositories\Interfaces\ISessionGetRepository;
use Exception;
use GuzzleHttp\Client;
use Illuminate\Support\Facades\Validator;

require_once __DIR__ . "../../../constantes.php";

class SessionGetRepository implements ISessionGetRepository
{
    public function __construct()
    {
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
}
