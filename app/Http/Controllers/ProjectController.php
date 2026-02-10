<?php

namespace App\Http\Controllers;
use App\Models\Project;
use App\Models\ikm;
use Illuminate\Http\Request;

class ProjectController extends Controller
{
    public function index(){
        if(request('search')){
              $data = Project::where('NamaProjek','like','%' . request('search') .'%')->GET();
        }else{
             $data = Project::orderBy('id', 'DESC')->get();
        }
   
        return view('pages.project.view',[
            'title'=>'Project',
            'projects'=>$data,
            'searchIkm'=>ikm::all()
        ]);
     }
    public function store(request $request){
            $validasi = $request->validate([
                'NamaProjek'=>'required',
                'keterangan'=>'',
            ]);
            Project::create($validasi);
            $request->session()->flash('Berhasil', 'Data Berhasil ditambahkan');
            return redirect('/project');
    }
    public function update(request $request){
        $validasi = $request->validate([
            'NamaProjek'=>'required',
            'keterangan'=>'',
        ]);
        Project::where('id',$request->id)->update($validasi);
        $request->session()->flash('UpdateBerhasil', 'Data Berhasil diubah');
        return redirect('/project');
    }
    public function hapus(request $request){
        Project::destroy($request->id);
        ikm::where('id_project',$request->id)->delete();
        $request->session()->flash('HapusBerhasil', 'Data Berhasil dihapus');
        return redirect('/project');
    }
}
