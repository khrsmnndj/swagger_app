<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests\CreatePilot;
use App\Http\Requests\UpdatePilot;
use App\Models\Pilot;



class PilotController extends Controller
{
	/**
     * @OA\Get(
     *     path="/pilot-api/v0/pilots",
     *     tags={"Pilots Data"},
     *     summary="Get Pilots",
     *     operationId="getPilots",
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
     *                  @OA\Items(type="object", ref="#/components/schemas/Pilots")
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
		$pilots = Pilot::with('pilot_cars')->orderBy('id', 'desc')->paginate(10);
		if(!$pilots){
			return response()->json([
				"error" => [
					"code" => 404,
					"message" => "Failed."
				]
			], 404);
		};

		return response()->json(["data" => $pilots]);
	}


    /**
     * @OA\Post(
     *     path="/pilot-api/v0/pilots",
     *     tags={"Pilots Data"},
     *     summary="Post Pilots",
     *     operationId="postPilots",
     *     @OA\RequestBody(
     *          @OA\JsonContent(
     *              type="object",
     *              @OA\Property(property="name", type="string", example="John Snow"),
     *              @OA\Property(property="level", type="string", example="sergeant"),
     *      ),

     *     @OA\Response(
     *         response=201,
     *         description="Pilot has been created",
     *          @OA\JsonContent(
     *              type="object",
     *              @OA\Property(property="data", type="object", ref="#/components/schemas/Pilots")
     *          ),
     *     ),
     * 
     *      @OA\Response(
     *         response=400,
     *         description="Bad Request"
     *     ),
     * )
     */
    
	public function store(CreatePilot $request)
	{
		$pilot = Pilot::create($request->all());
		if(!$pilot){
			return response()->json([
				"error" => [
					"code" => 400,
					"message" => "Failed."
				]
			], 400);
		};

		return response()->json([
			"data" => $pilot,
		], 201);
	}

	 /**
     * @OA\Put(
     *     path="/pilot-api/v0/pilots/{id}",
     *     tags={"Pilots Data"},
     *     summary="Update Pilots",
     *     operationId="updatePilots",
     *     @OA\Parameter(
     *          in="path",
     *          required=true,
     *          name="id",
     *          description="The id of the pilot",
     *          @OA\Schema(
     *              type="integer",
     *              example="1"
     *          ),
     *     ),
     *     @OA\RequestBody(
     *          @OA\JsonContent(
     *              type="object",
     *              @OA\Property(property="name", type="string", example="Leomord"),
     *              @OA\Property(property="level", type="string", example="sergeant"),
     *      ),

     *     @OA\Response(
     *         response=201,
     *         description="Pilot has been updated",
     *          @OA\JsonContent(
     *              type="object",
     *              @OA\Property(property="data", type="object", ref="#/components/schemas/Pilots")
     *          ),
     *     ),
     * 
     *      @OA\Response(
     *         response=400,
     *         description="Bad Request"
     *     ),
     * )
     */

	public function update(UpdatePilot $request, $id)
	{
		$pilot = Pilot::find($id);
		if(!$pilot){
			return response()->json([
				"error" => [
					"code" => 400,
					"message" => "Failed."
				]
			], 400);
		};

		$pilot->name = $request->has('name') ? $request->name : $pilot->name;
		$pilot->level = $request->has('level') ? $request->level : $pilot->level;
		$pilot->save();

		return response()->json([
			"data" => $pilot,
		], 201);
	}

	/**
     * @OA\Delete(
     *     path="/pilot-api/v0/pilots/{id}",
     *     tags={"Pilots Data"},
     *     summary="Delete Pilots",
     *     operationId="deletePilots",
     *     @OA\Parameter(
     *          in="path",
     *          required=true,
     *          name="id",
     *          description="The id of the pilot",
     *          @OA\Schema(
     *              type="integer",
     *              example="1"
     *          ),
     *     ),
     *     @OA\Response(
     *         response=201,
     *         description="Pilot has been deleted",
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
		$pilot = Pilot::find($id);
		if(!$pilot){
			return response()->json([
				"error" => [
					"code" => 400,
					"message" => "Failed."
				]
			], 400);
		};

		$pilot->delete();
		return response()->json(null, 204);
	}
    
}
