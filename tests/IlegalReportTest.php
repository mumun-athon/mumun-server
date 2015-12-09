<?php

use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;

use Carbon\Carbon;
use App\IlegalReport;

class IlegalReportTest extends TestCase
{
    /**
     * A test should return ilegal reports.
     *
     * @return void
     */
    public function testGetAllReport()
    {
        // Create a report
        $report = $this->createReport();
        $limit = 5;

        $user = $this->loginAsDummyUser();
        $headers = $this->generateHeaders($user);
        $this->get("/ilegal-reports?limit=5", $headers);
        $content = collect($this->getContentObject());

        $this->seeJsonContains(['id' => "{$report->id}"]);
        $this->assertCount($limit, $this->getContentCollect()->toArray());
    }

    public function testGetReportSpecifiedDate()
    {
        $report = $this->createReport();
        $user = $this->loginAsDummyUser();
        $headers = $this->generateHeaders($user);

        $this->get("/ilegal-reports?show_date=" . Carbon::now()->format('Y-m-d'), $headers);
        $this->seeJsonContains([
            'name' => $report->name,
            'ktp' => $report->ktp,
            'description' => $report->description,
        ]);
    }

    /**
     * A test should get specified report.
     *
     * @return void
     */
    public function testGetSpecifiedReport()
    {
        $user = $this->loginAsDummyUser();
        $headers = $this->generateHeaders($user);

        // Create a report
        $report = $this->createReport();

        $this->get("/ilegal-reports/{$report->id}", $headers);
        $this->seeJsonContains(['id' => "{$report->id}"]);
    }

    /**
     * A test should create a new report.
     *
     * @return void
     */
    public function testCreateANewReport()
    {
        $user = $this->loginAsDummyUser();

        $report = factory(IlegalReport::class)->make();
        $input = $report->toArray();
        $file = [];

        // Set photo
        // $file['photo'] = fopen( public_path('images/ilegalreports/unknown.jpg'), "r" );
        unset($input['photo']);

        $this->post('/ilegal-reports', $input);
        $this->seeJsonContains(['error' => false]);
    }

    /**
     * A test should update the report.
     *
     * @return void
     */
    public function testUpdateReport()
    {
        $user = $this->loginAsDummyUser();
        $headers = $this->generateHeaders($user);

        $report = $this->createReport();
        $dummyReport = factory(IlegalReport::class)->make();
        $input = $dummyReport->toArray();
        $file = [];

        // Set photo
        // $file['photo'] = fopen( public_path('images/ilegalreports/unknown.jpg'), "r" );
        unset($input['photo']);

        $this->put("ilegal-reports/{$report->id}", $input, $headers);
        $this->seeJsonContains(['error' => false]);
        $this->seeJsonContains(['name' => $dummyReport->name]);
        $this->seeJsonContains(['ktp' => $dummyReport->ktp]);
        $this->seeJsonContains(['longitude' => $dummyReport->longitude]);
        $this->seeJsonContains(['latitude' => $dummyReport->latitude]);
    }

    /**
     * A test should delete a scpecified report.
     *
     * @return void
     */
    public function testDeleteSpecifiedReport()
    {
        $user = $this->loginAsDummyUser();
        $headers = $this->generateHeaders($user);

        // Create a report
        $report = $this->createReport();

        $this->delete("/ilegal-reports/{$report->id}", [], $headers);
        $this->seeJsonContains(['error' => false]);
    }

    private function createReport($data = [])
    {
        return factory(IlegalReport::class)->create($data);
    }

}
