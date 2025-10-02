<?php

namespace App\Console\Commands;

use App\Helpers\DateRangeHelper;
use App\Models\Sale;
use App\Services\ApiFetcher;
use Illuminate\Console\Command;

class FetchSales extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'fetch:sales {account_id} {date_from?} {date_to?}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Импорт sales из API';

    /**
     * Execute the console command.
     */

    public function handle(ApiFetcher $fetcher)
    {
        $accountId = (int)$this->argument('account_id');

        [
            $dateFrom,
            $dateTo
        ] = DateRangeHelper::resolveDates(
            Sale::class,
            $accountId,
            $this->argument('date_from'),
            $this->argument('date_to')
        );

        $this->info("Начинаем загрузку sales");

        try
        {
            $fetcher->fetchAndSave(
                $accountId,
                'api/sales',
                [
                    'dateFrom' => $dateFrom,
                    'dateTo' => $dateTo
                ],
                Sale::class,
                [
                    'account_id',
                    'sale_id',
                    'nm_id',
                    'date'
                ],
                [
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
                ],
                fn($item) => Sale::prepareData($item, $accountId)
            );
            $this->info('sales загружен!');

            return 0;
        }
        catch (\Exception $e)
        {
            $this->error('Исключение в sales: ' . $e->getMessage());
            $this->error($e->getTraceAsString());

            return 1;
        }
    }
}
