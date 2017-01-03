<?php
namespace App\Services;

use App\Facades\Rbac;
use App\Models\Member;
use App\Models\LeadershipRole;
use App\Models\Coven;
use App\Helpers\Utility;

class MemberService
{
    protected $member;

    public function init()
    {
        $this->member = new Member;
    }

    /**
     * Retrieve existing record or, if none, return an empty Member object for "new".
     *
     * @param $member_id
     * @return mixed
     */
    public function getMemberById($member_id)
    {
        $this->init();
        return $this->member->firstOrNew(['MemberID' => $member_id]);
    }

    /**
     * Retrieve a member if email matches (single or in comma-delimited string)
     *
     * @param $test_email
     * @return Member
     */
    public function getMemberFromEmail($test_email)
    {
        $member = null;
        $found = Member::whereRaw('LOWER(`Email_Address`) LIKE ?', array('%' . strtolower($test_email) . '%'))
            ->select('*')
            ->get();
        if (!$found->isEmpty()) {
            $member = $found->first();
        }

        return $member;
    }

    /**
     * Retrieve the MemberID if a matching email address is found
     *
     * @param $test_email
     * @return int
     */
    public function getMemberIdFromEmail($test_email)
    {
        $member = $this->getMemberFromEmail($test_email);
        return (!is_null($member)) ? $member->MemberID : 0;
    }

    /**
     * Get the phone number listed as "primary" from the member record
     *
     * @param $member_id
     * @return string
     */
    public function getPrimaryPhone($member_id)
    {
        $member = $this->getMemberById($member_id);
        $primary_id = $member->Primary_Phone;
        $phone_types = array('Home_Phone', 'Work_Phone', 'Cell_Phone');
        $chosen = $phone_types[$primary_id - 1];
        $phones = $member->where('MemberID', $member_id)
            ->select($phone_types)
            ->get();
        $phone = $phones->first();
        $primary_phone = (isset($phone[$chosen])) ? $phone[$chosen] : '';

        return Utility::formatPhone($primary_phone);
    }

    /**
     * Retrieve data to display in member detail when user does not have edit permission
     *
     * @param $member_id
     * @return array
     */
    public function getStaticMemberData($member_id)
    {
        $member = $this->getMemberById($member_id);
        $middle = (!empty($member->Middle_Name)) ? $member->Middle_Name . ' ' : '';
        $name = $member->Title . ' ' . $member->First_Name . ' ' . $middle . $member->Last_Name . ' ' . $member->Suffix;
        $coven = Coven::find($member->Coven);
        $leadership = LeadershipRole::where('Role', $member->LeadershipRole)->first();
        $degree = Utility::ordinal($member->Degree);
        $bonded = ($member->Bonded) ? Utility::yesno($member->Bonded) : '';
        $solitary = ($member->Solitary) ? Utility::yesno($member->Solitary) : '';
        $board = ($this->isCurrentBoardMember($member_id)) ? $member->BoardRole : '';
        $board_expiry = date('M j, Y', strtotime($member->BoardRole_Expiry_Date));

        return [
            'name' => trim($name),
            'address1' => $member->Address1,
            'address2' => $member->Address2,
            'csz' => $member->City . ', ' . $member->State . ' ' . $member->Zip,
            'home_phone' => Utility::formatPhone($member->Home_Phone),
            'cell_phone' => Utility::formatPhone($member->Cell_Phone),
            'work_phone' => Utility::formatPhone($member->Work_Phone),
            'coven' => (!is_null($coven)) ? $coven->CovenFullName : '',
            'leadership' => (!is_null($leadership)) ? $leadership->Description : '',
            'degree' => (!is_null($degree)) ? $degree : '',
            'bonded' => $bonded,
            'solitary' => $solitary,
            'board' => $board,
            'board_expiry' => $board_expiry,
        ];
    }

    /**
     * Test if the board member role is current
     *
     * @param null $member_id
     * @return bool
     */
    public function isCurrentBoardMember($member_id = null)
    {
        if (is_null($this->member->MemberID) && !is_null($member_id)) {
            $this->member = $this->getMemberById($member_id);
        }
        $has_role = (!empty($this->member->BoardRole));
        $expired = (strtotime($this->member->BoardRole_Expiry_Date) < time());

        return ($has_role && !$expired);
    }

    /**
     * Test if email exists in members table
     *
     * @param $test_email
     * @return bool
     */
    public function isValidEmail($test_email)
    {
        $member_id = $this->getMemberIdFromEmail($test_email);

        return ($member_id != 0);
    }

    /**
     * Do operations necessary after the member record has been created or saved
     *
     * @param $changes
     * @param $member_id
     * @return void
     */
    public function postSaveMemberActions($changes, $member_id)
    {
        // If leadership role has been added or changed, we need to rewrite role permissions
        if (array_key_exists('LeadershipRole', $changes)) {
            Rbac::setLeadershipRoles();
        }

    }

    /* Methods used in "Missing Data" page only */

    public function boardExpired($member_id)
    {
        $member = $this->getMemberById($member_id);
        $active = (empty($member->BoardRole)) ? '' : 'Expired';

        return ($this->isCurrentBoardMember($member_id)) ? 'Yes' : $active;
    }

    public function hasAll($member_fields)
    {
        $hasAll = true;
        foreach ($member_fields as $field) {
            $hasAll = ($hasAll && !empty($field));
        }
        return (!$hasAll) ? 'X' : '';
    }

    public function hasNo($member_field)
    {
        return (empty($member_field)) ? 'X' : '';
    }

    public function nonAlphaOrMissing($member_field)
    {
        if (empty($member_field)) {
            return 'X';
        } else {
            $numbers = preg_replace('/[^0-9]/', '', $member_field);
            return (empty($numbers)) ? 'X' : '';
        }
    }

}