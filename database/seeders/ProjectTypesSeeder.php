<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\ProjectType;

class ProjectTypesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $kv = new ProjectType();
        $kv->name = 'Квартира';
        $kv->slug = 'apartment';
        $kv->save();

        $house = new ProjectType();
        $house->name = 'Дом';
        $house->slug = 'house';
        $house->save();
    }
}
