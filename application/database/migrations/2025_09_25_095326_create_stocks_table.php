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
        Schema::create('stocks', function(Blueprint $table)
        {
            $table->id();
            $table->date('date')->index();
            $table->date('last_change_date')->nullable();
            $table->string('supplier_article')->nullable();
            $table->string('tech_size')->nullable();
            $table->bigInteger('barcode')->index();
            $table->integer('quantity')->nullable();
            $table->boolean('is_supply')->nullable();
            $table->boolean('is_realization')->nullable();
            $table->integer('quantity_full')->nullable();
            $table->string('warehouse_name')->nullable()->index();
            $table->integer('in_way_to_client')->nullable();
            $table->integer('in_way_from_client')->nullable();
            $table->bigInteger('nm_id')->index();
            $table->string('subject')->nullable();
            $table->string('category')->nullable();
            $table->string('brand')->nullable();
            $table->bigInteger('sc_code')->nullable();
            $table->string('price')->nullable();
            $table->string('discount')->nullable();
            $table->timestamps();

            $table->unique([
                'barcode',
                'date',
                'nm_id'
            ]);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('stocks');
    }
};
