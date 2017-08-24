<?php

namespace App\Http\Controllers\Auth;

use DB;
use Session;
use App\User;
use Mail;
use App\Invite;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Validator;
use Illuminate\Foundation\Auth\RegistersUsers;
use App\Mail\UserVerification;


class RegisterController extends Controller
{
    use RegistersUsers;
    /**
     * Where to redirect users after login / registration.
     *
     * @var string
     */
    protected $redirectTo = '/verification';

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('guest');
    }

    /**
     * Get a validator for an incoming registration request.
     *
     * @param  array  $data
     * @return \Illuminate\Contracts\Validation\Validator
     */
    protected function validator(array $data)
    {
        return Validator::make($data, [
            'name' => 'required|max:255|unique:users|no_spaces',
            'email' => 'required|email|max:255|unique:users',
            'password' => 'required|min:6|confirmed',
        ]);
    }

    /**
     * Create a new user instance after a valid registration.
     *
     * @param  array  $data
     * @return User
     */
    protected function create (array $data)
    {
        // explicity set user role, even though default is 'user'
        $role = 'user';

        // check user register code exists
        if ($data['code'] !== '') {
            // code is present and not empty, let's get the details of Invite from db
            $invite = DB::table('invites')->where('code', '=', $data['code'])->first();

            $register_using_code = false;

            // let's make sure the code was linked to a valid instance of Invite.
            if ($invite != null) {
                // set the role as per the invite
                $role = $invite->role;

                // delete the invite
                DB::table('invites')->where('email', '=', $invite->email)->delete();

                $register_using_code = true;

            }

            Session::put('register_using_code', $register_using_code);
        }

        $token = str_random(16);
        $user =  User::create([
            'name' => $data['name'],
            'email' => $data['email'],
            'role' => $role,
            'imageURL' =>  'https://tracks-bachelor.s3.eu-west-2.amazonaws.com/avatars/no-avatar.png',
            'password' => bcrypt($data['password']),
            'verified' => false,
            'token' => $token
        ]);

        Mail::to($user)->queue(new UserVerification($user));
    }

    public function verify($token)
    {
        $user = User::where('token', $token)->first();
        $user->verified = true;
        if ($user->save()) {
            return view('user.verified', ['user' => $user]);
        }
    }

    public function verification() {
        return view('user.verification');
    }
}
