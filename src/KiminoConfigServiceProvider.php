<?php

namespace Danielrhodeswarp\KiminoConfig;

use Illuminate\Support\ServiceProvider;
use Blade;

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

        //make views publishable
        $this->publishes([
            __DIR__ . '/resources/views' => resource_path('views/vendor/kimino-config'),
        ]);
        
        //our Blade extensions
        //interestingly, and I guess this is an anti-eval technique, $kiminoConfig
        //will be a literal '($setting)' here. So use with() helper judiciously!
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
    }
}