<?php

use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use App\RaidLocation;
use Carbon\Carbon;
use App\User;
use App\Raid;

class RaidTest extends TestCase
{
    use DatabaseTransactions;

    /**
     * A test create raid via api.
     *
     * @return void
     */
    public function testCreateRaid()
    {
        $user = $this->loginAsDummyUser();
        $headers = $this->generateHeaders($user);

        // Test get empty raids
        $this->get('/raids', $headers);
        $this->seeJsonContains([]);

        // Create a new raid via api
        $dummyRaid = factory(Raid::class)->make();
        $locations = factory(RaidLocation::class, 3)->make()->toArray();
        $input = $dummyRaid->toArray();
        $input['locations'] = $locations;

        $this->post('/raids', $input, $headers);
        $this->seeJsonContains($dummyRaid->toArray());
    }

    /**
     *  A test check raid location by longitude and atitude.
     *
     *  @return void
     */
    public function testRaidCheckLocation()
    {
        $user = $this->loginAsDummyUser();

        // Create new raid and raid location in Pondok Programmer
        $newRaid = $this->createRaid($user);
        $dummyLocation = factory(RaidLocation::class)->make([
            'latitude' => -7.845046,
            'longitude' => 110.402123
        ]);
        $newRaid->locations()->save($dummyLocation);

        // Coordinates satuempat.com (near of Pondok Programmer)
        $latitude = -7.845072;
        $longitude = 110.402176;

        // Check location
        $this->get("/raids/check?longitude={$longitude}&latitude={$latitude}");
        $this->seeJsonContains(['status' => 'found', 'legal' => true]);
    }


    /**
     * A test to read a specified raid.
     *
     * @return void
     */
    public function testReadRaid()
    {
        $user = $this->loginAsDummyUser();
        $headers = $this->generateHeaders($user);

        $newRaid = $this->createRaid($user);

        // Get the raid via api
        $this->get("/raids/{$newRaid->id}", $headers);
        $this->seeJsonContains(['id' => "$newRaid->id"]);
        $this->seeJsonContains(['doc_number' => $newRaid->doc_number]);
        $this->seeJsonContains(['start_date' => $newRaid->start_date]);
        $this->seeJsonContains(['end_date' => $newRaid->end_date]);
    }

    protected function createRaid($user)
    {
        $dummyRaid = factory(Raid::class)->make();
        $newRaid = $user->raids()->save($dummyRaid);

        return $newRaid;
    }

    /**
     * A test to update a specified raid.
     *
     * @return void
     */
    public function testUpdateRaid()
    {
        $user = $this->loginAsDummyUser();
        $headers = $this->generateHeaders($user);
        $newRaid = $this->createRaid($user);
        $input = [
            'doc_number' => '123456',
            'start_date' => '2015-12-10',
            'end_date' => '2015-12-15',
            'description' => 'This is a description',
        ];

        // Test delete via api
        $this->put("/raids/{$newRaid->id}", $input, $headers);
        $content = $this->getContentObject();

        $this->seeJsonContains(['error' => false]);
        $this->assertEquals($input['doc_number'], $content->raid->doc_number);
        $this->assertEquals($input['start_date'], $content->raid->start_date);
        $this->assertEquals($input['end_date'], $content->raid->end_date);
        $this->assertEquals($input['description'], $content->raid->description);
    }

    /**
     * A test to delete raid via api.
     *
     * @return void
     */
    public function testDeleteRaid()
    {
        $user = $this->loginAsDummyUser();
        $headers = $this->generateHeaders($user);

        // Make a new raid
        $dummyRaid = factory(Raid::class)->make();
        $newRaid = $user->raids()->save($dummyRaid);

        // Test delete via api
        $this->delete("/raids/{$newRaid->id}", [], $headers);
        $this->seeJsonContains(['error' => false]);
    }
}
