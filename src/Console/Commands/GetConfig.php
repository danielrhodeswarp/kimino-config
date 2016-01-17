<?php

namespace Danielrhodeswarp\KiminoConfig\Console\Commands;

use Illuminate\Console\Command;

use Danielrhodeswarp\KiminoConfig\KiminoConfig;

/**
 * Artisan command to dump out a specific - or all - settings
 * 
 * @package    kimino-config (https://github.com/danielrhodeswarp/kimino-config)
 * @author     Daniel Rhodes <daniel.rhodes@warpasylum.co.uk>
 * @copyright  Copyright (c) 2016 Daniel Rhodes
 * @license    see LICENCE file in source code root folder     The MIT License
 */
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
        //get the input
        $setting = $this->argument('setting');
        
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
        
        //----get setting(s)
        
        //get all if $setting is null
        $settingsToShow = '';
        if(is_null($setting))
        {
            $settingsToShow = KiminoConfig::all()->sortBy('setting');
        }
        
        else    //try to get specified setting
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
        //----/end get setting(s)
        
        //----dump setting(s) out as table
        $dataRows = [];

        foreach ($settingsToShow as $oneSetting) {
            $dataRows[] = [$oneSetting->setting, $oneSetting->value, $oneSetting->valid_values];
            //TODO possibly also show user_hint based on --verbose
        }
        
        $this->table(
            ['Setting', 'Value', 'Valid values'],
            $dataRows
        );
        //----/end dump setting(s) out as table
    }
}
