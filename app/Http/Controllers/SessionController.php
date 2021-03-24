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
}
