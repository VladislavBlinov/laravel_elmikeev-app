<?php

namespace App\Console\Commands;

use App\Models\Company;
use Illuminate\Console\Command;

class AddCompany extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'add:company {name}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Добавить компанию';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $company = Company::create([
            'name' => $this->argument('name'),
        ]);

        $this->info("Компания создана: {$company->name}");
    }
}
