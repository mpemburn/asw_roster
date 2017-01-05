@extends('layouts.app')
@section('content')
    <div id="guild_manage" class="content">
        <div class="">
            <input  class="typeahead" id="typeahead-input" type="text" data-provide="typeahead" />
        </div>

        <div class="">
            <table class="member-list">
                <thead>
                <tr>
                    <td>Name</td>
                    <td>Phone</td>
                    <td class="show-sm-up">Email</td>
                    <td class="filterable">Coven</td>
                </tr>
                </thead>
                <tbody>
                @foreach ($members as $member)
                    <tr data-id="{{ $member->MemberID }}">
                        <td class="nobr">{{ $member->First_Name }} {{ $member->Last_Name }}</td>
                        <td class="nobr">{{ \App\Facades\Membership::getPrimaryPhone($member->MemberID) }}</td>
                        <td class="show-sm-up">{!! App\Helpers\Utility::mailto($member->Email_Address) !!}</td>
                        <td>{{ $member->Coven }}</td>
                    </tr>
                @endforeach
                <tfoot>
                <tr>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>
                </tr>
                </tfoot>
                </tbody>
            </table>
        </div>
    </div>
@endsection
    <!-- Push any scripts needed for this page onto the stack -->
@push('scripts')
<script src="{{ URL::to('/') }}/js/lib/typeahead.bundle.min.js"></script>
@endpush
