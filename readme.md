# Kimino Config

## Sounds weird. What is it?

It's a Laravel 5 package to quickly enable database setting and getting of administrator
or application settings; settings that *might* be tinkered with during the lifetime of an application.

## What does it come with?

* Migration for the settings table (you need to run this)
* Optional Artisan command to seed some example settings
* Controller, routing and view to see and update settings on an HTML form
* Above form view is publishable
* Artisan command to view all / certain settings
* Artisan command to update a setting

## NOTE

Kimino Config purposefully does not interact with Laravel's config/ folder or dotenv stuff.
I see those settings as more infrastructural / servery / DevOpsish.

## TODO

* DOCUMENTATION!
* Config for setting name separator
* Check for BaseController of main including app (for security gates etc)
* Prettier form view (Bootstrap and / or Foundation etc etc)
* General code cleanup
