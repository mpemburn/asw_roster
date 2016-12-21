@extends('layouts.app')
@section('content')
    <div class="content">
        <table class="member-list">
            <thead>
            <tr>
                <td>Name</td>
                <td class="show-sm-up">Address</td>
                <td>City</td>
                <td>State</td>
                <td class="show-sm-up">Zip</td>
                <td>Phone</td>
                <td class="show-sm-up">Email</td>
                <td class="filterable">Coven</td>
                <td class="show-lg-up filterable">Degree</td>
                <td class="show-lg-up">Bonded</td>
                <td class="show-lg-up filterable">Role</td>
                <td class="show-lg-up">Board</td>
            </tr>
            </thead>
            <tbody>
            @foreach ($members as $member)
                <tr data-id="{{ $member->MemberID }}">
                    <td class="nobr">{{ $member->First_Name }} {{ $member->Last_Name }}</td>
                    <td class="show-sm-up">{{ $member->Address1 }}</td>
                    <td>{{ $member->City }}</td>
                    <td>{{ $member->State }}</td>
                    <td class="show-sm-up">{{ $member->Zip }}</td>
                    <td class="nobr">{{ App\Models\TblMember::getPrimaryPhone($member->MemberID, $member->Primary_Phone) }}</td>
                    <td class="show-sm-up">{!! App\Helpers\Utility::mailto($member->Email_Address) !!}</td>
                    <td>{{ $member->Coven }}</td>
                    <td class="show-lg-up">{{ App\Helpers\Utility::ordinal($member->Degree) }}</td>
                    <td class="show-lg-up">{{ App\Helpers\Utility::yesno($member->Bonded) }}</td>
                    <td class="show-lg-up">{{ $member->LeadershipRole }}</td>
                    <td class="show-lg-up">{{ $member->BoardRole }}</td>
                </tr>
            @endforeach
            <tfoot>
            <tr>
                <td></td>
                <td></td>
                <td></td>
                <td></td>
                <td></td>
                <td></td>
                <td></td>
                <td></td>
                <td></td>
                <td></td>
                <td></td>
                <td></td>
            </tr>
            </tfoot>
            </tbody>
        </table>
    </div>
@endsection