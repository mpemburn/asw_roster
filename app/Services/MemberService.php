<?php
namespace App\Services;

use App\Models\TblMember;
use App\Models\TblLeadershipRole;
use App\Models\TblCoven;
use App\Helpers\Utility;

class MemberService {
    protected $member;

    public function init()
    {
        $this->member = new TblMember;
    }

    public function getMemberById($member_id)
    {
        $this->init();
        return $this->member->firstOrNew(['MemberID' => $member_id]);
    }

    public function getMemberFromEmail($test_email)
    {
        $member = null;
        $found = TblMember::whereRaw('LOWER(`Email_Address`) LIKE ?', array('%' . strtolower($test_email) . '%'))
            ->select('*')
            ->get();
        if (!$found->isEmpty()) {
            $member = $found->first();
        }

        return $member;
    }

    public function getMemberIdFromEmail($test_email)
    {
        $member = $this->getMemberFromEmail($test_email);
        return (!is_null($member)) ? $member->MemberID : 0;
    }

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

    public function getStaticMemberData($member_id)
    {
        $member = $this->getMemberById($member_id);
        $middle = (!empty($member->Middle_Name)) ? $member->Middle_Name . ' ' : '';
        $name = $member->Title . ' ' . $member->First_Name . ' ' . $middle . $member->Last_Name . ' ' . $member->Suffix;
        $coven = TblCoven::find($member->Coven);
        $leadership = TblLeadershipRole::where('Role', $member->LeadershipRole)->first();

        return [
            'name' => trim($name),
            'address1' => $member->Address1,
            'address2' => $member->Address2,
            'csz' => $member->City . ', ' . $member->State . ' ' . $member->Zip,
            'home_phone' => Utility::formatPhone($member->Home_Phone),
            'cell_phone' => Utility::formatPhone($member->Cell_Phone),
            'work_phone' => Utility::formatPhone($member->Work_Phone),
            'coven' => (!is_null($coven)) ? $coven->CovenFullName : '',
            'leadership' => (!is_null($leadership)) ? $leadership->Description : ''
        ];
    }

    public function isValidEmail($test_email)
    {
        $member_id = $this->getMemberIdFromEmail($test_email);

        return ($member_id != 0);
    }

}