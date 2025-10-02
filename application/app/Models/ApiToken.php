<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ApiToken extends Model
{
    use HasFactory;

    protected $fillable = [
        'account_id',
        'api_service_id',
        'token_type_id',
        'token'
    ];

    protected $casts = [
        'token' => 'array',
    ];

    public function account(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(Account::class);
    }

    public function service(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(ApiService::class, 'api_service_id');
    }

    public function tokenType(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(TokenType::class, 'token_type_id');

    }

    public function serviceTokenType()
    {
        return $this->belongsTo(ApiServiceTokenType::class, 'api_service_token_type_id');
    }
}
