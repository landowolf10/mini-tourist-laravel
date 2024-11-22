<?php

namespace App\Repositories\members;

interface MemberRepositoryInterface
{
    public function getCardsByMemberId($memberId);
    public function authenticate($email, $password);
    public function authenticateAdmin($email, $password);
}