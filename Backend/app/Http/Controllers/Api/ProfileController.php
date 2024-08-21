<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Validator;  //import library untuk validasi
use App\Profile;
use App\User;

class ProfileController extends Controller
{
    public function index()
    {
        $profile = Profile::all(); //mengambil semua data profile
        $profile = Profile::join('users', 'profile.id_user', '=', 'users.id_user')
            ->select('profile.*', 'users.nama', 'users.email')
            ->get();
        if (count($profile) > 0) {
            return response([
                'message' => 'Tampil Semua Profile Sukses',
                'data' => $profile
            ], 200);
        } //return data semua profile dalam bentuk json

        return response([
            'message' => 'Kosong',
            'data' => null
        ], 404); //return message data profile kosong
    }

    //method untuk menampilkan 1 data profile (search)
    public function show($id)
    {
        $profile = Profile::find($id);
        if (!is_null($profile)) {
            return response([
                'message' => 'Tampil Profile Sukses',
                'data' => $profile
            ], 200);
        } //return data profile yang ditemukan dalam bentuk json

        return response([
            'message' => 'Profile Tidak Ada',
            'data' => null
        ], 404); //return message saat data profile tidak ditemukan
    }

    public function showDetail($id)
    {
        $profile = Profile::find($id); //mencari data profile berdasarkan id
        $profile = Profile::join('users', 'profile.id_user', '=', 'users.id_user')
            ->select('profile.*', 'users.nama')
            ->where('profile.id_profile', '=', $id)
            ->get();
        if (!is_null($profile)) {
            return response([
                'message' => 'Tampil Detail Profile Sukses',
                'data' => $profile
            ], 200);
        } //return data profile yang ditemukan dalam bentuk json

        return response([
            'message' => 'Profile Tidak Ada',
            'data' => null
        ], 404); //return message saat data profile tidak ditemukan
    }

    //method untuk menambah 1 data profile baru (create)
    public function store(Request $request)
    {
        $storeData = $request->all(); //mengambil semua input dari api client
        $id_user = $storeData['id_user'];
        $profile = Profile::where([
            ['id_user', $id_user],
        ])->first();

        $validate = Validator::make($storeData, [
            'noinduk' => 'required|max:60|unique:profile',
            'tanggal' => 'required|max:60',
            'tempat' => 'required|max:60',
            'jeniskelamin' => 'required|max:60',
            'asalsekolah' => 'required|max:60',
            'tahun' => 'required|max:60',
            'id_user' => 'required|max:60|unique:profile',

        ]); //membuat rule validasi input
        //////////////////////////////////////////////////////////////////////////////


        if ($validate->fails())
            return response(['message' => $validate->errors()], 400); //return error invalid input

        $profile = Profile::create($storeData); //menambah data profile baru
        return response([
            'message' => 'Tambah Profile Sukses',
            'data' => $profile,
        ], 200); //return data profile baru dalam bentuk json
    }

    //method untuk menghapus 1 data profile (delete)
    public function destroy($id)
    {
        $profile = Profile::find($id); //mencari data profile berdasarkan id

        if (is_null($profile)) {
            return response([
                'message' => 'Profile Tidak Ada',
                'data' => null
            ], 404);
        } //return message saat data profile tidak ditemukan

        if ($profile->delete()) {
            return response([
                'message' => 'Hapus Profile Sukses',
                'data' => $profile,
            ], 200);
        } //return message saat berhasil menghapus data profile
        return response([
            'message' => 'Hapus Profile Gagal',
            'data' => null,
        ], 400); //return message saat gagal menghapus data profile
    }

    //method untuk mengubah 1 data profile (update)
    public function update(Request $request, $id)
    {
        $profile = Profile::find($id);
        if (is_null($profile)) {
            return response([
                'message' => 'Profile Tidak Ada',
                'data' => null
            ], 404);
        } //return message saat data profile tidak ditemukan

        $updateData = $request->all(); //mengambil semua input dari api client
        $validate = Validator::make($updateData, [
            'noinduk' => 'max:60',
            'tanggal' => 'max:60',
            'tempat' => 'max:60',
            'jeniskelamin' => 'max:60',
            'asalsekolah' => 'max:60',
            'tahun' => 'max:60',
        ]); //membuat rule validasi input
        if ($validate->fails())
            return response(['message' => $validate->errors()], 400); //return error invalid input

        $profile->noinduk = $updateData['noinduk'];
        $profile->tanggal = $updateData['tanggal'];
        $profile->tempat = $updateData['tempat'];
        $profile->jeniskelamin = $updateData['jeniskelamin'];
        $profile->asalsekolah = $updateData['asalsekolah'];
        $profile->tahun = $updateData['tahun'];

        if ($profile->save()) {
            return response([
                'message' => 'Update Profile Sukses',
                'data' => $profile,
            ], 200);
        } //return data profile yang telah di edit dalam bentuk json
        return response([
            'message' => 'Update Profile Gagal',
            'data' => null,
        ], 400); //return message saat profile gagal di edit
    }
}
