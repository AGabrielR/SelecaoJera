<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Laravel\Socialite\Facades\Socialite;
use App\Services\SocialFacebookAccountService;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Contracts\Auth\Authenticatable;
use Illuminate\Database\Eloquent\Model;
use App\Models\User;
use Exception;


class SocialAuthFacebookController extends Controller
{
    public function redirectToProvider()
    {
        return Socialite::driver('facebook')->redirect();
    }

    public function callback()
    {

        try {
    
            $user = Socialite::driver('facebook')->stateless()->user();

            $finduser = User::where('facebook_id', $user->id)->first();

            if(isset($finduser)){

                Auth::loginUsingId($finduser['id']);
                
                return redirect('/home');
     
            }else{
                $newUser = User::create([
                    'name' => $user->name,
                    'email' => $user->email,
                    'facebook_id'=> $user->id,
                    'password' => encrypt('123456dummy')
                ]);
    
                Auth::login($newUser, 'true');
     
                return redirect('/home');
            }
    
        } catch (Exception $e) {
            dd($e);
        }
    }
}
