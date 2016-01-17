{{--
 **
 * Foundation (6) view for Kimino Config
 * 
 * @package    kimino-config (https://github.com/danielrhodeswarp/kimino-config)
 * @author     Daniel Rhodes <daniel.rhodes@warpasylum.co.uk>
 * @copyright  Copyright (c) 2016 Daniel Rhodes
 * @license    see LICENCE file in source code root folder     The MIT License
 *
 --}}

<!doctype html>
<html class="no-js" lang="en">
  <head>
    <meta charset="utf-8" />
    <meta http-equiv="x-ua-compatible" content="ie=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Kimino Config</title>
    <link rel="stylesheet" href="{{ asset('vendor/kimino-config/foundation/css/foundation.min.css') }}" />
  </head>
  <body>

    <div class="row">
      <div class="large-12 columns">
        <h1>Kimino Config</h1>
      </div>
    </div>

    @if (session('message'))
        <p>{{ session('message') }}</p>
    @endif

    <div class="row">
      <div class="large-9 small-12 columns">
        
        
        
        @if (empty($groupedSettings))
            <p>No settings found. This is probably A Bad Thing.
            (For development you can seed some samples with "php artisan kimino:seed-examples".)</p>
        @else
            <form action="{{ action('\Danielrhodeswarp\KiminoConfig\Http\Controllers\KiminoConfigController@postSettings') }}" method="post">
                @foreach ($groupedSettings as $group => $settings)
                    <fieldset class="fieldset">
                    
                    <legend>{{ ucfirst($group) }}</legend>
                    @foreach ($settings as $setting) 
                        <div class="row">
                          <div class="large-12 columns">
                            <label for="{{ $setting->setting }}">{{ $setting->setting }}</label>
                            {!! $setting->toHtml() !!}
                            <p class="help-text">{{ $setting->user_hint }}</p>
                          </div>
                          </div>
                    @endforeach
                    </fieldset>
                @endforeach
                {{ csrf_field() }}
                <button type="submit" class="button float-right">Update</button>
            </form>
        @endif
        
      </div>

      
    </div>

    <script src="{{ asset('vendor/kimino-config/foundation/js/vendor/jquery.min.js') }}"></script>
    <script src="{{ asset('vendor/kimino-config/foundation/js/vendor/what-input.min.js') }}"></script>
    <script src="{{ asset('vendor/kimino-config/foundation/js/foundation.min.js') }}"></script>
    {{--<script src="{{ asset('vendor/kimino-config/foundation/js/app.js') }}"></script>--}}
  </body>
</html>
