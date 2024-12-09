<?php

declare(strict_types=1);

namespace App\Http\Resources\V1;

use App\Models\Contact;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

/**
 * @property int $id
 * @property string $start
 * @property string $end
 * @property string $description
 * @property int $created_at
 * @property int $updated_at
 *
 * @method Contact contact()
 */
class AppointmentResource extends JsonResource
{
    public static $wrap = 'appointments';

    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        // API/v1/AppointmentResource
        return [
            'type' => 'appointments',
            'id' => $this->id,
            'attributes' => [
                'description' => $this->description,
                'start' => $this->start,
                'end' => $this->end,
                'created_at' => $this->created_at,
                'updated_at' => $this->updated_at,
            ],
            'relationships' => [
                'contact' => ContactResource::make($this->contact()),
            ],
            'links' => [
                'self' => route('appointments.show', $this->id),
            ],
        ];
    }

    /**
     * @return string[]
     */
    public function with(Request $request)
    {
        return [
            'status' => 'success',
        ];
    }

    public function withResponse(Request $request, JsonResponse $response)
    {
        $response->header('Accept', 'application/json');
    }
}
