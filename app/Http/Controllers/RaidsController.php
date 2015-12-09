<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use Auth;
use App\Raid;
use Carbon\Carbon;
use App\RaidLocation;
use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\Http\Requests\RaidRequest;

class RaidsController extends Controller
{
    /**
     * @var int
     */
    protected $userId;

    /**
     * @var Raid
     */
    protected $raid;

    /**
     * Instance of controller.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('xauthtoken', ['except' => 'check']);
    }

    /**
     * Get raids belongs to logged user.
     *
     * @param Request $request
     * @return json
     */
    public function index(Request $request)
    {
        $raids = Auth::user()
            ->raids()
            ->withLocations();

        if ($request->has('show_date'))
        {
            $date = Carbon::parse($request->input('show_date'));
            $raids->where('start_date', '<=', $date->format('Y-m-d'))
                ->where('end_date', '>=', $date->format('Y-m-d'));
        }

        $raids = $raids->latest()->get();

        return $raids ? $raids : [];
    }

    /**
     * Check a raid location.
     *
     * @param Request $request
     * @return json
     */
    public function check(Request $request)
    {
        $status = 'not_found';
        $legal = false;
        $raid = [];
        $now = Carbon::now()->format('Y-m-d');

        // Check active raids
        $activeRaids = Raid::where('start_date', '<=', $now)
            ->where('end_date', '>=', $now)
            ->get();

        if (! $activeRaids->count()) return ['status' => $status, 'legal' => $legal, 'raid' => $raid];

        // Find nearest long and lat
        $con = mysqli_connect(env("DB_HOST"), env("DB_USERNAME"), env("DB_PASSWORD"), env("DB_DATABASE"));

        $latitude = mysqli_real_escape_string($con, $request->input('latitude'));
        $longitude = mysqli_real_escape_string($con, $request->input('longitude'));

        mysqli_close($con);

        $distance = RaidLocation::getDistanceInMiles();

        $query = "SELECT
                  id, raid_id, longitude, latitude, (
                    3959 * acos (
                      cos ( radians($latitude) )
                      * cos( radians( latitude ) )
                      * cos( radians( longitude ) - radians($longitude) )
                      + sin ( radians($latitude) )
                      * sin( radians( latitude ) )
                    )
                  ) AS distance
                  FROM raid_locations
                  HAVING distance < $distance
                  ";
        $queryResult = collect(\DB::select( \DB::raw($query) ));

        if ($queryResult->count())
        {
            return ['status' => 'found', 'legal' => true, 'raid' => $queryResult->first()];
        }
    }

    /**
     * Store raid.
     *
     * @param RaidRequest $request
     * @return json
     */
    public function store(RaidRequest $request)
    {
        $newLocations = [];
        $docNumber = $request->input('doc_number');
        $locations = $request->input('locations');
        $startDate = Carbon::parse($request->input('start_date'));
        $endDate = Carbon::parse($request->input('end_date'));
        $description = $request->input('description');

        foreach ($locations as $location)
        {
            $newLocations[] = new RaidLocation( (array) $location );
        }

        $input = [
            'doc_number' => $docNumber,
            'start_date' => $startDate->format('Y-m-d'),
            'end_date' => $endDate->format('Y-m-d'),
            'description' => $description,
        ];

        $raid = Auth::user()->raids()->save(new Raid($input));
        $raid->locations()->saveMany($newLocations);

        return $this->show($raid->id);
    }

    /**
     * Update a specified raid.
     *
     * @param Request $request
     * @param int $id
     * @return json
     */
    public function update(request $request, $id)
    {
        $raid = Auth::user()->raids()->find($id);

        if ($raid->update($request->all()))
        {
            return [
                'error' => false,
                'raid' => $raid
            ];
        }

        return ['error' => true];
    }

    /**
     * Get specified raid.
     *
     * @param int $id
     * @return json
     */
    public function show($id)
    {
        $raid = Auth::user()->raids()->withLocations()->find($id);

        return $raid ? $raid : [];
    }

    /**
     * Delete specific raid
     *
     * @param int $id
     * @return json
     */
    public function destroy($id)
    {
        $deleteRaid = Auth::user()->raids()->find($id)->delete();

        return ['error' => !$deleteRaid];
    }
}
