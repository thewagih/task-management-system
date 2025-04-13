<?php

namespace App\Providers;

use App\Repositories\Eloquent\AddressRepository;
use App\Repositories\Eloquent\AdminAuthRepository;
use App\Repositories\Eloquent\AdminRepository;
use App\Repositories\Eloquent\BaseRepository;
use App\Repositories\Eloquent\CarBrandRepository;
use App\Repositories\Eloquent\CarModelRepository;
use App\Repositories\Eloquent\CarRepository;
use App\Repositories\Eloquent\CityRepository;
use App\Repositories\Eloquent\ContactRepository;
use App\Repositories\Eloquent\CountryRepository;
use App\Repositories\Eloquent\GovernorateRepository;
use App\Repositories\Eloquent\NewsletterRepository;
use App\Repositories\Eloquent\OrderRepository;
use App\Repositories\Eloquent\PermissionRepository;
use App\Repositories\Eloquent\RoleRepository;
use App\Repositories\Eloquent\ServiceRepository;
use App\Repositories\Eloquent\TaskRepository;
use App\Repositories\Eloquent\UserAuthRepository;
use App\Repositories\Eloquent\UserRepository;
use App\Repositories\Eloquent\VoiceOrderRepository;
use App\Repositories\Eloquent\WasherRepository;
use App\Repositories\Interfaces\AddressRepositoryInterface;
use App\Repositories\Interfaces\AdminAuthRepositoryInterface;
use App\Repositories\Interfaces\AdminRepositoryInterface;
use App\Repositories\Interfaces\BaseRepositoryInterface;
use App\Repositories\Interfaces\CarBrandRepositoryInterface;
use App\Repositories\Interfaces\CarModelRepositoryInterface;
use App\Repositories\Interfaces\CarRepositoryInterface;
use App\Repositories\Interfaces\CityRepositoryInterface;
use App\Repositories\Interfaces\ContactRepositoryInterface;
use App\Repositories\Interfaces\CountryRepositoryInterface;
use App\Repositories\Interfaces\GovernorateRepositoryInterface;
use App\Repositories\Interfaces\NewsletterRepositoryInterface;
use App\Repositories\Interfaces\OrderRepositoryInterface;
use App\Repositories\Interfaces\PermissionRepositoryInterface;
use App\Repositories\Interfaces\RoleRepositoryInterface;
use App\Repositories\Interfaces\ServiceRepositoryInterface;
use App\Repositories\Interfaces\TaskRepositoryInterface;
use App\Repositories\Interfaces\UserAuthRepositoryInterface;
use App\Repositories\Interfaces\UserRepositoryInterface;
use App\Repositories\Interfaces\VoiceOrderRepositoryInterface;
use App\Repositories\Interfaces\WasherRepositoryInterface;
use Illuminate\Support\ServiceProvider;

class RepositoryServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     */
    public function register(): void
    {
        $this->app->bind(BaseRepositoryInterface::class, BaseRepository::class);
        $this->app->bind(TaskRepositoryInterface::class, TaskRepository::class);

    }

    /**
     * Bootstrap services.
     */
    public function boot(): void
    {
        //
    }
}
