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
        Schema::create('sales', function(Blueprint $table)
        {
            $table->id();
            $table->foreignId('account_id')->default(0);
            $table->string('g_number')->nullable();
            $table->date('date')->index();
            $table->date('last_change_date')->nullable();
            $table->string('supplier_article')->nullable();
            $table->string('tech_size')->nullable();
            $table->bigInteger('barcode')->nullable()->index();
            $table->string('total_price')->nullable();
            $table->integer('discount_percent')->nullable();
            $table->boolean('is_supply')->nullable();
            $table->boolean('is_realization')->nullable();
            $table->string('promo_code_discount')->nullable();
            $table->string('warehouse_name')->nullable()->index();
            $table->string('country_name')->nullable();
            $table->string('oblast_okrug_name')->nullable();
            $table->string('region_name')->nullable();
            $table->bigInteger('income_id')->nullable();
            $table->string('sale_id')->index();
            $table->string('odid')->nullable();
            $table->string('spp')->nullable();
            $table->string('for_pay')->nullable();
            $table->string('finished_price')->nullable();
            $table->string('price_with_disc')->nullable();
            $table->bigInteger('nm_id')->index();
            $table->string('subject')->nullable();
            $table->string('category')->nullable();
            $table->string('brand')->nullable();
            $table->boolean('is_storno')->nullable();
            $table->timestamps();

            $table->unique([
                'date',
                'sale_id',
                'nm_id',
            ]);
        });

    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('sales');
    }
};
