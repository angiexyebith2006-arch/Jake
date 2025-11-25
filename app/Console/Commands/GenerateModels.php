<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class GenerateModels extends Command
{
    protected $signature = 'generate:models';
    protected $description = 'Genera modelos basados en las tablas de la base de datos';

    public function handle()
    {
        $tables = DB::select('SHOW TABLES');
        $dbName = env('DB_DATABASE');
        $key = "Tables_in_$dbName";

        foreach ($tables as $table) {
            $tableName = $table->$key;

            // Ignorar tablas del sistema
            if (in_array($tableName, [
                'migrations', 'cache', 'cache_locks', 'jobs', 'job_batches',
                'failed_jobs', 'sessions', 'password_reset_tokens',
                'personal_access_tokens'
            ])) {
                continue;
            }

            $modelName = Str::studly(Str::singular($tableName));

            $this->info("Creando modelo: $modelName");

            // Crear modelo
            exec("php artisan make:model $modelName");
        }

        $this->info("Todos los modelos han sido generados.");
    }
}
