<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreIkmRequest;
use App\Http\Requests\UpdateIkmRequest;
use App\Models\District;
use App\Models\Ikm;
use App\Models\Project;
use App\Models\Province;
use App\Models\Regency;
use App\Models\Village;
use App\Models\BencmarkProduk;
use App\Models\ProdukDesign;
use App\Models\Cots;
use App\Models\DokumentasiCots;
use App\Models\IkmFolderDocument;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use App\Helpers\ThumbnailHelper;

class IkmController extends Controller
{
    public function view(Project $project, $folder_id = null)
    {
        $folders = \App\Models\IkmFolder::where('id_Project', $project->id)
            ->where('parent_id', $folder_id)
            ->get();
            
        $ikms = Ikm::where('id_Project', $project->id)
            ->where('folder_id', $folder_id)
            ->get();
            
        $currentFolder = null;
        $breadcrumbs = [];
        if ($folder_id) {
            $currentFolder = \App\Models\IkmFolder::findOrFail($folder_id);
            // Build breadcrumbs
            $temp = $currentFolder;
            while ($temp) {
                array_unshift($breadcrumbs, $temp);
                $temp = $temp->parent;
            }
        }
        
        $allFolders = \App\Models\IkmFolder::where('id_Project', $project->id)->get();

        $availableParentFolders = \App\Models\IkmFolder::where('id_Project', $project->id)
            ->where(function ($query) use ($folder_id) {
                if ($folder_id) {
                    $query->where('id', $folder_id)
                          ->orWhere('parent_id', $folder_id);
                } else {
                    $query->whereNull('parent_id');
                }
            })
            ->orderBy('name')
            ->get();

        return view('pages.ikm.show',[
            'title'=>'Form Brainstorming',
            'project'=>$project,
            'folders' => $folders,
            'dataIkm' => $ikms,
            'currentFolder' => $currentFolder,
            'breadcrumbs' => $breadcrumbs,
            'allFolders' => $allFolders,
            'availableParentFolders' => $availableParentFolders,
            'provinsi'=>Province::all()
        ]);
    }
       public function tambahIkm(Request $request){
        $validated = $request->validate([
            'nama' => 'required|string|max:255',
            'jenisProduk' => 'required|string|max:255',
            'id_Project' => 'required|exists:projects,id',
            'folder_id' => 'nullable|exists:ikm_folders,id'
        ]);

        Ikm::create([
            'nama' => $validated['nama'],
            'jenisProduk' => $validated['jenisProduk'],
            'id_Project' => $validated['id_Project'],
            'folder_id' => $validated['folder_id'] ?? null
        ]);

        $request->session()->flash('Berhasil', 'Data IKM Berhasil Disimpan');
        if ($request->folder_id) {
            return redirect('/project/dataIkm/'.$validated['id_Project'].'/folder/'.$request->folder_id);
        }
        return redirect('/project/dataIkm/'.$validated['id_Project']);
    }
    public function createIkm(StoreIkmRequest $request){
        $validatedData = $request->validated();
        $validatedData['folder_id'] = $request->folder_id;

        Ikm::create($validatedData);
        $request->session()->flash('Berhasil', 'Data IKM Berhasil Disimpan');
        if ($request->folder_id) {
            return redirect('/project/dataIkm/'.$validatedData['id_Project'].'/folder/'.$request->folder_id);
        }
        return redirect('/project/dataIkm/'.$validatedData['id_Project']);
    }

