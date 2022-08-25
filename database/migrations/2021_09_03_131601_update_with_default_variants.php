<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class UpdateWithDefaultVariants extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('items', function (Blueprint $table) {
            $table->integer('enable_system_variants')->default(0);
            $table->unsignedBigInteger('parent_id')->nullable();
            $table->integer('category_type')->default(1);
        });

        Schema::table('variants', function (Blueprint $table) {
            $table->integer('is_system')->default(0);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        //
    }
}
