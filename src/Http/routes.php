<?php

/**
 * Routing for HTML view and form submission
 * 
 * @package    kimino-config (https://github.com/danielrhodeswarp/kimino-config)
 * @author     Daniel Rhodes <daniel.rhodes@warpasylum.co.uk>
 * @copyright  Copyright (c) 2016 Daniel Rhodes
 * @license    see LICENCE file in source code root folder     The MIT License
 */

//show view page / update form
Route::get('kimino-config', 'Danielrhodeswarp\KiminoConfig\Http\Controllers\KiminoConfigController@getSettings');

//update form submission
Route::post('kimino-config', 'Danielrhodeswarp\KiminoConfig\Http\Controllers\KiminoConfigController@postSettings');