# Kimino Config

## Sounds weird. What is it?

It's a Laravel 5 package to quickly enable database setting and getting of administrator
or application level settings; settings that *might* be tinkered with during the lifetime of an application.

## What is it good for?

It's good for implementation preparedness around project specification that you sense - or know - will change in future. Also good for trialling different algorithms. Also good for experimenting with implementation or design specification that can't quite be decided by everyone. Also good for rulepacks.

## So what's new in v0.0.5?

* Documentation
* Improved views
* Commented and cleaned up the code somewhat

## How do I install this thing?

1. `composer require danielrhodeswarp/kimino-config` in your Laravel 5 project's home folder. (Or you can add `danielrhodeswarp/kimino-config` to your project's composer.json file - and then run `composer install` - if you know what you are doing.)
2. In the `'providers'` bit of your project's config/app.php file add `Danielrhodeswarp\KiminoConfig\KiminoConfigServiceProvider::class`.
3. `php artisan migrate --path=vendor/danielrhodeswarp/kimino-config/src/database/migrations` to run the migration to create the kimino_configs table.

## How do I give it a quick check?

After installation, your-project.url/kimino-config will now show the only view that Kimino Config has - a page to view **all** the settings in the database and to edit any of their values if necessary.

You won't have any settings yet. As a test you can add some dummy settings with `php artisan kimino:seed-examples`.

`php artisan kimino:get-config` will dump out all of the settings, something like:

| Setting  | Value | Valid values |
| ------------- | ------------- | ------------- |
| something_trial_months  | 6  |            |
| something_auth_method  | digest  | basic,digest           |
| other_news | no | yes,no |
| other_leg | arbitrary-value | |

`php artisan kimino:get-config other_news`  will dump out the specified setting, like:

| Setting  | Value | Valid values |
| ------------- | ------------- | ------------- |
| other_news | no | yes,no |

`php artisan kimino:set-config other_leg` will prompt for - and set if valid - a new value for the specified setting.






## What do these settings look like in the database?

Settings have four fields in the database (and also an auto-incrementing id field):

Field | Can be null? | Description
-- | -- | --
setting | no | Name of setting. Words separated by underscores and must contain at least one underscore. Like `something_or_other`. Settings are grouped on the HTML form by *prefix* which is the word before the first underscore.
value | no (but?) | Value of setting. Will be empty string, any string or - if valid_values is not empty - one of the strings in valid_values
valid_values | yes | If empty, then the setting is free text. If set to a comma-separated (no spaces) list of words, then the setting value can only be one of those words (and this will be enforced when updating values with the HTML form or the Artisan command).
user_hint | yes | A human friendly explanation of the setting for whosoever might be tinkering with it on the HTML form

## How do I add my own settings?

To add a basic free text setting you simply need to add a new database entry containing the setting name with relevant prefix in *setting* and the current or default value for the setting in *setting*. I would always recommend adding a nice, descriptive hint in *user_hint* at setting creation time too!

To add a setting with a restricted set of values, for example "queue emails: yes or no" or "heading font: courier, verdana or times", follow the same steps as above but also put the value restrictions in *valid_values* like `yes,no` or `courier,verdana,times` (respectively).

Note that, by design, there is no programmatic way to add a new setting. You can of course use a Laravel seed (created with, say, `php artisan make:seeder SeedMyKiminos`) something like:

```
<?php

//this is YourProject/database/seeds/SeedMyKiminos.php

use Illuminate\Database\Seeder;

use Danielrhodeswarp\KiminoConfig\KiminoConfig;

class SeedMyKiminos extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        //some settings that we want
        $settings = [
            'sitebot_hometown' => [
                'value' => 'Parts Unknown',
                'valid_values' => null,
                'user_hint' => 'Where should we say that Sitebot comes from?'
            ],
            'sitebot_temperament' => [
                'value' => 'cranky',
                'valid_values' => 'cranky,irritable,curmudgeonly,peevish,churlish',
                'user_hint' => 'What sort of mood is Sitebot in?'
            ],
            //etc
        ];
        
        //loop through and add to DB (via Eloquent)
        foreach ($settings as $name => $setting) {
            $config = new KiminoConfig();
            
            $config->setting = $name;
            $config->value = $setting['value'];
            $config->valid_values = $setting['valid_values'];
            $config->user_hint = $setting['user_hint'];
            
            $config->save();
        }
    }
}
```

This seed can be run with `php artisan db:seed --class=SeedMyKiminos` which will add the settings to the database.

## How do I use this in my project?

Here's a simple example:

```
<?php

//this is a controller, model, command etc in your project

use Danielrhodeswarp\KiminoConfig\KiminoConfig;

.
.
.

if(KiminoConfig::getSetting('other_news') == 'yes')
{
    showOtherNews();
}

else
{
    Log::debug('Other news not shown');
}
```

Note that KiminoConfig::getSetting('some-setting') will return NULL if some-setting does not exist in the database (hence setting values themselves can't be NULL). So, here is a more robust example:

```
<?php

//this is a controller, model, command etc in your project

use Danielrhodeswarp\KiminoConfig\KiminoConfig;

.
.
.

$settingValue = KiminoConfig::getSetting('very_important_setting');

if(is_null($settingValue))
{
    abort(500, 'Bad things have happened');
}

veryImportantThing($settingValue);
```

Here's a cute way to handle settings that have some valid_values set:

```
<?php

//this is a controller, model, command etc in your project

use Danielrhodeswarp\KiminoConfig\KiminoConfig;

.
.
.

class SomeClassInYourProject
{
    //configure auth method based on saved setting
	public function handleSomethingAuthMethod()
	{
		$authMethod = KiminoConfig::getSetting('something_auth_method');
		
		//call appropriate method based on setting value
        $this->{"setSomethingAuthMethod_{$authMethod}"}();
    }
    
    //set auth method to BASIC
    private function setSomethingAuthMethod_basic()
	{
		fancyThingToSetBasicAuth();
	}
	
    //set auth method to DIGEST
	private function setSomethingAuthMethod_digest()
	{
		CoolStuffToSetDigestAuth();
	}
}
```

You can of course use a smelly old switch / case instead.
## What does it come with?

* Migration for the settings table (you need to run this!)
* Optional Artisan command to seed some example settings
* Controller, routing and view to see and update settings on an HTML form
* Above form view is publishable into your project
* Artisan command to view all / certain settings
* Artisan command to update a setting

## NOTE

Kimino Config purposefully does not interact with Laravel's config/ folder or dotenv stuff.
I see those settings as more infrastructural / servery / DevOpsish.

Can't *add* a setting automatically via form or console.

## TODO


* Config for setting name separator
* Config for include_prefix_on_html_form_view
* Check if actually working in Laravel 5.0 and 5.1 (5.2 definitely OK)
* Check for BaseController of main including app (for security gates etc)
* Prettier form view (Bootstrap and / or Foundation etc etc)
* General code cleanup

## Musings

* Namespacing issues around a db:seed in a Composer package for Laravel...
* Seemingly can't introduce $variables in the `return "<?php ... ?>"` bit of Blade::directive()
