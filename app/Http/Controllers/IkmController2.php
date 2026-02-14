<?php

namespace App\Http\Controllers;
use App\Model;
use App\Models\Ikm;
use App\Models\Project;
use App\Models\Province;
use App\Models\Regency;
use App\Models\District;
use App\Models\Village;
use Illuminate\Http\Request;
use App\Http\Requests\StoreIkmRequest;
use App\Http\Requests\UpdateIkmRequest;

class IkmController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function view(project $project)
    {
        return view('pages.ikm.show',[
            'title'=>'Form Brainstorming',
            'project'=>$project,
            'dataIkm'=>Ikm::where('id_Project',$project->id)->get(),
            'provinsi'=>Province::all(),
            'searchIkm'=>Ikm::all()
        ]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \App\Http\Requests\StoreIkmRequest  $request
     * @return \Illuminate\Http\Response
     */
    public function store(StoreIkmRequest $request)
    {

    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Ikm  $ikm
     * @return \Illuminate\Http\Response
     */
    public function show(Ikm $ikm)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Ikm  $ikm
     * @return \Illuminate\Http\Response
     */
    public function edit(Ikm $ikm)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \App\Http\Requests\UpdateIkmRequest  $request
     * @param  \App\Models\Ikm  $ikm
     * @return \Illuminate\Http\Response
     */
    public function update(UpdateIkmRequest $request, Ikm $ikm)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Ikm  $ikm
     * @return \Illuminate\Http\Response
     */
    public function destroy(Ikm $ikm)
    {
        //
    }





}