    public function createFolder(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'id_Project' => 'required|exists:projects,id',
            'parent_id' => 'nullable|exists:ikm_folders,id'
        ]);

        \App\Models\IkmFolder::create([
            'name' => $request->name,
            'id_Project' => $request->id_Project,
            'parent_id' => $request->parent_id,
        ]);

        $request->session()->flash('Berhasil', 'Folder Berhasil Dibuat');
        if ($request->parent_id) {
            return redirect('/project/dataIkm/'.$request->id_Project.'/folder/'.$request->parent_id);
        }
        return redirect('/project/dataIkm/'.$request->id_Project);
    }

    public function renameFolder(Request $request)
    {
        $request->validate([
            'id_Folder' => 'required|exists:ikm_folders,id',
            'name'      => 'required|string|max:255',
        ]);

        $folder = \App\Models\IkmFolder::findOrFail($request->id_Folder);
        $folder->name = $request->name;
        $folder->save();

        return response()->json(['success' => true, 'message' => 'Nama folder berhasil diubah.']);
    }

    public function moveFolderAction(Request $request)
    {
        $request->validate([
            'id_Folder'       => 'required|exists:ikm_folders,id',
            'target_parent_id' => 'nullable|integer',
            'id_Project'      => 'required|exists:projects,id',
        ]);

        $folder = \App\Models\IkmFolder::findOrFail($request->id_Folder);
        $targetParentId = $request->filled('target_parent_id') ? $request->integer('target_parent_id') : null;

        if ($targetParentId && !\App\Models\IkmFolder::where('id', $targetParentId)->exists()) {
            $targetParentId = null;
        }

        if ($targetParentId == $folder->id) {
            return response()->json(['success' => false, 'message' => 'Tidak bisa memindahkan folder ke dalam dirinya sendiri.'], 422);
        }

        $folder->parent_id = $targetParentId;
        $folder->save();

        return response()->json(['success' => true, 'message' => 'Folder berhasil dipindahkan.']);
    }

    public function deleteFolder(Request $request)
    {
        $request->validate([
            'id_Folder' => 'required|exists:ikm_folders,id',
            'id_Project' => 'required|exists:projects,id',
        ]);

        $folder = \App\Models\IkmFolder::findOrFail($request->id_Folder);
        $parentId = $folder->parent_id;

        // Recursively delete folder and all its contents
        $this->deleteFolderRecursive($folder);

        return response()->json([
            'success' => true,
            'message' => 'Folder dan semua isinya berhasil dihapus.',
            'redirect' => $parentId
                ? '/project/dataIkm/' . $request->id_Project . '/folder/' . $parentId
                : '/project/dataIkm/' . $request->id_Project
        ]);
    }

    /**
     * Recursively delete a folder and all its contents (IKMs + sub-folders)
     */
    private function deleteFolderRecursive(\App\Models\IkmFolder $folder): void
    {
        // Delete all IKMs inside this folder
        foreach ($folder->ikms as $ikm) {
            // Delete IKM related files & records
            $bencmarks = \App\Models\BencmarkProduk::where('id_Ikm', $ikm->id)->get();
            foreach ($bencmarks as $b) {
                if ($b->gambar) self::deleteImageFile($b->gambar);
                $b->delete();
            }
            $designs = \App\Models\ProdukDesign::where('id_Ikm', $ikm->id)->get();
            foreach ($designs as $d) {
                if ($d->gambar) self::deleteImageFile($d->gambar);
                $d->delete();
            }
            $cots = \App\Models\Cots::where('id_Ikm', $ikm->id)->get();
            foreach ($cots as $cot) {
                $docs = \App\Models\DokumentasiCots::where('id_Ikm', $cot->id)->get();
                foreach ($docs as $doc) {
                    if ($doc->gambar) self::deleteImageFile($doc->gambar);
                    $doc->delete();
                }
                if ($cot->gambar) self::deleteImageFile($cot->gambar);
                $cot->delete();
            }
            if ($ikm->gambar) self::deleteImageFile($ikm->gambar);
            $ikm->delete();
        }

        // Recursively delete all sub-folders
        foreach ($folder->children as $child) {
            $this->deleteFolderRecursive($child);
        }

        // Delete the folder itself
        $folder->delete();
    }

    public function moveIkm(Request $request)
    {
        $request->validate([
            'id_Ikm' => 'required|exists:ikms,id',
            'target_folder_id' => 'nullable|integer',
            'id_Project' => 'required|exists:projects,id',
        ]);

        $ikm = Ikm::findOrFail($request->id_Ikm);
        $targetFolderId = $request->filled('target_folder_id') ? $request->integer('target_folder_id') : null;

        if ($targetFolderId && !\App\Models\IkmFolder::where('id', $targetFolderId)->exists()) {
            $targetFolderId = null;
        }

        $ikm->folder_id = $targetFolderId;
        $ikm->save();

        $request->session()->flash('UpdateBerhasil', 'Data IKM berhasil dipindahkan.');
        
        $current_folder_id = $request->current_folder_id;
        if ($current_folder_id) {
            return redirect('/project/dataIkm/'.$request->id_Project.'/folder/'.$current_folder_id);
        }
        return redirect('/project/dataIkm/'.$request->id_Project);
    }

    public function UpdateIkm(UpdateIkmRequest $request){
        $validasiData = $request->validated();
        Ikm::where('id',$request->id_Ikm)->update($validasiData);
        $request->session()->flash('UpdateBerhasil', 'Data Berhasil Diubah');
        $idikm = encrypt($request->id_Ikm);
        return redirect()->route('detail', [
            'id_Ikm'     => $idikm,
            'id_project' => $request->id_Project
        ]);
    }

    public function edit(Ikm $ikm)
        {
            return view('pages.ikm.update',[
                'title'=>'Update IKM',
                'project'=>Project::Firstwhere('id',$ikm->id_Project),
                'dataIkm'=>Ikm::where('id',$ikm->id)->get(),
                'provinsi'=>Province::all(),
            ]);
        }
    public function deleteIkm(Request $request){
        // Validate the request
        $validated = $request->validate([
            'id_Ikm' => 'required|integer|exists:ikms,id',
            'id_Project' => 'required|integer|exists:projects,id',
        ]);

        $ikmId = $validated['id_Ikm'];
        $projectId = $validated['id_Project'];

        try {
            DB::beginTransaction();

            // Get the Ikm record first
            $ikm = Ikm::findOrFail($ikmId);

            // Delete related records first (to handle foreign key constraints)
            // Delete Bencmark Produk
            $bencmarks = BencmarkProduk::where('id_Ikm', $ikmId)->get();
            foreach ($bencmarks as $bencmark) {
                // Delete images if exists
                if ($bencmark->gambar) {
                    self::deleteImageFile($bencmark->gambar);
                }
                $bencmark->delete();
            }

            // Delete Produk Design
            $designs = ProdukDesign::where('id_Ikm', $ikmId)->get();
            foreach ($designs as $design) {
                // Delete images if exists
                if ($design->gambar) {
                    self::deleteImageFile($design->gambar);
                }
                $design->delete();
            }

            // Delete Cots and their Dokumentasi
            $cots = Cots::where('id_Ikm', $ikmId)->get();
            foreach ($cots as $cot) {
                // Delete dokumentasi related to this cots
                $dokumentasis = DokumentasiCots::where('id_Ikm', $cot->id)->get();
                foreach ($dokumentasis as $doc) {
                    if ($doc->gambar) {
                        self::deleteImageFile($doc->gambar);
                    }
                    $doc->delete();
                }
                // Delete cots image if exists
                if ($cot->gambar) {
                    self::deleteImageFile($cot->gambar);
                }
                $cot->delete();
            }

            // Delete main Ikm image if exists
            if ($ikm->gambar) {
                self::deleteImageFile($ikm->gambar);
            }

            // Delete the Ikm record
            $ikm->delete();

            DB::commit();

            // Flash success message
            $request->session()->flash('HapusBerhasil', 'Data IKM berhasil dihapus beserta semua file terkait.');
            return redirect('/project/dataIkm/' . $projectId);

        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Error deleting IKM: ' . $e->getMessage());
            $request->session()->flash('HapusGagal', 'Gagal menghapus data IKM: ' . $e->getMessage());
            return redirect('/project/dataIkm/' . $projectId)->withErrors(['error' => 'Gagal menghapus data IKM']);
        }
    }

    /**
     * AJAX delete Ikm - handles deletion via AJAX request
     */
    public function ajaxDeleteIkm(Request $request)
    {
        // Validate the request
        $validated = $request->validate([
            'id_Ikm' => 'required|integer|exists:ikms,id',
            'id_Project' => 'required|integer|exists:projects,id',
        ]);

        $ikmId = $validated['id_Ikm'];
        $projectId = $validated['id_Project'];

        try {
            DB::beginTransaction();

            // Get the Ikm record first
            $ikm = Ikm::findOrFail($ikmId);

            // Delete related records first (to handle foreign key constraints)
            // Delete Bencmark Produk
            $bencmarks = BencmarkProduk::where('id_Ikm', $ikmId)->get();
            foreach ($bencmarks as $bencmark) {
                if ($bencmark->gambar) {
                    self::deleteImageFile($bencmark->gambar);
                }
                $bencmark->delete();
            }

            // Delete Produk Design
            $designs = ProdukDesign::where('id_Ikm', $ikmId)->get();
            foreach ($designs as $design) {
                if ($design->gambar) {
                    self::deleteImageFile($design->gambar);
                }
                $design->delete();
            }

            // Delete Cots and their Dokumentasi
            $cots = Cots::where('id_Ikm', $ikmId)->get();
            foreach ($cots as $cot) {
                $dokumentasis = DokumentasiCots::where('id_Ikm', $cot->id)->get();
                foreach ($dokumentasis as $doc) {
                    if ($doc->gambar) {
                        self::deleteImageFile($doc->gambar);
                    }
                    $doc->delete();
                }
                if ($cot->gambar) {
                    self::deleteImageFile($cot->gambar);
                }
                $cot->delete();
            }

            // Delete main Ikm image if exists
            if ($ikm->gambar) {
                self::deleteImageFile($ikm->gambar);
            }

            // Delete the Ikm record
            $ikm->delete();

            DB::commit();

            return response()->json([
                'success' => true,
                'message' => 'Data IKM berhasil dihapus beserta semua file terkait.',
                'redirect' => '/project/dataIkm/' . $projectId
            ]);

        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Error deleting IKM via AJAX: ' . $e->getMessage());

            return response()->json([
                'success' => false,
                'message' => 'Gagal menghapus data IKM: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Helper function to delete image files from storage
     */
    private static function deleteImageFile(string $imagePath): bool
    {
        try {
            // Delete thumbnails first
            if (class_exists('App\Helpers\ThumbnailHelper')) {
                \App\Helpers\ThumbnailHelper::deleteThumbnails($imagePath);
            }

            // Delete the original file
            $fullPath = storage_path('app/public/' . $imagePath);
            if (file_exists($fullPath)) {
                return unlink($fullPath);
            }

            return true;
        } catch (\Exception $e) {
            Log::error('Error deleting image file: ' . $e->getMessage());
            return false;
        }
    }

    public function uploadFolderDocument(Request $request)
    {
        $request->validate([
            'folder_id' => 'required|exists:ikm_folders,id',
            'id_Project' => 'required|exists:projects,id',
            'nama_file' => 'required|string|max:255',
            'url' => 'required|url|max:2048',
        ]);

        $folder = \App\Models\IkmFolder::findOrFail($request->folder_id);

        $document = new IkmFolderDocument([
            'folder_id' => $folder->id,
            'id_Project' => $request->id_Project,
            'nama_file' => $request->nama_file,
            'url' => $request->url,
            'mime_type' => 'url',
            'size' => null,
            'uploaded_by' => auth()->id(),
        ]);
        $document->save();

        return response()->json([
            'success' => true,
            'message' => 'Dokumen berhasil ditambahkan.',
            'document' => $document,
        ]);
    }

    public function deleteFolderDocument(Request $request, $folder, $document)
    {
        $document = IkmFolderDocument::where('id', $document)
            ->where('folder_id', $folder)
            ->firstOrFail();

        $document->delete();

        return response()->json([
            'success' => true,
            'message' => 'Dokumen berhasil dihapus.',
        ]);
    }

    public function getkabupaten(request $request){
        $id_provinsi = $request->id_provinsi;

        $option = "<option value=''> Kota/Kabupaten </option>";
        $kabupatens = Regency::where('province_id',$id_provinsi)->get();
        foreach($kabupatens as $kabupaten){
            $option.="<option value='$kabupaten->id'> $kabupaten->name </option>";
        }
        echo $option;
    }
    public function getkecamatan(request $request){
        $id_kabupaten = $request->id_kabupaten;

        $option = "<option value=''> Kecamatan </option>";
        $kecamatans = District::where('regency_id',$id_kabupaten)->get();
        foreach($kecamatans as $kecamatan){
            $option.="<option value='$kecamatan->id'> $kecamatan->name </option>";
        }
        echo $option;
    }

    public function getdesa(request $request){
        $id_kecamatan = $request->id_kecamatan;

        $option = "<option value=''> Kelurahan/Desa </option>";
        $desas = Village::where('district_id',$id_kecamatan)->get();
        foreach($desas as $desa){
            $option.= "<option value='$desa->id'> $desa->name </option>";
        }
        echo $option;
    }
    public function getmemberUpdate(request $request){

        $id_project = $request->getId_project;
        $id_IKM = $request->getId_Ikm;
        return view('pages.ikm.update',[
            'title'=>'Update IKM',
            'project'=>Project::Firstwhere('id',$id_project),
            'dataIkm'=>ikm::where('id',$id_IKM)->get(),
            'provinsi'=>Province::all(),

        ]);

    }

    public function folderTree(Request $request)
    {
        $request->validate([
            'project_id' => 'required|exists:projects,id',
            'parent_id' => 'nullable|integer',
            'exclude_folder_id' => 'nullable|integer',
        ]);

        $parentId = $request->filled('parent_id') ? $request->integer('parent_id') : null;
        $excludeId = $request->filled('exclude_folder_id') ? $request->integer('exclude_folder_id') : null;

        if ($parentId && !\App\Models\IkmFolder::where('id', $parentId)->exists()) {
            $parentId = null;
        }

        $query = \App\Models\IkmFolder::where('id_Project', $request->project_id);

        if ($parentId) {
            $query->where('parent_id', $parentId);
        } else {
            $query->whereNull('parent_id');
        }

        $query->orderBy('name');

        if ($excludeId) {
            $query->where('id', '!=', $excludeId);
        }

        $folders = $query->get();

        return response()->json([
            'folders' => $folders,
        ]);
    }

}

