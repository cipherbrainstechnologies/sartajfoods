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
        Schema::table('carts', function (Blueprint $table) {
            $table->after('quantity', function($table){
                $table->decimal('special_price', 10, 2)->nullable();
                $table->decimal('discount', 10, 2)->default('0');
                $table->enum('discount_type',['percent','amount'])->default('amount');
                
            });
            $table->after('price',function($table){
                $table->decimal('sub_total', 10, 2)->default('0');
            });
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('carts', function (Blueprint $table) {
            
            $table->dropColumn('special_price');
            $table->dropColumn('discount');
            $table->dropColumn('discount_type');
            $table->dropColumn('sub_total');
        });
    }
};
