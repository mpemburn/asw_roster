<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Collective\Html\Eloquent\FormAccessible;
use App\Helpers\Utility;
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

    public static function get_primary_phone($member_id, $primary_id)
    {
        $primary_phone = null;
        $phones = self::where('MemberID', $member_id)
                         ->select(
                             'Home_Phone',
                             'Work_Phone',
                             'Cell_Phone')
                         ->get();
        foreach ($phones as $phone) {
            switch ($primary_id) {
                case 1 :
                    $primary_phone = $phone->Home_Phone;
                    break;
                case 2 :
                    $primary_phone = $phone->Work_Phone;
                    break;
                case 3 :
                    $primary_phone = $phone->Cell_Phone;
                    break;
            }
        }

        return Utility::format_phone($primary_phone);
    }

	public static function get_member_details($member_id) {
		$this_member = self::firstOrNew([ 'MemberID' => $member_id]);
		$prefix = TblTitle::lists('Title', 'TitleID')->prepend('');
		$suffix = TblSuffix::lists('Suffix', 'SuffixID')->prepend('');

		return array(
			'member' => $this_member,
			'prefix' => $prefix,
			'suffix' => $suffix
		);
	}

	public static function get_active_members($status = 1) {
		$active_members = self::where('Active', $status)
	         ->orderBy('Last_Name', 'asc')
	         ->get();
		return array('members' => $active_members);
	}

}