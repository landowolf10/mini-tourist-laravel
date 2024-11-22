<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use App\Repositories\cards\CardRepositoryImp;
use App\Repositories\cards\CardRepositoryInterface;
use App\Repositories\cards_status\CardStatusRepositoryImp;
use App\Repositories\cards_status\CardStatusRepositoryInterface;
use App\Repositories\members\MemberRepositoryImp;
use App\Repositories\members\MemberRepositoryInterface;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
         // Bind the ClientRepositoryInterface to ClientRepository
         $this->app->bind(CardRepositoryInterface::class, CardRepositoryImp::class);
         $this->app->bind(CardStatusRepositoryInterface::class, CardStatusRepositoryImp::class);
         $this->app->bind(MemberRepositoryInterface::class, MemberRepositoryImp::class);
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        //
    }
}
