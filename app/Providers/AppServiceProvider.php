<?php

namespace App\Providers;

use App\Repositories\TransferRepository;
use App\Repositories\UserRepository;
use App\Services\TransferService;
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
        //
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        $this->app->bind(UserRepository::class, function () {
            return new UserRepository;
        });
        $this->app->alias(UserRepository::class, 'user.repository');

        $this->app->bind(TransferRepository::class, function () {
            return new TransferRepository;
        });
        $this->app->alias(TransferRepository::class, 'transfer.repository');

        $this->app->bind(TransferService::class, function () {
            $userRepository = app('user.repository');
            $transferRepository = app('transfer.repository');
            return new TransferService($userRepository, $transferRepository);
        });
        $this->app->alias(TransferService::class, 'transfer.service');
    }
}
