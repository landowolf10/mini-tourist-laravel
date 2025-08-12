<?php

namespace App\Repositories\cards;

use App\Models\Members;
use App\Models\Cards;
use App\Models\PlacesPerCategory;
use Cloudinary\Cloudinary;
use Cloudinary\Configuration\Configuration;
use Cloudinary\Api\Upload\UploadApi;
use Cloudinary\Transformation\Transformation;
use Exception;
use RuntimeException;

class CardRepositoryImp implements CardRepositoryInterface
{
    public function getAllCards()
    {
        // Returns a collection of all clients
        return Cards::all();
    }

    public function getCardById($cardId)
    {
        return Cards::where('cardid', $cardId)->first();
    }

    public function getCardsByMemberId($memberId)
    {
        return Cards::where('memberid', $memberId)->get();
    }

    public function getCardNameByCategory(string $category)
    {
        // Select only the 'card_name' field for clients with the given category
        return Cards::select('cardname')
            ->where('category', $category)
            ->get();
    }

    public function getCardsByCategory(string $category = null)
    {
        // If $category is equal to "premium", select clients where premium field is "Yes"
        if ($category === 'premium') {
            return Cards::where('premium', 'Yes')->get();
        }

        if ($category !== null && $category != "premium") {
            return Cards::where('category', $category)->get();
        }

        // If neither $category nor $isPremium is provided, return an empty collection or handle as needed
        return collect(); // Returns an empty collection
    }

    public function getCardsByPremium(string $isPremium)
    {
        // Get all clients with the given category
        return Cards::where('premium', $isPremium)->get();
    }

    public function getCardsByIsPlace(string $isPlace)
    {
        return Cards::where('place', $isPlace)->get();
    }

    public function getCardsByPlace(string $place)
    {
        // Count the number of clients with the given category
        return Cards::where('place', $place)->get();
    }

    public function getCardsByPlacesPerCategory($ownerId, string $category)
    {
        return PlacesPerCategory::where('owner_id', $ownerId)
                    ->where('category', $category)
                    ->get();
    }

    public function countCardsByCategory(string $category)
    {
        // Count the number of clients with the given category
        return Cards::where('category', $category)->count();
    }

    public function registerMember(array $cardData)
    {
        try {
            $member = new Members();
            $member->email = $cardData['email'];
            $member->password = $cardData['password']; // You can hash the password using bcrypt
            $member->role = 'member';
            $member->save();

            // Retrieve the id of the newly saved member
            $memberId = $member->id;

            // Create a new Card instance and fill with the provided data
            $card = new Cards();
            $card->memberid = $memberId;
            $card->cardname = $cardData['cardName'];
            $card->city = $cardData['city'];
            $card->category = $cardData['category'];
            $card->premium = $cardData['premium'];
            $card->creation_date = now();
            $card->update_date = null;
    
            // Handle image file upload if it exists
            if (isset($cardData['imageFile']) && isset($cardData['backImageFile'])) {
                $imagePath = $this->saveImage($cardData['imageFile'], $card->category, $card->premium);
                $backImagePath = $this->saveImage($cardData['backImageFile'], $card->category, $card->premium);
                $card->image = $imagePath;
                $card->back_image = $backImagePath;
            }
    
            // Save the client details along with the image path
            $card->save();
    
            // Return the saved client object (you can return it directly or as JSON)
            return $card;
        } catch (Exception $e) {
            // Handle exceptions and return an appropriate response or throw a custom exception
            throw new RuntimeException('Failed to save client data: ' . $e->getMessage());
        }
    }

    public function getLatAndLongdByCardId($cardId)
    {
        return Cards::select('lat', 'long')
            ->where('cardid', $cardId)
            ->get();
    }

    private function saveImage($file, $category, $isPremium) {
        // Configure Cloudinary
        $cloudinary = new Cloudinary([
            'cloud' => [
                'cloud_name' => 'dqvs83nao',
                'api_key' => '532562219644367',
                'api_secret' => 'FVCBgcmwipQubGt7jIH1Ej5RJQ8',
            ],
        ]);
    
        // Create main folder and subfolder (i.e. cards/Lugares y eventos)
        $folderPath = "cards/" . $category . "/";
    
        // Set options for uploading
        $options = [
            'folder' => $folderPath,
        ];
    
        // Upload the file to Cloudinary and create the folder if it doesn't exist
        $uploadedFile = $cloudinary->uploadApi()->upload($file->getPathname(), $options);
    
        // Check if the file should be uploaded as premium
        if (strtolower($isPremium) === 'yes') {
            $premiumPath = "cards/Premium/";
    
            // Set options for premium uploading
            $premiumOptions = [
                'folder' => $premiumPath,
            ];
    
            // Upload the file to Cloudinary for premium
            $uploadedFile = $cloudinary->uploadApi()->upload($file->getPathname(), $premiumOptions);
        }
    
        // Return the image URL
        return $uploadedFile['url'];
    }
}