<?php

namespace App\Http\Controllers\Api\v1;

use App\Http\Controllers\Controller;
use App\Repositories\members\MemberRepositoryInterface;
use Illuminate\Http\Request;

class MemberController extends Controller
{
    public function __construct(MemberRepositoryInterface $memberRepository)
    {
        $this->memberRepository = $memberRepository;
    }

    public function getCardsByMemberId(Request $request)
    {
        $memberId = $request->input('memberId');

        $cards = $this->memberRepository->getCardsByMemberId($memberId);

        return response()->json($cards);
    }

    public function authenticate(Request $request)
    {
        $validatedData = $request->validate([
            'email' => 'required|email',
            'password' => 'required'
        ]);

        $email = $validatedData['email'];
        $password = $validatedData['password'];

        // First verify if it's admin user
        $adminMember = $this->memberRepository->authenticateAdmin($email, $password);
        
        if ($adminMember) {
            return response()->json([
                'id' => $adminMember->id,
                'email' => $adminMember->email,
                'role' => $adminMember->role
            ], 200);
        }

        //If not admin, then verify is a normal user
        $member = $this->memberRepository->authenticate($email, $password);
        
        if ($member) {
            return response()->json([
                'id' => $member->id,
                'cardid' => $member->cardid,
                'email' => $member->email,
                'role' => $member->role
            ], 200);
        }

        // If no user was found
        return response()->json([
            'error' => 'Unauthorized',
            'message' => 'The provided credentials are incorrect'
        ], 401);
    }
}
