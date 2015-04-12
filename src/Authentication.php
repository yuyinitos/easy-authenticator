<?php namespace Yuyinitos\SocialAuthenticator;

use Yuyinitos\SocialAuthenticator\Repositories\UserRepository as Users;
use Laravel\Socialite\Contracts\Factory as Socialite;
use Illuminate\Contracts\Auth\Guard;
use Request;
use Session;

class Authentication extends AuthenticatorManager {

    public $socialite;

    public $auth;

    public $users;

    public function __construct(Socialite $socialite, Guard $auth, Users $users) {
        $this->socialite = $socialite;
        $this->users = $users;
        $this->auth = $auth;
    }

    public function execute($request = null, $provider) {
        if (!$request) return $this->getAuthorizationFirst($provider);
        $user = $this->users->findByUserNameOrCreate($this->getSocialUser($provider), $provider);

        if(!$user) {
            return redirect(config('socialAuthenticator.login_page'))->with('session', 'Email is already in use');
        }

        (config('socialAuthenticator.flash_session')) ?:
            Session::flash(
                config('socialAuthenticator.flash_session_key'),
                config('socialAuthenticator.flash_session_login')
            );
        $this->auth->login($user, true);

        return $this->userHasLoggedIn($user);
    }

    private function getAuthorizationFirst($provider) {
        $scopes = config('services.'.$provider.'.scopes');
        if (is_array($scopes))
            return $this->socialite->driver($provider)->scopes(config('services.'.$provider.'.scopes'))->redirect();
        else
            return $this->socialite->driver($provider)->redirect();
    }

    private function getSocialUser($provider) {
        return $this->socialite->driver($provider)->user();
    }

    public function userHasLoggedIn($user) {
        return redirect(config('socialAuthenticator.login_redirect'));
    }
}
