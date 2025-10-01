<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Stock extends Model
{
    protected $fillable = [
        'date',
        'last_change_date',
        'supplier_article',
        'tech_size',
        'barcode',
        'quantity',
        'is_supply',
        'is_realization',
        'quantity_full',
        'warehouse_name',
        'in_way_to_client',
        'in_way_from_client',
        'nm_id',
        'subject',
        'category',
        'brand',
        'sc_code',
        'price',
        'discount',
    ];

    protected $casts = [
        'date' => 'date',
        'last_change_date' => 'date',
        'barcode' => 'integer',
        'quantity' => 'integer',
        'is_supply' => 'boolean',
        'is_realization' => 'boolean',
        'quantity_full' => 'integer',
        'in_way_to_client' => 'integer',
        'in_way_from_client' => 'integer',
        'nm_id' => 'integer',
        'sc_code' => 'integer',
    ];

    public static function prepareData(array $item, int $accountId): array
    {
        return [
            'account_id' => $accountId,
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
        ];
    }
}
