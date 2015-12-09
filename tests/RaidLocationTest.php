<?php

use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use App\Raid;
use App\RaidLocation;

class RaidLocationTest extends TestCase
{
    use DatabaseTransactions;

    /**
     * A test to get locations by specified raid.
     *
     * @return void
     */
    public function testGetLocationsBySpecifiedRaid()
    {
        $user = $this->loginAsDummyUser();
        $headers = $this->generateHeaders($user);

        $newRaid = $user->raids()->save(factory(Raid::class)->make());
        $newRaidLocations = $newRaid->locations()->saveMany( factory(RaidLocation::class, 3)->make() );

        // Get locations via api
        $this->get("/raids/{$newRaid->id}/locations", $headers);
        $content = collect($this->getContentObject());

        $this->assertCount($newRaidLocations->count(), $content->toArray());
    }

    public function testGetLocationByLocationId()
    {
        $user = $this->loginAsDummyUser();
        $headers = $this->generateHeaders($user);

        $newRaid = $user->raids()->save(factory(Raid::class)->make());
        $newRaidLocation = $newRaid->locations()->save( factory(RaidLocation::class)->make() );

        // Get location via api
        $this->get("/raids/{$newRaid->id}/locations/{$newRaidLocation->id}", $headers);
        $this->seeJsonContains(['id' => "$newRaidLocation->id"]);
        $this->seeJsonContains(['raid_id' => "$newRaid->id"]);
    }

    public function testCreateNewLocationOnSpecifiedRaid()
    {
        $user = $this->loginAsDummyUser();
        $headers = $this->generateHeaders($user);
        $newRaid = $user->raids()->save(factory(Raid::class)->make());

        // Create via api
        $input = factory(RaidLocation::class)->make()->toArray();
        $this->post("/raids/{$newRaid->id}/locations", $input, $headers);
        $this->seeJsonContains(['error' => false]);
    }

    public function testUpdateLocationByRaid()
    {
        $user = $this->loginAsDummyUser();
        $headers = $this->generateHeaders($user);
        $newRaid = $user->raids()->save(factory(Raid::class)->make());
        $newRaidLocation = $newRaid->locations()->save( factory(RaidLocation::class)->make() );

        // Update via api
        $input = factory(RaidLocation::class)->make([
            'longitude' => 1234,
            'latitude' => 4321,
        ])->toArray();

        $this->put("/raids/{$newRaid->id}/locations/{$newRaidLocation->id}", $input, $headers);
        $this->seeJsonContains(['error' => false]);
    }

    public function testDeleteRaid()
    {
        $user = $this->loginAsDummyUser();
        $headers = $this->generateHeaders($user);

        $newRaid = $user->raids()->save(factory(Raid::class)->make());
        $newRaidLocation = $newRaid->locations()->save( factory(RaidLocation::class)->make() );

        // Delete via api
        $this->delete("/raids/{$newRaid->id}/locations/{$newRaidLocation->id}", [], $headers);
        $this->seeJson(['error' => false]);
    }
}
