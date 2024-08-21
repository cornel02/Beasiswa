<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

class Nilai extends Model
{
    protected $table = 'nilai';
    protected $primaryKey = 'id_nilai';

    protected $fillable = [
        'indonesia',
        'matematika',
        'ipa',
        'ips',
        'inggris',
        'pkn',
        'agama',
        'nilaiakhir',
        'bobot',
        'bobotnilai',
        'id_user',
        'id_profile',
        'id_sertifikat'

    ];

    public function getCreatedAtAttribute()
    {
        if (!is_null($this->attributes['created_at'])) {
            return Carbon::parse($this->attributes['created_at'])->format('Y-m-d H:i:s');
        }
    }

    public function getUpdateAtAttribute()
    {
        if (!is_null($this->attributes9['updated_at'])) {
            return Carbon::parse($this->attributes['updated_at'])->format('Y-m-d H:i:s');
        }
    }
}
