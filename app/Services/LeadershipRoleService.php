<?php
namespace App\Services;

use App\Models\TblLeadershipRole;

class LeadershipRoleService {

    public function getLeadershipRoleArray()
    {
        $roles = [];
        $leadership = TblLeadershipRole::where('GroupName', 'Leadership')->get();
        foreach ($leadership as $leader) {
            $roles[] = $leader->Role;
        }
        return $roles;
    }

}