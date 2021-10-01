<?php

namespace Database\Seeders;

use App\Models\GuardPostEnter;
use App\Models\GuardPost;

use Illuminate\Database\Seeder;

class GuardPostEntersSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
      $post = GuardPost::find(1);

      $enter1 = new GuardPostEnter();
      $enter1->name = 'КПП 1';
      $enter1->status = 'Активный';
      $enter1->phone = '375169842';
      $enter1->guard_post()->associate($post)->save();

      $enter2 = new GuardPostEnter();
      $enter2->name = 'КПП 2';
      $enter2->phone = '375169842';
      $enter2->status = 'Активный';
      $enter2->guard_post()->associate($post)->save();

      $enter3 = new GuardPostEnter();
      $enter3->name = 'КПП 3';
      $enter3->phone = '375169842';
      $enter3->status = 'Закрыт';
      $enter3->guard_post()->associate($post)->save();

      $post = GuardPost::find(2);

      $enter1 = new GuardPostEnter();
      $enter1->name = 'КПП 44';
      $enter1->status = 'Активный';
      $enter1->phone = '375169842';
      $enter1->guard_post()->associate($post)->save();

      $enter2 = new GuardPostEnter();
      $enter2->name = 'КПП 45';
      $enter2->status = 'Активный';
      $enter2->phone = '375169842';
      $enter2->guard_post()->associate($post)->save();

      $enter3 = new GuardPostEnter();
      $enter3->name = 'КПП';
      $enter3->status = 'Закрыт';
      $enter3->phone = '375169842';
      $enter3->guard_post()->associate($post)->save();
    }
}
