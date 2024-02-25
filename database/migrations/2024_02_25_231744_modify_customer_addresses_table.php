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
        Schema::table('customer_addresses', function (Blueprint $table) {
            // Drop the existing 'state' column
            $table->dropColumn('state');

            // Add the 'region_id' column
            $table->unsignedBigInteger('region_id')->nullable();

            // Add foreign key constraint
            $table->foreign('region_id')->references('id')->on('regions')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
            $table->string('state')->nullable();
            $table->dropForeign(['region_id']);
            $table->dropColumn('region_id');
    }
};
