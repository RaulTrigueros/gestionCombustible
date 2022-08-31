<?php

namespace App\Http\Controllers;

use App\EstatusProyecto;
use Illuminate\Http\Request;

class EstatusProyectoController extends Controller
{
    protected $bitacoraService;

    public function __construct(BitacoraService $bitacoraService)
    {
        $this->bitacoraService = $bitacoraService;
    }
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
     * @param  \App\EstatusProyecto  $estatusProyecto
     * @return \Illuminate\Http\Response
     */
    public function show(EstatusProyecto $estatusProyecto)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\EstatusProyecto  $estatusProyecto
     * @return \Illuminate\Http\Response
     */
    public function edit(EstatusProyecto $estatusProyecto)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\EstatusProyecto  $estatusProyecto
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, EstatusProyecto $estatusProyecto)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\EstatusProyecto  $estatusProyecto
     * @return \Illuminate\Http\Response
     */
    public function destroy(EstatusProyecto $estatusProyecto)
    {
        //
    }
}
