<?php
namespace App\Services;

use Illuminate\Support\Facades\Auth;
use App\Models\GuildMember;

class GuildService {
    protected $user;

    public function isMember($guild_id, $member_id)
    {
        $guild_member = GuildMember::where('GuildID', $guild_id)
            ->where('MemberID', $member_id)
            ->get()
            ->first();

        return ($guild_member !== null);
    }

}