<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ApiServiceTokenType extends Model
{
    protected $fillable = [
        'api_service_id',
        'token_type_id'
    ];

    public function service()
    {
        return $this->belongsTo(ApiService::class, 'api_service_id');
    }

    public function tokenType()
    {
        return $this->belongsTo(TokenType::class, 'token_type_id');
    }

    public function tokens()
    {
        return $this->hasMany(ApiToken::class, 'api_service_token_type_id');
    }
}
