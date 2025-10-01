<?php

namespace App\Console\Commands;

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

        $lastDate = Order::where('account_id', $accountId)->max('date');
        $today = date('Y-m-d');
        $dateFrom = $this->argument('date_from')
            ?? ($lastDate && $lastDate<$today ? date('Y-m-d', strtotime($lastDate . ' +1 day')) : $today);
        $dateTo = $this->argument('date_to') ?? $today;

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
                fn($item) => [
                    'account_id' => $accountId,
                    'g_number' => $item['g_number'] ?? null,
                    'date' => $item['date'] ?? null,
                    'last_change_date' => $item['last_change_date'] ?? null,
                    'supplier_article' => $item['supplier_article'] ?? null,
                    'tech_size' => $item['tech_size'] ?? null,
                    'barcode' => $item['barcode'] ?? null,
                    'total_price' => $item['total_price'] ?? null,
                    'discount_percent' => $item['discount_percent'] ?? null,
                    'warehouse_name' => $item['warehouse_name'] ?? null,
                    'oblast' => $item['oblast'] ?? null,
                    'income_id' => $item['income_id'] ?? null,
                    'odid' => $item['odid'] ?? null,
                    'nm_id' => $item['nm_id'] ?? null,
                    'subject' => $item['subject'] ?? null,
                    'category' => $item['category'] ?? null,
                    'brand' => $item['brand'] ?? null,
                    'is_cancel' => $item['is_cancel'] ?? false,
                    'cancel_dt' => $item['cancel_dt'] ?? null,
                ]
            );
            $this->info('orders загружен!');

            return 0;
        }
        catch (\Exception $e)
        {
            $this->error('Исключение в orders: ' . $e->getMessage());
        }
    }
}
