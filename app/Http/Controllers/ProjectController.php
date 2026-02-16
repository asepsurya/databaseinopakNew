<?php

namespace App\Http\Controllers;
use App\Models\Ikm;
use GuzzleHttp\Client;
use App\Models\Project;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use ImageSearch\Models\ImageSearch;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Database\Eloquent\Builder;

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

         $data = $query->orderBy('id', 'DESC')->paginate(20)->appends(request()->query());

         return view('pages.project.view',[
             'title'=>'Project',
             'projects'=>$data,
             'searchIkm'=>Ikm::all()
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
        $id = $request->id ?? $request->route('id');

        if (!$id) {
            $request->session()->flash('gagalSimpan', 'ID Project tidak ditemukan');
            return redirect('/project');
        }

        // Get project details before deletion for logging
        $project = Project::find($id);

        if (!$project) {
            $request->session()->flash('gagalSimpan', 'Project tidak ditemukan');
            return redirect('/project');
        }

        $ikmCount = Ikm::where('id_project', $id)->count();

        // Delete related data first
        Ikm::where('id_project', $id)->delete();

        // Delete the project
        Project::destroy($id);

        $request->session()->flash('HapusBerhasil', "Project '$project->NamaProjek' berhasil dihapus bersama $ikmCount data IKM terkait");
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
                $encryptedProject = $project->id;

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
            $ikmData = Ikm::where('nama', 'like', "%{$query}%")
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

    /**
     * Filter projects with live search (returns HTML for DOM update)
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function filterProjects(Request $request)
    {
        try {
            $search = $request->get('search', '');
            $year = $request->get('year', '');
            $ukm_count = $request->get('ukm_count', '');

            $query = Project::withCount(['ikms', 'produkDesigns']);

            // Filter by search (project name)
            if (!empty($search)) {
                $query->where('NamaProjek', 'like', '%' . $search . '%');
            }

            // Filter by year
            if (!empty($year)) {
                $query->whereYear('created_at', $year);
            }

            // Filter by minimum UKM count
            if (!empty($ukm_count)) {
                $query->whereHas('ikms', function (Builder $q) use ($ukm_count) {
                    $q->select('id_Project')
                      ->groupBy('id_Project')
                      ->havingRaw('COUNT(*) >= ?', [$ukm_count]);
                });
            }

            $projects = $query->orderBy('id', 'DESC')->limit(100)->get();

            // Generate HTML for projects
            $html = view('pages.project.partials.project-cards', ['projects' => $projects])->render();

            return response()->json([
                'success' => true,
                'html' => $html,
                'count' => $projects->count()
            ]);

        } catch (\Exception $e) {
            \Log::error('Filter projects error: ' . $e->getMessage());

            return response()->json([
                'success' => false,
                'error' => 'Terjadi kesalahan saat memfilter data'
            ], 500);
        }
    }


    /*
    |--------------------------------------------------------------------------
    | PROXY IMAGE (ANTI HOTLINK)
    |--------------------------------------------------------------------------
    */

    public function proxyImage(Request $request)
    {
        $url = $request->query('url');

        if (!$url) abort(404);

        try {

            $client = new Client([
                'verify' => false,
                'timeout' => 10,
            ]);

            $response = $client->get($url, [
                'headers' => [
                    'User-Agent' => 'Mozilla/5.0'
                ]
            ]);

            return response($response->getBody())
                ->header('Content-Type', $response->getHeaderLine('Content-Type'));

        } catch (\Exception $e) {
            abort(404);
        }
    }
}
