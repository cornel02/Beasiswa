<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\KelolaHasil;
use Illuminate\Http\Request;
use Validator;  //import library untuk validasi
use App\User;
use App\Nilai;
use App\Sertifikat;
use App\KelolaWawancara;


class KelolaHasilController extends Controller
{
    public function index()
    {
        $kelolahasil = KelolaHasil::join('users', 'kelolahasil.id_user', '=', 'users.id_user')
            ->join('nilai', 'kelolahasil.id_user', '=', 'nilai.id_nilai')
            ->join('sertifikat', 'kelolahasil.id_user', '=', 'sertifikat.id_sertifikat')
            ->join('kelolawawancara', 'kelolahasil.id_user', '=', 'kelolawawancara.id_kelolawawancara')
            ->select('kelolahasil.total', 'users.nama', 'nilai.bobotnilai', 'sertifikat.bobotsertifikat', 'kelolawawancara.bobotwawancara')
            ->get();
        //////////////////////////////////////////////////////////////////////////////

        if (count($kelolahasil) > 0) {
            return response([
                'message' => 'Tampil Semua Kelola Hasil Sukses',
                'data' => $kelolahasil
            ], 200);
        } //return data semua Kelola Hasil dalam bentuk json

        return response([
            'message' => 'Kosong',
            'data' => null
        ], 404); //return message data Kelola Hasil kosong
    }

    public function store(Request $request)
    {
        $storeData = $request->all(); //mengambil semua input dari api client
        //////////////////////////////////////////////////////////////////////////////
        $validate = Validator::make($storeData, [
            'id_user' => 'required|max:255||unique:kelolahasil',
        ]);
        //////////////////////////////////////////////////////////////////////////////
        $id_user = $storeData['id_user'];
        $user = User::where('id_user', $id_user)->first();
        if (is_null($user)) {
            return response([
                'message' => 'user tidak ditemukan',
                'data' => null
            ], 404);
        }
        $nilai = Nilai::where('id_nilai', $id_user)->first();
        if (is_null($nilai)) {
            return response([
                'message' => 'nilai tidak ditemukan',
                'data' => null
            ], 404);
        }
        $sertifikat = Sertifikat::where('id_sertifikat', $id_user)->first();
        if (is_null($sertifikat)) {
            return response([
                'message' => 'sertifikat tidak ditemukan',
                'data' => null
            ], 404);
        }
        $wawancara = KelolaWawancara::where('id_kelolawawancara', $id_user)->first();
        if (is_null($wawancara)) {
            return response([
                'message' => 'wawancara tidak ditemukan',
                'data' => null
            ], 404);
        }
        //////////////////////////////////////////////////////////////////////////////
        $storeData['total'] = $nilai['bobotnilai'] + $sertifikat['bobotsertifikat'] + $wawancara['bobotwawancara'];
        //////////////////////////////////////////////////////////////////////////////
        //menambah data Kelola Hasil baru
        if ($validate->fails()) {
            return response(['message' => $validate->errors()], 400);
        }
        $kelola_hasil = KelolaHasil::create($storeData);
        return response([
            'message' => 'Tambah Kelola Hasil Sukses',
            'data' => $kelola_hasil,
        ], 200); //return data Kelola Hasil baru dalam bentuk json
    }

    public function update(Request $request, $id)
    {
        $kelolahasil = KelolaHasil::find($id); //mencari data nilai berdasarkan id
        if (is_null($kelolahasil)) {
            return response([
                'message' => 'Kelola Hasil tidak ada',
                'data' => null
            ], 404);
        }

        $updateData = $request->all();
        $id_user = $updateData['id_user'];
        $user = User::where('id_user', $id_user)->first();
        if (is_null($user)) {
            return response([
                'message' => 'user tidak ditemukan',
                'data' => null
            ], 404);
        }
        $nilai = Nilai::where('id_nilai', $id_user)->first();
        if (is_null($nilai)) {
            return response([
                'message' => 'nilai tidak ditemukan',
                'data' => null
            ], 404);
        }
        $sertifikat = Sertifikat::where('id_sertifikat', $id_user)->first();
        if (is_null($sertifikat)) {
            return response([
                'message' => 'sertifikat tidak ditemukan',
                'data' => null
            ], 404);
        }
        $wawancara = KelolaWawancara::where('id_kelolawawancara', $id_user)->first();
        if (is_null($wawancara)) {
            return response([
                'message' => 'wawancara tidak ditemukan',
                'data' => null
            ], 404);
        }
        //////////////////////////////////////////////////////////////////////////////
        $updateData['total'] = $nilai['bobotnilai'] + $sertifikat['bobotsertifikat'] + $wawancara['bobotwawancara'];
        //////////////////////////////////////////////////////////////////////////////
        $kelolahasil->total = $updateData['total'];

        if ($kelolahasil->save()) {
            return response([
                'message' => 'kelolahasil Berhasil Diperbarui',
                'data' => $kelolahasil,
            ], 200);

            return response([
                'message' => 'Gagal Diperbarui',
                'data' => null,
            ], 400);
        }
    }
}
