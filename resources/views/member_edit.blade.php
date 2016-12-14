<?php
$member_id = (!empty($member->MemberID)) ? $member->MemberID : 0;
?>
@extends('layouts.app')
@section('content')
    <div class="container">
        <div class="row">
            <div class="col-md-12">
                <div class="panel panel-default">
                    <div class="panel-heading">
                        <h4>{{ $member->First_Name }} {{ $member->Last_Name }}</h4>
                        Member ID: {{ $member->MemberID }}
                    </div>
                    <div class="panel-body">
                        {{ Form::model($member, array('route' => array('member.update', $member_id), 'id' => 'member_update')) }}
                        {{ Form::hidden('MemberID', $member_id)}}
                        <main class="main-column col-md-9">
                            <div class="form-group">
                                <label for="name" class="col-md-1 control-label">Name</label>
                                <div class="col-md-11">
                                    {{ Form::select('Title', $prefix, null, ['class' => 'col-md-1'])}}
                                    {{ Form::text('First_Name', $member->First_Name, ['class' => 'col-md-3']) }}
                                    {{ Form::text('Middle_Name', $member->Middle_Name, ['class' => 'col-md-2']) }}
                                    {{ Form::text('Last_Name', $member->Last_Name, ['class' => 'col-md-3']) }}
                                    {{ Form::select('Suffix', $suffix, null, ['class' => 'col-md-2']) }}
                                </div>
                            </div>
                            <div class="form-group">
                                <label for="address" class="col-md-1 control-label">Address</label>
                                <div class="col-md-11">
                                    {{ Form::text('Address1', $member->Address1, ['class' => 'col-md-10']) }}
                                </div>
                                <div class="col-md-11 col-md-offset-1">
                                    {{ Form::text('Address2', $member->Address2, ['class' => 'col-md-10']) }}
                                </div>
                                <div class="col-md-11 col-md-offset-1">
                                    {{ Form::text('City', $member->City, ['class' => 'col-md-4']) }}
                                    {{ Form::select('State', $state, null, ['class' => 'col-md-3']) }}
                                    {{ Form::text('Zip', $member->Zip, ['class' => 'col-md-2']) }}
                                </div>
                            </div>
                            <div class="form-group">
                                <label for="email" class="col-md-1 control-label">Email</label>
                                <div class="col-md-11">
                                    {{ Form::text('Email_Address', $member->Email_Address, ['class' => 'col-md-10']) }}
                                </div>
                            </div>
                            <div class="form-group">
                                <div class="col-md-11 col-md-offset-1">
                                    {{ Form::submit(($member->MemberID == 0) ? 'Submit' : 'Update') }}
                                </div>
                            </div>
                        </main>
                        <aside class="sidebar-column col-md-3">
                            <div class="form-group">
                                <label for="magickal_name" class="control-label col-md-12">Magickal Name</label>
                                {{ Form::text('Magickal_Name', $member->Magickal_Name, ['class' => 'col-md-11']) }}
                            </div>
                            <div class="form-group">
                                <label for="birth_time" class="control-label col-md-12">Birth Date/Time</label>
                                {{ Form::text('Birth_Date', \App\Helpers\Utility::formatDate('M j, Y', $member->Birth_Date), ['class' => 'col-md-6 date-pick']) }}
                                {{ Form::text('Birth_Time', $member->Birth_Time, ['class' => 'col-md-5']) }}
                            </div>
                            <div class="form-group">
                                <label for="birth_place" class="control-label col-md-12">Birth Place</label>
                                {{ Form::text('Birth_Place', $member->Birth_Place, ['class' => 'col-md-6']) }}
                            </div>
                            <div class="form-group">
                                <label for="member_since" class="control-label col-md-12">Member Since</label>
                                {{ Form::text('Member_Since_Date', \App\Helpers\Utility::formatDate('M j, Y', $member->Member_Since_Date), ['class' => 'col-md-6 date-pick']) }}
                            </div>
                            <div class="form-group">
                                <label for="coven" class="control-label col-md-12">Coven or Order</label>
                                {{ Form::select('Coven', $coven, null, ['class' => 'col-md-11']) }}
                            </div>
                            <div class="form-group">
                                <label for="degree" class="control-label col-md-12">Degree</label>
                                {{ Form::select('Degree', $degree, null, ['id' => 'member_degree', 'class' => 'col-md-11']) }}
                            </div>
                            <div class="form-group degree-date {{ ($member->Degree >= 1) ? 'show' : 'hide' }}" data-degree-date="1">
                                <label for="first_degree" class="control-label col-md-12">1st Degree Date</label>
                                {{ Form::text('First_Degree_Date', \App\Helpers\Utility::formatDate('M j, Y', $member->First_Degree_Date), ['class' => 'col-md-6 date-pick']) }}
                            </div>
                            <div class="form-group degree-date {{ ($member->Degree >= 2) ? 'show' : 'hide' }}" data-degree-date="2">
                                <label for="second_degree" class="control-label col-md-12">2nd Degree Date</label>
                                {{ Form::text('Second_Degree_Date', \App\Helpers\Utility::formatDate('M j, Y', $member->Second_Degree_Date), ['class' => 'col-md-6 date-pick']) }}
                            </div>
                            <div class="form-group degree-date {{ ($member->Degree >= 3) ? 'show' : 'hide' }}" data-degree-date="3">
                                <label for="third_degree" class="control-label col-md-12">3rd Degree Date</label>
                                {{ Form::text('Third_Degree_Date', \App\Helpers\Utility::formatDate('M j, Y', $member->Third_Degree_Date), ['class' => 'col-md-6 date-pick']) }}
                            </div>
                            <div class="form-group degree-date {{ ($member->Degree >= 4) ? 'show' : 'hide' }}" data-degree-date="4">
                                <label for="fourth_degree" class="control-label col-md-12">4th Degree Date</label>
                                {{ Form::text('Fourth_Degree_Date', \App\Helpers\Utility::formatDate('M j, Y', $member->Fourth_Degree_Date), ['class' => 'col-md-6 date-pick']) }}
                            </div>
                            <div class="form-group degree-date {{ ($member->Degree >= 5) ? 'show' : 'hide' }}" data-degree-date="5">
                                <label for="fifth_degree" class="control-label col-md-12">5th Degree Date</label>
                                {{ Form::text('Fifth_Degree_Date', \App\Helpers\Utility::formatDate('M j, Y', $member->Fifth_Degree_Date), ['class' => 'col-md-6 date-pick']) }}
                            </div>
                            <div class="form-group">
                                <label for="bonded" class="control-label col-md-12">{{ Form::checkbox('Bonded', $member->Bonded) }} Bonded</label>
                            </div>
                            <div class="form-group bonded-date {{ ($member->Bonded) ? 'show' : 'hide' }}" data-degree-date="5">
                                <label for="bonded-date" class="control-label col-md-12">Bonded Date</label>
                                {{ Form::text('Bonded_Date', \App\Helpers\Utility::formatDate('M j, Y', $member->Bonded_Date), ['class' => 'col-md-6 date-pick']) }}
                            </div>
                        </aside>
                        {{ Form::close()}}
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection