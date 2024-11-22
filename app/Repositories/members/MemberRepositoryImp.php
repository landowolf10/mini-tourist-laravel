<?php

namespace App\Repositories\members;

use App\Models\Cards;
use App\Models\Members;
use Illuminate\Support\Facades\DB;

class MemberRepositoryImp implements MemberRepositoryInterface
{
    public function getCardsByMemberId($memberId)
    {
        return Cards::where('memberid', $memberId)->get();
    }

    public function authenticate($email, $password)
    {
            return DB::table('members AS m')
                ->selectRaw("
                    c.cardid AS card_id, m.id, m.email, m.role
                ")
                ->join('cards AS c', 'c.memberid', '=', 'm.id')
                ->where('m.email', $email)
                ->where('m.password', $password)
                ->first();
    }

    public function authenticateAdmin($email, $password)
    {
        return Members::where('email', $email)
                    ->where('password', $password)  // Make sure the password is hashed if necessary
                    ->select('id', 'email', 'role')
                    ->first();  // Use first() to get a single result

    }
}