<?php

namespace App\Http\Controllers;

use App\Models\Exemplo;
use Illuminate\Http\Request;

class ExemploController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return view('exemplo.index');
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
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Exemplo  $exemplo
     * @return \Illuminate\Http\Response
     */
    public function show(Exemplo $exemplo)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Exemplo  $exemplo
     * @return \Illuminate\Http\Response
     */
    public function edit(Exemplo $exemplo)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Exemplo  $exemplo
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Exemplo $exemplo)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Exemplo  $exemplo
     * @return \Illuminate\Http\Response
     */
    public function destroy(Exemplo $exemplo)
    {
        //
    }
}
