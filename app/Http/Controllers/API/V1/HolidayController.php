<?php

declare(strict_types=1);

namespace App\Http\Controllers\API\V1;

use App\Http\Controllers\Controller;
use App\Models\Holiday;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class HolidayController extends Controller
{
    /** @var array<string|Holiday> */
    public array $dataSaved = [];

    /**
     * Display the specified resource.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function create(Request $request)
    {
        DB::beginTransaction();
        try {
            $json = json_decode($request->getContent());
            $json = (is_array($json)) ? $json : [$json];

            foreach ($json as $item) {
                $this->createHoliday($item->date);
            }
        } catch (\Exception $e) {
            DB::rollBack();

            return response()->json(['message' => 'Holiday not created', 'error' => $e->getMessage()], 500);
        }
        DB::commit();

        return response()->json(['message' => 'Holiday created', 'data' => $this->dataSaved], 201);
    }

    /**
     * @param  string  $date  YYYY-mm-dd format
     * @return void
     */
    public function createHoliday($date)
    {
        $holiday = new Holiday;
        $holiday->date = $date;

        $holiday->save();
        $this->dataSaved[] = $holiday;
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  string  $date  YYYY-mm-dd format
     * @return \Illuminate\Http\JsonResponse
     */
    public function destroy(Request $request, $date)
    {
        if (Holiday::where('date', $date)->exists()) {
            $holidays = Holiday::where('date', $date)->get();
            foreach ($holidays as $holiday) {
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

    /**
     * Delete all of the holidays via the API.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function destroyAll(Request $request)
    {
        $holidays = Holiday::all();
        foreach ($holidays as $holiday) {
            $holiday->delete();
        }

        return response()->json([
            'message' => 'Holiday deleted',
        ], 202);
    }
}
