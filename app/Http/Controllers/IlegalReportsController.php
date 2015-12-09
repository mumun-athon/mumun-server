<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use Carbon\Carbon;
use App\Http\Requests\IlegalReportRequest;
use App\Http\Controllers\Controller;
use App\IlegalReport;
use App\Events\NewIlegalReport;

class IlegalReportsController extends Controller
{
    /**
     * @var IlegalReport
     */
    protected $ilegalReport;

    /**
     * Instance of controller.
     *
     * @param IlegalReport $ilegalReport
     * @return void
     */
    public function __construct(IlegalReport $ilegalReport)
    {
        $this->middleware('xauthtoken', ['except' => 'store']);
        $this->ilegalReport = $ilegalReport;
    }

    /**
     * Display a listing of the ilegal reports.
     *
     * @param Request $request
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $limit = 10;

        if ($request->has('limit')) $limit = $request->input('limit');

        $reports = $this->ilegalReport->take($limit);

        if ($request->has('show_date'))
        {
            $date = Carbon::parse($request->input('show_date'));
            $reports->where('created_at', '>=', $date->format('Y-m-d H:i:s'))
                ->where('created_at', '<', $date->addDay(1)->format('Y-m-d H:i:s'));
        }

        $reports = $reports->latest()->get();

        return $reports;
    }

    /**
     * Store a newly created ilegal reports in storage.
     *
     * @param  \Illuminate\Http\IlegalReportRequest  $request
     * @return \Illuminate\Http\Response
     */
    public function store(IlegalReportRequest $request)
    {
        $imageName = $request->hasFile('photo') ? date('Ymdhis') . '.' . $request->file('photo')->getClientOriginalExtension() : 'unknown.jpg';
        $imagePath = '/images/ilegalreports/';

        $report = $this->ilegalReport->create([
            'name' => $request->input('name'),
            'ktp' => $request->input('ktp'),
            'longitude' => $request->input('longitude'),
            'description' => $request->input('description'),
            'latitude' => $request->input('latitude'),
            'photo' => url($imagePath . $imageName),
        ]);

        if ($report)
        {
            if ($request->hasFile('photo'))
            {
                $request->file('photo')->move(
                    public_path() . $imagePath,
                    $imageName
                );
            }

            // Trigger the new ilegal report event
            event(new NewIlegalReport($report->toArray()));

            return ['error' => false, 'report' => $report];
        }

        return ['error' => true];
    }

    /**
     * Display the specified ilegal reports.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        return $this->ilegalReport->find($id);
    }

    /**
     * Update the specified ilegal reports in storage.
     *
     * @param  \Illuminate\Http\IlegalReportRequest  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(IlegalReportRequest $request, $id)
    {
        $report = $this->ilegalReport->find($id);

        if ($report->update($request->all()))
        {
            return ['error' => false, 'report' => $report];
        }

        return ['error' => true];
    }

    /**
     * Remove the specified ilegal reports from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        return ['error' => ! $this->ilegalReport->find($id)->delete()];
    }
}
