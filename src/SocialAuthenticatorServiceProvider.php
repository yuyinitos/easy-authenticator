<?php namespace Yuyinitos\SocialAuthenticator;

use Illuminate\Support\ServiceProvider;

class SocialAuthenticatorServiceProvider extends ServiceProvider {

    protected $defer = false;

    public function boot()
    {
        $this->loadViewsFrom(__DIR__.'/Views', 'socialAuthenticator');

        $this->publishes([
            __DIR__.'/Config/socialAuthenticator.php' => config_path('socialAuthenticator.php'),
            __DIR__.'/Migrations' => base_path('database/migrations'),
        ]);

        $this->app->config->set('auth.model', $this->app->config->get('socialAuthenticator.model'));

        include __DIR__.'/routes.php';
    }

    public function register()
    {
        $this->app->bind('authenticator', function($app)
        {
            return $app->make('Yuyinitos\SocialAuthenticator\AuthenticatorManager');
        });
        $this->registerSocialite();
        $this->registerUserModel();
    }

    public function registerSocialite()
    {
        $this->app->register('\Laravel\Socialite\SocialiteServiceProvider');
    }

    public function registerUserModel()
    {
        $this->app->make('Yuyinitos\SocialAuthenticator\Models\User');
    }

    public function provides()
    {
        return [
            'Yuyinitos\SocialAuthenticator\AuthenticatorManager',
            '\Laravel\Socialite\SocialiteServiceProvider',
        ];
    }
}
