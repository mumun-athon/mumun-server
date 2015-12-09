<?php

use Illuminate\Database\Seeder;
use App\User;
use App\Raid;

class UserTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $dummyUser = [
            'name' => 'dilbadil',
            'email' => 'dilbadil@localhost.com',
            'password' => bcrypt('qweasd123')
        ];

	    factory(User::class)->create($dummyUser);

	    factory(User::class, 5)->create()->each(function($user) {
		    $user->raids()->save(factory(Raid::class)->make());
	    });
    }
}
