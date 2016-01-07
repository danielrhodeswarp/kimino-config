<?php

namespace Danielrhodeswarp\KiminoConfig\Console\Commands;

use \Illuminate\Console\Command;
use Danielrhodeswarp\KiminoConfig\KiminoConfig;

class SeedExamples extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'kimino:seed-examples';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Seed some example settings for Kimino Config';

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        //sample chunk of settings that we want
        $settings = [
            'something_trial_months' => [
                'value' => '6',
                'valid_values' => null,
                'user_hint' => 'How many months before account expires for new accounts?'
            ],
            'something_auth_method' => [
                'value' => 'digest',
                'valid_values' => 'basic,digest',
                'user_hint' => 'Basic is more compatible but digest is more secure'
            ],
            'other_news' => [
                'value' => 'no',
                'valid_values' => 'yes,no',
                'user_hint' => 'Show "In other news" bit on homepage?'
            ],
            'other_leg' => [
                'value' => 'arbitrary-value',
                'valid_values' => null,
                'user_hint' => 'Pull the other leg'
            ],


        ];
        
        foreach ($settings as $name => $setting) {
            $config = new KiminoConfig();
            
            $config->setting = $name;
            $config->value = $setting['value'];
            $config->valid_values = $setting['valid_values'];
            $config->user_hint = $setting['user_hint'];
            
            $config->save();
        }
        
        $this->info('All done');
    }
}
