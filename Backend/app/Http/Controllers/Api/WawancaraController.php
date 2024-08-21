<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Validator;  //import library untuk validasi
use App\Wawancara;
use App\User;

class WawancaraController extends Controller
{
    public function index()
    {
        $wawancara = Wawancara::all(); //mengambil semua data wawancara
        $wawancara = Wawancara::join('users', 'wawancara.id_user', '=', 'users.id_user')
            ->select('wawancara.*', 'users.nama')
            ->get();
        if (count($wawancara) > 0) {
            return response([
                'message' => 'Tampil Semua wawancara Sukses',
                'data' => $wawancara
            ], 200);
        } //return data semua Wawancara dalam bentuk json

        return response([
            'message' => 'Kosong',
            'data' => null
        ], 404); //return message data Wawancara kosong
    }

    public function show($id)
    {
        $wawancara = Wawancara::find($id); //mencari data wawancara berdasarkan id
        if (!is_null($wawancara)) {
            return response([
                'message' => 'Tampil Wawancara Sukses',
                'data' => $wawancara
            ], 200);
        } //return data wawancara yang ditemukan dalam bentuk json

        return response([
            'message' => 'Wawancara Tidak Ada',
            'data' => null
        ], 404); //return message saat data wawancara tidak ditemukan
    }

    public function showDetail($id)
    {
        $wawancara = Wawancara::find($id); //mencari data Wawancara berdasarkan id
        $wawancara = Wawancara::join('users', 'wawancara.id_user', '=', 'users.id_user')
            ->select('wawancara.*', 'users.nama')
            ->where('wawancara.id_wawancara', '=', $id)
            ->get();
        if (!is_null($wawancara)) {
            return response([
                'message' => 'Tampil Wawancara Sukses',
                'data' => $wawancara
            ], 200);
        } //return data Wawancara yang ditemukan dalam bentuk json

        return response([
            'message' => 'Wawancara Tidak Ada',
            'data' => null
        ], 404); //return message saat data Wawancara tidak ditemukan
    }

    //method untuk menambah 1 data Wawancara baru (create)
    public function store(Request $request)
    {
        $storeData = $request->all();
        $id_user = $storeData['id_user'];
        $wawancara = Wawancara::where([
            ['id_user', $id_user],
        ])->first();
        $validate = Validator::make($storeData, [
            'jadwal' => 'required|max:60|date',
            'tempat' => 'required|max:60',
            'id_user' => 'unique:wawancara'

        ]); //membuat rule validasi input
        //////////////////////////////////////////////////////////////////////////////
        if ($validate->fails())
            return response(['message' => $validate->errors()], 400); //return error invalid input
        $wawancara = Wawancara::create($storeData); //menambah data Wawancara baru
        return response([
            'message' => 'Tambah Wawancara Sukses',
            'data' => $wawancara,
        ], 200); //return data Wawancara baru dalam bentuk json
    }

    public function destroy($id)
    {
        $wawancara = Wawancara::find($id); //mencari data wawancara berdasarkan id

        if (is_null($wawancara)) {
            return response([
                'message' => 'wawancara Tidak Ada',
                'data' => null
            ], 404);
        } //return message saat data wawancara tidak ditemukan

        if ($wawancara->delete()) {
            return response([
                'message' => 'Hapus wawancara Sukses',
                'data' => $wawancara,
            ], 200);
        } //return message saat berhasil menghapus data wawancara
        return response([
            'message' => 'Hapus wawancara Gagal',
            'data' => null,
        ], 400); //return message saat gagal menghapus data wawancara
    }

    public function update(Request $request, $id)
    {
        $wawancara = Wawancara::find($id);
        if (is_null($wawancara)) {
            return response([
                'message' => 'Wawancara Tidak Ada',
                'data' => null
            ], 404);
        } //return message saat data wawancara tidak ditemukan

        $updateData = $request->all(); //mengambil semua input dari api client
        $validate = Validator::make($updateData, [
            'jadwal' => 'max:60',
            'tempat' => 'max:60',
        ]); //membuat rule validasi input
        if ($validate->fails())
            return response(['message' => $validate->errors()], 400); //return error invalid input

        $wawancara->jadwal = $updateData['jadwal'];
        $wawancara->tempat = $updateData['tempat'];

        if ($wawancara->save()) {
            return response([
                'message' => 'Update wawancara Sukses',
                'data' => $wawancara,
            ], 200);
        } //return data wawancara yang telah di edit dalam bentuk json
        return response([
            'message' => 'Update wawancara Gagal',
            'data' => null,
        ], 400); //return message saat wawancara gagal di edit
    }
}
