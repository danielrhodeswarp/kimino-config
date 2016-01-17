<?php

namespace Danielrhodeswarp\KiminoConfig;

use Illuminate\Support\ServiceProvider;
use Blade;

/**
 * Laravel ServiceProvider to glue KiminoConfig's services into the main project
 * 
 * @package    kimino-config (https://github.com/danielrhodeswarp/kimino-config)
 * @author     Daniel Rhodes <daniel.rhodes@warpasylum.co.uk>
 * @copyright  Copyright (c) 2016 Daniel Rhodes
 * @license    see LICENCE file in source code root folder     The MIT License
 */
class KiminoConfigServiceProvider extends ServiceProvider
{
    /**
     * Perform post-registration booting of services.
     *
     * @return void
     */
    public function boot()
    {
        //the routes
        require __DIR__ . '/Http/routes.php';

        //the views
        $this->loadViewsFrom(__DIR__ . '/resources/views', 'kimino-config');

        //make views publishable (and give specific tag of 'views')
        $this->publishes([
            __DIR__ . '/resources/views' => resource_path('views/vendor/kimino-config'),
        ], 'views');
        
        //make config publishable (and give specific tag of 'config')
        $this->publishes([
            __DIR__ . '/config/kimino.php' => config_path('kimino.php'),
        ], 'config');
        
        //make public assets publishable (and give specific tag of 'public')
        $this->publishes([
            __DIR__ . '/public' => public_path('vendor/kimino-config'),
        ], 'public');
        
        //NOTE we can actually make migrations publishable as well
        
        //NOTE not in use and not working
        //our Blade extensions
        //interestingly, and I guess this is an anti-eval technique, $kiminoConfig
        //will be a literal '($setting)' here. So use the with() helper judiciously!
        Blade::directive('kiminoinput', function($kiminoConfig) {
            
            //render as radio button if a range of valid values is set
            //ELSE text input
            $php = <<<PHP
<?php            
            
            if(is_null(with{$kiminoConfig}->valid_values))
            {
                echo '<input type="text" name="' . with{$kiminoConfig}->setting . '" value="' . with{$kiminoConfig}->value . '">';
            }
            
            else
            {
                foreach(explode(',', with{$kiminoConfig}->valid_values as $valid)
                {
                    $checked = '';
                    if($valid == with{$kiminoConfig}->value)
                    {
                        $checked = 'checked="checked"';
                    }
                
                    echo '<input type="radio" name="' . with{$kiminoConfig}->setting . '" value="' . $valid . '" . $checked . '>';
                }
            }
            
?>            
PHP;

            return $php;
        });
        
        
    }

    /**
     * Register any package services.
     *
     * @return void
     */
    public function register()
    {
        //the Artisan commands
        $this->commands([\Danielrhodeswarp\KiminoConfig\Console\Commands\SetConfig::class,
            \Danielrhodeswarp\KiminoConfig\Console\Commands\GetConfig::class,
            \Danielrhodeswarp\KiminoConfig\Console\Commands\SeedExamples::class,
            ]);
        
        //let 'em override *individual* things in their copy of the kimino.php config file
        $this->mergeConfigFrom(
            __DIR__ . '/config/kimino.php', 'kimino'    //not actually sure what the second parm is for here...
        );
    }
}