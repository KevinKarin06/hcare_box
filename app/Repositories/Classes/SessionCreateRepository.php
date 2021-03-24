<?php

namespace App\Repositories\Classes;

use App\Fonctions;
use App\Repositories\Interfaces\ISessionCreateRepository;
use Exception;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

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
}
