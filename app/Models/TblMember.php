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

    protected $member;
    protected $member_id;

    public function getDetails($member_id = 0)
    {

        $this->member_id = $member_id;
        $this->member = Member::getMemberById($member_id);

        $can_create = $this->canCreate();
        $can_edit = (!is_null($this->member)) ? $this->canEdit() : false;

        $data = [
            'can_edit' => ($can_create || $can_edit),
            'is_my_profile' => $this->isCurrentUsersProfile(),
            'is_active' => ($member_id == 0) ? 1 : null, // Default to checked if this is a new record
            'member_id' => $this->member_id,
            'user_id' => Auth::user()->id,
            'member' => $this->member,
            'selected_coven' => $this->getSelectedCoven($can_create),
            'static' => (object) Member::getStaticMemberData($member_id),
            'main_col' => ($can_create || $can_edit) ? '9' : '6',
            'sidebar_col' => ($can_create || $can_edit) ? '3' : '6',
        ];
        if ($data['can_edit']) {
            $data = $data + $this->getDropdowns();
        }

        return $data;
    }

    public function getActiveMembers($status = 1)
    {
        $active_members = $this->where('Active', $status)
            ->orderBy('Last_Name', 'asc')
            ->get();
        return array('members' => $active_members);
    }

    public function saveMember($data)
    {
        $member_id = $data['MemberID'];
        $user_id = $data['user_id'];
        $is_new = false;

        if ($member_id == 0) {
            $member = new TblMember();
            $is_new = true;
        } else {
            $member = $this->find($member_id);
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
        $old_data = $member->toArray();

        if (!$is_new) {
            // Find fields changed from current record
            $changed = $this->findChanges($member, $data);
            // Log changes
            $changed = $this->writeAuditLog($changed, $member_id, $user_id);
        }


        $result = $member->fill($data)->save();
        $member_id = $member->MemberID;

        // Make any changes necessary after Member record has been saved
        $count = Member::postSaveMemberActions($changed, $member_id);
        return ['success' => $result, 'member_id' => $member_id, 'changed' => $changed, 'count' => $count, 'data' => $old_data];
    }

    /* Private Methods */

    /**
     * Use RosterAuth (facade to Services\RosterAuthService) to test if user can create a new record
     *
     * @return bool
     */
    private function canCreate()
    {
        // See if user is either a leader or scribe
        $is_leader_or_scribe = RosterAuth::userIsLeaderOrScribe();

        return (($this->member_id == 0 && $is_leader_or_scribe));

    }

    /**
     * Use RosterAuth (facade to Services\RosterAuthService) to test if user can edit this record
     *
     * @return bool
     */
    private function canEdit()
    {
        // See if this is the current member (user may edit their own profile).
        $is_this_user = RosterAuth::isThisMember($this->member_id);
        // See if user is an Admin
        $is_admin =  RosterAuth::isMemberOf('admin');
        // See if this user is a leader of this member's coven
        $is_coven_leader = RosterAuth::isCovenLeader($this->member->Coven);
        // See if this user is a scribe of this member's coven
        $is_coven_scribe = RosterAuth::isCovenScribe($this->member->Coven);

        return ($this->member_id !=0 && ($is_this_user || $is_admin || $is_coven_leader || $is_coven_scribe));
    }

    /**
     * Test if current user is editing their own profile
     *
     * @return bool
     */
    private function isCurrentUsersProfile()
    {
        // See if this is the current member (user may edit their own profile).
        $is_this_user = RosterAuth::isThisMember($this->member_id);
        // See if user is an Admin
        $is_admin =  RosterAuth::isMemberOf('admin');
        // See if user is either a leader or scribe
        $is_leader_or_scribe = RosterAuth::userIsLeaderOrScribe();

        return ($is_this_user && !($is_admin || $is_leader_or_scribe));
    }

    public function findChanges(TblMember $member, $new_data)
    {
        $changes = [];
        foreach ($member->getAttributes() as $field => $value) {
            $new_value = (isset($new_data[$field])) ? $new_data[$field] : null;
            if ($new_value != $value && !is_null($new_value) && !is_null($value)) {
                $changes[$field] = $new_value;
            }
        }
        return $changes;
    }
    /**
     * Use RosterAuth (facade to Services\RosterAuthService) to get coven abbreviation for new record
     *
     * @param $can_create
     * @return string
     */
    private function getSelectedCoven($can_create)
    {
        // Pre-select coven if this is a leader or scribe (but not an Elder) and it's a new record
        return ($can_create && !RosterAuth::isElder()) ? RosterAuth::getUserCoven() : null;
    }

    /**
     * Get dropdown lists needed for member create/edit
     *
     * @return array
     */
    private function getDropdowns()
    {
        $prefix = TblTitle::lists('Title', 'Title')->prepend('', '');
        $suffix = TblSuffix::lists('Suffix', 'Suffix')->prepend('', '');
        $state = TblState::lists('State', 'Abbrev')->prepend('', '');
        $coven = TblCoven::lists('CovenFullName', 'Coven')->prepend('', '');
        $degree = TblDegree::lists('Degree_Name', 'Degree');
        $leadership = TblLeadershipRole::lists('Description', 'Role')->prepend('None', '');
        $board = TblBoardRole::lists('BoardRole', 'BoardRole')->prepend('None', '');

        return [
            'prefix' => $prefix,
            'suffix' => $suffix,
            'state' => $state,
            'coven' => $coven,
            'degree' => $degree,
            'leadership' => $leadership,
            'board' => $board,
        ];
    }

    /**
     * Write changes to the audit log
     *
     * @param $changes
     * @param $member_id
     *
     * @return void
     */
    private function writeAuditLog(&$changes, $member_id, $user_id)
    {
        #TODO: Create audit log code

        return $changes;
    }
}
