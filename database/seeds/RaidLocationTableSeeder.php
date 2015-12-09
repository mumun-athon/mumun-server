<?php

use Illuminate\Database\Seeder;
use App\Raid;
use App\RaidLocation;

class RaidLocationTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
      Raid::all()->each(function($raid) {
	$raid->locations()->save(factory(RaidLocation::class)->make());
      });
    }
}
