<?php

namespace App\Console\Commands;

use App\Models\ApiService;
use App\Models\ApiServiceTokenType;
use App\Models\TokenType;
use Illuminate\Console\Command;

class AddApiServiceTokenType extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'add:service-tokentype {service_id} {token_type_id}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Добавить тип токена для API сервиса';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $serviceId = $this->argument('service_id');
        $tokenTypeId = $this->argument('token_type_id');

        $service = ApiService::find($serviceId);
        if (!$service)
        {
            $this->error("Сервис с id {$serviceId} не найден");

            return;
        }

        $tokenType = TokenType::find($tokenTypeId);
        if (!$tokenType)
        {
            $this->error("Тип токена с id {$tokenTypeId} не найден");

            return;
        }

        $serviceTokenType = ApiServiceTokenType::create([
            'api_service_id' => $serviceId,
            'token_type_id' => $tokenTypeId,
        ]);

        $this->info("Тип токена {$tokenType->name} добавлен для сервиса {$service->name}");
    }
}
