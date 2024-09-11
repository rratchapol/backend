<?php

namespace App\Http\Controllers;
use App\Models\Preorder;
use Illuminate\Http\Request;

class PreorderController extends Controller
{
    /**
     * @OA\Get(
     *     path="/api/preorders",
     *     summary="Get a list of preorders",
     *     tags={"Preorders"},
     *     @OA\Response(response=200, description="Successful operation"),
     *     @OA\Response(response=400, description="Invalid request")
     * )
     */
    public function index()
    {
        $preorder = Preorder::all(); // ดึงข้อมูลผู้ใช้ทั้งหมดจากฐานข้อมูล
        return response()->json($preorder); // ส่งข้อมูลผู้ใช้ทั้งหมดกลับในรูปแบบ JSON
    }


    /**
     * @OA\Post(
     *     path="/api/preorders",
     *     summary="Create a new preorder",
     *     tags={"Preorders"},
     *     @OA\RequestBody(
     *         @OA\JsonContent(
     *             @OA\Property(property="buyer_id", type="integer"),
     *             @OA\Property(property="product_id", type="integer"),
     *             @OA\Property(property="qty", type="integer"),
     *             @OA\Property(property="deal_date", type="string", format="date"),
     *             @OA\Property(property="status", type="string"),
     *             @OA\Property(property="bill", type="string")
     *         )
     *     ),
     *     @OA\Response(response=201, description="Preorder created successfully"),
     *     @OA\Response(response=400, description="Invalid input")
     * )
     */
    public function store(Request $request)
    {
        $request->validate([
            'buyer_id' => 'required|integer',
            'product_id' => 'required|integer',
            'qty' => 'required|integer',
            'deal_date' => 'required|date',
            'status' => 'required|string',
            'bill' => 'required|string',
        ]);

        $preorder = Preorder::create([
            'buyer_id' => $request->buyer_id,
            'product_id' => $request->product_id,
            'qty' => $request->qty,
            'deal_date' => $request->deal_date,
            'status' => $request->status,
            'bill' => $request->bill,
        ]);

        return response()->json($preorder, 201);
    }


     /**
     * @OA\Get(
     *     path="/api/preorders/{id}",
     *     summary="Get a specific preorder",
     *     tags={"Preorders"},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         @OA\Schema(type="string")
     *     ),
     *     @OA\Response(response=200, description="Successful operation"),
     *     @OA\Response(response=404, description="Preorder not found")
     * )
     */
    public function show($id)
    {
        // $preorder = Preorder::findOrFail($id);
        // return response()->json($preorder);

        $preorder = Preorder::where('buyer_id', $id)->get();
        return response()->json($preorder);
    }


     /**
     * @OA\Put(
     *     path="/api/preorders/{id}",
     *     summary="Update a specific preorder",
     *     tags={"Preorders"},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         @OA\Schema(type="string")
     *     ),
     *     @OA\RequestBody(
     *         @OA\JsonContent(
     *             @OA\Property(property="buyer_id", type="integer"),
     *             @OA\Property(property="product_id", type="integer"),
     *             @OA\Property(property="qty", type="integer"),
     *             @OA\Property(property="deal_date", type="string", format="date"),
     *             @OA\Property(property="status", type="string"),
     *             @OA\Property(property="bill", type="string")
     *         )
     *     ),
     *     @OA\Response(response=200, description="Preorder updated successfully"),
     *     @OA\Response(response=404, description="Preorder not found"),
     *     @OA\Response(response=400, description="Invalid input")
     * )
     */
    public function update(Request $request, $id)
    {
        $request->validate([
            'buyer_id' => 'required|integer',
            'product_id' => 'required|integer',
            'qty' => 'required|integer',
            'deal_date' => 'required|date',
            'status' => 'required|string',
            'bill' => 'required|string',
        ]);

        $preorder = Preorder::findOrFail($id);
        $preorder->update($request->all());

        return response()->json($preorder);
    }


     /**
     * @OA\Delete(
     *     path="/api/preorders/{id}",
     *     summary="Delete a specific preorder",
     *     tags={"Preorders"},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         @OA\Schema(type="string")
     *     ),
     *     @OA\Response(response=200, description="Preorder deleted successfully"),
     *     @OA\Response(response=404, description="Preorder not found")
     * )
     */
    public function destroy($id)
    {

        // $userId = $request->input('buyer_id'); 
        // $preorderId = $request->input('product_id');
        // $preorder = Preorder::where('buyer_id', $userId)
        //                 ->where('product_id', $preorderId)
        //                 ->first();

        $preorder = Preorder::findOrFail($id);
        $preorder->delete();



        return response()->json(['message' => 'Preorder deleted successfully']);
    }
}
