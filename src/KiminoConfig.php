<?php

namespace Danielrhodeswarp\KiminoConfig;

use \Illuminate\Database\Eloquent\Model;

//an Eloquent model
class KiminoConfig extends Model
{
	//getting "no updated_at field" errors without this even though no timestamps() in the migration??
	public $timestamps = false;

    //scope a query to only return settings matching the given prefix
    public function scopeWithPrefix($query, $prefix)
    {
    	return $query->where('name', 'LIKE', "{$prefix}_%");
    }
	
	//oh dear. but I couldn't get a custom Blade extension to work
	//(due to it not liking temporary variables being set in the returned PHP code)
	public function toHtml()
	{
		//render as radio button if a range of valid values is set
        //ELSE text input
		
		if(is_null($this->valid_values))
		{
			return "<input type='text' name='setting[{$this->setting}]' value='" . e($this->value) . "' id='{$this->setting}'>";
		}
		
		else
		{
			$html = '';
			
			foreach(explode(',', $this->valid_values) as $validValue)
			{
				$checked = '';
				if($validValue == $this->value)
				{
					$checked = 'checked="checked"';
				}
				
				$oneRadio = "<label for='{$this->setting}_{$validValue}'>{$validValue}</label>";
				$oneRadio .= "<input type='radio' name='setting[{$this->setting}]' value='{$validValue}' id='{$this->setting}_{$validValue}' {$checked}>";
				
				$html .= $oneRadio;
			}
			
			return $html;
		}
	}
}
