<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateZipsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('zips', function (Blueprint $table) {
            $table->id();
            $table->string('zip');
            $table->string('lat');
            $table->string('lng');
            $table->string('city');
            $table->string('state_id');
            $table->string('state_name');
            $table->string('zcta');
            $table->string('parent_zcta')->nullable();
            $table->integer('population');
            $table->float('density');
            $table->integer('county_fips');
            $table->string('county_name');
            $table->string('county_weights');
            $table->string('county_names_all');
            $table->string('county_fips_all');
            $table->string('imprecise');
            $table->string('military');
            $table->string('timezone');
            $table->timestamp('created_at')->default(\DB::raw('CURRENT_TIMESTAMP'));
            $table->timestamp('updated_at')->default(\DB::raw('CURRENT_TIMESTAMP on update CURRENT_TIMESTAMP'));
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('zips');
    }
}
