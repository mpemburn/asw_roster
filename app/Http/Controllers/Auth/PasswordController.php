<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Foundation\Auth\ResetsPasswords;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Password;

class PasswordController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Password Reset Controller
    |--------------------------------------------------------------------------
    |
    | This controller is responsible for handling password reset requests
    | and uses a simple trait to include this behavior. You're free to
    | explore this trait and override any methods you wish to tweak.
    |
    */

    use ResetsPasswords;

    /**
     * Create a new password controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('guest');
    }

    public function sendResetLinkEmail(Request $request)
    {
        $this->validateSendResetLinkEmail($request);

        $broker = $this->getBroker();

        $response = Password::broker($broker)->sendResetLink(
            $this->getSendResetLinkEmailCredentials($request),
            $this->resetEmailBuilder()
        );

        switch ($response) {
            case Password::RESET_LINK_SENT:
                return $this->getSendResetLinkEmailSuccessResponse($response, $request->email);
            case Password::INVALID_USER:
                return $this->getSendResetLinkEmailInvalid($response, $request->email);
            default:
                return $this->getSendResetLinkEmailFailureResponse();
        }
    }

    // Methods to override defaults in ResetsPasswords trait

    protected function getSendResetLinkEmailFailureResponse()
    {
        $heading = trans('passwords-failure-heading');
        $message = trans('passwords-failure', [
            'link' => url('/password/reset'),
            'support' => config('app.support')
        ]);

        return view('user_message', ['heading' => $heading, 'message' => $message]);
    }

    protected function getSendResetLinkEmailInvalid($response, $email)
    {
        $heading = trans($response . '-heading');
        $message = trans($response, [
            'email' => $email,
            'link' => url('/password/reset')
        ]);

        return view('user_message', ['heading' => $heading, 'message' => $message]);
    }

    protected function getSendResetLinkEmailSuccessResponse($response, $email)
    {
        $heading = trans($response . '-heading');
        $message = trans($response, [
            'email' => $email
        ]);

        return view('user_message', ['heading' => $heading, 'message' => $message]);
    }

}
