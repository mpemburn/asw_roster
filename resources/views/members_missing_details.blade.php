@extends('layouts.app')
<style>
    .page-header {
        margin: 0 !important;
        padding: 0;
        text-align: left;
    }

    .missing-list {
        margin-bottom: 64px;
    }

    table {
        border-collapse: collapse;
    }

    tbody tr:hover {
        background-color: #fff !important;
        color: #000 !important;
    }

    td.center {
        text-align: center;
    }

    td.pad {
        padding-left: 8px;
    }

    td.shade {
        background-color: #ddd;
    }

    tr.header td {
        background-color: #ccc;
    }
</style>
@section('content')
    <div class="content">
        <div class="page-header">
            <h3>Missing or Incorrect Data</h3>
            <strong>NOTES:</strong>
            <ol>
                <li>Columns containing an "X" indicate that data is missing for that field.</li>
                <li>The "Role" field refers to [Acting]High Priestess, [Acting]High Priest, Scribe, and Pursewarden.</li>
                <li>Make sure all of your degreed members have the date of each degree and a Magickal Name.</li>
                <li>Make sure all of your bonded members have the date of bonding.</li>
                <li>Complete birth info requires Date, Time, and Place.</li>
            </ol>
        </div>
        <table class="missing-list">
            <tbody>
            @foreach($covens as $coven)
                @if (!empty($members[$coven->Coven]))
                    <tr>
                        <td colspan="15">
                            <h4>{{ $coven->CovenFullName }}</h4>
                        </td>
                    <tr class="header">
                        <td>Name</td>
                        <td>Address</td>
                        <td>City</td>
                        <td>State</td>
                        <td>Zip</td>
                        <td>Home Phone</td>
                        <td>Cell Phone</td>
                        <td>Work Phone</td>
                        <td>Email</td>
                        <td>Birth Info</td>
                        <td>Mag. Name</td>
                        <td>Degree</td>
                        <td>Bonded</td>
                        <td>Role</td>
                        <td>Board</td>
                    </tr>
                    </tr>
                    @foreach($members[$coven->Coven] as $member)
                        <tr data-id="{{ $member->MemberID }}">
                            <td>
                                {{ $member->First_Name . ' ' . $member->Last_Name }}
                            </td>
                            <td class="center shade">
                                {{ \App\Facades\Member::hasNo($member->Address1) }}
                            </td>
                            <td class="center">
                                {{ \App\Facades\Member::hasNo($member->City) }}
                            </td>
                            <td class="center shade">
                                {{ \App\Facades\Member::hasNo($member->State) }}
                            </td>
                            <td class="center">
                                {{ \App\Facades\Member::nonAlphaOrMissing($member->Zip) }}
                            </td>
                            <td class="center shade">
                                {{ \App\Facades\Member::hasNo($member->Home_Phone) }}
                            </td>
                            <td class="center">
                                {{ \App\Facades\Member::hasNo($member->Cell_Phone) }}
                            </td>
                            <td class="center shade">
                                {{ \App\Facades\Member::hasNo($member->Work_Phone) }}
                            </td>
                            <td class="center">
                                {{ \App\Facades\Member::hasNo($member->Email_Address) }}
                            </td>
                            <td class="center shade">
                                {{ \App\Facades\Member::hasAll([$member->Birth_Date, $member->Birth_Time, $member->Birth_Place]) }}
                            </td>
                            <td class="center">
                                {{ \App\Facades\Member::hasNo($member->Magickal_Name) }}
                            </td>
                            <td class="pad shade">
                                {{ \App\Helpers\Utility::ordinal($member->Degree) }}
                            </td>
                            <td class="center">
                                {{ (!empty($member->Bonded)) ? 'Yes' : '' }}
                            </td>
                            <td class="pad shade">
                                {{ $member->LeadershipRole }}
                            </td>
                            <td class="center">
                                {{ \App\Facades\Member::boardExpired($member->MemberID) }}
                            </td>
                        </tr>
                    @endforeach
                @endif
            @endforeach
            </tbody>
        </table>
    </div>
@endsection