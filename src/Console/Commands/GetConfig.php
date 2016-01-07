<?php

namespace Danielrhodeswarp\KiminoConfig\Console\Commands;

use \Illuminate\Console\Command;
use Danielrhodeswarp\KiminoConfig\KiminoConfig;

class GetConfig extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'kimino:get-config {setting?}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Get configuration value(s)';

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $setting = $this->argument('setting');
        
        //TODO exception catching for if table not exist etc
        
        //get all if $setting is null
        $settingsToShow = '';
        if(is_null($setting))
        {
            $settingsToShow = KiminoConfig::all();//->orderBy('setting', 'asc');
        }
        
        else
        {
            try
            {
                $settingsToShow = collect([KiminoConfig::where('setting', $setting)->firstOrFail()]);
            }
            
            catch(\Illuminate\Database\Eloquent\ModelNotFoundException $exception)
            {
                $this->error("Could not find Kimino Config with name [{$setting}]");
                exit(1);    //non-zero
            }
        }
        
        //DUMP(get_class($settingsToShow));
        
        //summary report
        $dataRows = [];

        foreach ($settingsToShow as $oneSetting) {
            $dataRows[] = [$oneSetting->setting, $oneSetting->value, $oneSetting->valid_values];
            //do summat with user_hint?
        }
        
        $this->table(
            ['Setting', 'Value', 'Valid values'],
            $dataRows
        );
        
    }
}
