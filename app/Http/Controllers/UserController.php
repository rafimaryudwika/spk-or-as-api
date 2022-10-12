<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use App\Actions\Fortify\PasswordValidationRules;

class UserController extends Controller
{
    use PasswordValidationRules;
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function user(Request $request, User $user)
    {
        $usr = $user->with('UserRole')->find($request->user()->id);

        return [
            'name' => $usr->name,
            'email' => $usr->email,
            'email_verified_at' => $usr->email_verified_at,
            'role'    => $usr->UserRole->role,
            'created_at'    => $usr->created_at,
            'updated_at'    => $usr->updated_at,
        ];
    }

    public function index(User $user)
    {
        $this->authorize('admin', $user);

        $users = $user->with('UserRole')->get();

        $response = [
            'message' => 'Data users',
            'data' => $users
        ];

        return response()->json($response, Response::HTTP_OK);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request, User $user)
    {
        $this->authorize('admin', $user);

        $validator = Validator::make($request->all(), [
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users'],
            'password' => $this->passwordRules(),
            'roles' => ['required', 'int', 'min:1'],
        ]);

        if ($validator->fails()) {
            return response()->json(
                $validator->errors(),
                Response::HTTP_UNPROCESSABLE_ENTITY
            );
        }
        try {
            $create = $user->create([
                'name' => $request['name'],
                'email' => $request['email'],
                'password' => Hash::make($request['password']),
                'roles_id' => $request['roles'],
            ]);

            $response = [
                'message' => 'User created',
                'data' => $create
            ];
            return response()->json($response, Response::HTTP_CREATED); //code...
        } catch (\Throwable $e) {
            return response()->json([
                'message' => "Failed " . $e->getMessage()
            ], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show(User $user)
    {
        $this->authorize('admin', $user);

        $response = [
            'message' => 'Data user',
            'data' => $user
        ];
        return response()->json($response, Response::HTTP_OK);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, User $user)
    {
        $this->authorize('admin', $user);

        $validator = Validator::make($request->all(), [
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users'],
            'password' => $this->passwordRules(),
            'roles' => ['required', 'int', 'min:1'],
        ]);

        if ($validator->fails()) {
            return response()->json(
                $validator->errors(),
                Response::HTTP_UNPROCESSABLE_ENTITY
            );
        }
        try {
            $update = $user->update([
                'name' => $request['name'],
                'email' => $request['email'],
                'password' => Hash::make($request['password']),
                'roles_id' => $request['roles'],
            ]);

            $response = [
                'message' => 'User updated',
                'data' => $update
            ];
            return response()->json($response, Response::HTTP_CREATED); //code...
        } catch (\Throwable $e) {
            return response()->json([
                'message' => "Failed " . $e->getMessage()
            ], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $request, User $user)
    {
        $this->authorize('admin', $user);

        try {
            if ($request->user()->id == $user->id) {
                $response = [
                    'message' => 'Anda tidak dapat menghapus diri anda sendiri',
                ];
                return response()->json($response, Response::HTTP_UNPROCESSABLE_ENTITY);
            } else {
                $delete = $user->delete();
                $response = [
                    'message' => 'User deleted',
                    'data' => $delete
                ];
                return response()->json($response, Response::HTTP_OK);
            }
        } catch (\Throwable $e) {
            return response()->json([
                'message' => "Deleting failed: " . $e->getMessage()
            ], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }
}
