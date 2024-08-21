<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

class KelolaWawancara extends Model
{
    protected $table = 'kelolawawancara';
    protected $primaryKey = 'id_kelolawawancara';

    protected $fillable = [
        'hasilkelola',
        'bobot',
        'bobotwawancara',
        'id_user',
        'id_wawancara'
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
