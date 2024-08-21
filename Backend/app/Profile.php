<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

class profile extends Model
{
    protected $table = 'profile';
    protected $primaryKey = 'id_profile';

    protected $fillable = [
        'noinduk',
        'tanggal',
        'tempat',
        'jeniskelamin',
        'asalsekolah',
        'tahun',
        'id_user'
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
