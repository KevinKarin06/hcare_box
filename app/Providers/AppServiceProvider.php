<?php

namespace App\Providers;

use App\Repositories\Classes\AlerteEquipementRepository;
use App\Repositories\Classes\AppRepository;
use App\Repositories\Classes\BasicRepository;
use App\Repositories\Classes\BoxRepository;
use App\Repositories\Classes\CategorieEqpAltOccRepository;
use App\Repositories\Classes\EquipementBoxRepository;
use App\Repositories\Classes\OccupationRepository;
use App\Repositories\Classes\SessionCreateRepository;
use App\Repositories\Classes\SessionGetRepository;
use App\Repositories\Classes\SessionRepository;
use App\Repositories\Interfaces\IAlerteEquipementRepository;
use App\Repositories\Interfaces\IAppRepository;
use App\Repositories\Interfaces\IBasicRepository;
use App\Repositories\Interfaces\IBoxRepository;
use App\Repositories\Interfaces\ICategorieEqpAltOccRepository;
use App\Repositories\Interfaces\iEquipementBoxRepository;
use App\Repositories\Interfaces\IOccupationRepository;
use App\Repositories\Interfaces\ISessionCreateRepository;
use App\Repositories\Interfaces\ISessionGetRepository;
use App\Repositories\Interfaces\ISessionRepository;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        $this->app->singleton(IAppRepository::class, AppRepository::class);
        $this->app->singleton(IBasicRepository::class, BasicRepository::class);
        $this->app->singleton(ISessionRepository::class, SessionRepository::class);
        $this->app->singleton(ISessionCreateRepository::class, SessionCreateRepository::class);
        $this->app->singleton(ISessionGetRepository::class, SessionGetRepository::class);

        $this->app->singleton(IAlerteEquipementRepository::class, AlerteEquipementRepository::class);
        $this->app->singleton(IBoxRepository::class, BoxRepository::class);
        $this->app->singleton(ICategorieEqpAltOccRepository::class, CategorieEqpAltOccRepository::class);
        $this->app->singleton(iEquipementBoxRepository::class, EquipementBoxRepository::class);
        $this->app->singleton(IOccupationRepository::class, OccupationRepository::class);
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        //
    }
}
