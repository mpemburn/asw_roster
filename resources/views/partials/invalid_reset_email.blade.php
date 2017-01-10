<div class="col-md-8 col-md-offset-2">
    <div class="panel panel-default">
        <div class="panel-heading">Reset Password Error</div>
        <div class="panel-body text-center">
            <h3>Sorry, the email you entered ({{ $email }}) is not in our system.</h3>

            <a href="{{ url('/password/reset') }}">Please try again.</a>
        </div>
    </div>
</div>