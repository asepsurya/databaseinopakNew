<?php

namespace App\Http\Controllers;
use App\Models\Province;
use App\Models\Regency;
use App\Models\District;
use App\Models\Village;
use Illuminate\Http\Request;
use App\Models\ikm;

class MyController extends Controller
{
    public function brainstorming(){
        return view('pages.brainstorming.view',[
            'title'=>'Sesi Brainstorming'
        ]);
    }
    public function dataikm(){
        return view('pages.ikm.show',[
            'title'=>'Data IKM'
        ]);
    }
    public function brainstormingInsert(){
        
        return view('pages.brainstorming.insert',[
            'title'=>'Form Brainstorming',
            'provinsi'=>Province::Firstwhere('id',auth()->user()->id_provinsi),  
            'kota'=>Regency::where('id',auth()->user()->id_kota)->get(),
            'kecamatan'=>District::where('id',auth()->user()->id_kecamatan)->get(),
            'desa'=>Village::where('id',auth()->user()->id_desa)->get(),
        ]);
    }
    
    public function kurasi(){
        return view('pages.kurasi.view',[
            'title'=>'Kurasi IKM'
        ]);
    }

    public function searchIkm(Request $request)
    {
        $keyword = $request->query('q');

    $results = Ikm::with('kategori')
        ->where('nama', 'like', "%{$keyword}%")
        ->orWhere('jenisProduk', 'like', "%{$keyword}%")
        ->get()
        ->map(function ($item) {
            $item->encrypted_id = encrypt($item->id);
            return $item;
        });

    return response()->json($results);
    }
}
