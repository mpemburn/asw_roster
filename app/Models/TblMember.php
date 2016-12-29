<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;
use Collective\Html\Eloquent\FormAccessible;
use App\Helpers\Utility;
use App\Facades\Member;
use App\Facades\RosterAuth;
use DB;

/**
 * Class TblMember
 */
class TblMember extends Model
{
    use FormAccessible;

    protected $table = 'tblMembers';

    protected $primaryKey = 'MemberID';

    public $timestamps = false;

    protected $fillable = [
        'Active',
        'Member_Since_Date',
        'Member_End_Date',
        'Last_Name',
        'First_Name',
        'Middle_Name',
        'Suffix',
        'Title',
        'Address1',
        'Address2',
        'Magickal_Name',
        'City',
        'State',
        'Zip',
        'Home_Phone',
        'Work_Phone',
        'Cell_Phone',
        'Fax_Phone',
        'Primary_Phone',
        'Email_Address',
        'Birth_Date',
        'Birth_Time',
        'Birth_Place',
        'Degree',
        'First_Degree_Date',
        'Second_Degree_Date',
        'Third_Degree_Date',
        'Fourth_Degree_Date',
        'Fifth_Degree_Date',
        'Bonded',
        'Bonded_Date',
        'Solitary',
        'Solitary_Date',
        'Coven',
        'LeadershipRole',
        'Leadership_Date',
        'BoardRole',
        'BoardRole_Expiry_Date',
        'Comments',
        'UserLogon',
        'UserPassword',
        'InitialPassword',
        'Security_Question_ID',
        'Security_Answer',
        'UserTimeZone',
        'LoginEnabled',
        'LastOnlineTime',
        'PasswordWarnings'
    ];

    protected $guarded = [];

    public static function getActiveMembers($status = 1)
    {
        $active_members = self::where('Active', $status)
            ->orderBy('Last_Name', 'asc')
            ->get();
        return array('members' => $active_members);
    }

    public static function getMemberDetails($member_id = 0)
    {
        $this_member = Member::getMemberById($member_id);
        $this_member_id = (!empty($this_member->MemberID)) ? $this_member->MemberID : 0;
        // Define dropdowns
        $prefix = TblTitle::lists('Title', 'Title')->prepend('', '');
        $suffix = TblSuffix::lists('Suffix', 'Suffix')->prepend('', '');
        $state = TblState::lists('State', 'Abbrev')->prepend('', '');
        $coven = TblCoven::lists('CovenFullName', 'Coven')->prepend('', '');
        $degree = TblDegree::lists('Degree_Name', 'Degree');
        $leadership = TblLeadershipRole::lists('Description', 'Role')->prepend('None');
        $board = TblBoardRole::lists('BoardRole', 'RoleID')->prepend('None');


        echo Member::getMemberIdFromEmail('mark@pemburn.com');

        // See if user is an Admin
        $is_admin =  RosterAuth::isMemberOf('admin');
        // See if this is the current member (user may edit their own profile).
        $is_this_user = RosterAuth::isThisMember($member_id);
        // See if this user is a leader of this member's coven
        $is_coven_leader = RosterAuth::isCovenLeader($this_member->Coven);
        // See if this user is a scribe of this member's coven
        $is_coven_scribe = RosterAuth::isCovenScribe($this_member->Coven);
        // Pre-select coven if this is a leader or scribe (but not an Elder) and it's a new record
        $selected_coven = ($member_id == 0 && RosterAuth::userIsLeaderOrScribe() && !RosterAuth::isElder()) ? RosterAuth::getUserCoven() : null;

        return array(
            'can_edit' => ($is_this_user || $is_admin || $is_coven_leader || $is_coven_scribe),
            'is_active' => ($member_id == 0) ? 1 : null, // Default to checked if this is a new record
            'member_id' => $this_member_id,
            'member' => $this_member,
            'prefix' => $prefix,
            'suffix' => $suffix,
            'state' => $state,
            'coven' => $coven,
            'selected_coven' => $selected_coven,
            'degree' => $degree,
            'leadership' => $leadership,
            'board' => $board,
            'static' => (object) Member::getStaticMemberData($member_id)
        );
    }

    public static function saveMember($data)
    {
        $member_id = $data['MemberID'];

        if ($member_id == 0) {
            $member = new TblMember();
        } else {
            $member = self::find($member_id);
        }

        $data = Utility::reformatDates($data, [
            'Member_Since_Date',
            'Member_End_Date',
            'Birth_Date',
            'First_Degree_Date',
            'Second_Degree_Date',
            'Third_Degree_Date',
            'Fourth_Degree_Date',
            'Fifth_Degree_Date',
            'Bonded_Date',
            'Solitary_Date',
            'Leadership_Date',
            'BoardRole_Expiry_Date',
        ], 'Y-m-d');

        $data = Utility::reformatCheckboxes($data, [
            'Active',
            'Bonded',
            'Solitary',
        ]);

        $result = $member->fill($data)->save();
        $member_id = $member->MemberID;

        return ['success' => $result, 'member_id' => $member_id];
    }

    /* Methods used in "Missing Data" page only */

    public static function hasAll($member_fields) {
        $hasAll = true;
        foreach ($member_fields as $field) {
            $hasAll = ($hasAll && !empty($field));
        }
        return (!$hasAll) ? 'X' : '';
    }

    public static function hasNo($member_field) {
        return (empty($member_field)) ? 'X' : '';
    }

    public static function nonAlphaOrMissing($member_field) {
        if (empty($member_field)) {
            return 'X';
        } else {
            $numbers = preg_replace('/[^0-9]/', '', $member_field);
            return (empty($numbers)) ? 'X' : '';
        }
    }
}
