<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Password Reset Language Lines
    |--------------------------------------------------------------------------
    |
    | The following language lines are the default lines which match reasons
    | that are given by the password broker for a password update attempt
    | has failed, such as for an invalid token or invalid new password.
    |
    */

    'password' => 'Passwords must be at least six characters and match the confirmation.',
    'reset' => 'Your password has been reset!',
    'sent-heading' => 'Success',
    'sent' => '<h3>Thanks! An email message was sent to :email.</h3>
            When you receive this email, please click the link.<br/>
            You will be directed to a page that will allow you to enter a new password.',
    'token' => 'This password reset token is invalid.',
    'user-heading' => 'Password Reset Error',
    'user' => '<h3>Sorry, the email you entered (:email) is not in our system.</h3>
                <a href=":link">Please try again.</a>',
    'failure-heading' => 'Reset Password Error',
    'failure' => '<h3>Sorry, something went wrong.</h3>
                <a href=":link">Please try again.</a> or contact support at <a href="mailto: :support">:support</a>',

];
