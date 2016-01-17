<?php

namespace Danielrhodeswarp\KiminoConfig;

use Illuminate\Database\Eloquent\Model;

/**
 * Model - and the only *actual* class in the package - representing a KiminoConfig
 * 
 * @package    kimino-config (https://github.com/danielrhodeswarp/kimino-config)
 * @author     Daniel Rhodes <daniel.rhodes@warpasylum.co.uk>
 * @copyright  Copyright (c) 2016 Daniel Rhodes
 * @license    see LICENCE file in source code root folder     The MIT License
 */
class KiminoConfig extends Model	//NOTE this is an Eloquent model
{
	/**
	 * getting "no updated_at field" errors without this (even though no timestamps() in the migration??) 
	 *
	 * @var boolean
	 */
	public $timestamps = false;

	/**
	 * Get the value (and only the value) of the specified setting,
	 * returning NULL if the specified setting does not exist
	 *
	 * @param string $name name of setting
	 *
	 * @return string|null value of specified setting. Or null if setting not exist.
	 */
	public static function getSetting($name)
	{
		//first() is safe here as "setting" column is UNIQUE in the database
		$setting = KiminoConfig::where('setting', $name)->first();
		
		if(is_null($setting))
		{
			return null;
		}
		
		return $setting->value;
	}
	
    /**
	 * scope a query to only return settings whose name matches the given prefix
	 *
	 * @return \Illuminate\Database\Eloquent\Builder
	 */
	public function scopeWithPrefix($query, $prefix)
    {
    	return $query->where('name', 'LIKE', "{$prefix}_%");
    }
	
	/**
	 * Get HTML to render setting on a form
	 *
	 * oh dear. but I couldn't get a custom Blade directive to work
	 * (due to it not liking temporary variables being set in the returned PHP code)
	 *
	 * @return string <input> HTML to render setting on a form
	 */
	public function toHtml()
	{
		//rules:
		//render as radio button if a range of valid values is set
        //ELSE text input
		
		if(is_null($this->valid_values))	//text input
		{
			return "<input type='text' name='setting[{$this->setting}]' value='" . e($this->value) . "' id='{$this->setting}'>";
		}
		
		else	//radio button
		{
			$html = '';
			
			foreach(explode(',', $this->valid_values) as $validValue)
			{
				$checked = '';
				if($validValue == $this->value)
				{
					$checked = 'checked="checked"';
				}
				
				
				$oneRadio = "<input type='radio' name='setting[{$this->setting}]' value='{$validValue}' id='{$this->setting}_{$validValue}' {$checked}>";
				$oneRadio .= "<label for='{$this->setting}_{$validValue}'>{$validValue}</label>";
				
				$html .= $oneRadio;
			}
			
			return $html;
		}
	}
}
