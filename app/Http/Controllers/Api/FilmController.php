<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Film;
use Storage;
use Validator;

class FilmController extends Controller
{
    public function index(){
        $film = Film::with(['genre','aktor'])->get();
        return response()->json([
            'success' => true,
            'message' => 'Data film',
            'data' => $film,
        ]);
    }
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'judul' => 'required|string|unique:films',
            'deskripsi' => 'required|string',
            'foto' => 'required|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
            'url_video' => 'required|string',
            'id_kategori' => 'required',
            'genre' => 'required|array',
            'aktor' => 'required|array',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'validasi gagal',
                'errors' => $validator->errors(),
            ], 402);
        }
        try {
            $path = $request->file('foto')->store('public/foto');

            $film = Film::create([
                'judul' => $request->judul,
                'deskripsi' => $request->deskripsi,
                'foto' => $path,
                'url_video' => $request->url_video,
                'id_kategori' => $request->id_kategori,
            ]);
            $film->genre()->attach($request->genre);
            $film->aktor()->attach($request->aktor);

            return response()->json([
                'succcess' => true,
                'message' => 'data berhasil di simpan',
                'data' => $film,
            ], 201);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan',
                'errors' => $e->getMessage(),
            ], 500);
        }
    }
    public function show($id)
    {
        try {
            $film = Film::with(['genre','aktor'])->findOrFail($id);
            return response()->json([
                'success' => true,
                'message' => 'Detail film',
                'data' => $film,
            ], 200);
        } catch (\Exception $e) {
            return responbse()->json([
                'success' => false,
                'message' => 'Data tidak di temukan',
                'errors' => $e->getMessage(),
            ], 404);
        }
    }
    public function update(Request $request, $id)
    {
        $film = Film::findOrFail($id);

        $validator = Validator::make($request->all(), [
            'judul' => 'required|string|unique:films,judul,' . $id,
            'deskripsi' => 'required|string',
            'foto' => 'image|mimes:jpeg,png,jpg,gif,svg|max:2048',
            'url_video' => 'required|string',
            'id_kategori' => 'required|exists:kategoris,id',
            'genre' => 'required|array',
            'aktor' => 'required|array',
        ]);
        if ($validator->fails()) {
            return response()->json([
            'success' => false,
            'message' => 'Validasi gagal',
            'errors' => $validator->errors(),
            ], 422);
        }

        try {
            if ($request->hasFile('foto')) {
                //hapus foto
                $Storage::delete($film->foto);

                $path = $request->file('foto')->store('public/foto');
                $film->foto = $path;
            }
            $film->update($request->only(['judul', ' deskripsi', 'url_video', 'id_kategori']));
            if ($request->has('genre')) {
                $film->genre()->sync($request->genre);
            }
            if ($request->has('aktor')) {
                $film->aktor()->sync($request->aktor);
            }
            return response()->json([
                'success' => true,
                'message' => 'Data berhasil di perbaharui',
                'errors' => $film,
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Ah error occured',
                'errors' => $e->getMessage(),
            ], 500);
        }
    }
    public function destroy($id)
    {
        try {
            $film = Film::findOrFail($id);

            Storage::delete($film->foto);
            return response()->json([
                'success' => true,
                'message' => 'Data deleted success',
                'data' => null,
            ], 204);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'data not found',
                'errors' => $e->getMessage(),
            ], 404);
        }
    }
}
