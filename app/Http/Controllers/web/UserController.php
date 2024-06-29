<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{
    /**
     * Get all Group menus
     * @OA\Get (
     *     path="/tecnimotors-backend/public/api/user",
     *     tags={"User"},
     *     security={{"bearerAuth":{}}},
     *     @OA\Response(
     *         response=200,
     *         description="List of active Users",
     *         @OA\JsonContent(
     *             type="array",
     *             @OA\Items(ref="#/components/schemas/User")
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
        return response()->json(User::with(['typeUser'])->simplePaginate(15));
    }

    /**
     * @OA\Post(
     *      path="/tecnimotors-backend/public/api/user",
     *      summary="Store a new user",
     *      tags={"User"},
     *      security={{"bearerAuth": {}}},
     *      @OA\RequestBody(
     *          required=true,
     *          @OA\JsonContent(
     *              required={"username","password","typeofUser_id","worker_id"},
     *              @OA\Property(property="username", type="string", example="username", description="Username of the user"),
     *              @OA\Property(property="password", type="string", example="12345678", description="Password of the user"),
     *              @OA\Property(property="typeofUser_id", type="integer", example="1", description="Type of user"),
     *              @OA\Property(property="worker_id", type="integer", example="1", description="Worker of user")
     *          )
     *      ),
     *      @OA\Response(
     *          response=201,
     *          description="User created",
     *          @OA\JsonContent(ref="#/components/schemas/User")
     *      ),
     *      @OA\Response(
     *          response=401,
     *          description="Unauthenticated",
     *          @OA\JsonContent(
     *              @OA\Property(property="message", type="string", example="Unauthenticated.")
     *          )
     *      ),
     *      @OA\Response(
     *          response=404,
     *          description="User not found",
     *          @OA\JsonContent(
     *              @OA\Property(property="message", type="string", example="User not found")
     *          )
     *      )
     * )
     */

    public function store(Request $request)
    {
        $validator = validator()->make($request->all(), [
            'username' => 'required|string',
            'password' => 'required|string',
            'typeofUser_id' => 'required|exists:type_users,id',

        ]);

        if ($validator->fails()) {
            return response()->json(['error' => $validator->errors()->first()], 422);
        }

        $hashedPassword = Hash::make($request->password);

        $data = [
            'username' => $request->username,
            'password' => $hashedPassword,
            'typeofUser_id' => $request->typeofUser_id,

        ];

        $object = User::create($data);
        $object = User::find($object->id);
        return response()->json($object, 200);

    }

    /**
     * @OA\Put(
     *     path="/tecnimotors-backend/public/api/user/{id}",
     *     summary="Update user by ID",
     *     tags={"User"},
     *     security={{"bearerAuth": {}}},
     *     @OA\Parameter(
     *          name="id",
     *          in="path",
     *          description="ID of user",
     *          required=true,
     *          example="1",
     *          @OA\Schema(
     *              type="string"
     *          )
     *     ),
     *      @OA\RequestBody(
     *          required=true,
     *          @OA\JsonContent(
     *              required={"username","password","typeofUser_id","worker_id"},
     *              @OA\Property(property="username", type="string", example="username", description="Username of the user"),
     *              @OA\Property(property="password", type="string", example="12345678", description="Password of the user"),
     *              @OA\Property(property="typeofUser_id", type="integer", example="1", description="Type of user"),
     *              @OA\Property(property="worker_id", type="integer", example="1", description="Worker of user")
     *          )
     *      ),
     *     @OA\Response(
     *          response=200,
     *          description="User updated",
     *          @OA\JsonContent(ref="#/components/schemas/User")
     *     ),
     *     @OA\Response(
     *          response=404,
     *          description="User  not found",
     *          @OA\JsonContent(
     *              @OA\Property(property="message", type="string", example="User not found")
     *          )
     *     ),
     *     @OA\Response(
     *          response=401,
     *          description="Unauthenticated",
     *          @OA\JsonContent(
     *              @OA\Property(property="message", type="string", example="Unauthenticated.")
     *          )
     *     ),
     * )
     *
     */
    public function update(Request $request, string $id)
    {

        $object = User::find($id);

        if (!$object) {
            return response()->json(
                ['message' => 'User not found'], 404
            );
        }
        $validator = validator()->make($request->all(), [
            'username' => 'required|string',
            'password' => 'required|string',
            'typeofUser_id' => 'required|exists:type_users,id',
        ]);

        if ($validator->fails()) {
            return response()->json(['error' => $validator->errors()->first()], 422);
        }

        $hashedPassword = Hash::make($request->password);

        $data = [
            'username' => $request->username,
            'password' => $hashedPassword,
            'typeofUser_id' => $request->typeofUser_id,
        ];

        $object->update($data);
        $object = User::find($object->id);
        return response()->json($object, 200);
    }

    /**
     * Show the specified Group menu
     * @OA\Get (
     *     path="/tecnimotors-backend/public/api/user/{id}",
     *     tags={"User"},
     *     security={{"bearerAuth":{}}},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         description="ID of the User",
     *         @OA\Schema(
     *             type="number"
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="User found",
     *
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="User not found",
     *         @OA\JsonContent(
     *             @OA\Property(
     *                 property="message",
     *                 type="string",
     *                 example="User not found"
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

        $object = User::find($id);
        if ($object) {
            return response()->json($object, 200);
        }
        return response()->json(
            ['message' => 'User not found'], 404
        );

    }

    /**
     * Remove the specified Group menu
     * @OA\Delete (
     *     path="/tecnimotors-backend/public/api/user/{id}",
     *     tags={"User"},
     *     security={{"bearerAuth":{}}},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         description="ID of the User",
     *         @OA\Schema(
     *             type="number"
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="User deleted",
     *         @OA\JsonContent(
     *             @OA\Property(
     *                 property="message",
     *                 type="string",
     *                 example="User deleted successfully"
     *             )
     *         )
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="User not found",
     *         @OA\JsonContent(
     *             @OA\Property(
     *                 property="message",
     *                 type="string",
     *                 example="User not found"
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
     *     @OA\Response(
     *         response=409,
     *         description="User has option menus associated",
     *         @OA\JsonContent(
     *             @OA\Property(
     *                 property="message",
     *                 type="string",
     *                 example="User has option menus associated"
     *             )
     *         )
     *     )
     * )
     *
     */
    public function destroy(int $id)
    {
        $object = User::find($id);
        if (!$object) {
            return response()->json(
                ['message' => 'User not found'], 404
            );
        }

        $object->delete();

    }

}
