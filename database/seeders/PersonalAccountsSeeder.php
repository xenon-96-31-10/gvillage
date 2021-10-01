<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\PersonalAccount;
use App\Models\Profile;

class PersonalAccountsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
      $profile = Profile::find(2);
      $acc = new PersonalAccount();
      $acc->number = '0176.СО.00000000.00';
      $acc->balance = 0;
      $acc->frozen = 0;
      $acc->profile()->associate($profile)->save();

      $profile = Profile::find(3);
      $acc = new PersonalAccount();
      $acc->number = '0177.СО.00000000.00';
      $acc->balance = 0;
      $acc->frozen = 0;
      $acc->profile()->associate($profile)->save();

      $profile = Profile::find(4);
      $acc = new PersonalAccount();
      $acc->number = '0178.СО.00000000.00';
      $acc->balance = 0;
      $acc->frozen = 0;
      $acc->profile()->associate($profile)->save();

    }
}
