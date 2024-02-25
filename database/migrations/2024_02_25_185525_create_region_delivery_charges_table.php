<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('region_delivery_charges', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('region_id');
            $table->double('minimum_frozen_weight', 10, 2)->nullable()->default(0);
            $table->double('minimum_purchase_amount', 10, 2)->nullable()->default(0);
            $table->double('delivery_charge',10,2);
            $table->enum('status',['active','inactive'])->default('active');
            // Define foreign key relationship with the users table
            $table->foreign('region_id')->references('id')->on('regions')->onDelete('cascade');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('region_delivery_charges');
    }
};
