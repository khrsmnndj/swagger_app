<?php

namespace App\Models;
use App\Models\Cars;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * @OA\Schema(
 *     schema="Pilots",
 *     type="object",
 *
 *     @OA\Property(
 *          property="id",
 *          type="integer",
 *          example="1"
 *     ),
 *
 *     @OA\Property(
 *          property="name",
 *          type="string",
 *          example="Leopord"
 *     ),
 *
 *     @OA\Property(
 *          property="level",
 *          type="string",
 *          example="sergeant"
 *     ),
 *
 *     @OA\Property(
 *          property="created_at",
 *          type="string",
 *          format="date-time",
 *          example="2022-05-13T00:00:00Z"
 *     ),
 *
 *     @OA\Property(
 *          property="updated_at",
 *          type="string",
 *          format="date-time",
 *          example="2022-05-13T00:00:00Z"
 *     )
 * )
 */

class Pilot extends Model
{
    use HasFactory;

    protected $fillable = ['name', 'level'];

    public function pilot_cars()
    {
        return $this->hasMany(Cars::class);
    }

    public function delete()
    {
        Cars::where("pilot_id", $this->id)->delete();
        return parent::delete();
    }
}
