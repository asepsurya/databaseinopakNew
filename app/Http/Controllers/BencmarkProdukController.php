<?php

namespace App\Http\Controllers;

use App\Models\BencmarkProduk;
use App\Http\Requests\StoreBencmarkProdukRequest;
use App\Http\Requests\UpdateBencmarkProdukRequest;

class BencmarkProdukController extends Controller
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
     * @param  \App\Http\Requests\StoreBencmarkProdukRequest  $request
     * @return \Illuminate\Http\Response
     */
    public function store(StoreBencmarkProdukRequest $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\BencmarkProduk  $bencmarkProduk
     * @return \Illuminate\Http\Response
     */
    public function show(BencmarkProduk $bencmarkProduk)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\BencmarkProduk  $bencmarkProduk
     * @return \Illuminate\Http\Response
     */
    public function edit(BencmarkProduk $bencmarkProduk)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \App\Http\Requests\UpdateBencmarkProdukRequest  $request
     * @param  \App\Models\BencmarkProduk  $bencmarkProduk
     * @return \Illuminate\Http\Response
     */
    public function update(UpdateBencmarkProdukRequest $request, BencmarkProduk $bencmarkProduk)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\BencmarkProduk  $bencmarkProduk
     * @return \Illuminate\Http\Response
     */
    public function destroy(BencmarkProduk $bencmarkProduk)
    {
        //
    }
}
