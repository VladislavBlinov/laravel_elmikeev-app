<?php

namespace App\Console\Commands;

use App\Helpers\DateRangeHelper;
use App\Models\Order;
use App\Services\ApiFetcher;
use Illuminate\Console\Command;

class FetchOrders extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'fetch:orders {account_id} {date_from?} {date_to?}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Импорт orders из API';

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
            Order::class,
            $accountId,
            $this->argument('date_from'),
            $this->argument('date_to')
        );

        $this->info("Начинаем загрузку orders");

        try
        {
            $fetcher->fetchAndSave(
                $accountId,
                'api/orders',
                [
                    'dateFrom' => $dateFrom,
                    'dateTo' => $dateTo
                ],
                Order::class,
                [
                    'account_id',
                    'g_number',
                    'nm_id',
                    'date'
                ],
                [
                    'last_change_date',
                    'supplier_article',
                    'tech_size',
                    'barcode',
                    'total_price',
                    'discount_percent',
                    'warehouse_name',
                    'oblast',
                    'income_id',
                    'odid',
                    'subject',
                    'category',
                    'brand',
                    'is_cancel',
                    'cancel_dt',
                    'updated_at',
                ],
                fn($item) => Order::prepareData($item, $accountId)
            );
            $this->info('orders загружен!');

            return 0;
        }
        catch (\Exception $e)
        {
            $this->error('Исключение в orders: ' . $e->getMessage());
            $this->error($e->getTraceAsString());

            return 1;
        }
    }
}
