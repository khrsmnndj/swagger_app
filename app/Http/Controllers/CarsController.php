<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests\CreateCars;
use App\Http\Requests\UpdateCars;
use App\Models\Cars;
use App\Models\Pilot;
use http\Env\Response;
use OpenApi\Annotations as OA;

class CarsController extends Controller
{
    /**
     * @OA\Get(
     *     path="/pilot-api/v0/cars",
     *     tags={"Cars Data"},
     *     summary="Get Cars",
     *     operationId="getCars",
     * 
     *     @OA\Response(
     *         response=200,
     *         description="Successful",
     *          @OA\JsonContent(
     *              type="object",
     *              @OA\Property(
     *                  property="current_page",
     *                  type="integer",
     *                  example="1"
     *              ),
     *              @OA\Property(
     *                  property="data",
     *                  type="array",
     *                  @OA\Items(type="object", ref="#/components/schemas/Cars")
     *              ),
     *              @OA\Property(
     *                  property="first_page_url",
     *                  type="string",
     *                  example="/pilot-api/v0/cars?page=1"
     *              ),
     *              @OA\Property(
     *                  property="from",
     *                  type="integer",
     *                  example="1"
     *              ),
     *              @OA\Property(
     *                  property="last_page",
     *                  type="integer",
     *                  example="10"
     *              ),
     *          ),
     *     ),
     *      @OA\Response(
     *         response=404,
     *         description="Not Found"
     *     )
     * )
     */
    public function index()
    {
        $cars = Cars::orderBy('id', 'desc')->paginate(10);
        if ($cars) {
            return response()->json(["data"=>$cars], 201);
        } else {
            return response()->json(["error" => [
                    "code" => 404,
                    "message" => "Failed."
                ],
            ], 404);
        };
    }

    /**
     * @OA\Post(
     *     path="/pilot-api/v0/cars",
     *     tags={"Cars Data"},
     *     summary="Post Cars",
     *     operationId="postCars",
     *     @OA\RequestBody(
     *          @OA\JsonContent(
     *              type="object",
     *              @OA\Property(property="name", type="string", example="John Snow"),
     *              @OA\Property(property="pilot_id", type="integer", example="1"),
     *      ),

     *     @OA\Response(
     *         response=201,
     *         description="Car has been created",
     *          @OA\JsonContent(
     *              type="object",
     *              @OA\Property(property="data", type="object", ref="#/components/schemas/Cars")
     *          ),
     *     ),
     * 
     *      @OA\Response(
     *         response=400,
     *         description="Bad Request"
     *     ),
     * )
     */
    
    public function store(CreateCars $request)
    {
        $cars = Cars::create($request->post());
        if(!$cars) {
            return response()->json(["error" => [
                    "code" => 400,
                    "message" => "Bad Request"
                ],
            ], 400);
        };

        return response()->json([
            "data" => $cars,
        ], 201);
    }

    /**
     * @OA\Put(
     *     path="/pilot-api/v0/cars/{id}",
     *     tags={"Cars Data"},
     *     summary="Update Cars",
     *     operationId="updateCars",
     *     @OA\Parameter(
     *          in="path",
     *          required=true,
     *          name="id",
     *          description="The id of the car",
     *          @OA\Schema(
     *              type="integer",
     *              example="1"
     *          ),
     *     ),
     *     @OA\RequestBody(
     *          @OA\JsonContent(
     *              type="object",
     *              @OA\Property(property="name", type="string", example="John Snow"),
     *              @OA\Property(property="pilot_id", type="integer", example="1"),
     *      ),

     *     @OA\Response(
     *         response=201,
     *         description="Car has been updated",
     *          @OA\JsonContent(
     *              type="object",
     *              @OA\Property(property="data", type="object", ref="#/components/schemas/Cars")
     *          ),
     *     ),
     * 
     *      @OA\Response(
     *         response=400,
     *         description="Bad Request"
     *     ),
     * )
     */

    public function update(UpdateCars $request, $id)
    {
        $car = Cars::find($id);
        if($car){
            $car->name = $request->has('name') ? $request->name : $car->name;
            $car->pilot_id = $request->has('pilot_id') ? $request->pilot_id : $car->pilot_id;
            $car->save();

            return response()->json([
                "data" => $car,
            ], 201);
        } else {
            return response()->json([ 
                "error" => [
                    "code" => 400,
                    "message" => "Failed."
                ]
            ], 400);
        };
    }

    /**
     * @OA\Delete(
     *     path="/pilot-api/v0/cars/{id}",
     *     tags={"Cars Data"},
     *     summary="Delete Cars",
     *     operationId="deleteCars",
     *     @OA\Parameter(
     *          in="path",
     *          required=true,
     *          name="id",
     *          description="The id of the car",
     *          @OA\Schema(
     *              type="integer",
     *              example="1"
     *          ),
     *     ),
     *     @OA\Response(
     *         response=201,
     *         description="Car has been deleted",
     *     ),
     * 
     *      @OA\Response(
     *         response=400,
     *         description="Bad Request"
     *     ),
     * )
     */


    public function destroy($id)
    {
        $car = Cars::find($id);
        if(!$car){
            return response()->json([ 
                "error" => [
                    "code" => 500,
                    "message" => "Failed."
                ],
            ], 500);
        };

        $car->delete();
        return response()->json(null, 204);
    }

}
