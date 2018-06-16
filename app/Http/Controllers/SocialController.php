<?php

namespace App\Http\Controllers;
use App\Social_Account;
use Socialite;
use Auth;
use App\User;

class SocialController extends Controller
{
    public function redirectToProvider($provider)
    {
        return Socialite::driver($provider)->redirect();
    }

    public function handleProviderCallback($provider)
    {
        $user = Socialite::driver($provider)->stateless()->user();

        $authUser = $this->findOrCreateUser($user, $provider);

        Auth::login($authUser, true);

        return redirect(session('link'));
    }

    private function findOrCreateUser($socialUser, $provider)
    {
        if(Social_Account::where('provider_id', $socialUser->id)->first()) {

            $authUser = Social_Account::where('provider_id', $socialUser->id)->first()->user();

            return $authUser;
        }
        else {
            $user = User::create([
                'name' => $socialUser->name,
                'email' => $socialUser->email,
                'avatar' => $socialUser->avatar
            ]);

            $soc_account = Social_Account::create([
                'provider' => $provider,
                'provider_id' => $socialUser->id,
                'user_id' => $user->id
            ]);
        }
    }
}
