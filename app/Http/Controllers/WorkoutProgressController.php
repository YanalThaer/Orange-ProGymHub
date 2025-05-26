<?php

namespace App\Http\Controllers;

use App\Models\WorkoutProgress;
use App\Http\Requests\StoreWorkoutProgressRequest;
use App\Http\Requests\UpdateWorkoutProgressRequest;

class WorkoutProgressController extends Controller
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
    public function store(StoreWorkoutProgressRequest $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(WorkoutProgress $workoutProgress)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(WorkoutProgress $workoutProgress)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateWorkoutProgressRequest $request, WorkoutProgress $workoutProgress)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(WorkoutProgress $workoutProgress)
    {
        //
    }
}
