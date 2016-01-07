<?php

namespace Danielrhodeswarp\KiminoConfig\Http\Controllers;

use \Illuminate\Routing\Controller as Controller;
use \Illuminate\Http\Request;
use Danielrhodeswarp\KiminoConfig\KiminoConfig;
//use \DB;

class KiminoConfigController extends Controller	//TODO extend from main app's BaseController if we have that
{
	public function getSettings()
	{
		try
		{
			$settings = KiminoConfig::all()->sortBy('setting');
			//$settings = DB::table('kimino_configs')->orderBy('setting', 'asc');
			//DUMP($settings);
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
		
		//return "this is kimino config";
		return view('kimino-config::get-settings', ['groupedSettings' => $groupedSettings]);
	}

	public function postSettings(Request $request)
	{
		foreach($request->input('setting') as $setting => $value)
		{
			$config = KiminoConfig::where('setting', $setting)->first();
			
			$config->value = $value;
			
			$config->save();
		}
		
		return redirect()->action('\Danielrhodeswarp\KiminoConfig\Http\Controllers\KiminoConfigController@getSettings');
	}
}
