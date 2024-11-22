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

        $member = $this->memberRepository->authenticateAdmin($email, $password);

        if ($member->role == 'admin') {
            return response()->json([
                'id' => $member['id'],
                'email' => $member['email'],
                'role' => $member['role']
            ], 200);
        }
        else 
        {
            $member = $this->memberRepository->authenticate($email, $password);

            return response()->json([
                'id' => $member->id,
                'card_id' => $member->card_id, // This is coming from the query, not from the model
                'email' => $member->email,
                'role' => $member->role
            ], 200);
        }

        // Return a response for failed authentication
        return response()->json(['error' => 'Invalid email or password'], 401);
    }
}
