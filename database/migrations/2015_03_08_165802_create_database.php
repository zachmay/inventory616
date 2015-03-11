<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateDatabase extends Migration {

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('buildings', function(Blueprint $table)
        {
            $table->increments('id');
            $table->string('name');
            $table->string('description');
            $table->timestamps();
        });

        Schema::create('rooms', function(Blueprint $table)
        {
            $table->increments('id');
            $table->string('name');
            $table->string('description');
            $table->integer('building_id')->unsigned();
            $table->timestamps();

            $table->foreign('building_id')->references('id')->on('buildings');
        });

        Schema::create('item_types', function(Blueprint $table)
        {
            $table->increments('id');
            $table->string('name');
            $table->timestamps();
        });

        Schema::create('items', function(Blueprint $table)
        {
            $table->increments('id');
            $table->string('asset_tag');
            $table->string('name');
            $table->string('funding_source');
            $table->integer('item_type_id')->unsigned();
            $table->string('model');
            $table->string('cpu');
            $table->string('ram');
            $table->string('hard_disk');
            $table->string('os');
            $table->boolean('administrator_flag');
            $table->boolean('teacher_flag');
            $table->boolean('student_flag');
            $table->boolean('institution_flag');
            $table->timestamps();

            $table->unique('asset_tag');
            $table->foreign('item_type_id')->references('id')->on('item_types');
        });

        Schema::create('check_ins', function(Blueprint $table)
        {
            $table->increments('id');
            $table->integer('room_id')->unsigned();
            $table->integer('item_id')->unsigned();
            $table->timestamps();

            $table->foreign('room_id')->references('id')->on('rooms');
            $table->foreign('item_id')->references('id')->on('items');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('check_ins');
        Schema::drop('items');
        Schema::drop('item_types');
        Schema::drop('rooms');
        Schema::drop('buildings');
    }
}
