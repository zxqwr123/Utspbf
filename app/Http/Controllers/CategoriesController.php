<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use App\Models\Categories;

class CategoriesController extends Controller
{
        //menambahkan data ke database
        public function store(Request $request) {
            // memvalidasi inputan
            $validator = Validator::make($request->all(), [
                'name' => 'required|max:255',
            ]);
    
            // kondisi apabila inputan yang diinginkan tidak sesuai
            if($validator->fails()) {
                // response json akan dikirim jika ada inputan yang salah
                return response()->json($validator->getMessageBag())->setStatusCode(422);
            }
    
            $payload = $validator->validated();
            // masukkan inputan yang benar ke database (table product)
            Categories::create([
                'name' => $payload['name'],
            ]);
            // response json akan dikirim inputan benar
            return response()->json([
                'msg' => 'Data Categories berhasil disimpan'
            ], 201);
        }
    
        // menampilkan product (GET)
        public function show() {
            $product = Categories::all();
    
            return response()->json([
                'message'=>'Data Categories',
                'data' => $product
            ],200);
        }
    
        // menampilkan product berdasarkan id
        public function id($id) {
            $product = Categories::find($id);
    
            return response()->json([
                'message'=>'Data Categories',
                'data' => $product
            ],200);
        }
    
        // menampilkan product berdasarkan nama
        public function name($name) {
            $product = Categories::find($name);
    
            return response()->json([
                'message'=>'Data Categories',
                'data' => $product
            ],200);
        }
    
        public function update(Request $request, $id) {
            // memvalidasi inputan
            $validator = Validator::make($request->all(), [
                'name' => 'sometimes|max:255',
            ]);
    
            // kondisi apabila inputan yang diinginkan tidak sesuai
            if($validator->fails()) {
                // response json akan dikirim jika ada inputan yang salah
                return response()->json($validator->getMessageBag())->setStatusCode(422);
            }
    
            $validated = $validator->validate();
            $product = Categories::find($id);
    
            if($product) {
                Categories::where('id', $id)->update($validated);
    
                return response()->json("Data Categories dengan id : {$id} berhasil di update", 200);
            }
    
            return response()->json("Data Categories dengan id : {$id} tidak ditemukan", 404);
        }
    
        public function delete($id) {
            $product = Categories::where('id', $id)->get();
    
            if($product) {
                Categories::where('id', $id)->delete();
    
                // response json akan dikirim
                return response()->json("Data Categories dengan id: {$id} berhasil dihapus", 200);
            }
            
            return response()->json("Data Categories produk dengan id: {$id} tidak ditemukan",404);
        }
        
        public function restore($id) {
            $product = Categories::onlyTrashed()->where('id', $id);
            $product->restore();
            
            return response()->json("Data Categories dengan id: {$id} berhasil dipulihkan", 200);
    
        }
}