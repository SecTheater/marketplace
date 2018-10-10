<?php

namespace SecTheater\Marketplace\Providers;
use Illuminate\Support\Facades\Gate;
use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;

class AuthyServiceProvider extends ServiceProvider
{
    /**
     * The policy mappings for the application.
     *
     * @var array
     */
    protected $policies = [
        \SecTheater\Marketplace\Models\EloquentProduct::class => \SecTheater\Marketplace\Policies\ProductPolicy::class,
        \SecTheater\Marketplace\Models\EloquentCategory::class => \SecTheater\Marketplace\Policies\CategoryPolicy::class,
        \SecTheater\Marketplace\Models\EloquentUser::class => \SecTheater\Marketplace\Policies\UserPolicy::class,
        \SecTheater\Marketplace\Models\EloquentCart::class => \SecTheater\Marketplace\Policies\CartPolicy::class,
        
    ];
    /**
     * Register any authentication / authorization services.
     *
     * @return void
     */
    public function boot()
    {
        $this->registerPolicies();
        Gate::define('view-product','\SecTheater\Marketplace\Policies\ProductPolicy@view');
        Gate::define('update-product','\SecTheater\Marketplace\Policies\ProductPolicy@update');
        Gate::define('delete-product','\SecTheater\Marketplace\Policies\ProductPolicy@delete');
        Gate::define('create-product','\SecTheater\Marketplace\Policies\ProductPolicy@create');
        Gate::define('review-product','\SecTheater\Marketplace\Policies\ProductPolicy@review');
        Gate::define('rate-product','\SecTheater\Marketplace\Policies\ProductPolicy@rate');
        Gate::define('create-category','\SecTheater\Marketplace\Policies\CategoryPolicy@create');
        Gate::define('update-category','\SecTheater\Marketplace\Policies\CategoryPolicy@update');
        Gate::define('delete-category','\SecTheater\Marketplace\Policies\CategoryPolicy@delete');
        Gate::define('view-category','\SecTheater\Marketplace\Policies\CategoryPolicy@view');
        Gate::define('upgrade-user','\SecTheater\Marketplace\Policies\UserPolicy@upgrade');
        Gate::define('downgrade-user','\SecTheater\Marketplace\Policies\UserPolicy@downgrade');
        Gate::define('add-cart','\SecTheater\Marketplace\Policies\CartPolicy@add');
        Gate::define('delete-cart','\SecTheater\Marketplace\Policies\CartPolicy@delete');
        Gate::define('update-cart','\SecTheater\Marketplace\Policies\CartPolicy@update');
        Gate::define('delete-from-others-cart','\SecTheater\Marketplace\Policies\CartPolicy@delete_from_others');
        
    }
}