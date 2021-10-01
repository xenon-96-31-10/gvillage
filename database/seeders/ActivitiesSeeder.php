<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

use App\Models\Activity;

class ActivitiesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
      $jobit = new Activity();
      $jobit->name = 'Работа по IT';
      $jobit->save();

      $jobelectro = new Activity();
      $jobelectro->name = 'Электро Работы';
      $jobelectro->save();

      $jobwc = new Activity();
      $jobwc->name = 'Сантехнические работы';
      $jobwc->save();

      $jobdesign = new Activity();
      $jobdesign->name = 'Проектировка';
      $jobdesign->save();
    }
}
