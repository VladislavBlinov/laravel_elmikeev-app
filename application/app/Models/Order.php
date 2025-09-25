<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Order extends Model
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
        'warehouse_name',
        'oblast',
        'income_id',
        'odid',
        'nm_id',
        'subject',
        'category',
        'brand',
        'is_cancel',
        'cancel_dt',
    ];

    protected $casts = [
        'date' => 'datetime',
        'last_change_date' => 'date',
        'barcode' => 'integer',
        'discount_percent' => 'integer',
        'income_id' => 'integer',
        'nm_id' => 'integer',
        'is_cancel' => 'boolean',
        'cancel_dt' => 'date',
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
}
