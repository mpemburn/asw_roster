@extends('layouts.master')
@section('content')
<div class="content">
    <table class="member-list">
        <thead>
            <tr>
                <td>Name</td>
                <td>Address</td>
                <td>City</td>
                <td>State</td>
                <td>Zip</td>
                <td>Primary Phone</td>
                <td>Email</td>
                <td>Coven</td>
                <td>Degree</td>
                <td>Bonded</td>
                <td>Role</td>
                <td>Board</td>
            </tr>
        </thead>
        <tbody>
        @foreach ($members as $member)
            <tr data-id="{{ $member->MemberID }}">
                <td>{{ $member->First_Name }} {{ $member->Last_Name }}</td>
                <td>{{ $member->Address1 }}</td>
                <td>{{ $member->City }}</td>
                <td>{{ $member->State }}</td>
                <td>{{ $member->Zip }}</td>
                <td>{{ App\Models\TblMember::get_primary_phone($member->MemberID, $member->Primary_Phone) }}</td>
                <td>{!! App\Helpers\Utility::mailto($member->Email_Address) !!}</td>
                <td>{{ $member->Coven }}</td>
                <td>{{ App\Helpers\Utility::ordinal($member->Degree) }}</td>
                <td>{{ App\Helpers\Utility::yesno($member->Bonded) }}</td>
                <td>{{ $member->LeadershipRole }}</td>
                <td>{{ $member->BoardRole }}</td>
            </tr>
    @endforeach
        </tbody>
    </table>
</div>
@endsection