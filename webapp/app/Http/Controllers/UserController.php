<?php

namespace App\Http\Controllers;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash; 
use Illuminate\Validation\ValidationException;

class UserController extends Controller
{
    /**
     * @OA\Get(
     *     path="/api/users",
     *     summary="Get all users",
     *     tags={"Users"},
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
    
        $col = ['id', 'name','email','mobile','address','faculty','department','class_year'  ];
        $orderby = ['id', 'name','email','mobile','address','faculty','department','class_year'];
    
        $user = User::select($col);
    
        if (isset($order[0]['column']) && isset($orderby[$order[0]['column']])) {
            $user->orderBy($orderby[$order[0]['column']], $order[0]['dir']);
        }
    
        if (!empty($search['value'])) {
            $user->where(function ($query) use ($search, $col) {
                foreach ($col as $c) {
                    $query->orWhere($c, 'like', '%' . $search['value'] . '%');
                }
            });
        }
    
        $d = $user->paginate($length, ['*'], 'page', $page);
    
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
     * @OA\Get(
     *     path="/api/users/{id}",
     *     summary="Get a specific user by ID",
     *     tags={"Users"},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         @OA\Schema(type="string")
     *     ),
     *     @OA\Response(response=200, description="Successful operation"),
     *     @OA\Response(response=404, description="User not found")
     * )
     */
    public function show(string $id)
    {
        $User = User::find($id);

        if (!$User) {
            return response()->json(['message' => 'ไม่พบผู้ใช้งาน'], 404);
        }

        return response()->json($User);
    }


    /**
     * @OA\Post(
     *     path="/api/users",
     *     summary="Create a new user",
     *     tags={"Users"},
     *     @OA\RequestBody(
     *         @OA\JsonContent(
     *             @OA\Property(property="name", type="string", maxLength=255),
     *             @OA\Property(property="email", type="string", format="email", maxLength=255),
     *             @OA\Property(property="password", type="string", minLength=8),
     *             @OA\Property(property="mobile", type="string", maxLength=20),
     *             @OA\Property(property="address", type="string"),
     *             @OA\Property(property="faculty", type="string"),
     *             @OA\Property(property="department", type="string"),
     *             @OA\Property(property="class_year", type="string"),
     *             @OA\Property(property="role", type="string", enum={"seller", "buyer"})
     *         )
     *     ),
     *     @OA\Response(response=201, description="User created successfully"),
     *     @OA\Response(response=422, description="Validation error")
     * )
     */
    public function store(Request $request)
    {
        try {
            $request->validate([
                'name' => 'required|string|max:255',
                'email' => 'required|string|email|max:255|unique:users',
                'password' => 'required|string|min:8',
                'mobile' => 'required|string|max:20',
                'address' => 'required|string',
                'faculty' => 'required|string',
                'department' => 'required|string',
                'class_year' => 'required|string',
                'role' => 'required|in:seller,buyer',
            ]);

            $user = User::create([
                'name' => $request->name,
                'email' => $request->email,
                'password' => Hash::make($request->password),
                'mobile' => $request->mobile,
                'address' => $request->address,
                'faculty' => $request->faculty,
                'department' => $request->department,
                'class_year' => $request->class_year,
                'role' => $request->role,
            ]);

            return response()->json(['message' => 'User created successfully', 'user' => $user], 201);

        } catch (ValidationException $e) {
            $errors = $e->errors();
            
            // ตรวจสอบว่ามีข้อผิดพลาดเกี่ยวกับ email หรือไม่
            if (isset($errors['email'])) {
                return response()->json(['message' => 'Email นี้มีอยู่ในระบบแล้ว', 'email' => $request->email], 422);
            }

            // สำหรับข้อผิดพลาดอื่น ๆ
            return response()->json(['message' => 'Validation failed', 'errors' => $errors], 422);
        }
    }


    /**
     * @OA\Put(
     *     path="/api/users/{id}",
     *     summary="Update a user by ID",
     *     tags={"Users"},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         @OA\Schema(type="string")
     *     ),
     *     @OA\RequestBody(
     *         @OA\JsonContent(
     *             @OA\Property(property="name", type="string", maxLength=255),
     *             @OA\Property(property="password", type="string", minLength=8),
     *             @OA\Property(property="mobile", type="string", maxLength=20),
     *             @OA\Property(property="address", type="string"),
     *             @OA\Property(property="faculty", type="string"),
     *             @OA\Property(property="department", type="string"),
     *             @OA\Property(property="class_year", type="string"),
     *             @OA\Property(property="role", type="string", enum={"seller", "buyer"})
     *         )
     *     ),
     *     @OA\Response(response=200, description="User updated successfully"),
     *     @OA\Response(response=404, description="User not found"),
     *     @OA\Response(response=422, description="Validation error")
     * )
     */
    public function update(Request $request, $id)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            // 'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:8',
            'mobile' => 'required|string|max:20',
            'address' => 'required|string',
            'faculty' => 'required|string',
            'department' => 'required|string',
            'class_year' => 'required|string',
            'role' => 'required|in:seller,buyer',
        ]);

        $User = User::findOrFail($id);
        $User->update($validated);

        return response()->json($User);
    }


    /**
     * @OA\Delete(
     *     path="/api/users/{id}",
     *     summary="Delete a user by ID",
     *     tags={"Users"},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         @OA\Schema(type="string")
     *     ),
     *     @OA\Response(response=204, description="User deleted successfully"),
     *     @OA\Response(response=404, description="User not found")
     * )
     */
    public function destroy($id)
    {
        $User = User::findOrFail($id);
        $User->delete();

        return response()->json(null, 204);
    }
}
