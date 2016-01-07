<h1>This is "get-settings" for kimino config</h1>

@if (empty($groupedSettings))
    <p>No settings found. This is probably A Bad Thing.
    (For development you can seed some samples with "php artisan kimino:seed-examples".)</p>
@else
    <form action="{{ action('\Danielrhodeswarp\KiminoConfig\Http\Controllers\KiminoConfigController@postSettings') }}" method="post">
        @foreach ($groupedSettings as $group => $settings)
            <fieldset>
            
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
        <input type="submit" value="Update settings">
    </form>
@endif


    


