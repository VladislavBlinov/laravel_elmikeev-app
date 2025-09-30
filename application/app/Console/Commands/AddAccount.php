<?php

namespace App\Console\Commands;

use App\Models\Account;
use Illuminate\Console\Command;

class AddAccount extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'add:account {company_id} {name}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Добавить аккаунт';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $account = Account::create([
            'company_id' => $this->argument('company_id'),
            'name' => $this->argument('name'),
        ]);
        $this->info("Аккаунт создан: {$account->name}");
    }
}
