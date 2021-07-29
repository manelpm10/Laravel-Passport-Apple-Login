<?php

namespace PassportAppleLogin;

use DateInterval;
use Laravel\Passport\Bridge\RefreshTokenRepository;
use Laravel\Passport\Bridge\UserRepository;
use Laravel\Passport\Passport;
use Laravel\Passport\PassportServiceProvider;
use League\OAuth2\Server\AuthorizationServer;
use League\OAuth2\Server\Grant\PasswordGrant;

/**
 * Class AppleLoginGrantProvider
 *
 * @package App\Providers
 */
class AppleLoginGrantProvider extends PassportServiceProvider
{
    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        /**
         * Create our apple.php configuration file.
         */
        $this->publishes([
            __DIR__.'/config/apple.php' => config_path('apple.php'),
        ]);

        if (file_exists(storage_path('oauth-private.key'))) {
            app(AuthorizationServer::class)->enableGrantType(
                $this->makeRequestGrant(),
                new \DateInterval(config('apple.app.token_expire_interval'))
            );
        }
    }

    /**
     * Register the service provider.
     *
     * @return void
     */
    public function register()
    {
        //
    }

    /**
     * Create and configure a Password grant instance.
     *
     * @return PasswordGrant
     */
    protected function makeRequestGrant()
    {
        $grant = new AppleLoginRequestGrant(
            $this->app->make(UserRepository::class),
            $this->app->make(RefreshTokenRepository::class)
        );

        $grant->setRefreshTokenTTL(Passport::refreshTokensExpireIn());

        return $grant;
    }
}
