<?php

namespace App\Http\Controllers\Api\v1;

use App\Http\Controllers\Controller;
use App\Repositories\cards\CardRepositoryInterface;
use Illuminate\Http\Request;
use Exception;

class CardController extends Controller
{
    public function __construct(CardRepositoryInterface $cardRepository)
    {
        $this->cardRepository = $cardRepository;
    }

    public function getAllCards()
    {
        $cards = $this->cardRepository->getAllCards();
        return response()->json($cards);
    }

    public function getCardById($cardId)
    {
        $card = $this->cardRepository->getCardById($cardId);
        return response()->json($card);
    }

    public function getCardsByMemberId($memberId)
    {
        $cards = $this->cardRepository->getCardsByMemberId($memberId);
        return response()->json($cards);
    }

    public function getCardNameByCategory(Request $request)
    {
        // Get the category from the query parameters
        $category = $request->query('category');
        
        if ($category) {
            $client = $this->cardRepository->getCardNameByCategory($category);
            return response()->json($client);
        } else {
            return response()->json(['error' => 'Category parameter is missing'], 400);
        }
    }

    public function getCardsByCategory(Request $request)
    {
        // Retrieve 'category' and 'isPremium' from the request, or set defaults
        $category = $request->input('category', null);

        // Call the repository method
        $cards = $this->cardRepository->getCardsByCategory($category);

        // Return the result as a JSON response
        return response()->json($cards);
    }

    public function getCardsByPremium(Request $request)
    {
        // Get the category from the query parameters
        $isPremium = $request->query('isPremium');
        
        if ($isPremium) {
            $cards = $this->cardRepository->getCardsByPremium($isPremium);
            return response()->json($cards);
        } else {
            return response()->json(['error' => 'isPremium parameter is missing'], 400);
        }
    }

    public function getCardsByIsPlace(Request $request)
    {
        $isPlace = $request->query('is_place');
        
        if ($isPlace) {
            $cards = $this->cardRepository->getCardsByIsPlace($isPlace);
            return response()->json($cards);
        } else {
            return response()->json(['error' => 'is_place parameter is missing'], 400);
        }
    }

    public function getCardsByPlace(Request $request)
    {
        // Get the category from the query parameters
        $place = $request->query('place');
        
        if ($place) {
            $cards = $this->cardRepository->getCardsByPlace($place);
            return response()->json($cards);
        } else {
            return response()->json(['error' => 'place parameter is missing'], 400);
        }
    }

    public function getCardsByPlacesPerCategory(Request $request)
    {
        // Get the category from the query parameters
        $ownerId = $request->query('owner_id');
        $category = $request->query('category');
        
        if ($ownerId && $category) {
            $cards = $this->cardRepository->getCardsByPlacesPerCategory($ownerId, $category);
            return response()->json($cards);
        } else {
            return response()->json(['error' => 'owner_id and category parameters are missing'], 400);
        }
    }

    public function countCardsByCategory(Request $request)
    {
        // Get the category from the query parameters
        $category = $request->query('category');
        
        if ($category) {
            $count = $this->cardRepository->countCardsByCategory($category);
            // Return the category and the count in the JSON response
            return response()->json([
                'category' => $category,
                'count' => $count
            ]);
        } else {
            return response()->json(['error' => 'Category parameter is missing'], 400);
        }
    }

    public function registerMember(Request $request)
    {
        try {
            // Convert the request input to an array
            $cardData = $request->all();
    
            // Handle the file upload separately if necessary
            if ($request->hasFile('imageFile')) {
                $cardData['imageFile'] = $request->file('imageFile');
            }
    
            // Call the repository method to register the user with the array data
            $client = $this->cardRepository->registerMember($cardData);
    
            // Return a successful response with the client data
            return response()->json($client, 201);
        } catch (Exception $e) {
            // Handle any exceptions and return an error response
            return response()->json(['error' => 'Failed to register client.', 'message' => $e->getMessage()], 500);
        }
    }

    public function getLatAndLongdByCardId($cardId)
    {
        $card = $this->cardRepository->getLatAndLongdByCardId($cardId);
        return response()->json($card[0]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(Clients $clients)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Clients $clients)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Clients $clients)
    {
        //
    }
}
