<?php

declare(strict_types=1);

namespace App\Http\Controllers\API\V1;

use App\Http\Controllers\Controller;
use App\Models\Holiday;
use Illuminate\Http\Request;
use PHPUnit\Exception;

class HolidayController extends Controller
{

    /**
     * Display the specified resource.
     */
    public function create(Request $request)
    {
        $holiday = new Holiday();
        $holiday->date = $request->date;
        try {
            $holiday->save();
            return response()->json(['message' => 'Holiday created', 'date' => $holiday->date], 201);
        } catch (\Exception $e) {
            return response()->json(['message' => 'Holiday not created', 'error' => $e->getMessage()], 500);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Request $request, $date)
    {
        if (Holiday::where('date', $date)->exists()) {
            $holidays = Holiday::where('date', $date)->all();
            foreach ($holidays as $holiday ) {
                $holiday->delete();
            }

            return response()->json([
                'message' => 'Holiday deleted',
            ], 202);
        } else {
            return response()->json([
                'message' => 'Not found',
            ], 404);
        }
    }
}
