<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Validator;
use Illuminate\Validation\Rule;  //import library untuk validasi
use App\Nilai;
use App\Sertifikat;
use App\Profile;
use App\User;

class NilaiController extends Controller
{
    public function index()
    {
        $nilai = Nilai::all(); //mengambil semua data nilai
        $nilai = Nilai::join('users', 'nilai.id_user', '=', 'users.id_user')
            ->join('profile', 'nilai.id_profile', '=', 'profile.id_profile')
            ->join('sertifikat', 'nilai.id_sertifikat', '=', 'sertifikat.id_sertifikat')
            ->select(
                'nilai.*',
                'users.nama',
                'profile.noinduk',
                'sertifikat.nama_kegiatan',
                'sertifikat.tingkat',
                'sertifikat.gambar_sertifikat',
                'sertifikat.bobots',
            )
            ->get();
        if (count($nilai) > 0) {
            return response([
                'message' => 'Tampil Semua Nilai Sukses',
                'data' => $nilai
            ], 200);
        } //return data semua nilai dalam bentuk json

        return response([
            'message' => 'Empty',
            'data' => null
        ], 404); //return message data nilai kosong
    }

    public function show($id)
    {
        $nilai = Nilai::find($id); //mencari data nilai berdasarkan id

        if (!is_null($nilai)) {
            return response([
                'message' => 'Tampil Nilai Sukses',
                'data' => $nilai
            ], 200);
        } //return data nilai yang ditemukan dalam bentuk json

        return response([
            'message' => 'Nilai Tidak Ada',
            'data' => null
        ], 404); //return message saat data nilai tidak ditemukan
    }

    public function showDetail($id)
    {
        $nilai = Nilai::find($id);
        $nilai = Nilai::join('users', 'Nilai.id_user', '=', 'users.id_user')
            ->select('Nilai.*', 'users.nama')
            ->where('Nilai.id_sertifikat', '=', $id)
            ->get();
        if (!is_null($nilai)) {
            return response([
                'message' => 'Tampil Detail Nilai Success',
                'data' => $nilai
            ], 200);
        } //return data nilai yang ditemukan dalam bentuk json

        return response([
            'message' => 'Nilai Not Found',
            'data' => null
        ], 404); //return message saat data nilai tidak ditemukan
    }

    public function store(Request $request)
    {
        $storeData = $request->all(); //mengambil semua input dari api client
        $id_user = $storeData['id_user'];
        $id_profile = $storeData['id_profile'];
        $id_sertifikat = $storeData['id_sertifikat'];
        $nilai = Nilai::where([
            ['id_user', $id_user], ['id_profile', $id_profile], ['id_sertifikat', $id_sertifikat]
        ])->first();
        $validate = Validator::make($storeData, [
            'indonesia' => 'required|max:255',
            'matematika' => 'required|max:255',
            'ipa' => 'required|max:255',
            'ips' => 'required|max:255',
            'inggris' => 'required|max:255',
            'pkn' => 'required|max:255',
            'agama' => 'required|max:255',
            'id_user' => 'required|max:255||unique:nilai',
            'id_profile' => 'required|max:255||unique:nilai',
            'id_sertifikat' => 'required|max:255||unique:nilai',

        ]); //membuat rule validasi input
        $storeData['nilaiakhir'] = ($storeData['indonesia'] + $storeData['matematika'] + $storeData['ipa'] +
            $storeData['ips'] + $storeData['inggris'] + $storeData['pkn'] + $storeData['agama']) / 7;

        if ($storeData['nilaiakhir'] >= 50 && $storeData['nilaiakhir'] <= 70) {
            $storeData['bobot'] = 0.10;
        }
        if ($storeData['nilaiakhir'] >= 71 && $storeData['nilaiakhir'] <= 85) {
            $storeData['bobot'] = 0.28;
        }
        if ($storeData['nilaiakhir'] >= 86 && $storeData['nilaiakhir'] <= 100) {
            $storeData['bobot'] = 0.62;
        } else if ($storeData['nilaiakhir'] < 50) {
            $storeData['bobot'] = 0;
        }

        $storeData['bobotnilai'] =  $storeData['bobot'] * 0.72;
        //////////////////////////////////////////////////////////////////////////////
        // $id_user = $storeData['id_user'];
        // $user = User::where('id_user', $id_user)->first();
        // if (is_null($user)) {
        //     return response([
        //         'message' => 'user tidak ditemukan',
        //         'data' => null
        //     ], 404);
        // }
        // //////////////////////////////////////////////////////////////////////////////
        // $id_profile = $storeData['id_profile'];
        // $profile = Profile::where('id_profile', $id_profile)->first();
        // if (is_null($profile)) {
        //     return response([
        //         'message' => 'profile tidak ditemukan',
        //         'data' => null
        //     ], 404);
        // }
        // //////////////////////////////////////////////////////////////////////////////
        // $id_sertifikat = $storeData['id_sertifikat'];
        // $sertifikat = Sertifikat::where('id_sertifikat', $id_sertifikat)->first();
        // if (is_null($sertifikat)) {
        //     return response([
        //         'message' => 'sertifikat tidak ditemukan',
        //         'data' => null
        //     ], 404);
        // }
        //////////////////////////////////////////////////////////////////////////////
        if ($validate->fails())
            return response(['message' => $validate->errors()], 400); //return error invalid input

        $nilai = Nilai::create($storeData); //menambah data nilai baru
        return response([
            'message' => 'Tambah Nilai Sukses',
            'data' => $nilai,
        ], 200); //return data nilai baru dalam bentuk json
    }

