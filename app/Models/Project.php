<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Project extends Model
{
    use HasFactory;
    protected $guarded  =['id'];

    public function ikms(){
        return $this->hasMany('App\Models\ikm','id_Project');
    }

    public function produkDesigns(){
        return $this->hasManyThrough('App\Models\ProdukDesign', 'App\Models\ikm', 'id_Project', 'id_ikm');
    }
}
