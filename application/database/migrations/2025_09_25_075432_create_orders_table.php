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
        Schema::create('orders', function(Blueprint $table)
        {
            $table->id();
            $table->string('g_number');
            $table->dateTime('date')->nullable()->index();
            $table->date('last_change_date');
            $table->string('supplier_article')->nullable();
            $table->string('tech_size')->nullable();
            $table->bigInteger('barcode')->nullable()->index();
            $table->string('total_price')->nullable();
            $table->integer('discount_percent')->nullable();
            $table->string('warehouse_name')->nullable()->index();
            $table->string('oblast')->nullable();
            $table->bigInteger('income_id')->nullable();
            $table->string('odid')->nullable();
            $table->bigInteger('nm_id')->index();
            $table->string('subject')->nullable();
            $table->string('category')->nullable();
            $table->string('brand')->nullable();
            $table->boolean('is_cancel')->nullable();
            $table->date('cancel_dt')->nullable();
            $table->timestamps();

            $table->unique([
                'g_number',
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
        Schema::dropIfExists('orders');
    }
};
