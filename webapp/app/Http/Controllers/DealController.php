<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Deal;

class DealController extends Controller
{
    /**
     * @OA\Get(
     *     path="/api/deals",
     *     summary="Get a list of deals",
     *     tags={"Deals"},
     *     @OA\Response(response=200, description="Successful operation"),
     *     @OA\Response(response=400, description="Invalid request")
     * )
     */
    public function index()
    {
        $deal = Deal::all(); // ดึงข้อมูลผู้ใช้ทั้งหมดจากฐานข้อมูล
        return response()->json($deal); // ส่งข้อมูลผู้ใช้ทั้งหมดกลับในรูปแบบ JSON
    }


    /**
     * @OA\Post(
     *     path="/api/deals",
     *     summary="Create a new deal",
     *     tags={"Deals"},
     *     @OA\RequestBody(
     *         @OA\JsonContent(
     *             @OA\Property(property="buyer_id", type="integer"),
     *             @OA\Property(property="product_id", type="integer"),
     *             @OA\Property(property="qty", type="integer"),
     *             @OA\Property(property="deal_date", type="string", format="date"),
     *             @OA\Property(property="status", type="string")
     *         )
     *     ),
     *     @OA\Response(response=201, description="Deal created successfully"),
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
        ]);

        $deal = Deal::create([
            'buyer_id' => $request->buyer_id,
            'product_id' => $request->product_id,
            'qty' => $request->qty,
            'deal_date' => $request->deal_date,
            'status' => $request->status,
        ]);

        return response()->json($deal, 201);
    }


    /**
     * @OA\Get(
     *     path="/api/deals/{id}",
     *     summary="Get a specific deal",
     *     tags={"Deals"},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         @OA\Schema(type="string")
     *     ),
     *     @OA\Response(response=200, description="Successful operation"),
     *     @OA\Response(response=404, description="Deal not found")
     * )
     */
    public function show($id)
    {
        // $deal = Deal::findOrFail($id);
        $deal = Deal::where('buyer_id', $id)->get();
        return response()->json($deal);
    }

    
    /**
     * @OA\Put(
     *     path="/api/deals/{id}",
     *     summary="Update a specific deal",
     *     tags={"Deals"},
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
     *             @OA\Property(property="status", type="string")
     *         )
     *     ),
     *     @OA\Response(response=200, description="Deal updated successfully"),
     *     @OA\Response(response=404, description="Deal not found"),
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
        ]);

        $deal = Deal::findOrFail($id);
        $deal->update($request->all());

        return response()->json($deal);
    }


    /**
     * @OA\Delete(
     *     path="/api/deals/{id}",
     *     summary="Delete a specific deal",
     *     tags={"Deals"},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         @OA\Schema(type="string")
     *     ),
     *     @OA\Response(response=200, description="Deal deleted successfully"),
     *     @OA\Response(response=404, description="Deal not found")
     * )
     */
    public function destroy($id)
    {
        $deal = Deal::findOrFail($id);
        $deal->delete();

        return response()->json(['message' => 'Deal deleted successfully']);
    }
}
