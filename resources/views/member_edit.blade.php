@extends('layouts.app')
@section('content')
{{ Form::model($member, array('route' => array('member.update', $member->MemberID))) }}
<div>
    {{ Form::select('Title',$prefix)}}
    {{ Form::text('First_Name', $member->First_Name) }}
    {{ Form::text('Middle_Name', $member->Middle_Name) }}
    {{ Form::text('Last_Name', $member->Last_Name) }}
    {{ Form::select('Suffix',$suffix)}}
</div>
<div>
    {{ Form::text('Email_Address',$member->Email_Address)}}
</div>
<div>
    {{ Form::submit('Submit')}}
</div>
{{ Form::close()}}
@endsection