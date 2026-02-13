<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Ikm extends Model
{
    use HasFactory;
    protected $guarded =["id"];

       public function province(){
        return $this->belongsTo("App\Models\Province","id_provinsi");
    }
    public function regency(){
        return $this->belongsTo("App\Models\Regency","id_kota");
    }
    public function district(){
        return $this->belongsTo("App\Models\District","id_kecamatan");
    }
    public function village(){
        return $this->belongsTo("App\Models\Village","id_desa");
    }
    public function bencmark(){
        return $this->hasMany("App\Models\BencmarkProduk","id_Ikm");
    }
    public function produkDesign(){
        return $this->hasMany("App\Models\ProdukDesign","id_Ikm");
    }
    public function cots(){
        return $this->hasMany("App\Models\Cots","id_Ikm");
    }
     public function kategori(){
        return $this->belongsTo("App\Models\Project","id_Project");
    }

}
