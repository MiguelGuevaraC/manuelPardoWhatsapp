<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\GroupMenu;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class GroupMenuController extends Controller
{

    /**
     * Get all Group menus
     * @OA\Get (
     *     path="/tecnimotors-backend/public/api/groupmenu",
     *     tags={"Group Menu"},
     *     security={{"bearerAuth":{}}},
     *     @OA\Response(
     *         response=200,
     *         description="List of active Group Menus",
     *         @OA\JsonContent(
     *             type="array",
     *             @OA\Items(ref="#/components/schemas/GroupMenu")
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
        $userTypeId = auth()->user()->typeofUser_id;

        $groupMenus = GroupMenu::getFilteredGroupMenus($userTypeId);

        return response()->json($groupMenus);
    }

    /**
     * Create a new Group menu
     * @OA\Post (
     *     path="/tecnimotors-backend/public/api/groupmenu",
     *     tags={"Group Menu"},
     *     security={{"bearerAuth":{}}},
     *     @OA\RequestBody(
     *          required=true,
     *          @OA\JsonContent(
     *              required={"name", "icon"},
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
     *         description="New Group Menu created",
     *         @OA\JsonContent(
     *             ref="#/components/schemas/GroupMenu"
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
                Rule::unique('group_menus')->whereNull('deleted_at'),
            ],
            'icon' => 'required|string',
        ]);

        if ($validator->fails()) {
            return response()->json(['error' => $validator->errors()->first()], 422);
        }

        $data = [
            'name' => $request->input('name'),
            'icon' => $request->input('icon'),
        ];

        $object = GroupMenu::create($data);
        $object = GroupMenu::with(['optionMenus'])->find($object->id);
        return response()->json($object, 200);
    }

    /**
     * Show the specified Group menu
     * @OA\Get (
     *     path="/tecnimotors-backend/public/api/groupmenu/{id}",
     *     tags={"Group Menu"},
     *     security={{"bearerAuth":{}}},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         description="ID of the Group Menu",
     *         @OA\Schema(
     *             type="number"
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Group Menu found",
     *
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Group Menu not found",
     *         @OA\JsonContent(
     *             @OA\Property(
     *                 property="message",
     *                 type="string",
     *                 example="Group Menu not found"
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

        $object = GroupMenu::with(['optionMenus'])->find($id);
        if ($object) {
            return response()->json($object, 200);
        }
        return response()->json(
            ['message' => 'Group Menu not found'], 404
        );

    }

    /**
     * Update the specified Group menu
     * @OA\Put (
     *     path="/tecnimotors-backend/public/api/groupmenu/{id}",
     *     tags={"Group Menu"},
     *     security={{"bearerAuth":{}}},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         description="ID of the Group Menu",
     *         @OA\Schema(
     *             type="number"
     *         )
     *     ),
     *     @OA\RequestBody(
     *          required=true,
     *          @OA\JsonContent(
     *              required={"name", "icon"},
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
     *         description="Group Menu updated",
     *         @OA\JsonContent(
     *             ref="#/components/schemas/GroupMenu"
     *         )
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Group Menu not found",
     *         @OA\JsonContent(
     *             @OA\Property(
     *                 property="message",
     *                 type="string",
     *                 example="Group Menu not found"
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

        $object = GroupMenu::find($id);
        if (!$object) {
            return response()->json(
                ['message' => 'Group Menu not found'], 404
            );
        }
        $validator = validator()->make($request->all(), [
            'name' => [
                'required',
                Rule::unique('group_menus')->ignore($id)->whereNull('deleted_at'),
            ],
            'icon' => 'required|string',
        ]);

        if ($validator->fails()) {
            return response()->json(['error' => $validator->errors()->first()], 422);
        }

        $data = [
            'name' => $request->input('name'),
            'icon' => $request->input('icon'),
        ];

        $object->update($data);
        $object = GroupMenu::with(['optionMenus'])->find($object->id);
        return response()->json($object, 200);

    }

    /**
     * Remove the specified Group menu
     * @OA\Delete (
     *     path="/tecnimotors-backend/public/api/groupmenu/{id}",
     *     tags={"Group Menu"},
     *     security={{"bearerAuth":{}}},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         description="ID of the Group Menu",
     *         @OA\Schema(
     *             type="number"
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Group Menu deleted",
     *         @OA\JsonContent(
     *             @OA\Property(
     *                 property="message",
     *                 type="string",
     *                 example="Group Menu deleted successfully"
     *             )
     *         )
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Group Menu not found",
     *         @OA\JsonContent(
     *             @OA\Property(
     *                 property="message",
     *                 type="string",
     *                 example="Group Menu not found"
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
     *         description="Group Menu has option menus associated",
     *         @OA\JsonContent(
     *             @OA\Property(
     *                 property="message",
     *                 type="string",
     *                 example="Group Menu has option menus associated"
     *             )
     *         )
     *     )
     * )
     *
     */
    public function destroy(int $id)
    {
        $groupMenu = GroupMenu::find($id);
        if (!$groupMenu) {
            return response()->json(
                ['message' => 'Group Menu not found'], 404
            );
        }
        if ($groupMenu->optionMenus()->count() > 0) {
            return response()->json(
                ['message' => 'Group Menu has option menus associated'], 409
            );
        }
        $groupMenu->delete();

        return response()->json(
            ['message' => 'Group Menu deleted successfully']
        );

    }
}
