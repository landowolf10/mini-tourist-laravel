<?php

namespace App\Repositories\cards;

interface CardRepositoryInterface
{
    public function getAllCards();
    public function getCardById($cardId);
    public function getCardsByMemberId($memberId);
    public function getCardNameByCategory(string $category);
    public function getCardsByCategory(string $category);
    public function getCardsByPremium(string $isPremium);
    public function countCardsByCategory(string $category);
    public function registerMember(array $cardData);
    /*public function updateClient($id, array $clientData);
    public function deleteClient($id);*/
}