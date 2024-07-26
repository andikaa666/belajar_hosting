<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;

use App\Models\Kategori;
use Illuminate\Http\Request;
use Validator;

class KategoriController extends Controller
{
    public function index(){
        $kategori = Kategori::latest()->get();
        $response = [
            'success' =>true,
            'message' => 'Data Kategori',
            'data' => $kategori,
        ];
        return response()->json($response, 200);
    }

    public function store(Request $request)
    {
        //validasi
        $validator = Validator::make($request->all(), [
            'nama_kategori' => 'required|unique:kategoris'
        ], [
            'nama_kategori.required' => 'Masukan Kategori',
            'nama_kategori.unique' => 'Kategori Sudah Digunakan!',
        ]);
        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Silahkan isi dengan benar',
                'data' => $validator->errors(),
            ], 401);
        } else {
            $kategori = new Kategori;
            $kategori->nama_kategori = $request->nama_kategori;
            $kategori->save();
        }
        if ($kategori) {
            return response()->json([
                'success' => true,
                'message' => 'data berhasil di simpan',
            ], 200);
        } else {
            return response()->json([
                'success' => true,
                'message' => 'data gagal di simpan',
            ], 400);
        }
    }

    public function show($id) {
        $kategori = Kategori::find($id);

        if ($kategori){
            return response()->json([
                'success' =>true,
                'message' => 'Detail Kategori',
                'data' => $kategori,
            ], 200);
        } else {
            return response()->json([
                'success' =>false,
                'message' => 'Kategori tidak ditemukan ',
            ], 404);
        }

    }

    public function update(Request $request, $id)
    {
        //validasi
        $validator = Validator::make($request->all(), [
            'nama_kategori' => 'required'
        ], [
            'nama_kategori.required' => 'Masukan Kategori',

        ]);
        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Silahkan isi dengan benar',
                'data' => $validator->errors(),
            ], 401);
        } else {
            $kategori = Kategori::find($id);
            $kategori->nama_kategori = $request->nama_kategori;
            $kategori->save();
        }
        if ($kategori) {
            return response()->json([
                'success' => true,
                'message' => 'data berhasil di perbarui',
            ], 200);
        } else {
            return response()->json([
                'success' => true,
                'message' => 'data gagal di perbarui',
            ], 400);
        }
    }

    public function destroy($id){
        $kategori = Kategori::find($id);

        if ($kategori) {
        $kategori->delete();
        return response()->json([
            'success' => true,
            'message' => 'data' . $kategori->nama_kategori . ' berhasil di hapus',
        ], 200);
    } else {
        return response()->json([
            'success' => true,
            'message' => 'data tidak ditemukan',
        ], 404);
    }

    }

}
