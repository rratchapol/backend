<?php

namespace App\Http\Controllers;
use App\Models\UserLike;
use Illuminate\Http\Request;

class UserLikeController extends Controller
{
    /**
     * @OA\Get(
     *     path="/api/user-likes",
     *     summary="Get all user likes",
     *     tags={"UserLikes"},
     *     @OA\Response(response=200, description="Successful operation"),
     *     @OA\Response(response=400, description="Invalid request")
     * )
     */
    public function index()
    {
        $likes = UserLike::all();
        return response()->json($likes);
    }


    /**
     * @OA\Post(
     *     path="/api/user-likes",
     *     summary="Add a like for a product",
     *     tags={"UserLikes"},
     *     @OA\RequestBody(
     *         @OA\JsonContent(
     *             @OA\Property(property="user_id", type="integer"),
     *             @OA\Property(property="product_id", type="integer")
     *         )
     *     ),
     *     @OA\Response(response=201, description="Like added successfully"),
     *     @OA\Response(response=422, description="Validation error")
     * )
     */
    public function store(Request $request)
    {
        $request->validate([
            'user_id' => 'required|exists:users,id',
            'product_id' => 'required|exists:products,id',
        ]);

        $like = UserLike::create([
            'user_id' => $request->user_id,
            'product_id' => $request->product_id,
        ]);

        return response()->json(['message' => 'Like added successfully!', 'like' => $like], 201);
    }


    /**
     * @OA\Get(
     *     path="/api/user-likes/{id}",
     *     summary="Get likes by user ID",
     *     tags={"UserLikes"},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Response(response=200, description="Successful operation"),
     *     @OA\Response(response=404, description="User not found")
     * )
     */
    public function show($id)
    {
        $likes = UserLike::where('user_id', $id)->get();
        return response()->json($likes);
    }


    /**
     * @OA\Put(
     *     path="/api/user-likes/{id}",
     *     summary="Update a like by ID",
     *     tags={"UserLikes"},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\RequestBody(
     *         @OA\JsonContent(
     *             @OA\Property(property="user_id", type="integer"),
     *             @OA\Property(property="product_id", type="integer")
     *         )
     *     ),
     *     @OA\Response(response=200, description="Like updated successfully"),
     *     @OA\Response(response=404, description="Like not found"),
     *     @OA\Response(response=422, description="Validation error")
     * )
     */
    public function destroy($id)
    {
        $like = UserLike::findOrFail($id);

        $like->delete();

        return response()->json(['message' => 'Like removed successfully!']);
    }


    /**
     * @OA\Delete(
     *     path="/api/user-likes/{id}",
     *     summary="Delete a like by ID",
     *     tags={"UserLikes"},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Response(response=200, description="Like removed successfully"),
     *     @OA\Response(response=404, description="Like not found")
     * )
     */
    public function update(Request $request, string $id)
    {
        $request->validate([
            'user_id' => 'required|exists:users,id',
            'product_id' => 'required|exists:products,id',
        ]);

        $likes = UserLike::find($id);
        if (!$likes) {
            return response()->json(['message' => 'like not found'], 404);
        }
        $likes->update([
            'product_id' => $request->product_id,
        ]);
        return response()->json($likes);
    }

}
