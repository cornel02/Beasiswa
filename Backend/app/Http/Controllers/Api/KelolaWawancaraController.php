<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Validator;  //import library untuk validasi
use App\Wawancara;
use App\User;
use App\KelolaWawancara;

class KelolaWawancaraController extends Controller
{
    public function index()
    {
        $kelolawawancara = KelolaWawancara::all(); //mengambil semua data nilai
        $kelolawawancara = KelolaWawancara::join('users', 'kelolawawancara.id_wawancara', '=', 'users.id_user')
            ->join('wawancara', 'kelolawawancara.id_wawancara', '=', 'wawancara.id_wawancara')
            ->select('kelolawawancara.*', 'users.nama', 'wawancara.jadwal', 'wawancara.tempat')
            ->get();
        if (count($kelolawawancara) > 0) {
            return response([
                'message' => 'Tampil Semua kelola wawancara Sukses',
                'data' => $kelolawawancara
            ], 200);
        } //return data semua kelolawawancara dalam bentuk json

        return response([
            'message' => 'Kosong',
            'data' => null
        ], 404); //return message data kelolawawancara kosong
    }

    public function show($id)
    {
        $kelolawawancara = KelolaWawancara::find($id); //mencari data nilai berdasarkan id

        if (!is_null($kelolawawancara)) {
            return response([
                'message' => 'Tampil Kelola Wawancara Sukses',
                'data' => $kelolawawancara
            ], 200);
        } //return data nilai yang ditemukan dalam bentuk json

        return response([
            'message' => 'Nilai Tidak Ada',
            'data' => null
        ], 404); //return message saat data nilai tidak ditemukan
    }

    public function store(Request $request)
    {
        $storeData = $request->all(); //mengambil semua input dari api client
        $validate = Validator::make($storeData, [
            'hasilkelola' => 'required|max:60',
            'id_wawancara' => 'unique:kelolawawancara',
        ]); //membuat rule validasi input
        //////////////////////////////////////////////////////////////////////////////
        if ($storeData['hasilkelola'] == 'kurang') {
            $storeData['bobot'] = 0.08;
        }
        if ($storeData['hasilkelola'] == 'cukup') {
            $storeData['bobot'] = 0.19;
        }
        if ($storeData['hasilkelola'] == 'baik') {
            $storeData['bobot'] = 0.72;
        }
        if ($storeData['hasilkelola'] == null) {
            $storeData['bobot'] = 0;
        }
        $storeData['bobotwawancara'] = $storeData['bobot'] * 0.08;
        //////////////////////////////////////////////////////////////////////////////
        $id_wawancara = $storeData['id_wawancara'];
        $wawancara = Wawancara::where('id_wawancara', $id_wawancara)->first();
        if (is_null($wawancara)) {
            return response([
                'message' => 'wawancara tidak ditemukan',
                'data' => null
            ], 404);
        }
        $user = User::where('id_user', $id_wawancara)->first();
        if (is_null($user)) {
            return response([
                'message' => 'user tidak ditemukan',
                'data' => null
            ], 404);
        }

        //////////////////////////////////////////////////////////////////////////////
        if ($validate->fails())
            return response(['message' => $validate->errors()], 400); //return error invalid input
        $kelola_wawancara = KelolaWawancara::create($storeData); //menambah data Kelola Wawancara baru
        return response([
            'message' => 'Tambah Kelola Wawancara Sukses',
            'data' => $kelola_wawancara,
        ], 200); //return data Kelola Wawancara baru dalam bentuk json
    }

    public function update(Request $request, $id)
    {
        $kelolawawancara = KelolaWawancara::find($id);
        if (is_null($kelolawawancara)) {
            return response([
                'message' => 'Kelola Wawancara Tidak Ada',
                'data' => null
            ], 404);
        } //return message saat data Kelola Wawancara tidak ditemukan

        $updateData = $request->all(); //mengambil semua input dari api client
        $validate = Validator::make($updateData, [
            'hasilkelola' => 'max:60',
        ]); //membuat rule validasi input
        if ($validate->fails())
            return response(['message' => $validate->errors()], 400); //return error invalid input
        //////////////////////////////////////////////////////////////////////////////
        if ($updateData['hasilkelola'] == 'kurang') {
            $updateData['bobot'] = 0.14;
        }
        if ($updateData['hasilkelola'] == 'cukup') {
            $updateData['bobot'] = 0.26;
        }
        if ($updateData['hasilkelola'] == 'baik') {
            $updateData['bobot'] = 0.60;
        }
        if ($updateData['hasilkelola'] == null) {
            $updateData['bobot'] = 0;
        }
        $updateData['bobotwawancara'] = $updateData['bobot'] * 0.08;
        //////////////////////////////////////////////////////////////////////////////
        $kelolawawancara->hasilkelola = $updateData['hasilkelola'];
        $kelolawawancara->bobot = $updateData['bobot'];
        $kelolawawancara->bobotwawancara = $updateData['bobotwawancara'];
        //////////////////////////////////////////////////////////////////////////////

        if ($kelolawawancara->save()) {
            return response([
                'message' => 'Update Kelola Wawancara Sukses',
                'data' => $kelolawawancara,
            ], 200);
        } //return data Kelola Wawancara yang telah di edit dalam bentuk json
        return response([
            'message' => 'Update Kelola Wawancara Gagal',
            'data' => null,
        ], 400); //return message saat Kelola Wawancara gagal di edit
    }
}
