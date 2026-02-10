<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\ikm;
use App\Models\cots;
use App\Models\DokumentasiCots;
use Faker\Factory as Faker; //import Faker untuk generate random data
use Barryvdh\DomPDF\Facade\Pdf;
// use PDF; //import Fungsi PDF
class ReportController extends Controller
{
    public function ReportBrainstorming($id, $name){
        $dataDummy = [];
        $data1 = ikm::where('id',$id)->get();
         foreach($data1 as $a){
            $people = (Object) [
                "jenis_produk" => $a->jenisProduk,
                "merk"=>$a->merk,
                "komposisi"=>$a->komposisi,
                "varian"=>$a->varian,
                "kelebihan"=>$a->kelebihan,
                "namaUsaha"=>$a->namaUsaha,
                "PIRT"=>$a->noPIRT,
                "Halal"=>$a->noHalal,
                "legalitasLain"=>$a->legalitasLain,
                "saranpenyajian"=>$a->other,
                "segmentasi"=>$a->segmentasi,
                "jeniskemasan"=>$a->jenisKemasan,
                "tagline"=>$a->tagline,
                "redaksi"=>$a->redaksi,
                "gramasi"=>$a->gramasi,
                "harga"=>$a->harga
            ];
         }
       
        array_push($dataDummy, $people);
        $data = [
            "row" => $dataDummy
        ];
       
        $pdf = PDF::loadView('report.brainstorming', $data);

        $pdf->setOption('enable-local-file-access', true);

        return $pdf->download('Form Brainstorming-'.$name.'.pdf');

    }
    public function Reportcots($id, $name){
       
        
        $dataDummy = [];
        $data = ikm::where('id',$id)->get();
        $datadoc = DokumentasiCots::where('id_ikm',$id)->get();
        
         foreach($data as $a){
            foreach ($a->cots as $cots){
                
                
            $items= (Object) [
                "nama" => $a->nama,
                "NamaProduk"=>$a->jenisProduk,
                "merk"=>$a->merk,
                "alamat"=>$a->alamat,
                "provinsi"=>$a->province->name,
                "kota"=>$a->regency->name,
                "kecamatan"=>$a->district->name,
                "desa"=>$a->village->name,
                "no_hp"=>$a->telp,
                "sejarahSingkat"=>$cots->sejarahSingkat,
                "produkjual"=>$cots->produkjual,
                "bahanbaku"=>$cots->bahanbaku,
                "carapemasaran"=>$cots->carapemasaran,
                "prosesproduksi"=>$cots->prosesproduksi,
                "omset"=>$cots->omset,
                "kapasitasproduksi"=>$cots->kapasitasProduksi,
                "kendala"=>$cots->kendala,
                "solusi"=>$cots->solusi,
                "gambar"=>$a->gambar,
                
            ];
             
            }
          
        }
        array_push($dataDummy, $items);
        $data =  $dataDummy;


        $pdf = PDF::loadView('report.cots', [
            'row'=>$data,
            'dokumentasi'=>$datadoc
        ]);
        $pdf->setOption('enable-local-file-access', true);

        return $pdf->download('Laporan COTS-'.$name.'.pdf');
        
    }
    public function ikmReport($id_project, $nama_project){
      
        $dataDummy = [];
        $dataikm = ikm::where('id_Project',$id_project)->get();
        $pdf = PDF::loadView('report.ikm',[
            'ikm'=> $dataikm
        ]);
        $pdf->setOption('enable-local-file-access', true);

        return $pdf->download('Data Ikm-'.$nama_project.'.pdf');
    }

}
