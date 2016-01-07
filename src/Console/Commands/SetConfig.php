<?php

namespace Danielrhodeswarp\KiminoConfig\Console\Commands;

use \Illuminate\Console\Command;
use Danielrhodeswarp\KiminoConfig\KiminoConfig;

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
        $setting = $this->argument('setting');
        $value = $this->argument('value');  //will be null if not passed
        
        //TODO exception catching for if table not exist etc
        
        try
        {
            $config = KiminoConfig::where('setting', $setting)->firstOrFail();
        }
        
        catch(\Illuminate\Database\Eloquent\ModelNotFoundException $exception)
        {
            $this->error("Could not find Kimino Config with name [{$setting}]");
            exit(1);    //non-zero
        }
        
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
        
        //if $value has been passed, we can safely set it now
        if(!is_null($value))
        {
            $config->value = $value;
            $config->save();
            
        }
        
        else
        {
            //prompt to set new value
            $this->info("Current value of Kimino Config [$setting] is: {$config->value}");
            
            if(is_null($config->valid_values))
            {
                $value = $this->ask('New value to set?');
            }
            
            else
            {
                $valids = explode(',', $config->valid_values);
                
                $value = $this->anticipate("New value to set? (Must be one of: {$config->valid_values})", explode(',', $config->valid_values));
                
                if(!in_array($value, $valids))
                {
                    $this->error("Value for Kimino Config [{$setting}] must be one of: {$config->valid_values}");
                    exit(1);
                }
            }
            
            $config->value = $value;
            $config->save();
        }
        
        $this->info("Set value of Kimino Config [{$setting}] to: {$value}");
    }
}
