<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Sale extends Model
{
    protected $fillable = [
        'g_number',
        'date',
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
        'sale_id',
        'odid',
        'spp',
        'for_pay',
        'finished_price',
        'price_with_disc',
        'nm_id',
        'subject',
        'category',
        'brand',
        'is_storno'
    ];

    protected $casts = [
        'date' => 'date',
        'last_change_date' => 'date',
        'barcode' => 'integer',
        'income_id' => 'integer',
        'nm_id' => 'integer',
        'discount_percent' => 'integer',
        'is_supply' => 'boolean',
        'is_realization' => 'boolean',
        'is_storno' => 'boolean',
    ];

    public static function prepareData(array $item): array
    {
        return [
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
        ];
    }
}
