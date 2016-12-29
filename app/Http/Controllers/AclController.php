<?php

namespace App\Http\Controllers;

use App\Models\TblMember;
use Illuminate\Http\Request;

use App\Http\Requests;
use App\Models\Role;
use App\Models\Permission;
use App\Models\User;
use App\Facades\Member;
use App\Facades\LeadershipRole;

class AclController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $user = User::where('member_id', '6')->first();
        $admin = Role::where('name', 'admin')->get()->first();

//        if ($user->hasRole('admin')) {
//            $createPost = new Permission();
//            $createPost->name         = 'create-user';
//            $createPost->display_name = 'Create User'; // optional
//// Allow a user to...
//            $createPost->description  = 'Create new user'; // optional
//            $createPost->save();

//            $editUser = new Permission();
//            $editUser->name         = 'edit-user';
//            $editUser->display_name = 'Edit Users'; // optional
//// Allow a user to...
//            $editUser->description  = 'edit existing users'; // optional
//            $editUser->save();

        $editUser = Permission::where('name', 'edit-user')->first();
        $admin->attachPermission($editUser);
// equivalent to $admin->perms()->sync(array($createPost->id));

//
// role attach alias
        //$user->attachRole($admin); // parameter can be an Role object, array, or id

    }

    public function set_leaders()
    {
        $admin = Role::getRoleByName('admin');
        $coven_leader = Role::getRoleByName('coven-leader');
        $coven_scribe = Role::getRoleByName('coven-scribe');

        // Get an array of the leadership types that can be assigned these roles
        $valid_roles = LeadershipRole::getLeadershipRoleArray();

        // Get list of all users
        $all_users = User::all();
        foreach ($all_users as $user) {
            // Get the Member record associated with this user
            $member = Member::getMemberById($user->member_id);
            // ...and the leadership role associated with that member, if any
            $role = $member->LeadershipRole;
            // Detach all roles first; they will be recreated in the next steps
            if ($user->hasRole('coven-leader')) {
                $user->detachRole($coven_leader);
            }
            if ($user->hasRole('coven-scribe')) {
                $user->detachRole($coven_scribe);
            }
            // Attach coven leader roles (also applies to Elders)
            if (in_array($role, $valid_roles)) {
                if (!is_null($coven_leader)) {
                    $user->attachRole($coven_leader);
                }
            }
            // Attach Scribe role
            if ($role == 'SCR') {
                if (!is_null($coven_scribe)) {
                    $user->attachRole($coven_scribe);
                }
            }
            // Attach admin role
            if (in_array($role, ['ELDER', 'CRF', 'CRM'])) {
                if (!is_null($admin) && !$user->hasRole('admin')) {
                    $user->attachRole($admin);
                }
            }
        }
    }

    public function set_role_permissions() {
        $create_user = Permission::getPermissionByName('create-user');
        $edit_user = Permission::getPermissionByName('edit-user');
        $coven_leader = Role::getRoleByName('coven-leader');
        $coven_scribe = Role::getRoleByName('coven-scribe');

        $coven_leader->attachPermission($create_user);
        $coven_leader->attachPermission($edit_user);
        $coven_scribe->attachPermission($create_user);
        $coven_scribe->attachPermission($edit_user);
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
        //
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
