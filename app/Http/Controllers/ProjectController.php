<?php

namespace App\Http\Controllers;
use App\Models\Project;
use App\Models\ikm;
use Illuminate\Http\Request;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\Crypt;

class ProjectController extends Controller
{
     public function index(){
         $query = Project::withCount(['ikms', 'produkDesigns']);

         // Filter by search (project name)
         if(request('search')){
             $query->where('NamaProjek','like','%' . request('search') .'%');
         }

         // Filter by year
         if(request('year')){
             $query->whereYear('created_at', request('year'));
         }

         // Filter by status (placeholder - add status column to projects table if needed)
         if(request('status')){
             // Add status filter when status column exists
             // $query->where('status', request('status'));
         }

         // Filter by minimum UKM count
         if(request('ukm_count')){
             $query->whereHas('ikms', function (Builder $q) {
                 $q->select('id_Project')
                   ->groupBy('id_Project')
                   ->havingRaw('COUNT(*) >= ?', [request('ukm_count')]);
             });
         }

         $data = $query->orderBy('id', 'DESC')->paginate(8)->appends(request()->query());

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

    /**
     * Search projects and IKM for autocomplete dropdown
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function searchProjects(Request $request)
    {
        try {
            $query = $request->get('q', '');

            // Validate query - return empty array if null, empty, or too short
            if (empty($query) || strlen($query) < 1) {
                return response()->json([], 200);
            }

            // Sanitize query to prevent SQL injection (basic protection)
            $query = htmlspecialchars(strip_tags(trim($query)), ENT_QUOTES, 'UTF-8');

            // Validate query length
            if (strlen($query) > 100) {
                return response()->json([
                    'error' => 'Query terlalu panjang'
                ], 400);
            }

            $results = [];

            // Search projects by NamaProjek
            $projects = Project::where('NamaProjek', 'like', "%{$query}%")
                ->select('id', 'NamaProjek as nama_project')
                ->limit(10)
                ->get();

            foreach ($projects as $project) {
                // Encrypt the project ID for secure routing
                $encryptedProject = Crypt::encryptString($project->id);

                $results[] = [
                    'id' => $project->id,
                    'nama_project' => $project->nama_project,
                    'nama_ikm' => null,
                    'type' => 'project',
                    'route' => route('project.ikm', ['id' => $encryptedProject]),
                    'encrypted_project' => $encryptedProject
                ];
            }

            // Search IKM by nama
            $ikmData = ikm::where('nama', 'like', "%{$query}%")
                ->select('id', 'nama', 'namaUsaha', 'id_project')
                ->limit(10)
                ->get();

            foreach ($ikmData as $ikm) {
                // Get project name for this IKM
                $projectName = null;
                if ($ikm->id_project) {
                    $project = Project::find($ikm->id_project);
                    $projectName = $project ? $project->NamaProjek : null;
                }

                // Encrypt the IDs for secure routing
                $encryptedIkm = Crypt::encryptString($ikm->id);
                $encryptedProject = Crypt::encryptString($ikm->id_project ?? 0);

                $results[] = [
                    'id' => $ikm->id,
                    'nama_project' => $projectName,
                    'nama_ikm' => $ikm->nama,
                    'type' => 'ikm',
                    'route' => route('detail.encrypted', ['encrypted_id' => $encryptedIkm, 'encrypted_project' => $encryptedProject]),
                    'encrypted_ikm' => $encryptedIkm,
                    'encrypted_project' => $encryptedProject
                ];
            }

            // Limit total results to 10
            $results = array_slice($results, 0, 10);

            return response()->json($results, 200);

        } catch (\Exception $e) {
            // Log error
            \Log::error('Search error: ' . $e->getMessage());

            return response()->json([
                'error' => 'Terjadi kesalahan saat melakukan pencarian'
            ], 500);
        }
    }
}
