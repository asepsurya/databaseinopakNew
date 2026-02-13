<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Cots extends Model
{
    use HasFactory;
    protected $guarded=["id"];

    public function Cotsikms(){
        return $this->belongsTo("App\Models\Ikm","id_Ikm");
    }
    public function docikms(){
        return $this->hasMany("App\Models\DokumentasiCots","id","id_Ikm");
    }
}
