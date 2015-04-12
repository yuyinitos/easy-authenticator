<?php namespace Yuyinitos\SocialAuthenticator\Repositories;

use Yuyinitos\SocialAuthenticator\Models\User;
use Config;

class UserRepository {

    public function findByUserNameOrCreate($userData, $provider) {

        if (!isset($userData->email)) {
            $userData->email = time() . '-no-reply@socialauthenticator.com';
        }

        $user = User::where('provider_id', '=', $userData->id)->first();
//        $emailExists = User::where('email', '=', $userData->email)->first();

//        if(!$user && $emailExists) {
//            return false;
//        }

        if(!$user) {
            switch ($provider) {
                case 'facebook':
                    $user = User::create([
                        'provider_id' => $userData->id,
                        'provider' => $provider,
                        'name' => $userData->name,
                        'username' => $userData->nickname,
                        'email' => $userData->email,
                        'avatar' => $userData->avatar,
                        'gender' => $userData->user['gender'],
                        'birthday' => $userData->user['birthday'],
                        'locale' => $userData->user['locale'],
                    ]);
                    break;
                
                case 'twitter':
                    $user = User::create([
                        'provider_id' => $userData->id,
                        'provider' => $provider,
                        'name' => $userData->name,
                        'username' => $userData->nickname,
                        'email' => $userData->email,
                        'avatar' => $userData->avatar,
                        'locale' => $userData->user['lang'],
                    ]);
                    break;
                
                default:
                    $user = User::create([
                        'provider_id' => $userData->id,
                        'provider' => $provider,
                        'name' => $userData->name,
                        'username' => $userData->nickname,
                        'email' => $userData->email,
                        'avatar' => $userData->avatar,
                    ]);
                    break;
            }
        }
        $this->checkIfUserNeedsUpdating($provider, $userData, $user);
        return $user;
    }

    public function checkIfUserNeedsUpdating($provider, $userData, $user) {

        $socialData = [
            'avatar' => $userData->avatar,
            'email' => $userData->email,
            'name' => $userData->name,
            'username' => $userData->nickname,
        ];
        $dbData = [
            'avatar' => $user->avatar,
            'email' => $user->email,
            'name' => $user->name,
            'username' => $user->username,
        ];

        if (!empty(array_diff($socialData, $dbData))) {
            $user->avatar = $userData->avatar;
            $user->email = $userData->email;
            $user->name = $userData->name;
            $user->username = $userData->nickname;
            $user->save();
        }

        switch ($provider) {
            case 'facebook':
                $socialData = [
                    'gender' => $userData->user['gender'],
                    'birthday' => $userData->user['birthday'],
                    'locale' => $userData->user['locale'],
                ];
                $dbData = [
                    'gender' => $user->gender,
                    'birthday' => $user->birthday,
                    'locale' => $user->locale,
                ];

                if (!empty(array_diff($socialData, $dbData))) {
                    $user->gender = $userData->user['gender'];
                    $user->birthday = $userData->user['birthday'];
                    $user->locale = $userData->user['locale'];
                    $user->save();
                }
                break;
            
            case 'twitter':
                $socialData = [
                    'locale' => $userData->user['lang'],
                ];
                $dbData = [
                    'locale' => $user->locale,
                ];

                if (!empty(array_diff($socialData, $dbData))) {
                    $user->locale = $userData->user['lang'];
                    $user->save();
                }
                break;            
        }

    }

    public function accountIsActive($code) {
        $user = User::where('activation_code', '=', $code)->first();
        $user->active = 1;
        $user->activation_code = '';
        if($user->save()) {
            Auth::login($user);
        }
        return true;
    }
}
