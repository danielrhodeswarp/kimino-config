{{--
 **
 * Vanilla view for Kimino Config
 * 
 * @package    kimino-config (https://github.com/danielrhodeswarp/kimino-config)
 * @author     Daniel Rhodes <daniel.rhodes@warpasylum.co.uk>
 * @copyright  Copyright (c) 2016 Daniel Rhodes
 * @license    see LICENCE file in source code root folder     The MIT License
 *
 --}}

<!doctype html>
<html>
<head>
    <title>Kimino Config</title>
</head>
<body>
<h1>Kimino Config</h1>

@if (session('message'))
    <p>{{ session('message') }}</p>
@endif

@if (empty($groupedSettings))
    <p>No settings found. This is probably A Bad Thing.
    (For development you can seed some samples with "php artisan kimino:seed-examples".)</p>
@else
    <form action="{{ action('\Danielrhodeswarp\KiminoConfig\Http\Controllers\KiminoConfigController@postSettings') }}" method="post">
        @foreach ($groupedSettings as $group => $settings)
            <fieldset style="width:80%; margin-bottom:1em;">
            
            <legend>{{ ucfirst($group) }}</legend>
            @foreach ($settings as $setting) 
                <div>
                    <label for="{{ $setting->setting }}">{{ $setting->setting }}:</label>
                    {!! $setting->toHtml() !!}
                    <span style="font-size:smaller;">{{ $setting->user_hint }}</span>
                </div>
            @endforeach
            </fieldset>
        @endforeach
        {{ csrf_field() }}
        <div style="width:80%;">
            <input type="submit" value="Update" style="float:right;">
        </div>
    </form>
@endif
</body>
</html>
