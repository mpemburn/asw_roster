<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Collective\Html\Eloquent\FormAccessible;
use App\Helpers\Utility;
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
        $this_member = self::firstOrNew(['MemberID' => $member_id]);
        $this_member_id = (!empty($this_member->MemberID)) ? $this_member->MemberID : 0;
        $prefix = TblTitle::lists('Title', 'Title')->prepend('', '');
        $suffix = TblSuffix::lists('Suffix', 'Suffix')->prepend('', '');
        $state = TblState::lists('State', 'Abbrev')->prepend('', '');
        $coven = TblCoven::lists('CovenFullName', 'Coven')->prepend('', '');
        $degree = TblDegree::lists('Degree_Name', 'Degree');
        $leadership = TblLeadershipRole::lists('Description', 'Role')->prepend('None');
        $board = TblBoardRole::lists('BoardRole', 'RoleID')->prepend('None');

        return array(
            'can_edit' => false,
            'member_id' => $this_member_id,
            'member' => $this_member,
            'prefix' => $prefix,
            'suffix' => $suffix,
            'state' => $state,
            'coven' => $coven,
            'degree' => $degree,
            'leadership' => $leadership,
            'board' => $board,
            'static' => (object) self::getStaticMemberData($this_member)
        );
    }

    public static function getPrimaryPhone($member_id, $primary_id)
    {
        $phone_types = array('Home_Phone', 'Work_Phone', 'Cell_Phone');
        $chosen = $phone_types[$primary_id - 1];
        $phones = self::where('MemberID', $member_id)
            ->select($phone_types)
            ->get();
        $phone = $phones->first();
        $primary_phone = (isset($phone[$chosen])) ? $phone[$chosen] : '';

        return Utility::formatPhone($primary_phone);
    }

    public static function getMemberIdFromEmail($test_email)
    {
        $member_id = 0;
        $found = self::whereRaw('LOWER(`Email_Address`) LIKE ?', array('%' . strtolower($test_email) . '%'))
            ->select('MemberID')
            ->get();
        if (!$found->isEmpty()) {
            $member_id = $found->first()->MemberID;
        }
        return ($member_id);
    }

    public static function getStaticMemberData($member) {
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
            'coven' => $coven->CovenFullName,
            'leadership' => $leadership->Description
        ];
    }

    public static function isValidEmail($test_email)
    {
        $member_id = self::getMemberIdFromEmail($test_email);

        return ($member_id != 0);
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
}
