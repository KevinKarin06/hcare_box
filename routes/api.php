<?php

use App\Http\Controllers\SessionController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});

Route::prefix('session')->group(
    function () {
        Route::prefix('box')->group(function () {
            Route::get('{code}', [SessionController::class, 'getBox']);
            Route::get('', [SessionController::class, 'getAllBoxes']);
            Route::get('{code}/equipements', [SessionController::class, 'getEquipementByBox']);
            Route::get('{code}/occupations', [SessionController::class, 'getOccupationByBox']);
            Route::post('', [SessionController::class, 'createBox']);
        });
        Route::prefix('categorie')->group(function () {
            Route::get('{code}', [SessionController::class, 'getCategorie']);
            Route::get('', [SessionController::class, 'getAllCategorie']);
            Route::get('{code}/equipements', [SessionController::class, 'getEquipementByCategorie']);
            Route::get('{code}/alertes', [SessionController::class, 'getAlerteEquipementByCategorie']);
            Route::get('{code}/occupations', [SessionController::class, 'getOccupationByCategorie']);
            Route::post('', [SessionController::class, 'createCategorie']);
        });
        Route::prefix('occupation')->group(function () {
            Route::get('{code}', [SessionController::class, 'getOccupation']);
            Route::get('', [SessionController::class, 'getAllOccupations']);
            Route::post('', [SessionController::class, 'createOccupation']);
            //Route::get('{code}/children',[SessionController::class,'getAllChambreByStanding']);
        });
        Route::prefix('equipement')->group(function () {
            Route::get('{code}', [SessionController::class, 'getEquipementBox']);
            Route::get('', [SessionController::class, 'getAllEquipementBox']);
            Route::get('{code}/alertes', [SessionController::class, 'getAlerteEquipementByEquipement']);
            Route::post('', [SessionController::class, 'createEquipementBox']);
            //Route::get('{code}/children',[SessionController::class,'getAllChambreByStanding']);
        });
        Route::prefix('alerte_equipement')->group(function () {
            Route::get('{code}', [SessionController::class, 'getAlerteEquipement']);
            Route::get('', [SessionController::class, 'getAllAlerteEquipement']);
            Route::post('', [SessionController::class, 'createAlerteEquipement']);
            //Route::get('{code}/children',[SessionController::class,'getAllChambreByStanding']);
        });
    }
);
