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
    protected $signature = 'fetch:stocks';

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
        try
        {
            $fetcher->fetchAndSave(
                'stocks',
                ['dateFrom' => date('Y-m-d')],
                Stock::class,
                [
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
                fn($item) => [
                    'date' => $item['date'] ?? null,
                    'last_change_date' => $item['last_change_date'] ?? null,
                    'supplier_article' => $item['supplier_article'] ?? null,
                    'tech_size' => $item['tech_size'] ?? null,
                    'barcode' => $item['barcode'] ?? null,
                    'quantity' => $item['quantity'] ?? null,
                    'is_supply' => $item['is_supply'] ?? null,
                    'is_realization' => $item['is_realization'] ?? null,
                    'quantity_full' => $item['quantity_full'] ?? null,
                    'warehouse_name' => $item['warehouse_name'] ?? null,
                    'in_way_to_client' => $item['in_way_to_client'] ?? null,
                    'in_way_from_client' => $item['in_way_from_client'] ?? null,
                    'nm_id' => $item['nm_id'] ?? null,
                    'subject' => $item['subject'] ?? null,
                    'category' => $item['category'] ?? null,
                    'brand' => $item['brand'] ?? null,
                    'sc_code' => $item['sc_code'] ?? null,
                    'price' => $item['price'] ?? null,
                    'discount' => $item['discount'] ?? null,
                ]
            );
            $this->info('stocks загружен!');
        }
        catch (\Exception $exception)
        {
            $this->error($exception->getMessage());
        }
    }
}
