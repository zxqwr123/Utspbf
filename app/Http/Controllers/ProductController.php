<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use App\Models\Product;

class ProductController extends Controller
{
    //menambahkan data ke database
    public function store(Request $request) {
        // memvalidasi inputan
        $validator = Validator::make($request->all(), [
            'name' => 'required|max:255',
            'category_id'=> 'required|numeric',
            'description'=> 'required',
            'price' => 'required|numeric',
            'image'=> 'required|max:255',
            'modified_by'=> 'required|max:255',
            'expired_at' => 'required|date'
        ]);

        // kondisi apabila inputan yang diinginkan tidak sesuai
        if($validator->fails()) {
            // response json akan dikirim jika ada inputan yang salah
            return response()->json($validator->getMessageBag())->setStatusCode(422);
        }

        $payload = $validator->validated();
        // masukkan inputan yang benar ke database (table product)
        Product::create([
            'name' => $payload['name'],
            'category_id'=> $payload ['category_id'],
            'description' => $payload['description'],
            'price' => $payload['price'],
            'image' => $payload['image'],
            'modified_by' => $payload['modified_by'],
            'expired_at' => $payload['expired_at']
        ]);
        // response json akan dikirim inputan benar
        return response()->json([
            'msg' => 'Data produk berhasil disimpan'
        ], 201);
    }

    // menampilkan product (GET)
    public function show() {
        $product = Product::all();

        return response()->json([
            'message'=>'Data Produk',
            'data' => $product
        ],200);
    }

    // menampilkan product berdasarkan id
    public function id($id) {
        $product = Product::find($id);

        return response()->json([
            'message'=>'Data Produk',
            'data' => $product
        ],200);
    }

    // menampilkan product berdasarkan nama
    public function name($name) {
        $product = Product::find($name);

        return response()->json([
            'message'=>'Data Produk',
            'data' => $product
        ],200);
    }

    public function update(Request $request, $id) {
        // memvalidasi inputan
        $validator = Validator::make($request->all(), [
            'name' => 'sometimes|max:255',
            'category_id'=> 'sometimes|numeric',
            'description'=> 'sometimes',
            'price' => 'sometimes|numeric',
            'image'=> 'sometimes|max:255',
            'modified_by'=> 'sometimes|max:255',
            'expired_at' => 'sometimes|date'
        ]);

        // kondisi apabila inputan yang diinginkan tidak sesuai
        if($validator->fails()) {
            // response json akan dikirim jika ada inputan yang salah
            return response()->json($validator->getMessageBag())->setStatusCode(422);
        }

        $validated = $validator->validate();
        $product = Product::find($id);

        if($product) {
            Product::where('id', $id)->update($validated);

            return response()->json("Data dengan id : {$id} berhasil di update", 200);
        }

        return response()->json("Data dengan id : {$id} tidak ditemukan", 404);
    }

    public function delete($id) {
        $product = Product::where('id', $id)->get();

        if($product) {
            Product::where('id', $id)->delete();

            // response json akan dikirim
            return response()->json("Data produk dengan id: {$id} berhasil dihapus", 200);
        }
        
        return response()->json("Data produk dengan id: {$id} tidak ditemukan",404);
    }
    
    public function restore($id) {
        $product = Product::onlyTrashed()->where('id', $id);
        $product->restore();
        
        return response()->json("Data produk dengan id: {$id} berhasil dipulihkan", 200);

    }
}