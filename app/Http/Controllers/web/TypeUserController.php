<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\TypeUser;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class TypeUserController extends Controller
{
    /**
     * Get all TypeUsers
     * @OA\Get (
     *     path="/tecnimotors-backend/public/api/typeUser",
     *     tags={"TypeUser"},
     *     security={{"bearerAuth":{}}},
     *     @OA\Response(
     *         response=200,
     *         description="List of active TypeUsers",
     *         @OA\JsonContent(
     *             type="array",
     *             @OA\Items(ref="#/components/schemas/TypeUser")
     *         )
     *     ),
     *     @OA\Response(
     *         response=401,
     *         description="Unauthorized",
     *         @OA\JsonContent(
     *             @OA\Property(
     *                 property="message",
     *                 type="string",
     *                 example="Unauthenticated"
     *             )
     *         )
     *     )
     * )
     */

    public function index()
    {
        $typeUsers = TypeUser::simplePaginate(15);
        $typeUsers->getCollection()->transform(function ($typeUser) {
            $typeUser->optionMenuAccess = $typeUser->getAccess($typeUser->id);
            return $typeUser;
        });
        return response()->json($typeUsers);
    }

    /**
     * Show the specified TypeUser
     * @OA\Get (
     *     path="/tecnimotors-backend/public/api/typeUser/{id}",
     *     tags={"TypeUser"},
     *     security={{"bearerAuth":{}}},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         description="ID of the TypeUser",
     *         @OA\Schema(
     *             type="number"
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="TypeUser found",
     *
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="TypeUser not found",
     *         @OA\JsonContent(
     *             @OA\Property(
     *                 property="message",
     *                 type="string",
     *                 example="TypeUser not found"
     *             )
     *         )
     *     ),
     *     @OA\Response(
     *         response=401,
     *         description="Unauthorized",
     *         @OA\JsonContent(
     *             @OA\Property(
     *                 property="message",
     *                 type="string",
     *                 example="Unauthenticated"
     *             )
     *         )
     *     )
     * )
     */

    public function show(int $id)
    {

        $object = TypeUser::find($id);
        if ($object) {
            $object->optionMenuAccess = $object->getAccess($id);
            return response()->json($object, 200);
        }
        return response()->json(
            ['message' => 'TypeUser not found'], 404
        );

    }

    /**
     * Create a new Type User
     * @OA\Post (
     *     path="/tecnimotors-backend/public/api/typeUser",
     *     tags={"TypeUser"},
     *     security={{"bearerAuth":{}}},
     *     @OA\RequestBody(
     *          required=true,
     *          @OA\JsonContent(
     *              required={"name"},
     *              @OA\Property(
     *                  property="name",
     *                  type="string",
     *                  example="Admin"
     *              ),
     *          )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="New Type User created",
     *         @OA\JsonContent(
     *             ref="#/components/schemas/TypeUser"
     *         )
     *     ),
     *      @OA\Response(
     *          response=401,
     *          description="Unauthorized",
     *          @OA\JsonContent(
     *              @OA\Property(
     *                  property="message",
     *                  type="string",
     *                  example="Unauthenticated"
     *              )
     *          )
     *      )
     * )
     */
    public function store(Request $request)
    {
        $validator = validator()->make($request->all(), [
            'name' => [
                'required',
                Rule::unique('type_users')->whereNull('deleted_at'),
            ],
        ]);

        if ($validator->fails()) {
            return response()->json(['error' => $validator->errors()->first()], 422);
        }

        $data = [
            'name' => $request->input('name'),
        ];

        $object = TypeUser::create($data);
        $object = TypeUser::find($object->id);
        $object->optionMenuAccess = $object->getAccess($object->id);
        return response()->json($object, 200);
    }

    /**
     * Set Access for a Type User
     * @OA\Post (
     *     path="/tecnimotors-backend/public/api/typeUser/setAccess",
     *     tags={"TypeUser"},
     *     security={{"bearerAuth":{}}},
     *     @OA\RequestBody(
     *          required=true,
     *          @OA\JsonContent(
     *              required={"typeUser_id", "accesses"},
     *              @OA\Property(
     *                  property="typeUser_id",
     *                  type="integer",
     *                  example=1
     *              ),
     *              @OA\Property(
     *                  property="accesses",
     *                  type="array",
     *                  @OA\Items(type="integer"),
     *                  example={1, 2, 3}
     *              )
     *          )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Accesses set for Type User",
     *         @OA\JsonContent(
     *             ref="#/components/schemas/TypeUser"
     *         )
     *     ),
     *     @OA\Response(
     *         response=401,
     *         description="Unauthorized",
     *         @OA\JsonContent(
     *             @OA\Property(
     *                 property="message",
     *                 type="string",
     *                 example="Unauthenticated"
     *             )
     *         )
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="TypeUser not found",
     *         @OA\JsonContent(
     *             @OA\Property(
     *                 property="message",
     *                 type="string",
     *                 example="TypeUser not found"
     *             )
     *         )
     *     )
     * )
     */
    public function setAccess(Request $request)
    {
        $validator = validator()->make($request->all(), [
            'typeUser_id' => 'required|integer|exists:type_users,id',
            'accesses' => 'required|array',
            'accesses.*' => 'integer|exists:optionmenus,id',
        ]);

        if ($validator->fails()) {
            return response()->json(['error' => $validator->errors()->first()], 422);
        }

        $typeUserId = $request->input('typeUser_id');
        $accesses = $request->input('accesses');

        $typeUser = TypeUser::find($typeUserId);
        $typeUser->setAccess($typeUserId, $accesses);

        $typeUser->optionMenuAccess = $typeUser->getAccess($typeUser->id);
        return response()->json($typeUser, 200);
    }

    /**
     * Update the specified Type User
     * @OA\Put (
     *     path="/tecnimotors-backend/public/api/typeUser/{id}",
     *     tags={"TypeUser"},
     *     security={{"bearerAuth":{}}},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         description="ID of the TypeUser",
     *         @OA\Schema(
     *             type="number"
     *         )
     *     ),
     *     @OA\RequestBody(
     *          required=true,
     *          @OA\JsonContent(
     *              required={"name"},
     *              @OA\Property(
     *                  property="name",
     *                  type="string",
     *                  example="Admin"
     *              ),
     *              @OA\Property(
     *                  property="icon",
     *                  type="string",
     *                  example="fas fa-user"
     *              ),

     *          )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="TypeUser updated",
     *         @OA\JsonContent(
     *             ref="#/components/schemas/TypeUser"
     *         )
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="TypeUser not found",
     *         @OA\JsonContent(
     *             @OA\Property(
     *                 property="message",
     *                 type="string",
     *                 example="TypeUser not found"
     *             )
     *         )
     *     ),
     *     @OA\Response(
     *         response=401,
     *         description="Unauthorized",
     *         @OA\JsonContent(
     *             @OA\Property(
     *                 property="message",
     *                 type="string",
     *                 example="Unauthenticated"
     *             )
     *         )
     *     )
     * )
     */
    public function update(Request $request, int $id)
    {

        $object = TypeUser::find($id);
        if (!$object) {
            return response()->json(
                ['message' => 'TypeUser not found'], 404
            );
        }
        $validator = validator()->make($request->all(), [
            'name' => [
                'required',
                Rule::unique('type_users')->ignore($id)->whereNull('deleted_at'),
            ],

        ]);

        if ($validator->fails()) {
            return response()->json(['error' => $validator->errors()->first()], 422);
        }

        $data = [
            'name' => $request->input('name'),
        ];

        $object->update($data);
        $object = TypeUser::find($object->id);
        return response()->json($object, 200);

    }

    /**
     * Remove the specified TypeUser
     * @OA\Delete (
     *     path="/tecnimotors-backend/public/api/typeUser/{id}",
     *     tags={"TypeUser"},
     *     security={{"bearerAuth":{}}},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         description="ID of the TypeUser",
     *         @OA\Schema(
     *             type="number"
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="TypeUser deleted",
     *         @OA\JsonContent(
     *             @OA\Property(
     *                 property="message",
     *                 type="string",
     *                 example="TypeUser deleted successfully"
     *             )
     *         )
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="TypeUser not found",
     *         @OA\JsonContent(
     *             @OA\Property(
     *                 property="message",
     *                 type="string",
     *                 example="TypeUser not found"
     *             )
     *         )
     *     ),
     *     @OA\Response(
     *         response=401,
     *         description="Unauthorized",
     *         @OA\JsonContent(
     *             @OA\Property(
     *                 property="message",
     *                 type="string",
     *                 example="Unauthenticated"
     *             )
     *         )
     *     ),

     * )
     *
     */
    public function destroy(int $id)
    {
        $object = TypeUser::find($id);
        if (!$object) {
            return response()->json(
                ['message' => 'TypeUser not found'], 404
            );
        }
        if (count($object->getAccess($id)) > 0) {
            return response()->json(
                ['message' => 'TypeUser has Access associated'], 409
            );
        }
        $object->delete();

    }
}
