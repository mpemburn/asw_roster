<?php
    $member_id = (!empty($member->MemberID)) ? $member->MemberID : 0;
?>
@extends('layouts.app')
@section('content')
{{ Form::model($member, array('route' => array('member.update', $member_id), 'id' => 'member_update')) }}
<div>
    Member ID: {{ $member->MemberID }}
    {{ Form::hidden('MemberID', $member_id)}}
    {{ Form::select('Title', $prefix)}}
    {{ Form::text('First_Name', $member->First_Name) }}
    {{ Form::text('Middle_Name', $member->Middle_Name) }}
    {{ Form::text('Last_Name', $member->Last_Name) }}
    {{ Form::select('Suffix', $suffix) }}
</div>
<div>
    {{ Form::text('Email_Address', $member->Email_Address) }}
</div>
<div>
    {{ Form::submit(($member->MemberID == 0) ? 'Submit' : 'Update') }}
</div>
{{ Form::close()}}
@endsection