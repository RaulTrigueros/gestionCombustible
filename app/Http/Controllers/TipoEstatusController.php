<?php

namespace App\Http\Controllers;

use App\TipoEstatus;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class TipoEstatusController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        try {
            $tipoEstatus = DB::table('tipoestatus')->get();
            return $tipoEstatus;
        } catch (\Throwable $th) {
            return $th;
        }
        
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
     * @param  \App\TipoEstatus  $tipoEstatus
     * @return \Illuminate\Http\Response
     */
    public function show(TipoEstatus $tipoEstatus)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\TipoEstatus  $tipoEstatus
     * @return \Illuminate\Http\Response
     */
    public function edit(TipoEstatus $tipoEstatus)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\TipoEstatus  $tipoEstatus
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, TipoEstatus $tipoEstatus)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\TipoEstatus  $tipoEstatus
     * @return \Illuminate\Http\Response
     */
    public function destroy(TipoEstatus $tipoEstatus)
    {
        //
    }
}
