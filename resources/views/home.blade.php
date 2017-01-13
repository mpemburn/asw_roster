@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row">
        <div class="col-md-10 col-md-offset-1">
            <div class="panel panel-default">
                @if (Auth::check())
                    <div class="panel-heading">Welcome!</div>

                    <div class="panel-body">
                        <h3 class="text-center">Assembly of the Sacred Wheel Membership Roster</h3>

                        <div class="col-md-10 col-lg-offset-1">
                            <strong>NOTE:</strong> This resource is available to all members of the ASW. If your coveners wish to have
                            access, they will need to register using the email address that is associated with their
                            profile in this system.  If they attempt to register and are told that their email is invalid,
                            please check to see that the email listed in the system is correct, and change if necessary.
                            <h4>Who Can Use This System?</h4>
                            All active members of the ASW are eligible to use this Membership Roster program as a resource to contact
                            other members in their coven and throughout the organization.  In addition, the ASW leadership and coven scribes
                            have the ability edit some or all of the member's records as follows:<br/><br/>
                            <ul>
                                <li><strong>Coven Leaders (HPS, HP, and Acting) and Scribes:</strong> Abilty to add new members to their own coven.  Access to other member records is read-only.</li>
                                <li><strong>Elders:</strong> Administrator-level access: May edit any member's record.</li>
                                <li><strong>Guild Leaders:</strong> May add or remove guild members to their guild</li>
                            </ul>
                        </div>
                    </div>
                @else
                    <div class="panel-heading">ASW Membership Roster</div>

                    <div class="panel-body">
                        Please <a href="{{ url('/login') }}">log in</a> to continue.
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>
@endsection
