<?php

namespace App\Console\Commands;

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
    protected $signature = 'fetch:sales {dateFrom} {dateTo}';

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
        $dateFrom = $this->argument('dateFrom');
        $dateTo = $this->argument('dateTo');

        try
        {
            $fetcher->fetchAndSave(
                'sales',
                [
                    'dateFrom' => $dateFrom,
                    'dateTo' => $dateTo
                ],
                Sale::class,
                [
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
                fn($item) => [
                    'g_number' => $item['g_number'] ?? null,
                    'date' => $item['date'] ?? null,
                    'last_change_date' => $item['last_change_date'] ?? null,
                    'supplier_article' => $item['supplier_article'] ?? null,
                    'tech_size' => $item['tech_size'] ?? null,
                    'barcode' => $item['barcode'] ?? null,
                    'total_price' => $item['total_price'] ?? null,
                    'discount_percent' => $item['discount_percent'] ?? null,
                    'is_supply' => $item['is_supply'] ?? null,
                    'is_realization' => $item['is_realization'] ?? null,
                    'promo_code_discount' => $item['promo_code_discount'] ?? null,
                    'warehouse_name' => $item['warehouse_name'] ?? null,
                    'country_name' => $item['country_name'] ?? null,
                    'oblast_okrug_name' => $item['oblast_okrug_name'] ?? null,
                    'region_name' => $item['region_name'] ?? null,
                    'income_id' => $item['income_id'] ?? null,
                    'sale_id' => $item['sale_id'] ?? null,
                    'odid' => $item['odid'] ?? null,
                    'spp' => $item['spp'] ?? null,
                    'for_pay' => $item['for_pay'] ?? null,
                    'finished_price' => $item['finished_price'] ?? null,
                    'price_with_disc' => $item['price_with_disc'] ?? null,
                    'nm_id' => $item['nm_id'] ?? null,
                    'subject' => $item['subject'] ?? null,
                    'category' => $item['category'] ?? null,
                    'brand' => $item['brand'] ?? null,
                    'is_storno' => $item['is_storno'] ?? null,
                ]
            );
            $this->info('sales загружен!');
        }
        catch (\Exception $exception)
        {
            $this->error($exception->getMessage());
        }
    }
}
