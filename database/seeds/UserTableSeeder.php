<?php
use Illuminate\Database\Seeder;
use App\Permission;
use App\Role;
use App\User;

class UserTableSeeder extends seeder
{
    public function run()
    {
        Permission::truncate();
        Role::truncate();
        //User::truncate();
        \DB::table('role_user')->delete();
        \DB::table('permission_role')->delete();
//create a user
//        $veeru = User::create([
//            'name' => 'veeru',
//            'email' => 'something@something.com',
//            'password' => bcrypt('qwerty'),
//        ]);

        //create a role of admin
        $admin = Role::create([
            'name' => 'admin',
            'display_name' => 'Admin',
            'description' => 'Only one and only admin',
        ]);
//create a permission for role
        $manage_users = Permission::create([
            'name' => 'manage-users-roles-and-permissions',
            'display_name' => 'Manage Users,Roles and Permissions',
            'description' => 'Can manage users,roles and permission"s',
        ]);
        //here attaching permission for admin role
        $admin->attachPermission($manage_users);
//here attaching role for user
        $veeru->attachRole($admin);

        //here iam creating another role and permisssion
        $application = Role::create([
            'name' => 'appapirequestlogs',
            'display_name' => 'AppApiRequestLogs',
            'description' => 'This has full control on Application Core Request logs',
        ]);
        $corereq = Permission::create([
            'name' => 'appapireqlogindex',
            'display_name' => 'AppApiReqLogIndex',
            'description' => 'This has control on Application Core Request Logs index only',
        ]);
        //here attaching roles and permissions
        $application->attachPermission($corereq);
        $veeru->attachRole($application);
    }
}