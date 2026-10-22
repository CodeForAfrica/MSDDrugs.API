<?php

namespace App\Providers;

use App\Database\LegacyMySqlConnection;
use Closure;
use Illuminate\Database\Connection;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\ServiceProvider;
use PDO;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot(): void
    {
        Schema::defaultStringLength(191);
    }

    /**
     * Register any application services.
     *
     * @return void
     */
    public function register(): void
    {
        Connection::resolverFor('mysql', static function (
            PDO|Closure $connection,
            string $database,
            string $tablePrefix,
            array $configuration,
        ): LegacyMySqlConnection {
            return new LegacyMySqlConnection($connection, $database, $tablePrefix, $configuration);
        });
    }
}
