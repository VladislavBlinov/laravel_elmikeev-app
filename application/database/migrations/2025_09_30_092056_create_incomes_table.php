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
        Schema::create('incomes', function(Blueprint $table)
        {
            $table->id();
            $table->foreignId('account_id')->constrained()->onDelete('cascade');
            $table->bigInteger('income_id')->index();
            $table->string('number')->nullable();
            $table->date('date')->index();
            $table->date('last_change_date')->nullable();
            $table->string('supplier_article')->nullable();
            $table->string('tech_size')->nullable();
            $table->bigInteger('barcode')->nullable()->index();
            $table->integer('quantity')->nullable();
            $table->string('total_price')->nullable();
            $table->date('date_close')->nullable();
            $table->string('warehouse_name')->nullable()->index();
            $table->bigInteger('nm_id');
            $table->timestamps();

            $table->unique([
                'account_id',
                'income_id',
                'date',
                'nm_id',
            ]);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('incomes');
    }
};
