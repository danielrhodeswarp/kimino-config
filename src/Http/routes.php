<?php

Route::get('kimino-config', 'Danielrhodeswarp\KiminoConfig\Http\Controllers\KiminoConfigController@getSettings');
Route::post('kimino-config', 'Danielrhodeswarp\KiminoConfig\Http\Controllers\KiminoConfigController@postSettings');