
text/x-generic CotsController.php ( PHP script, ASCII text )
<?php

namespace App\Http\Controllers;

use App\Models\Cots;
use App\Http\Requests\StorecotsRequest;
use App\Http\Requests\UpdatecotsRequest;

class CotsController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
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
     * @param  \App\Http\Requests\StorecotsRequest  $request
     * @return \Illuminate\Http\Response
     */
    public function store(StorecotsRequest $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\cots  $cots
     * @return \Illuminate\Http\Response
     */
    public function show(Cots $cots)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\cots  $cots
     * @return \Illuminate\Http\Response
     */
    public function edit(Cots $cots)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \App\Http\Requests\UpdatecotsRequest  $request
     * @param  \App\Models\cots  $cots
     * @return \Illuminate\Http\Response
     */
    public function update(UpdatecotsRequest $request, Cots $cots)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\cots  $cots
     * @return \Illuminate\Http\Response
     */
    public function destroy(Cots $cots)
    {
        //
    }
}
