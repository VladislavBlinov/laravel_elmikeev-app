<?php

namespace Tests\Feature;

use App\Models\Account;
use App\Models\ApiService;
use App\Models\ApiServiceTokenType;
use App\Models\ApiToken;
use App\Models\Company;
use App\Models\TokenType;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class FetchAllCommandTest extends TestCase
{
    use RefreshDatabase;

    public function test_fetch_all_for_single_account()
    {
        $company = Company::factory()->create();
        $account = Account::factory()->create(['company_id' => $company->id]);
        $service = ApiService::factory()->create([
            'name' => 'Service',
            'base_url' => config('api.base_url')
        ]);
        $tokenType = TokenType::factory()->create(['name' => 'api-key']);

        ApiServiceTokenType::create([
            'api_service_id' => $service->id,
            'token_type_id' => $tokenType->id,
        ]);

        ApiToken::factory()->create([
            'account_id' => $account->id,
            'api_service_id' => $service->id,
            'token_type_id' => $tokenType->id,
            'token' => config('api.api_key')
        ]);

        $dateFrom = '2025-09-01';
        $dateTo = '2025-09-02';

        $exitCode = $this->artisan('fetch:all', [
            'account_id' => $account->id,
            'date_from' => $dateFrom,
            'date_to' => $dateTo
        ])
            ->expectsOutput("Обработка аккаунта {$account->name}")
            ->expectsOutput('Все данные получены!')
            ->run()
        ;

        $this->assertEquals(0, $exitCode);
    }

    public function test_fetch_all_skips_account_without_token()
    {
        $company = Company::factory()->create();
        $account = Account::factory()->create(['company_id' => $company->id]);

        $dateFrom = '2025-01-01';
        $dateTo = '2025-10-02';

        $exitCode = $this->artisan('fetch:all', [
            'account_id' => $account->id,
            'date_from' => $dateFrom,
            'date_to' => $dateTo
        ])
            ->expectsOutput("Токен для аккаунта {$account->id} не найден")
            ->run()
        ;

        $this->assertEquals(0, $exitCode);
    }
}
