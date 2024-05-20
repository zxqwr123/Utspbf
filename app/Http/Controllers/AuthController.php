<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Firebase\JWT\JWT;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Log;          
use App\Models\User;

class AuthController extends Controller
{
    public function register(Request $request) {

        // buat validasi inputan
        $validator = Validator::make($request->all(), [
            'name' => 'required|max:255',
            'email' => 'required|max:255|unique:users',
            'password' => 'required|string'

        ]);
        // kondisi ketika satu atau lebih inputan tidak sesuai aturan diatas
        if ($validator->fails()) {
            Return response()->json($validator->messages()->toArray());
        }

        // validasi inputan yang sudah lulus
        $validatedData = $validator->validate();

        // hash password sebelum menyimpannya
        $validatedData['password'] = Hash::make($validatedData['password']);

        // Buat user baru

        $user = User::create([
            'name' => $validatedData['name'],
            'email' => $validatedData['email'],
            'password' => $validatedData['password']
        ]);

        // $user = $validator->validate();

        $payload = [
            'name' => $user->name,
            'role' => 'user',
            'iat' => now()->timestamp, //waktu token dibuat
            'exp' => now()->timestamp + 60*60*2, 
        ];

        // generate token dengan algoritma HS256
        $token = JWT::encode($payload, env('JWT_SECRET_KEY'), 'HS256');

        // kirim response ke pengguna
        return response()->json([
            "data" => [
                'msg' => "berhasil registrasi",
                'name' => $user->name,
                'email' => $user->email,
                'role' => 'user'
            ],
            "token" => "Bearer {$token}"
        ], 201);
    }
    public function login(Request $request) {
        // buat validasi inputan
        $validator = Validator::make($request->all(), [
            'email' => 'required|string|email',
            'password' => 'required|string'
        ]);

        // kondisi ketika satu atau lebih inputan tidak sesuai aturan diatas
        if ($validator->fails()) {
            return response()->json($validator->messages()->toArray(), 400);
        }

        // validasi inputan yang sudah lulus
        $validatedData = $validator->validate();

        // cek apakah pengguna dengan email ini ada
        $user = User::where('email', $validatedData['email'])->first();

        // Log untuk debugging
        Log::info('User trying to login:', ['email' => $validatedData['email']]);

        // jika pengguna tidak ditemukan atau password salah
        if (!$user || !Hash::check($validatedData['password'], $user->password)) {
            Log::warning('Invalid login attempt:', ['email' => $validatedData['email']]);
            return response()->json(['message' => 'Invalid credentials'], 401);
        }

        // payload untuk token
        $payload = [
            'name' => $user->name,
            'role' => $user->role,
            'iat' => now()->timestamp, // waktu token dibuat
            'exp' => now()->timestamp + 60*60*2, // token kadaluarsa dalam 2 jam
        ];

        // generate token dengan algoritma HS256
        $token = JWT::encode($payload, env('JWT_SECRET_KEY'), 'HS256');

        // kirim response ke pengguna
        return response()->json([
            "data" => [
                'msg' => "berhasil login",
                'name' => $user->name,
                'email' => $user->email,
                'role' => $user->role
            ],
            "token" => "Bearer {$token}"
        ]);
    }
}