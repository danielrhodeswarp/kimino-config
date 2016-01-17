<?php

namespace Danielrhodeswarp\KiminoConfig\Console\Commands;

use Illuminate\Console\Command;

use Danielrhodeswarp\KiminoConfig\KiminoConfig;

/**
 * Artisan command to set a new value for a specific setting
 * 
 * @package    kimino-config (https://github.com/danielrhodeswarp/kimino-config)
 * @author     Daniel Rhodes <daniel.rhodes@warpasylum.co.uk>
 * @copyright  Copyright (c) 2016 Daniel Rhodes
 * @license    see LICENCE file in source code root folder     The MIT License
 */
class SetConfig extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'kimino:set-config {setting} {value?}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Set a configuration value';

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        //get the input
        $setting = $this->argument('setting');
        $value = $this->argument('value');  //will be null if not passed
        
        //----exception catching for if table not exist etc
        try
        {
            $firstSetting = KiminoConfig::first();
        }
        
        catch(\Exception $exception)
        {
            $this->error("Oh dear, something went wrong. I probably couldn't find the kimino_configs database table. Did you run the migration file supplied in the kimino-config package? (php artisan migrate --path=vendor/danielrhodeswarp/kimino-config/src/database/migrations)");
            exit(1);    //non-zero
        }
        //----/end exception catching for if table not exist etc
        
        //----attempt to get specified setting
        try
        {
            $config = KiminoConfig::where('setting', $setting)->firstOrFail();
        }
        
        catch(\Illuminate\Database\Eloquent\ModelNotFoundException $exception)
        {
            $this->error("Could not find Kimino Config with name [{$setting}]");
            exit(1);    //non-zero
        }
        //----/end attempt to get specified setting
        
        //----handle if $value has already been passed
        
        //if a $value has been passed, and config has valid_values, $value must be in range
        if(!is_null($value) and !is_null($config->valid_values))
        {
            $valids = explode(',', $config->valid_values);
            
            if(!in_array($value, $valids))
            {
                $this->error("Value for Kimino Config [{$setting}] must be one of: {$config->valid_values}");
                exit(1);
            }
        }
        
        //now, for both free text and restricted settings, we can safely set to $value
        if(!is_null($value))
        {
            $config->value = $value;
            $config->save();
            
        }
        //----/end handle if $value has already been passed
        
        //----handle if $value has *not* been passed in (ie. prompt for it accordingly)
        else
        {
            //politeness reminder about *current* value
            $this->info("Current value of Kimino Config [$setting] is: {$config->value}");
            
            //simple prompt for free text settings
            if(is_null($config->valid_values))
            {
                $value = $this->ask('New value to set?');
            }
            
            else    //restricted setting so plug our valid_values into anticipate(), BUT we must still check for a match!
            {
                $valids = explode(',', $config->valid_values);
                
                $value = $this->anticipate("New value to set? (Must be one of: {$config->valid_values})", explode(',', $config->valid_values));
                
                if(!in_array($value, $valids))
                {
                    $this->error("Value for Kimino Config [{$setting}] must be one of: {$config->valid_values}");
                    exit(1);
                }
            }
            
            //safe to save now
            $config->value = $value;
            $config->save();
        }
        //----handle if $value has *not* been passed in (ie. prompt for it accordingly)
        
        //something will have been set by now so report that
        $this->info("Set value of Kimino Config [{$setting}] to: {$value}");
    }
}
