<?php
namespace Tests\Feature;

use App\Models\Account;
use App\Models\ApiService;
use App\Models\ApiServiceTokenType;
use App\Models\ApiToken;
use App\Models\Company;
use App\Models\Sale;
use App\Models\TokenType;
use App\Services\ApiFetcher;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ApiFetcherTest extends TestCase
{
    use RefreshDatabase;

    public function test_fetch_and_save_exists_token()
    {
        $company = Company::factory()->create();
        $account = Account::factory()->create(['company_id' => $company->id]);

        $service = ApiService::factory()->create();
        $tokenType = TokenType::factory()->create(['name' => 'api-key']);

        ApiServiceTokenType::create([
            'api_service_id' => $service->id,
            'token_type_id' => $tokenType->id,
        ]);

        $token = ApiToken::factory()->create([
            'account_id' => $account->id,
            'api_service_id' => $service->id,
            'token_type_id' => $tokenType->id,
            'token' => 'FAKEKEY'
        ]);

        $fetcher = new ApiFetcher();

        $this->assertEquals($account->tokens->first()->token, 'FAKEKEY');
        $this->assertEquals($account->tokens->first()->service->id, $service->id);
        $this->assertEquals($account->tokens->first()->tokenType->name, 'api-key');
    }

    public function test_fetch_and_save_exception_on_missing_token()
    {
        $this->expectException(ModelNotFoundException::class);

        $fetcher = new ApiFetcher();
        $fetcher->fetchAndSave(
            999,
            'endpoint',
            [],
            \stdClass::class,
            ['id'],
            ['id'],
            fn($item, $accountId) => $item
        );
    }

    public function test_fetch_real_service()
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

        $accountId = $account->id;
        $endpoint = 'api/sales';
        $params = [
            'dateFrom' => '2025-09-01',
            'dateTo' => '2025-09-02'
        ];
        $model = Sale::class;
        $uniqueBy = [
            'account_id',
            'sale_id',
            'nm_id',
            'date'
        ];
        $updateFields = [
            'g_number',
            'last_change_date',
            'supplier_article',
            'tech_size',
            'barcode',
            'total_price',
            'discount_percent',
            'is_supply',
            'is_realization',
            'promo_code_discount',
            'warehouse_name',
            'country_name',
            'oblast_okrug_name',
            'region_name',
            'income_id',
            'odid',
            'spp',
            'for_pay',
            'finished_price',
            'price_with_disc',
            'subject',
            'category',
            'brand',
            'is_storno',
            'updated_at',
        ];

        $fetcher = new ApiFetcher();

        try
        {
            $fetcher->fetchAndSave(
                $accountId,
                $endpoint,
                $params,
                $model,
                $uniqueBy,
                $updateFields,
                fn($item, $accountId) => Sale::prepareData($item, $accountId)
            );
            $this->assertTrue(true);
        }
        catch (\Exception $e)
        {
            $this->fail("Запрос к сервису упал: " . $e->getMessage());
        }
    }
}
