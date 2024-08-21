<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\User;
use Validator;

class AuthController extends Controller
{
    public function register(Request $request)
    {
        $registrationData = $request->all();
        $validate = Validator::make($registrationData, [
            'nama' => 'required|max:60',
            'email' => 'required|email:rfc,dns|unique:users',
            'password' => 'required'
        ]);
        if ($validate->fails())
            return response(['message' => $validate->errors()], 400);

        $registrationData['password'] = bcrypt($request->password);
        $user = User::create($registrationData);
        return response([
            'message' => 'Register Succes',
            'user' => $user,
        ], 200);
    }


    public function show($id)
    {
        $user = User::find($id); //mencari data profile berdasarkan id
        if (!is_null($user)) {
            return response([
                'message' => 'Tampil Profile Sukses',
                'data' => $user
            ], 200);
        } //return data profile yang ditemukan dalam bentuk json

        return response([
            'message' => 'User Tidak Ada',
            'data' => null
        ], 404); //return message saat data profile tidak ditemukan
    }

    public function login(Request $request)
    {
        $loginData = $request->all();
        $validate = Validator::make($loginData, [
            'email' => 'required|email:rfc,dns',
            'password' => 'required',

        ]);
        if ($validate->fails())
            return response(['message' => $validate->errors()], 400);

        if (!Auth::attempt($loginData))
            return response(['message' => 'Email atau Password Salah'], 401);


        $user = Auth::user();
        $token = $user->createToken('Authentication Token')->accessToken;

        return response([
            'message' => 'Berhasil Login',
            'user' => $user,
            'token_type' => 'Bearer',
            'access_token' => $token
        ]);
    }

    //HAPUS
    public function destroy($id)
    {
        $user = User::find($id);
        if (is_null($user)) {
            return response([
                'message' => 'User Tidak Ditemukan',
                'data' => null
            ], 404);
        }
        if ($user->delete()) {
            return response([
                'message' => 'User Produk Sukses',
                'data' => $user,
            ], 200);
        }
        return response([
            'message' => 'Hapus Akun Gagal',
            'data' => null,
        ], 400);
    }

    public function logout(Request $request)
    {
        $request->user()->token()->revoke();

        return response()->json([
            'message' => 'Logout Suksess'
        ]);
    }
}
