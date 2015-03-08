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
        });

        Schema::create('rooms', function(Blueprint $table)
        {
            $table->increments('id');
            $table->string('name');
            $table->string('description');
            $table->integer('building_id')->unsigned();

            $table->foreign('building_id')->references('id')->on('buildings');
        });

        Schema::create('item_types', function(Blueprint $table)
        {
            $table->increments('id');
            $table->string('name');
        });

        Schema::create('attribute_types', function(Blueprint $table)
        {
            $table->increments('id');
            $table->string('name');
            $table->enum('input', ['text', 'numeric', 'checkbox']);
        });

        Schema::create('item_type_attributes', function (Blueprint $table)
        {
            $table->increments('id');
            $table->integer('item_type_id')->unsigned();
            $table->integer('attribute_type_id')->unsigned();

            $table->foreign('item_type_id')->references('id')->on('item_types');
            $table->foreign('attribute_type_id')->references('id')->on('attribute_types');
        });

        Schema::create('items', function(Blueprint $table)
        {
            $table->increments('id');
            $table->string('name');
            $table->integer('item_type_id')->unsigned();
            $table->datetime('deleted_at');

            $table->foreign('item_type_id')->references('id')->on('item_types');
        });

        Schema::create('item_attributes', function(Blueprint $table)
        {
            $table->increments('id');
            $table->string('value');
            $table->integer('item_id')->unsigned();
            $table->integer('attribute_type_id')->unsigned();

            $table->foreign('item_id')->references('id')->on('items');
            $table->foreign('attribute_type_id')->references('id')->on('attribute_types');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('item_attributes');
        Schema::drop('items');
        Schema::drop('item_type_attributes');
        Schema::drop('attribute_types');
        Schema::drop('item_types');
        Schema::drop('rooms');
        Schema::drop('buildings');
    }
}
