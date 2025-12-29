<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Schema;

class AddressDataSeeder extends Seeder
{
    public function run()
    {
        $path = database_path('sql_files');

        $tablesToRun = ['states', 'districts', 'pincodes', 'cities'];

        foreach ($tablesToRun as $table) {
            $table = strtolower($table);
            $file = "$path/{$table}.sql";

            if (!file_exists($file)) {
                echo "Skipped: $table.sql (file not found)\n";
                continue;
            }

            if (!Schema::hasTable($table)) {
                echo "Skipped: $table (table does not exist)\n";
                continue;
            }

            if (DB::table($table)->count() > 0) {
                echo "Skipped: $table (already has data)\n";
                continue;
            }

            DB::unprepared(File::get($file));
            echo "Executed: $table.sql\n";
        }
    }
}