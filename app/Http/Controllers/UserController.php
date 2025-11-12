<?php

namespace App\Http\Controllers;

use App\Models\User;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class UserController extends Controller
{
    public function index()
    {
        return User::orderBy('created_at')->get();
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string',
            'email' => 'required|email',
            'password' => 'required|string'
        ]);

        if ($validator->fails()) {
            return ['status' => 'error', 'message' => 'Invalid request'];
        }

        extract($validator->valid());

        try {
            $user = User::create([
                'name' => $name,
                'email' => $email,
                'password' => Hash::make($password)
            ]);
        } catch (Exception $e) {
            return ['status' => 'error', 'message' => 'Unable to create user in database'];
        }

        return ['status' => 'success', 'user' => $user];
    }

    public function update(Request $request, int $id)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string',
            'email' => 'required|email',
            'changePass' => 'required|boolean',
            'password' => 'required_if:changePass,true|string'
        ]);

        if ($validator->fails()) {
            return ['status' => 'error', 'message' => 'Invalid request'];
        }

        $user = User::find($id);

        if (!$user) {
            return ['status' => 'error', 'message' => 'User not found'];
        }

        extract($validator->valid());

        try {
            $user->name = $name;
            $user->email = $email;
            if ($changePass) {
                $user->password = Hash::make($password);
            }
            $user->save();
        } catch (Exception $e) {
            return ['status' => 'error', 'message' => 'Unable to update user in database'];
        }

        return ['status' => 'success', 'user' => $user];
    }

    public function destroy(int $id)
    {
        $user = User::find($id);

        if (!$user) {
            return ['status' => 'error', 'message' => 'User not found'];
        }

        $user->delete();

        try {
            $user->delete();
        } catch (Exception $e) {
            return ['status' => 'error', 'message' => 'Unable to delete user from database'];
        }

        return ['status' => 'success'];
    }
}
