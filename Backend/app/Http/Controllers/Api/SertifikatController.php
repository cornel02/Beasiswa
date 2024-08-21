<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Validator;
use Illuminate\Validation\Rule;  //import library untuk validasi
use App\Sertifikat;

class SertifikatController extends Controller
{
    public function index()
    {
        $sertifikat = Sertifikat::all(); //mengambil semua data sertifikat

        if (count($sertifikat) > 0) {
            return response([
                'message' => 'Tampil Semua Sertifikat Sukses',
                'data' => $sertifikat
            ], 200);
        } //return data semua sertifikat dalam bentuk json

        return response([
            'message' => 'Kosong',
            'data' => null
        ], 404); //return message data sertifikat kosong
    }

    public function show($id)
    {
        $sertifikat = Sertifikat::find($id); //mencari data sertifikat berdasarkan id

        if (!is_null($sertifikat)) {
            return response([
                'message' => 'Tampil Sertifikat Sukses',
                'data' => $sertifikat
            ], 200);
        } //return data sertifikat yang ditemukan dalam bentuk json

        return response([
            'message' => 'Sertifikat Tidak Ada',
            'data' => null
        ], 404); //return message saat data sertifikat tidak ditemukan
    }

    public function showDetail($id)
    {
        $sertifikat = Sertifikat::find($id); //mencari data profile berdasarkan id
        $sertifikat = Sertifikat::join('users', 'sertifikat.id_user', '=', 'users.id_user')
            ->select('sertifikat.*', 'users.nama')
            ->where('sertifikat.id_sertifikat', '=', $id)
            ->get();
        if (!is_null($sertifikat)) {
            return response([
                'message' => 'Tampil Detail Profile Sukses',
                'data' => $sertifikat
            ], 200);
        } //return data profile yang ditemukan dalam bentuk json

        return response([
            'message' => 'Sertifikat Tidak Ada',
            'data' => null
        ], 404); //return message saat data profile tidak ditemukan
    }

    public function store(Request $request)
    {
        $storeData = $request->all(); //mengambil semua input dari api client
        $id_user = $storeData['id_user'];
        $sertifikat = Sertifikat::where([
            ['id_user', $id_user],
        ])->first();

        $validate = Validator::make($storeData, [
            'nama_kegiatan' => 'required|max:255',
            'tingkat' => 'required|max:255',
            'gambar_sertifikat' => 'required|max:2048',
            'id_user' => 'required|max:60|unique:sertifikat',
        ]);

        if ($validate->fails())
            return response(['message' => $validate->errors()], 400);

        if ($storeData['tingkat'] == 'nasional') {
            $storeData['bobots'] = 0.66;
        }
        if ($storeData['tingkat'] == 'provinsi') {
            $storeData['bobots'] = 0.26;
        }
        if ($storeData['tingkat'] == 'kabupaten') {
            $storeData['bobots'] = 0.08;
        }
        $storeData['bobotsertifikat'] = $storeData['bobots'] * 0.19;
        //////////////////////////////////////////////////////////////////////////
        if ($files = $request->file('gambar_sertifikat')) {
            $imageName = $files->getClientOriginalName();
            $request->gambar_sertifikat->move(public_path('images'), $imageName);

            $sertifikat = Sertifikat::create([
                'nama_kegiatan' => $request->nama_kegiatan,
                'tingkat' => $request->tingkat,
                'id_user' =>  $request->id_user,
                'gambar_sertifikat' => '/images/' . $imageName,
                'bobots' => $storeData['bobots'],
                'bobotsertifikat' => $storeData['bobotsertifikat'],
            ]);

            return response([
                'message' => 'Add Sertifikat Success',
                'data' => $sertifikat
            ], 200);
        }
    }


    public function destroy($id)
    {
        $sertifikat = Sertifikat::find($id); //mencari data sertifikat berdasarkan id

        if (is_null($sertifikat)) {
            return response([
                'message' => 'Sertifikat Tidak Ada',
                'data' => null
            ], 404);
        } //return message saat data sertifikat tidak ditemukan

        if ($sertifikat->delete()) {
            return response([
                'message' => 'Hapus Sertifikat Sukses',
                'data' => $sertifikat,
            ], 200);
        } //return message saat berhasil menghapus data sertifikat
        return response([
            'message' => 'Delete Sertifikat Gagal',
            'data' => null,
        ], 400); //return message saat gagal menghapus data sertifikat
    }

    public function update(Request $request, $id)
    {
        $sertifikat = Sertifikat::find($id); //mencari data sertifikat berdasarkan id
        if (is_null($sertifikat)) {
            return response([
                'message' => 'Sertifikat Tidak Ada',
                'data' => null
            ], 404);
        } //return message saat data sertifikat tidak ditemukan

        $updateData = $request->all(); //mengambil semua input dari api client
        $validate = Validator::make($updateData, [
            'nama_kegiatan' => 'required',
            'tingkat' => 'required',
        ]);

        if ($validate->fails())
            return response(['message' => $validate->errors()], 400); //return error invalid input

        if ($updateData['tingkat'] == 'nasional') {
            $updateData['bobots'] = 0.6555;
        }
        if ($updateData['tingkat'] == 'provinsi') {
            $updateData['bobots'] = 0.2648;
        }
        if ($updateData['tingkat'] == 'kabupaten') {
            $updateData['bobots'] = 0.0796;
        }
        $updateData['bobotsertifikat'] = $updateData['bobots'] * 0.19;
        //////////////////////////////////////////////////////////////////////////////
        $sertifikat->nama_kegiatan = $updateData['nama_kegiatan'];
        $sertifikat->tingkat = $updateData['tingkat'];
        $sertifikat->bobots = $updateData['bobots'];
        $sertifikat->bobotsertifikat = $updateData['bobotsertifikat'];
        /////////////////////////////////////////////////////////////////////////////
        // if ($files = $request->file('gambar_sertifikat')) {
        //     $imageName = $files->getClientOriginalName();
        //     $request->gambar_sertifikat->move(public_path('images'), $imageName);

        //     $sertifikat = Sertifikat::create([
        //         'nama_kegiatan' => $request->nama_kegiatan,
        //         'gambar_sertifikat' => '/images/' . $imageName,
        //         'bobots' => $updateData['bobots'],
        //         'bobotsertifikat' => $updateData['bobotsertifikat'],
        //     ]);

        if ($sertifikat->save()) {
            return response([
                'message' => 'Update Sertifikat Sukses',
                'data' => $sertifikat,
            ], 200);
        }
        return response([
            'message' => 'Update Sertifikat Gagal',
            'data' => null,
        ], 400);
    }
}
