<?php
// Register the legacy MySQL connection lazily so Laravel schema inspection remains compatible with the existing database server without opening a connection at boot.


namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Database\Connection;
use Illuminate\Support\Facades\Schema;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        Schema::defaultStringLength(191);
    }

    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        Connection::resolverFor('mysql', function ($connection, $database, $prefix, $config) {
            return new \App\Database\LegacyMySqlConnection($connection, $database, $prefix, $config);
        });
    }
}
