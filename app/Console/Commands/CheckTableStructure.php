<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Schema;

class CheckTableStructure extends Command
{
    protected $signature = 'check:table-structure {table}';
    protected $description = 'Check table structure';

    public function handle()
    {
        $table = $this->argument('table');
        $columns = Schema::getColumnListing($table);
        
        $this->info("Columns in {$table}:");
        foreach ($columns as $column) {
            $this->info("- {$column}");
        }
        
        return 0;
    }
}
