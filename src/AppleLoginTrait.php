<?php

namespace PassportAppleLogin;


use App\User;
use AppleSignIn\ASDecoder;
use AppleSignIn\ASPayload;
use Illuminate\Http\Request;
use League\OAuth2\Server\Exception\OAuthServerException;

trait AppleLoginTrait
{
    /**
     * Logs a App\User in using a Apple token via Passport
     *
     * @param \Illuminate\Http\Request $request
     *
     * @return \Illuminate\Database\Eloquent\Model|null
     * @throws \League\OAuth2\Server\Exception\OAuthServerException
     */
    public function loginApple(Request $request)
    {
        $identityToken = $request->get('jwt');
        if (!$identityToken) {
            return null;
        }

        try {
            /** @userLanguage ASPayload $appleSignInPayload */
            $appleSignInPayload = ASDecoder::getAppleSignInPayload($identityToken);

            /** @userLanguage User $userModel */
            $userModel = config('auth.providers.users.model');

            $appleUserId  = $appleSignInPayload->getUser();
            $userEmail    = $appleSignInPayload->getEmail();
            $userName     = $request->get('name', 'User ' . rand(1000, 99999));
            $userLanguage = $request->get('language', 'en');

            $user = $userModel::where('email', $userEmail)
                              ->orWhere('apple_id', $appleUserId)
                              ->first();

            // Check if the user has already signed up.
            if (empty($user)) {
                $user              = new $userModel();
                $user->apple_id    = $appleUserId;
                $user->name        = $userName;
                $user->avatar      = '';
                $user->email       = $userEmail;
                $user->password    = uniqid('apple_', true);
                $user->language    = $userLanguage;
                $user->is_verified = 1;

                $user->save();
            } elseif (empty($user->apple_id)) {
                // If user is signed up before via credentials, save the apple id.
                $user->apple_id = $appleUserId;

                $user->save();
            }

            return $user;
        } catch (\Exception $e) {
            throw OAuthServerException::accessDenied($e->getMessage());
        }
    }
}
