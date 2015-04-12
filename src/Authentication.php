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
    	switch ($provider) {
    	    
    		case 'facebook':
    			return Socialize::with($provider)
    			    ->scopes(['email', 'user_about_me', 'user_birthday', 'user_hometown', 'user_website', 'offline_access', 'read_stream', 'publish_stream', 'read_friendlists'])
    			    ->redirect();
    			break;
    			
    		case 'google':
    			return Socialize::with($provider)
    			    ->redirect();
    			break;
    		
    		case 'twitter':
    			return Socialize::with($provider)
    			    ->redirect();
    			break;
    	}
        return $this->socialite->driver($provider)->redirect();
    }

    private function getSocialUser($provider) {
        return $this->socialite->driver($provider)->user();
    }

    public function userHasLoggedIn($user) {
        return redirect(config('socialAuthenticator.login_redirect'));
    }
}
