<?php

namespace App\Http\Controllers\Api\v1;

use App\Http\Controllers\Controller;
use App\Repositories\cards_status\CardStatusRepositoryInterface;
use Illuminate\Http\Request;
use Exception;
use Carbon\Carbon;

class CardStatusController extends Controller
{
    public function __construct(CardStatusRepositoryInterface $cardStatusRepository)
    {
        $this->cardStatusRepository = $cardStatusRepository;
    }

    public function insertCardStatus(Request $request)
    {
        $cardStatus = $request->all(); // Example for retrieving input
        return $this->cardStatusRepository->insertCardStatus($cardStatus);
    }

    public function countByStatus()
    {
        $count = $this->cardStatusRepository->countByStatus();

        if (!empty($count)) {
            return response()->json($count[0]); // Return the first element as an object
        }
    
        return response()->json(['visited_count' => 0, 'downloaded_count' => 0]);
    }

    public function countByStatusAndDate(Request $request)
    {
        try {
            // Validate the incoming date from the request
            $request->validate([
                'date' => 'required|date_format:Y-m-d', // Ensures the format is 'YYYY-MM-DD'
            ]);

            // Convert the input date to a Carbon instance
            $date = Carbon::parse($request->input('date'));

            // Call the repository method to get the count data
            $countData = $this->cardStatusRepository->countByStatusAndDate($date);

            if (count($countData) > 0) {
                return response()->json($countData[0]); // Return the first element as an object
            }

            // Return the result as a JSON response
            return response()->json(['visited_count' => 0, 'downloaded_count' => 0], 200);

        } catch (Exception $e) {
            // Handle any exceptions and return an error response
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }

    public function countByStatusAndDateBetween(Request $request)
    {
        $startDate = Carbon::parse($request->startDate);
        $endDate = Carbon::parse($request->endDate);

        $count = $this->cardStatusRepository->countByStatusAndDateBetween($startDate, $endDate);

        if (count($count) > 0) {
            return response()->json($count[0]); // Return the first element as an object
        }

        return response()->json(['visited_count' => 0, 'downloaded_count' => 0]);
    }

    public function countByCityAndStatus(Request $request)
    {
        $city = $request->input('city');

        // Validate that 'city' is provided
        if (!$city) {
            return response()->json(['error' => 'City is required'], 400);
        }

        // Call the repository method to get the counts
        $counts = $this->cardStatusRepository->countByCityAndStatus($city);

        if (!empty($counts)) {
            return response()->json($counts[0]); // Return the first element as an object
        }

        return response()->json(['visited_count' => 0, 'downloaded_count' => 0]);
    }

    public function countByCardIdAndStatus(Request $request)
    {
        $cardId = $request->input('cardId');

        // Call the repository method to get the counts
        $counts = $this->cardStatusRepository->countByCardIdAndStatus($cardId);

        if (count($counts) > 0) {
            return response()->json($counts[0]); // Return the first element as an object
        }

        // Return the result as a JSON response
        return response()->json(['visited_count' => 0, 'downloaded_count' => 0], 200);
    }

    public function countByDateAndStatusAndCardId(Request $request)
    {
        $cardId = $request->input('cardId');
        $date = Carbon::parse($request->input('date'));

        // Call the repository method to get the counts
        $counts = $this->cardStatusRepository->countByDateAndStatusAndCardId($cardId, $date);

        if (count($counts) > 0) {
            return response()->json($counts[0]); // Return the first element as an object
        }

        // Return the result as a JSON response
        return response()->json(['visited_count' => 0, 'downloaded_count' => 0], 200);
    }

    public function countByStatusAndCardIdAndDateBetween(Request $request)
    {
        $cardId = $request->input('cardId');
        $startDate = Carbon::parse($request->startDate);
        $endDate = Carbon::parse($request->endDate);

        // Call the repository method to get the counts
        $counts = $this->cardStatusRepository->countByStatusAndCardIdAndDateBetween($cardId, $startDate, $endDate);

        if (count($counts) > 0) {
            return response()->json($counts[0]); // Return the first element as an object
        }

        // Return the result as a JSON response
        return response()->json(['visited_count' => 0, 'downloaded_count' => 0], 200);
    }

    public function countByCityAndStatusAndCardId(Request $request)
    {
        $cardId = $request->input('cardId');
        $city = $request->input('city');

        // Call the repository method to get the counts
        $counts = $this->cardStatusRepository->countByCityAndStatusAndCardId($cardId, $city);

        if (count($counts) > 0) {
            return response()->json($counts[0]); // Return the first element as an object
        }

        // Return the result as a JSON response
        return response()->json(['visited_count' => 0, 'downloaded_count' => 0], 200);
    }
}
