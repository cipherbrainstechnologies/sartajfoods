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
        Schema::table('banners', function (Blueprint $table) {
            $table->after('link', function($table){
                $table->enum('ad_section',['slider_ad_banner','best_seller_ad','left_section_ad','right_section_ad'])->nullable()->comment('Slider_ad_banner', 'best_seller_ad', 'left_section_ad', 'right_section_ad');
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
        Schema::table('banners', function (Blueprint $table) {
            $table->dropColumn('ad_section');
        });
    }
};
