<?php

namespace Database\Seeders;

use App\Models\Account;
use App\Models\ApiService;
use App\Models\ApiServiceTokenType;
use App\Models\ApiToken;
use App\Models\Company;
use App\Models\TokenType;
use Illuminate\Database\Seeder;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $company = Company::factory()->create(['name' => 'Company']);
        $account = Account::factory()->create([
            'company_id' => $company->id,
            'name' => 'user',
        ]);

        $service = ApiService::factory()->create([
            'name' => 'Service',
            'base_url' => 'https://api.test.ru',
        ]);

        $tokenType = TokenType::factory()->create([
            'name' => 'bearer',
        ]);

        ApiServiceTokenType::create([
            'api_service_id' => $service->id,
            'token_type_id' => $tokenType->id,
        ]);

        ApiToken::factory()->create([
            'account_id' => $account->id,
            'api_service_id' => $service->id,
            'token_type_id' => $tokenType->id,
            'token' => 'TOKEN',
        ]);
    }
}
