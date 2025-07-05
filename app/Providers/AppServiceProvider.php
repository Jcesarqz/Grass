<?php

namespace App\Providers;
use Illuminate\Support\Facades\Response;
use Spatie\ArrayToXml\ArrayToXml;
use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\URL;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public const HOME = '/productos';

    public function register(): void
    {
        
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        Response::macro('format', function ($data, $status = 200) {
        $format = request()->header('Accept') ?? request()->format();

        if (str_contains($format, 'xml')) {
            return Response::xml($data, $status);
        }

        return response()->json($data, $status);
    });

    Response::macro('xml', function ($data, $status = 200, array $headers = []) {
        $array = is_array($data) ? $data : $data->toArray();
        $xml = ArrayToXml::convert(['item' => $array], 'response');
        return response($xml, $status, array_merge(['Content-Type' => 'application/xml'], $headers));
    });
        
        Response::macro('format', function ($data) {
            $format = request()->format() ?? request()->header('Accept');

            if (str_contains($format, 'xml')) {
                return response()->xml($data);
            }

            return response()->json($data);
        });

        Response::macro('xml', function ($data, $status = 200, array $headers = [], $rootElement = 'response') {
            $array = is_array($data) ? $data : $data->toArray();
            $xml = ArrayToXml::convert(['item' => $array], $rootElement);
            return response($xml, $status, array_merge(['Content-Type' => 'application/xml'], $headers));
        });

        if (config('app.env') === 'production') {
            URL::forceScheme('https');
        }
    }
}
