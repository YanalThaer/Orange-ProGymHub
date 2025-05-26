<?php

namespace App\Http\Controllers;

use App\Models\DietProgress;
use App\Http\Requests\StoreDietProgressRequest;
use App\Http\Requests\UpdateDietProgressRequest;

class DietProgressController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreDietProgressRequest $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(DietProgress $dietProgress)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(DietProgress $dietProgress)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateDietProgressRequest $request, DietProgress $dietProgress)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(DietProgress $dietProgress)
    {
        //
    }
}
