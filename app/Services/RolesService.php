<?php
namespace App\Services;

use App\Models\BoardRole;
use App\Models\LeadershipRole;

class RolesService {

    /**
     * Retrieve array of leadership roles of the "Leadership" type (not Scribes or Purse Wardens)
     *
     * @return array
     */
    public function getLeadershipRoleArray()
    {
        $roles = [];
        $leadership = LeadershipRole::where('GroupName', 'Leadership')->get();
        foreach ($leadership as $leader) {
            $roles[] = $leader->Role;
        }
        return $roles;
    }

    public function test(){
        echo "yo";
    }

    /**
     * Standard dropdown for board roles
     *
     * @return array
     */
    public function boardDropdown()
    {
        return BoardRole::lists('BoardRole', 'BoardRole')->prepend('None', '');
    }

    /**
     * Standard dropdown for leadership roles
     *
     * @return array
     */
    public function leadershipDropdown()
    {
        return LeadershipRole::lists('Description', 'Role')->prepend('None', '');
    }

}