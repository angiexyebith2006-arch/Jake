<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class GenerateControllers extends Command
{
    protected $signature = 'generate:controllers';
    protected $description = 'Genera controladores resource basados en las tablas de la base de datos';

    public function handle()
    {
        $dbName = env('DB_DATABASE');
        $tables = DB::select('SHOW TABLES');
        $column = "Tables_in_$dbName";

        foreach ($tables as $table) {
            $tableName = $table->$column;

            // Tablas que NO deben tener controlador
            if (in_array($tableName, [
                'cache', 'cache_locks', 'failed_jobs', 'jobs', 'job_batches',
                'migrations', 'password_reset_tokens', 'personal_access_tokens', 'sessions'
            ])) {
                continue;
            }

            $controllerName = Str::studly(Str::singular($tableName)) . 'Controller';

            $this->info("Creando controlador: $controllerName ...");

            exec("php artisan make:controller $controllerName --resource");
        }

        $this->info("✔ Controladores generados correctamente.");
    }
}

