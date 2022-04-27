<?php

namespace App\Providers;

use App\Contracts\Repositories\BillRepository;
use App\Contracts\Repositories\CategoryRepository;
use App\Contracts\Repositories\UserRepository;
use App\Contracts\Services\AuthContract;
use App\Contracts\Services\BillContract;
use App\Contracts\Services\CategoryContract;
use App\Contracts\Services\UserContract;
use App\Repositories\BillRepositoryEloquent;
use App\Repositories\CategoryRepositoryEloquent;
use App\Repositories\UserRepositoryEloquent;
use App\Services\AuthService;
use App\Services\BillService;
use App\Services\CategoryService;
use App\Services\UserService;
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
        $this->app->bind(AuthContract::class, AuthService::class);

        $this->app->bind(UserRepository::class, UserRepositoryEloquent::class);
        $this->app->bind(UserContract::class, UserService::class);

        $this->app->bind(BillRepository::class, BillRepositoryEloquent::class);
        $this->app->bind(BillContract::class, BillService::class);

        $this->app->bind(CategoryRepository::class, CategoryRepositoryEloquent::class);
        $this->app->bind(CategoryContract::class, CategoryService::class);
    }
}
