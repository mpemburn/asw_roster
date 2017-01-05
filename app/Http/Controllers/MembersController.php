<?php

namespace App\Http\Controllers;

use App\Facades\GuildMembership;
use App\Models\Coven;
use Illuminate\Http\Request;
use App\Http\Requests;
use App\Models\Member;
use Illuminate\Support\Facades\Hash;
use App\Models\User;
use App\Http\Controllers\UserController;

class MembersController extends Controller
{
    protected $member;

    public function __construct(Member $member)
    {
        $this->member = $member;
    }

    /**
     * Display a listing of Members.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $active = $this->member->getActiveMembers();
        return view('members_list', $active);
    }

    /**
     * List of covens
     *
     * @return JSON
     */
    public function listCovens()
    {
        $covens = Coven::lists('CovenFullName', 'Coven');
        $covens_array = $covens->toArray();
        ksort($covens_array);
        return $covens_array;
    }

    /**
     * Display individual Member.
     *
     * @return \Illuminate\Http\Response
     */
    public function memberDetails($member_id = 0)
    {
        $this_member = $this->member->getDetails($member_id);
        return view('member_edit', $this_member);
    }

    public function memberSearch(Request $request)
    {
        $return = [];
        $query_string = $request->q;
        $guild_id = $request->guild;

        $results = Member::where('Active', 1)
            ->where('First_Name', 'LIKE', $query_string . '%')
            ->orWhere('Last_Name', 'LIKE', $query_string . '%')
            ->orderBy('Last_Name', 'asc')
            ->get();
        foreach ($results as $member) {
            if (!GuildMembership::isMember($guild_id, $member->MemberID)) {
                $return[] = $member->First_Name . ' ' . $member->Last_Name;
            }
        }

        return $return;
    }

    public function missingDetails($member_id = 0)
    {
        $covens = Coven::all();
        $members = [];
        foreach ($covens as $coven) {
            $coveners = Member::where('Coven', $coven->Coven)
                ->where('Active', 1)
                ->orderBy('Last_Name', 'asc')
                ->get();
            if (!$coveners->isEmpty()) {
                $members[$coven->Coven] = $coveners;
            }
        }
//        foreach ($members['KHC'] as $keeper) {
//            echo $keeper->First_Name;
//        }
        $missing_data = [
            'covens' => $covens,
            'members' => $members
        ];
        return view('members_missing_details', $missing_data);
    }

    public function migrate()
    {
        $active = Member::getActiveMembers();
        $user = new UsersController;
        foreach ($active['members'] as $member) {
            if (!empty($member->LeadershipRole) && !empty($member->UserPassword)) {
                $hash = Hash::make($member->UserPassword);
                $data = array(
                    'member_id' => $member->MemberID,
                    'name' => $member->First_Name . ' ' . $member->Last_Name,
                    'email' => $member->Email_Address,
                    'password' => $hash
                );
                $user->insert((object)$data);
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
     * @param  \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request $request
     * @param  int $id
     * @return Success status
     */
    public function update(Request $request, $id)
    {
        $success = $this->member->saveMember($request->all());

        return response()->json(['success' => $success]);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }

}
