@extends('layouts.master')
@section('content')
    {{ Form::model($member, array('route' => array('member.update', $member->MemberID))) }}
{{ Form::select('Title',$prefix)}}
{{ Form::text('First_Name', $member->First_Name) }}
{{ Form::text('Middle_Name', $member->Middle_Name) }}
{{ Form::text('Last_Name', $member->Last_Name) }}
{{ Form::select('Suffix',$suffix)}}
<br/>
{{ Form::text('email','yourmail@here.com')}}
<br/>
{{ Form::password('password')}}
<br/>
{{ Form::checkbox('name','value')}}
<br/>
{{ Form::radio('name','value')}}
<br/>
{{ Form::file('image')}}
<br/>
<br/>
{{ Form::submit('Register Now')}}
{{ Form::close()}}
@endsection