<?php
namespace App\Services;

use Illuminate\Support\Facades\Auth;
use App\Facades\Member;

class RosterAuthService {
    protected $user;
    protected $member;

    /**
     * init
     *
     * Initialize properties with data pulled from the logged-in user
     *
     * @return void
     */
    public function init()
    {
        $success = false;
        $this->user = Auth::user();
        if (!is_null($this->user)) {
            $this->member = Member::getMemberById($this->user->member_id);
            $success = true;
        }
        return $success;
    }

    public function getMemberName()
    {
        return ($this->init()) ? $this->member->First_Name . ' ' . $this->member->Last_Name : null;
    }

    public function getUserCoven()
    {
        return ($this->init()) ? $this->member->Coven : null;
    }

    public function isCovenLeader($coven)
    {
        return ($this->init()) ? ($this->member->Coven == $coven && $this->user->hasRole('coven-leader')) : false;
    }

    public function isCovenScribe($coven)
    {
        return ($this->init()) ? ($this->member->Coven == $coven && $this->user->hasRole('coven-scribe')) : false;
    }

    public function isElder()
    {
        return ($this->init()) ? (in_array($this->member->LeadershipRole, ['ELDER', 'CRF', 'CRM'])) : false;
    }

    public function isMemberOf($role_name)
    {
        return ($this->init()) ? $this->user->hasRole($role_name) : false;
    }

    public function isThisMember($member_id)
    {
        return ($this->init()) ? ($this->user->member_id == $member_id) : false;
    }

    public function userIsLeaderOrScribe()
    {
        if ($this->init()) {
            $leadershipRoleService = new LeadershipRoleService();
            $valid_roles = $leadershipRoleService->getLeadershipRoleArray();
            $valid_roles[] = 'SCR';

            return (in_array($this->member->LeadershipRole, $valid_roles));
        }
        return null;
    }

}