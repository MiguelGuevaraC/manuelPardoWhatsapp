<?php

namespace App\Http\Controllers\web;

use App\Http\Controllers\Controller;
use App\Models\Optionmenu;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class OptionMenuController extends Controller
{
    public function __construct()
    {
        $this->middleware('ensureTokenIsValid');
    }
    
    /**
     * Get all Option Menus
     * @OA\Get (
     *     path="/tecnimotors-backend/public/api/optionMenu",
     *     tags={"Option Menu"},
     *     security={{"bearerAuth":{}}},
     *     @OA\Response(
     *         response=200,
     *         description="List of active Option Menus",
     *         @OA\JsonContent(
     *             type="array",
     *             @OA\Items(ref="#/components/schemas/OptionMenu")
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
        return response()->json(Optionmenu::simplePaginate(15));
    }

    /**
     * Create a new Option Menu
     * @OA\Post (
     *     path="/tecnimotors-backend/public/api/optionMenu",
     *     tags={"Option Menu"},
     *     security={{"bearerAuth":{}}},
     *     @OA\RequestBody(
     *          required=true,
     *          @OA\JsonContent(
     *              required={"name","route", "icon"},
     *              @OA\Property(
     *                  property="name",
     *                  type="string",
     *                  example="Admin"
     *              ),
     *              @OA\Property(
     *                  property="route",
     *                  type="string",
     *                  example="admin"
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
     *         description="New Option Menu created",
     *         @OA\JsonContent(
     *             ref="#/components/schemas/OptionMenu"
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
                Rule::unique('optionmenus')->whereNull('deleted_at'),
            ],
            'route' => [
                'required',
                Rule::unique('optionmenus')->whereNull('deleted_at'),
            ],
            'icon' => 'required|string',
        ]);

        if ($validator->fails()) {
            return response()->json(['error' => $validator->errors()->first()], 422);
        }

        $data = [
            'name' => $request->input('name'),
            'route' => $request->input('route'),
            'icon' => $request->input('icon'),
        ];

        $object = Optionmenu::create($data);
        $object = Optionmenu::find($object->id);
        return response()->json($object, 200);
    }

    /**
     * Update the specified Group menu
     * @OA\Put (
     *     path="/tecnimotors-backend/public/api/optionMenu/{id}",
     *     tags={"Option Menu"},
     *     security={{"bearerAuth":{}}},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         description="ID of the Option Menu",
     *         @OA\Schema(
     *             type="number"
     *         )
     *     ),
     *     @OA\RequestBody(
     *          required=true,
     *          @OA\JsonContent(
     *              required={"name", "icon","route", "order"},
     *              @OA\Property(
     *                  property="name",
     *                  type="string",
     *                  example="Admin"
     *              ),
     *          @OA\Property(
     *                  property="route",
     *                  type="string",
     *                  example="admin"
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
     *         description="Option Menu updated",
     *         @OA\JsonContent(
     *             ref="#/components/schemas/OptionMenu"
     *         )
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Option Menu not found",
     *         @OA\JsonContent(
     *             @OA\Property(
     *                 property="message",
     *                 type="string",
     *                 example="Option Menu not found"
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
    public function update(Request $request,$id)
    {

        $object = OptionMenu::find($id);
       
        if (!$object) {
            return response()->json(
                ['message' => 'Option Menu not found'], 404
            );
        }
        $validator = validator()->make($request->all(), [
            'name' => [
                'required',
                Rule::unique('optionmenus')->ignore($id)->whereNull('deleted_at'),
            ],
            'route' => [
                'required',
                Rule::unique('optionmenus')->ignore($id)->whereNull('deleted_at'),
            ],
            'icon' => 'required|string',
        ]);

        if ($validator->fails()) {
            return response()->json(['error' => $validator->errors()->first()], 422);
        }

        $data = [
            'name' => $request->input('name'),
            'route' => $request->input('route'),
            'icon' => $request->input('icon'),
        ];

        $object->update($data);
        $object =Optionmenu::find($object->id);
        return response()->json($object, 200);

    }

    /**
     * Show the specified Option Menu
     * @OA\Get (
     *     path="/tecnimotors-backend/public/api/optionMenu/{id}",
     *     tags={"Option Menu"},
     *     security={{"bearerAuth":{}}},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         description="ID of the Option Menu",
     *         @OA\Schema(
     *             type="number"
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Option Menu found",
     *
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Option Menu not found",
     *         @OA\JsonContent(
     *             @OA\Property(
     *                 property="message",
     *                 type="string",
     *                 example="Option Menu not found"
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

        $object = Optionmenu::find($id);
        if ($object) {
            return response()->json($object, 200);
        }
        return response()->json(
            ['message' => 'Option Menu not found'], 404
        );

    }

    /**
     * Remove the specified Option Menu
     * @OA\Delete (
     *     path="/tecnimotors-backend/public/api/optionMenu/{id}",
     *     tags={"Option Menu"},
     *     security={{"bearerAuth":{}}},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         description="ID of the Option Menu",
     *         @OA\Schema(
     *             type="number"
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Option Menu deleted",
     *         @OA\JsonContent(
     *             @OA\Property(
     *                 property="message",
     *                 type="string",
     *                 example="Option Menu deleted successfully"
     *             )
     *         )
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Option Menu not found",
     *         @OA\JsonContent(
     *             @OA\Property(
     *                 property="message",
     *                 type="string",
     *                 example="Option Menu not found"
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
        $object = Optionmenu::find($id);
        if (!$object) {
            return response()->json(
                ['message' => 'Option Menu not found'], 404
            );
        }
        $object->delete();
    }
}
