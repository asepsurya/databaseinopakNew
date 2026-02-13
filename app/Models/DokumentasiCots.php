<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DokumentasiCots extends Model
{
    use HasFactory;
    protected $guarded=['id'];
    protected $casts = [
        'gambar' => 'array'
    ];
    public function ikms(){
        return $this->hasMany('App\\Models\\Ikm','id');
    }
}
