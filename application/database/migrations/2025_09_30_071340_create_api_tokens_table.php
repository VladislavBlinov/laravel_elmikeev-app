<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('api_tokens', function(Blueprint $table)
        {
            $table->id();
            $table->foreignId('account_id')->constrained()->onDelete('cascade');
            $table->foreignId('api_service_id')->constrained()->onDelete('cascade');
            $table->foreignId('token_type_id')->constrained('token_types')->onDelete('cascade');
            $table->json('token');
            $table->timestamps();

            $table->unique([
                'account_id',
                'api_service_id',
                'token_type_id'
            ]);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('api_tokens');
    }
};
