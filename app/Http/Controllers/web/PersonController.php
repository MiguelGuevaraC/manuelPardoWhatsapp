<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Person;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class PersonController extends Controller
{
    /**
     * Get all Persons
     * @OA\Get (
     *     path="/tecnimotors-backend/public/api/person",
     *     tags={"Person"},
     *     security={{"bearerAuth":{}}},
     *     @OA\Response(
     *         response=200,
     *         description="List of active Persons",
     *         @OA\JsonContent(
     *             type="array",
     *             @OA\Items(ref="#/components/schemas/Person")
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
        //REVISAR LO DE LA PAGINACIÓN
        return response()->json(Person::where('id', '!=', 1)->simplePaginate(100));

    }

    /**
     * @OA\Post(
     *      path="/tecnimotors-backend/public/api/person",
     *      summary="Store a new person",
     *      tags={"Person"},
     *      security={{"bearerAuth": {}}},
     *      @OA\RequestBody(
     *          required=true,
     *          @OA\JsonContent(
     *              required={"typeofDocument","documentNumber"},
     *              @OA\Property(property="typeofDocument", type="string", example="DNI", description="Type of Document"),
     *              @OA\Property(property="documentNumber", type="string", example="12345678", description="Document Number"),
     *              @OA\Property(property="names", type="string", example="John Doe", description="Names"),
     *              @OA\Property(property="fatherSurname", type="string", example="Doe", description="Father's Surname"),
     *              @OA\Property(property="motherSurname", type="string", example="Smith", description="Mother's Surname"),
     *              @OA\Property(property="businessName", type="string", example="Doe Enterprises", description="Business Name"),
     *              @OA\Property(property="representativeDni", type="string", example="87654321", description="Representative's DNI"),
     *              @OA\Property(property="representativeNames", type="string", example="Jane Doe", description="Representative's Names"),
     *              @OA\Property(property="address", type="string", example="123 Main St", description="Address"),
     *              @OA\Property(property="phone", type="string", example="+123456789", description="Phone Number"),
     *              @OA\Property(property="email", type="string", example="example@example.com", description="Email"),
     *              @OA\Property(property="origin", type="string", example="USA", description="Origin"),
     *              @OA\Property(property="ocupation", type="string", example="Engineer", description="Occupation")
     *          )
     *      ),
     *      @OA\Response(
     *          response=201,
     *          description="Person created",
     *          @OA\JsonContent(ref="#/components/schemas/Person")
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
     *          description="Person not found",
     *          @OA\JsonContent(
     *              @OA\Property(property="message", type="string", example="Person not found")
     *          )
     *      )
     * )
     */

    public function store(Request $request)
    {

        $validator = validator()->make($request->all(), [
            'typeofDocument' => 'required',
            'documentNumber' => [
                'required',
                Rule::unique('people')->whereNull('deleted_at'),
            ],
        ]);

        if ($validator->fails()) {
            return response()->json(['error' => $validator->errors()->first()], 422);
        }

        $data = [
            'typeofDocument' => $request->input('typeofDocument'),
            'documentNumber' => $request->input('documentNumber'),
            'address' => $request->input('address') ?? null,
            'phone' => $request->input('phone') ?? null,
            'email' => $request->input('email') ?? null,
            'origin' => $request->input('origin') ?? null,
            'ocupation' => $request->input('ocupation') ?? null,
            'names' => null,
            'fatherSurname' => null,
            'motherSurname' => null,
            'businessName' => null,
            'representativeDni' => null,
            'representativeNames' => null,
        ];

        if ($request->input('typeofDocument') == 'DNI') {
            $data['names'] = $request->input('names') ?? null;
            $data['fatherSurname'] = $request->input('fatherSurname') ?? null;
            $data['motherSurname'] = $request->input('motherSurname') ?? null;
        } elseif ($request->input('typeofDocument') == 'RUC') {
            $data['businessName'] = $request->input('businessName') ?? null;
            $data['representativeDni'] = $request->input('representativeDni') ?? null;
            $data['representativeNames'] = $request->input('representativeNames') ?? null;
        }

        $object = Person::create($data);
        $object = Person::find($object->id);
        return response()->json($object, 200);

    }

    /**
     * Show the specified Person
     * @OA\Get (
     *     path="/tecnimotors-backend/public/api/person/{id}",
     *     tags={"Person"},
     *     security={{"bearerAuth":{}}},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         description="ID of the Person",
     *         @OA\Schema(
     *             type="number"
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Person found",
     *
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Person not found",
     *         @OA\JsonContent(
     *             @OA\Property(
     *                 property="message",
     *                 type="string",
     *                 example="Person not found"
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
     */

    public function show(int $id)
    {

        $object = Person::find($id);
        if ($object) {
            return response()->json($object, 200);
        }
        return response()->json(
            ['message' => 'Person not found'], 404
        );

    }

    /**
     * @OA\Put(
     *     path="/tecnimotors-backend/public/api/person/{id}",
     *     summary="Update person by ID",
     *     tags={"Person"},
     *     security={{"bearerAuth": {}}},
     *     @OA\Parameter(
     *          name="id",
     *          in="path",
     *          description="ID of person",
     *          required=true,
     *          example="1",
     *          @OA\Schema(
     *              type="string"
     *          )
     *     ),
     *      @OA\RequestBody(
     *          required=true,
     *          @OA\JsonContent(
     *              required={"typeofDocument","documentNumber"},
     *              @OA\Property(property="typeofDocument", type="string", example="DNI", description="Type of Document"),
     *              @OA\Property(property="documentNumber", type="string", example="12345678", description="Document Number"),
     *              @OA\Property(property="names", type="string", example="John Doe", description="Names"),
     *              @OA\Property(property="fatherSurname", type="string", example="Doe", description="Father's Surname"),
     *              @OA\Property(property="motherSurname", type="string", example="Smith", description="Mother's Surname"),
     *              @OA\Property(property="businessName", type="string", example="Doe Enterprises", description="Business Name"),
     *              @OA\Property(property="representativeDni", type="string", example="87654321", description="Representative's DNI"),
     *              @OA\Property(property="representativeNames", type="string", example="Jane Doe", description="Representative's Names"),
     *              @OA\Property(property="address", type="string", example="123 Main St", description="Address"),
     *              @OA\Property(property="phone", type="string", example="+123456789", description="Phone Number"),
     *              @OA\Property(property="email", type="string", example="example@example.com", description="Email"),
     *              @OA\Property(property="origin", type="string", example="USA", description="Origin"),
     *              @OA\Property(property="ocupation", type="string", example="Engineer", description="Occupation")
     *          )
     *      ),
     *     @OA\Response(
     *          response=200,
     *          description="User updated",
     *          @OA\JsonContent(ref="#/components/schemas/User")
     *     ),
     *     @OA\Response(
     *          response=404,
     *          description="Person or User not found",
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

        $object = Person::find($id);

        if (!$object) {
            return response()->json(
                ['message' => 'User not found'], 404
            );
        }
        $validator = validator()->make($request->all(), [
            'typeofDocument' => 'required',
            'documentNumber' => [
                'required',
                Rule::unique('people')->ignore($id)->whereNull('deleted_at'),
            ],
        ]);

        if ($validator->fails()) {
            return response()->json(['error' => $validator->errors()->first()], 422);
        }

        $data = [
            'typeofDocument' => $request->input('typeofDocument'),
            'documentNumber' => $request->input('documentNumber'),
            'address' => $request->input('address') ?? null,
            'phone' => $request->input('phone') ?? null,
            'email' => $request->input('email') ?? null,
            'origin' => $request->input('origin') ?? null,
            'ocupation' => $request->input('ocupation') ?? null,
        ];

        if ($request->input('typeofDocument') == 'DNI') {
            $data['names'] = $request->input('names') ?? null;
            $data['fatherSurname'] = $request->input('fatherSurname') ?? null;
            $data['motherSurname'] = $request->input('motherSurname') ?? null;
            $data['businessName'] = null;
            $data['representativeDni'] = null;
            $data['representativeNames'] = null;
        } elseif ($request->input('typeofDocument') == 'RUC') {
            $data['names'] = null;
            $data['fatherSurname'] = null;
            $data['motherSurname'] = null;
            $data['businessName'] = $request->input('businessName') ?? null;
            $data['representativeDni'] = $request->input('representativeDni') ?? null;
            $data['representativeNames'] = $request->input('representativeNames') ?? null;
        }

        $object->update($data);
        $object = Person::find($object->id);
        return response()->json($object, 200);
    }

    /**
     * Remove the specified Person
     * @OA\Delete (
     *     path="/tecnimotors-backend/public/api/person/{id}",
     *     tags={"Person"},
     *     security={{"bearerAuth":{}}},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         description="ID of the Person",
     *         @OA\Schema(
     *             type="number"
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Person deleted",
     *         @OA\JsonContent(
     *             @OA\Property(
     *                 property="message",
     *                 type="string",
     *                 example="Person deleted successfully"
     *             )
     *         )
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Person not found",
     *         @OA\JsonContent(
     *             @OA\Property(
     *                 property="message",
     *                 type="string",
     *                 example="Person not found"
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
        $object = Person::find($id);
        if (!$object) {
            return response()->json(
                ['message' => 'Person not found'], 404
            );
        }
        //REVISAR ASOCIACIONES
        $object->delete();
    }

}
