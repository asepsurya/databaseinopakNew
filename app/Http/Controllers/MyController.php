<?php

namespace App\Http\Controllers;
use App\Models\Province;
use App\Models\Regency;
use App\Models\District;
use App\Models\Village;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use App\Models\Ikm;

class MyController extends Controller
{
    // Ollama Proxy - Forward requests to myollama.scrollwebid.com
    // Maps /api/generate to /api/tags
    public function ollamaProxy(Request $request, $endpoint)
    {
        $targetUrl = 'https://myollama.scrollwebid.com';

        // Map /api/generate to /api/tags
        $mappedEndpoint = str_replace('api/generate', 'api/tags', $endpoint);

        $fullTargetPath = $targetUrl . '/' . $mappedEndpoint;

        try {
            $client = Http::withOptions([
                'verify' => false,
                'timeout' => 60,
            ]);

            $method = $request->method();
            $headers = $request->header();
            $body = $request->getContent();

            unset($headers['host']);
            unset($headers['Host']);

            $response = $client->withHeaders($headers)->$method($fullTargetPath, $body ? json_decode($body, true) : []);

            return response()->json([
                'data' => $response->json(),
                'status' => $response->status(),
            ], $response->status());

        } catch (\Exception $e) {
            Log::error('Ollama Proxy Error: ' . $e->getMessage());

            return response()->json([
                'error' => 'Failed to connect to Ollama server',
                'message' => $e->getMessage(),
            ], 500);
        }
    }

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
