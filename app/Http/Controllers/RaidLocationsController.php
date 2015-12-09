<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use Auth;
use App\Http\Requests;
use App\Http\Requests\RaidLocationRequest;
use App\Http\Controllers\Controller;

class RaidLocationsController extends Controller
{
    /**
     * Instance of controller.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('xauthtoken');
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index($raidId)
    {
        return Auth::user()->raids()->find($raidId)->locations()->latest()->get();
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param   int $raidId
     * @return \Illuminate\Http\Response
     */
    public function store(RaidLocationRequest $request, $raidId)
    {
        $newLocation = Auth::user()->raids()->find($raidId)->locations()->create($request->all());

        if ($newLocation) return ['error' => false, 'location' => $newLocation];

        return ['error' => true];
    }

    /**
     * Display the specified location.
     *
     * @param  int  $raidId
     * @param  int  $locationId
     * @return \Illuminate\Http\Response
     */
    public function show($raidId, $locationId)
    {
        return Auth::user()->raids()->find($raidId)->locations()->find($locationId);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $raidId
     * @param  int  $locationId
     * @return \Illuminate\Http\Response
     */
    public function update(RaidLocationRequest $request, $raidId, $locationId)
    {
        $location = Auth::user()->raids()->find($raidId)->locations()->find($locationId);

        if ($location->update($request->all()))
        {
            return ['error' => false, 'location' => $location];
        }

        return ['error' => true];
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $raidId
     * @param  int  $locationId
     * @return \Illuminate\Http\Response
     */
    public function destroy($raidId, $locationId)
    {
        return ['error' => ! Auth::user()->raids()->find($raidId)->delete()];
    }
}
