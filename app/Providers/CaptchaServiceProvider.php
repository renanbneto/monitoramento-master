<?php

namespace App\Providers;

use App\Captcha\Captcha;
use Illuminate\Routing\Router;
use Illuminate\Support\ServiceProvider;
use Illuminate\Validation\Factory;

/**
 * Class CaptchaServiceProvider
 * @package app\Captcha
 */
class CaptchaServiceProvider extends ServiceProvider
{
    /**
     * Boot the service provider.
     *
     * @return void
     */
    public function boot(): void
    {
        // Publish configuration files
        $this->publishes([
            base_path().'/config/captcha.php' => config_path('captcha.php')
        ], 'config');

        // HTTP routing
        if (strpos($this->app->version(), 'Lumen') !== false) {
            /* @var Router $router */
            $router = $this->app;
            $router->get('captcha[/api/{config}]', 'App\Http\Controllers\CaptchaController@getCaptchaApi');
            $router->get('captcha[/{config}]', 'App\Http\Controllers\CaptchaController@getCaptcha');
        } else {
            /* @var Router $router */
            $router = $this->app['router'];
            if ((double)$this->app->version() >= 5.2) {
                $router->get('captcha/api/{config?}', 'App\Http\Controllers\CaptchaController@getCaptchaApi')->middleware('web');
                $router->get('captcha/{config?}', 'App\Http\Controllers\CaptchaController@getCaptcha')->middleware('web');
            } else {
                $router->get('captcha/api/{config?}', 'App\Http\Controllers\CaptchaController@getCaptchaApi');
                $router->get('captcha/{config?}', 'App\Http\Controllers\CaptchaController@getCaptcha');
            }
        }

        /* @var Factory $validator */
        $validator = $this->app['validator'];

        // Validator extensions
        $validator->extend('captcha', function ($attribute, $value, $parameters) {
            return captcha_check($value);
        });

        // Validator extensions
        $validator->extend('captcha_api', function ($attribute, $value, $parameters) {
            return captcha_api_check($value, $parameters[0], $parameters[1] ?? 'default');
        });
    }

    /**
     * Register the service provider.
     *
     * @return void
     */
    public function register(): void
    {
        // Merge configs
        $this->mergeConfigFrom(
            base_path().'/config/captcha.php',
            'captcha'
        );

        // Bind captcha
        $this->app->bind('captcha', function ($app) {
            return new Captcha(
                $app['Illuminate\Filesystem\Filesystem'],
                $app['Illuminate\Contracts\Config\Repository'],
                $app['Intervention\Image\ImageManager'],
                $app['Illuminate\Session\Store'],
                $app['Illuminate\Hashing\BcryptHasher'],
                $app['Illuminate\Support\Str']
            );
        });
    }
}
