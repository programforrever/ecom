<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class EnsureProductThumbnailImgColumn extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('products', function (Blueprint $table) {
            // Asegurar que la columna thumbnail_img exista
            if (!Schema::hasColumn('products', 'thumbnail_img')) {
                $table->string('thumbnail_img', 100)->nullable()->after('photos');
            }
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('products', function (Blueprint $table) {
            if (Schema::hasColumn('products', 'thumbnail_img')) {
                $table->dropColumn('thumbnail_img');
            }
        });
    }
}
