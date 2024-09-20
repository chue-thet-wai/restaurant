<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use App\Interfaces\CommonRepositoryInterface;
use App\Repositories\CommonRepository;
use App\Interfaces\EmailRepositoryInterface;
use App\Repositories\EmailRepository;


class RepositoryServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     *
     * @return void
     */
    public function register()
    {
        $this->app->bind(CommonRepositoryInterface::class, CommonRepository::class);
        $this->app->bind(EmailRepositoryInterface::class, EmailRepository::class);
    }

    /**
     * Bootstrap services.
     *
     * @return void
     */
    public function boot()
    {
        //
    }
}
