<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Income extends Model
{
    protected $fillable = [
        'income_id',
        'number',
        'date',
        'last_change_date',
        'supplier_article',
        'tech_size',
        'barcode',
        'quantity',
        'total_price',
        'date_close',
        'warehouse_name',
        'nm_id',
    ];

    protected $casts = [
        'date' => 'date',
        'last_change_date' => 'date',
        'date_close' => 'date',
        'income_id' => 'integer',
        'barcode' => 'integer',
        'quantity' => 'integer',
        'nm_id' => 'integer',
    ];

    public static function prepareData(array $item, int $accountId): array
    {
        return [
            'account_id' => $accountId,
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
}
