<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

/**
 * Migration for the kimino_configs table
 * 
 * @package    kimino-config (https://github.com/danielrhodeswarp/kimino-config)
 * @author     Daniel Rhodes <daniel.rhodes@warpasylum.co.uk>
 * @copyright  Copyright (c) 2016 Daniel Rhodes
 * @license    see LICENCE file in source code root folder     The MIT License
 */
class CreateKiminoConfigsTable extends Migration
{
    /**
     * Run the migrations to create kimino_configs table
     *
     * @author Daniel Rhodes <daniel.rhodes@warpasylum.co.uk>
     *
     * @return void
     */
    public function up()
    {
        Schema::create('kimino_configs', function (Blueprint $table) {
            $table->increments('id');
            $table->string('setting', 50)->unique();    //setting name
            $table->string('value', 300);   //to allow nulls or not?
            $table->string('valid_values', 200)->nullable()->default(null); //Comma-separated (no spaces) value restrictions for the setting. If empty, then setting is free text.
            $table->string('user_hint', 300)->nullable();   //for humans to read and understand the setting
        });
    }

    /**
     * Reverse the migrations to create kimino_configs table
     *
     * @author Daniel Rhodes <daniel.rhodes@warpasylum.co.uk>
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('kimino_configs');
    }
}