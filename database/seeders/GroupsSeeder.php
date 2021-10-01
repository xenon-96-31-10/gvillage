<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Group;

class GroupsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $managers = new Group();
        $managers->name = 'Управляющие';
        $managers->slug = 'managers';
        $managers->save();

        $owners = new Group();
        $owners->name = 'Собственники';
        $owners->slug = 'owners';
        $owners->save();

        $workers = new Group();
        $workers->name = 'Работники';
        $workers->slug = 'workers';
        $workers->save();
    }
}
