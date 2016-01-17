<?php

namespace Danielrhodeswarp\KiminoConfig\Http\Controllers;

use Illuminate\Routing\Controller as Controller;
use Illuminate\Http\Request;

use Danielrhodeswarp\KiminoConfig\KiminoConfig;

/**
 * Controller for viewing and updating settings
 * 
 * @package    kimino-config (https://github.com/danielrhodeswarp/kimino-config)
 * @author     Daniel Rhodes <daniel.rhodes@warpasylum.co.uk>
 * @copyright  Copyright (c) 2016 Daniel Rhodes
 * @license    see LICENCE file in source code root folder     The MIT License
 */
class KiminoConfigController extends Controller	//TODO extend from main app's BaseController if we have that
{
	/**
	 * Show view page / update form for settings
	 *
	 * @author Daniel Rhodes <daniel.rhodes@warpasylum.co.uk>
	 */
	public function getSettings()
	{
		try
		{
			$settings = KiminoConfig::all()->sortBy('setting');
		}
		
		catch(\Exception $exception)
		{
			abort(500, "Ooops-a-bobbles. Something goofed.
				I probably couldn't find the kimino_configs database table.
				Did you run the migration file supplied in the kimino-config package?
				(php artisan migrate --path=vendor/danielrhodeswarp/kimino-config/src/database/migrations)");
		}
		
		//group settings by prefix
		$groupedSettings = [];
		
		foreach($settings as $setting)
		{
			$prefix = explode('_', $setting->setting)[0];
			
			$groupedSettings[$prefix][] = $setting;
		}
		
		//return appropriate "kimino config" page
		$viewName = config('kimino.frontend') . '.get-settings';
		return view("kimino-config::{$viewName}", ['groupedSettings' => $groupedSettings]);
	}

	/**
	 * Handle form submit from view page / update form for settings
	 *
	 * @author Daniel Rhodes <daniel.rhodes@warpasylum.co.uk>
	 */
	public function postSettings(Request $request)
	{
		foreach($request->input('setting') as $setting => $value)
		{
			//first() is safe here as "setting" column is UNIQUE in the database
			$config = KiminoConfig::where('setting', $setting)->first();
			
			$config->value = $value;
			
			$config->save();
		}
		
		//redirect to "kimino config" page
		//TODO get flashed session messages working (prob something to do with routing groups and middleware etc)
		return redirect()->action('\Danielrhodeswarp\KiminoConfig\Http\Controllers\KiminoConfigController@getSettings')->with('message', 'Settings updated successfully');
	}
}
