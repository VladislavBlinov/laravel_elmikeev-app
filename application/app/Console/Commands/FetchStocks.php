<?php

namespace App\Console\Commands;

use App\Models\Stock;
use App\Services\ApiFetcher;
use Illuminate\Console\Command;

class FetchStocks extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'fetch:stocks {account_id}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Импорт stocks из API';

    /**
     * Execute the console command.
     */

    public function handle(ApiFetcher $fetcher)
    {
        $accountId = (int)$this->argument('account_id');

        $today = date('Y-m-d');

        $this->info("Начинаем загрузку stocks");

        try
        {
            $fetcher->fetchAndSave(
                $accountId,
                'api/stocks',
                ['dateFrom' => date('Y-m-d')],
                Stock::class,
                [
                    'account_id',
                    'date',
                    'nm_id',
                    'barcode',
                ],
                [
                    'last_change_date',
                    'supplier_article',
                    'tech_size',
                    'quantity',
                    'is_supply',
                    'is_realization',
                    'quantity_full',
                    'warehouse_name',
                    'in_way_to_client',
                    'in_way_from_client',
                    'subject',
                    'category',
                    'brand',
                    'sc_code',
                    'price',
                    'discount',
                ],
                fn($item) => Stock::prepareData($item, $accountId)
            );
            $this->info('stocks загружен!');

            return 0;
        }
        catch (\Exception $e)
        {
            $this->error('Исключение в stocks: ' . $e->getMessage());
            $this->error($e->getTraceAsString());

            return 1;
        }
    }
}
