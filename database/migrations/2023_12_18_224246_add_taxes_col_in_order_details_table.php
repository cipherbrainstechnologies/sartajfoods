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
        Schema::table('order_details', function (Blueprint $table) {
                $table->decimal('eight_percent_tax', 8, 2)->after('tax_amount')->nullable()->default(0.00);
                $table->decimal('ten_percent_tax', 8, 2)->after('eight_percent_tax')->nullable()->default(0.00);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('order_details', function (Blueprint $table) {
           $table->dropColumn('eight_percent_tax');
           $table->dropColumn('ten_percent_tax');
        });
    }
};
