<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests;
use App\Models\TblMember;
use Illuminate\Support\Facades\Hash;
use App\Models\User;
use App\Http\Controllers\UserController;

class Member extends Controller
{
    /**
     * Display a listing of Members.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $active = TblMember::getActiveMembers();
        return view('member_list', $active);
    }

    /**
     * Display individual Member.
     *
     * @return \Illuminate\Http\Response
     */
    public function member_details($member_id = 0)
    {
        $this_member = TblMember::getMemberDetails($member_id);
        return view('member_edit', $this_member);
    }

    public function migrate()
    {
        $active = TblMember::getActiveMembers();
        $user = new UserController;
        foreach ( $active['members'] as $member ) {
            if (!empty($member->LeadershipRole) && !empty($member->UserPassword)) {
                $hash = Hash::make($member->UserPassword);
                $data = array(
                    'member_id' => $member->MemberID,
                    'name' => $member->First_Name . ' ' . $member->Last_Name,
                    'email' => $member->Email_Address,
                    'password' => $hash
                );
                $user->insert((object) $data);
            }
        }
    }


    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        var_dump($request);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }

}
