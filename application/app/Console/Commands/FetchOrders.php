<?php

namespace App\Console\Commands;

use App\Models\Order;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Http;

class FetchOrders extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'fetch-orders {dateFrom} {dateTo}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Импорт orders из API';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $dateFrom = $this->argument('dateFrom');
        $dateTo = $this->argument('dateTo');
        $apiUrl = config('api.base_url') . '/orders';

        $page = 1;
        $limit = 500;

        try
        {
            while (true)
            {
                $response = Http::retry(3, 2000)
                    ->get($apiUrl, [
                        'dateFrom' => $dateFrom,
                        'dateTo' => $dateTo,
                        'page' => $page,
                        'limit' => $limit,
                        'key' => config('api.api_key')
                    ])
                ;

                if ($response->failed())
                {
                    $this->error('Статус ошибки: ' . $response->status());
                    break;
                }

                $data = $response->json();
                $this->info(json_encode($data['data']));
                if (empty($data['data']))
                {
                    break;
                }

                $rows = [];
                foreach ($data['data'] as $item)
                {
                    $rows[] = [
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
                    ];
                }

                Order::upsert($rows, [
                    'g_number',
                    'nm_id',
                    'date'
                ], [
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
                ]);

                $page++;
                sleep(1);
            }
        }

        catch (\Exception $e)
        {
            $this->error($e->getMessage());
        }
    }
}
