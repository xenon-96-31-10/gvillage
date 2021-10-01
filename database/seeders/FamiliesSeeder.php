<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Family;
use App\Models\Organization;

class FamiliesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
      $bdo = Organization::where('name', 'BdoDesigner')->first();
      $family1 = new Family();
      $family1->name = 'Семья 1';
      $family1->organization()->associate($bdo);
      $family1->save();

      $family2 = new Family();
      $family2->name = 'Семья 2';
      $family2->organization()->associate($bdo);
      $family2->save();

      $family3 = new Family();
      $family3->name = 'Семья 3';
      $family3->organization()->associate($bdo);
      $family3->save();
    }
}
