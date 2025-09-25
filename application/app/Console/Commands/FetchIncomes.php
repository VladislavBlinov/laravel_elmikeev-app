<?php

namespace App\Console\Commands;

use App\Models\Income;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Http;

class FetchIncomes extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'fetch-incomes {dateFrom} {dateTo}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Импорт incomes из API';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $dateFrom = $this->argument('dateFrom');
        $dateTo = $this->argument('dateTo');
        $apiUrl = config('api.base_url') . '/incomes';

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
                        'income_id' => $item['income_id'] ?? null,
                        'number' => $item['number'] ?? null,
                        'date' => $item['date'] ?? null,
                        'last_change_date' => $item['last_change_date'] ?? null,
                        'supplier_article' => $item['supplier_article'] ?? null,
                        'tech_size' => $item['tech_size'] ?? null,
                        'barcode' => $item['barcode'] ?? null,
                        'quantity' => $item['quantity'] ?? null,
                        'total_price' => $item['total_price'] ?? null,
                        'date_close' => $item['date_close'] ?? null,
                        'warehouse_name' => $item['warehouse_name'] ?? null,
                        'nm_id' => $item['nm_id'] ?? null,
                    ];
                }

                Income::upsert($rows, [
                    'income_id',
                    'nm_id',
                    'date',
                ], [
                    'number',
                    'last_change_date',
                    'supplier_article',
                    'tech_size',
                    'barcode',
                    'quantity',
                    'total_price',
                    'date_close',
                    'warehouse_name',
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