    public function destroy($id)
    {
        $nilai = Nilai::find($id); //mencari data nilai berdasarkan id

        if (is_null($nilai)) {
            return response([
                'message' => 'Nilai tidak ada',
                'data' => null
            ], 404);
        } //return message saat data nilai tidak ditemukan

        if ($nilai->delete()) {
            return response([
                'message' => 'Hapus Nilai Sukses',
                'data' => $nilai,
            ], 200);
        } //return message saat berhasil menghapus data nilai
        return response([
            'message' => 'Hapus Nilai Gagal',
            'data' => null,
        ], 400); //return message saat gagal menghapus data nilai
    }

    public function update(Request $request, $id)
    {
        $nilai = Nilai::find($id); //mencari data nilai berdasarkan id
        if (is_null($nilai)) {
            return response([
                'message' => 'Nilai tidak ada',
                'data' => null
            ], 404);
        } //return message saat data nilai tidak ditemukan

        $updateData = $request->all(); //mengambil semua input dari api client
        $validate = Validator::make($updateData, [
            'indonesia' => 'max:255',
            'matematika' => 'max:255',
            'ipa' => 'max:255',
            'ips' => 'max:255',
            'inggris' => 'max:255',
            'pkn' => 'max:255',
            'agama' => 'max:255',
        ]); //membuat rule validasi input

        if ($validate->fails())
            return response(['message' => $validate->errors()], 400); //return error invalid input
        //////////////////////////////////////////////////////////////////////////////
        $updateData['nilaiakhir'] = ($updateData['indonesia'] + $updateData['matematika'] + $updateData['ipa'] +
            $updateData['ips'] + $updateData['inggris'] + $updateData['pkn'] + $updateData['agama']) / 7;

        if ($updateData['nilaiakhir'] >= 50 && $updateData['nilaiakhir'] <= 70) {
            $updateData['bobot'] = 0.0872;
        }
        if ($updateData['nilaiakhir'] >= 71 && $updateData['nilaiakhir'] <= 85) {
            $updateData['bobot'] = 0.2648;
        }
        if ($updateData['nilaiakhir'] >= 86 && $updateData['nilaiakhir'] <= 100) {
            $updateData['bobot'] = 0.6555;
        } else if ($updateData['nilaiakhir'] < 50) {
            $updateData['bobot'] = 0;
        }
        $updateData['bobotnilai'] =  $updateData['bobot'] * 0.72;
        //////////////////////////////////////////////////////////////////////////////
        $nilai->indonesia = $updateData['indonesia'];
        $nilai->matematika = $updateData['matematika'];
        $nilai->ipa = $updateData['ipa'];
        $nilai->ips = $updateData['ips'];
        $nilai->inggris = $updateData['inggris'];
        $nilai->pkn = $updateData['pkn'];
        $nilai->agama = $updateData['agama'];
        $nilai->nilaiakhir = $updateData['nilaiakhir'];
        $nilai->bobot = $updateData['bobot'];
        $nilai->bobotnilai = $updateData['bobotnilai'];
        if ($nilai->save()) {
            return response([
                'message' => 'Update Nilai sukses',
                'data' => $nilai,
            ], 200);
        } //return data nilai yang telah di edit dalam bentuk json
        return response([
            'message' => 'Update Nilai gagal',
            'data' => null,
        ], 400); //return message saat nilai gagal di edit
    }
}
