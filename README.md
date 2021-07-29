# Laravel Passport Apple Login
Provides a new Laravel Passport Grant Client named `apple_login`, allowing you to log a user in with just their Apple JWT.

A new user will be created (and optionally assigned to an role - `$user->attachRole(ID)`) if the email address doesn't exist.

## Installation:
Install with composer `composer require manelpm10/laravel-passport-apple-login`

### Versions:
* Laravel 5.5 and Passport 4.0 only supported at this time

## Dependencies:
* `"laravel/passport": "^4.0"`
* `"griffinledingham/php-apple-signin": "~1.1"`

## Setup:
* ***Using Laravel 5.4 or below?*** add `PassportAppleLogin\AppleLoginGrantProvider::class` to your list of providers **after** `Laravel\Passport\PassportServiceProvider`. Laravel 5.5 uses auto-discovery, so manual service registration is no longer required.
* Add `PassportAppleLogin\AppleLoginTrait` Trait to your `User` model (or whatever model you have configured to work with Passport).
* Run `php artisan vendor:publish`, this will create a `config/apple.php` file.
* Optional: To automatically attach a role (https://github.com/Zizaco/entrust) to new users, use the 'ATTACH_ROLE' env setting.

**Config:**
```php
    /*
    |--------------------------------------------------------------------------
    | Application
    |--------------------------------------------------------------------------
    |
    | The DateInterval format for expire token ttl.
    | See https://www.php.net/manual/es/dateinterval.format.php for mor info about format
    |
    */
    'app' => [
        'token_expire_interval' => env('TOKEN_EXPIRE_INTERVAL', 'P1D'),
    ],

    /*
    |--------------------------------------------------------------------------
    | Registration Fields
    |--------------------------------------------------------------------------
    |
    | The name of the fields on the user model that need to be updated,
    | if null, they shall not be updated. (valid for name, first_name, last_name)
    |
    */

    'registration' => [
        'apple_id'    => env('APPLE_ID_COLUMN', 'apple_id'),
        'email'       => env('EMAIL_COLUMN', 'email'),
        'password'    => env('PASSWORD_COLUMN', 'password'),
        'first_name'  => env('FIRST_NAME_COLUMN', 'first_name'),
        'last_name'   => env('LAST_NAME_COLUMN', 'last_name'),
        'name'        => env('NAME_COLUMN', 'name'),
        'attach_role' => env('ATTACH_ROLE', null),
    ],
```

## How To Use:

* Make a **POST** request to `https://your-site.com/oauth/token`.
* The POST body should contain
    1. `grant_type` = `apple_login`
    2. `jwt` = `{JWT from apple sign in}`.
    3. `name` = `{Name for registered user}`.
    4. client_id
    5. client_secret
* An `access_token` and `refresh_token` will be returned if successful.

## Assumptions:
* Your `User` model has the folowing fields:
* * `apple_id`
* * `name` or `first_name` & `last_name`
* * `email`
* * `password`

## Credits:
* https://github.com/manelpm10/Laravel-Passport-Apple-Login
* https://github.com/mikemclin/passport-custom-request-grant
