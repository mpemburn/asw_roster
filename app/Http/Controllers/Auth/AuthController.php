<?php

namespace App\Http\Controllers\Auth;

use App\Models\TblMember;
use App\Models\User;
use App\Facades\Member;
use Validator;
use App\Http\Controllers\Controller;
use Illuminate\Foundation\Auth\ThrottlesLogins;
use Illuminate\Foundation\Auth\AuthenticatesAndRegistersUsers;

class AuthController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Registration & Login Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles the registration of new users, as well as the
    | authentication of existing users. By default, this controller uses
    | a simple trait to add these behaviors. Why don't you explore it?
    |
    */

    use AuthenticatesAndRegistersUsers, ThrottlesLogins;

    /**
     * Where to redirect users after login / registration.
     *
     * @var string
     */
    protected $redirectTo = '/';

    /**
     * Create a new authentication controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware($this->guestMiddleware(), ['except' => 'logout']);
    }

    /**
     * Get a validator for an incoming registration request.
     *
     * @param  array $data
     * @return \Illuminate\Contracts\Validation\Validator
     */
    protected function validator(array $data)
    {
        /* NOTE: member_email and password_mask rules are specified in /app/Providers/AppServiceProvider.php
            Custom error messages found in /resource/lang/en/validation.php
        */
        return Validator::make($data, [
            'email' => 'required|member_email|email|max:255|unique:users',
            'password' => 'required|min:6|bad_pattern|confirmed',
        ]);
    }

    /**
     * Create a new user instance after a valid registration.
     *
     * @param  array $data
     * @return User
     */
    protected function create(array $data)
    {
        $member = Member::getMemberFromEmail($data['email']);
        $member_id = (!is_null($member)) ? $member->MemberID : null;
        $name = (!is_null($member)) ? $member->First_Name . ' ' . $member->Last_Name : '';

        return User::create([
            'member_id' => $member_id,
            'name' => $name,
            'email' => $data['email'],
            'password' => bcrypt($data['password']),
        ]);
    }
}
