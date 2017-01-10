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

    protected function getSendResetLinkEmailSuccessInvalid($email)
    {
        return view('user_message', ['template' => 'partials.invalid_reset_email', 'email' => $email]);
    }

    protected function getSendResetLinkEmailFailureResponse($email)
    {
        return view('user_message', ['template' => 'partials.failure_reset_email', 'email' => $email]);
    }

    protected function getSendResetLinkEmailSuccessResponse($email)
    {
        return view('user_message', ['template' => 'partials.success_reset_email', 'email' => $email]);
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
                return $this->getSendResetLinkEmailSuccessResponse($request->email);
            case Password::INVALID_USER:
                return $this->getSendResetLinkEmailSuccessInvalid($request->email);
            default:
                return $this->getSendResetLinkEmailFailureResponse($response);
        }
    }
}
