<?php

use Illuminate\Database\Seeder;
use App\IlegalReport;

class IlegalReportTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        factory(IlegalReport::class, 50)->create();
    }
}
