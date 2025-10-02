<?php

namespace App\Console\Commands;

use App\Models\ApiService;
use App\Models\ApiToken;
use App\Models\TokenType;
use Illuminate\Console\Command;

class AddApiToken extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'add:token {account_id} {service_id} {token_type} {token}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Добавить токен аккаунту';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $service = ApiService::findOrFail($this->argument('service_id'));
        $type = $this->argument('token_type');
        $tokenType = TokenType::where('name', $type)->firstOrFail();

        $token = ApiToken::create([
            'account_id' => $this->argument('account_id'),
            'api_service_id' => $this->argument('service_id'),
            'token_type_id' => $tokenType->id,
            'token' => $this->argument('token'),
        ]);

        $this->info("Токен создан для аккаунта {$token->account_id}");
    }
}
