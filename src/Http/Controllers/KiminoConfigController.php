<?php

namespace Danielrhodeswarp\KiminoConfig\Http\Controllers;

class KiminoConfigController extends \Illuminate\Routing\Controller
{
	public function getSettings()
	{
		//return "this is kimino config";
		return view('kimino-config::get-settings');
	}

	public function postSettings()
	{

	}
}
