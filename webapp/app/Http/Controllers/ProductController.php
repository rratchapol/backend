<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Product;
use OpenApi\Annotations as OA;

class ProductController extends Controller
{
    /**
     * @OA\Get(
     *     path="/api/products",
     *     summary="Get all products",
     *     tags={"Products"},
     *     @OA\Response(response=200, description="Successful operation"),
     *     @OA\Response(response=400, description="Invalid request")
     * )
     */
    public function index(Request $request)
    {
        $columns = $request->input('columns', []);
        $length = $request->input('length', 10);
        $order = $request->input('order', []);
        $search = $request->input('search', []);
        $start = $request->input('start', 0);
        $page = ($start / $length) + 1;
    
        $col = ['id', 'product_name','product_qty','product_price','product_description','item_category','item_type','seller_id','date_exp'  ];
        $orderby = ['id', 'product_name','product_qty','product_price','product_description','item_category','item_type','seller_id','date_exp'];
    
        $products = Product::select($col);
    
        if (isset($order[0]['column']) && isset($orderby[$order[0]['column']])) {
            $products->orderBy($orderby[$order[0]['column']], $order[0]['dir']);
        }
    
        if (!empty($search['value'])) {
            $products->where(function ($query) use ($search, $col) {
                foreach ($col as $c) {
                    $query->orWhere($c, 'like', '%' . $search['value'] . '%');
                }
            });
        }
    
        $d = $products->paginate($length, ['*'], 'page', $page);
    
        if ($d->isNotEmpty()) {
            $d->transform(function ($item, $key) use ($page, $length) {
                $item->No = ($page - 1) * $length + $key + 1;
                return $item;
            });
        }
    
        return response()->json([
            'status' => 'success',
            'message' => 'เรียกดูข้อมูลสำเร็จ',
            'data' => $d
        ]);
    }

    
    /**
     * @OA\Post(
     *     path="/api/products",
     *     summary="Create a new product",
     *     tags={"Products"},
     *     @OA\RequestBody(
     *         @OA\JsonContent(
     *             @OA\Property(property="product_name", type="string", maxLength=255),
     *             @OA\Property(property="product_qty", type="integer"),
     *             @OA\Property(property="product_price", type="number", format="float"),
     *             @OA\Property(property="product_description", type="string"),
     *             @OA\Property(property="item_category", type="string", maxLength=255),
     *             @OA\Property(property="item_type", type="string", maxLength=255),
     *             @OA\Property(property="seller_id", type="integer"),
     *             @OA\Property(property="date_exp", type="string", format="date")
     *         )
     *     ),
     *     @OA\Response(response=201, description="Product created successfully"),
     *     @OA\Response(response=400, description="Invalid input")
     * )
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'product_name' => 'required|string|max:255',
            'product_qty' => 'required|integer',
            'product_price' => 'required|numeric',
            'product_description' => 'nullable|string',
            'item_category' => 'required|string|max:255',
            'item_type' => 'required|string|max:255',
            'seller_id' => 'required|exists:users,id',
            'date_exp' => 'required|date',
        ]);

        $product = Product::create($validated);

        return response()->json($product, 201);
    }


    /**
     * @OA\Get(
     *     path="/api/products/{id}",
     *     summary="Get a specific product by ID",
     *     tags={"Products"},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Response(response=200, description="Successful operation"),
     *     @OA\Response(response=404, description="Product not found")
     * )
     */
    public function show($id)
    {
        $product = Product::findOrFail($id);
        return response()->json($product);
    }


    /**
     * @OA\Put(
     *     path="/api/products/{id}",
     *     summary="Update a specific product by ID",
     *     tags={"Products"},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\RequestBody(
     *         @OA\JsonContent(
     *             @OA\Property(property="product_name", type="string", maxLength=255),
     *             @OA\Property(property="product_qty", type="integer"),
     *             @OA\Property(property="product_price", type="number", format="float"),
     *             @OA\Property(property="product_description", type="string"),
     *             @OA\Property(property="item_category", type="string", maxLength=255),
     *             @OA\Property(property="item_type", type="string", maxLength=255),
     *             @OA\Property(property="seller_id", type="integer"),
     *             @OA\Property(property="date_exp", type="string", format="date")
     *         )
     *     ),
     *     @OA\Response(response=200, description="Product updated successfully"),
     *     @OA\Response(response=404, description="Product not found"),
     *     @OA\Response(response=400, description="Invalid input")
     * )
     */
    public function update(Request $request, $id)
    {
        $validated = $request->validate([
            'product_name' => 'sometimes|required|string|max:255',
            'product_qty' => 'sometimes|required|integer',
            'product_price' => 'sometimes|required|numeric',
            'product_description' => 'nullable|string',
            'item_category' => 'sometimes|required|string|max:255',
            'item_type' => 'sometimes|required|string|max:255',
            'seller_id' => 'sometimes|required|exists:users,id',
            'date_exp' => 'sometimes|required|date',
        ]);

        $product = Product::findOrFail($id);
        $product->update($validated);

        return response()->json($product);
    }


    /**
     * @OA\Delete(
     *     path="/api/products/{id}",
     *     summary="Delete a specific product by ID",
     *     tags={"Products"},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Response(response=204, description="Product deleted successfully"),
     *     @OA\Response(response=404, description="Product not found")
     * )
     */
    public function destroy($id)
    {
        $product = Product::findOrFail($id);
        $product->delete();

        return response()->json(null, 204);
    }
}
