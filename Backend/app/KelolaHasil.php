<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

class KelolaHasil extends Model
{
    protected $table = 'kelolahasil';
    protected $primaryKey = 'id_kelolahasil';

    protected $fillable = [
        'total',
        'id_user',
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
