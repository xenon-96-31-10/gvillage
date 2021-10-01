<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Profile;
use App\Models\Organization;
use App\Models\Car;

class CarsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $bdo = Organization::find(1);
        $mir = Organization::find(2);

        $car = new Car();
        $car->model = 'Subaru';
        $car->number = 'А300АА77';
        $car->type = 'Постоянное';
        $car->body = 'Легковое';
        $car->color = 'Белый';
        $car->save();
        $car->organization()->attach($bdo);

        $owner = Profile::find(2);
        $car = new Car();
        $car->model = 'Toyota';
        $car->number = 'А301АА77';
        $car->type = 'Постоянное';
        $car->body = 'Легковое';
        $car->color = 'Красный';
        $car->owner()->associate($owner)->save();
        $car->organization()->attach($bdo);

        $owner = Profile::find(3);
        $car = new Car();
        $car->model = 'Mercedes';
        $car->number = 'А302АА77';
        $car->type = 'Постоянное';
        $car->body = 'Легковое';
        $car->color = 'Черный';
        $car->owner()->associate($owner)->save();
        $car->organization()->attach($bdo);

        $owner = Profile::find(4);
        $car = new Car();
        $car->model = 'Skoda';
        $car->number = 'А303АА77';
        $car->type = 'Постоянное';
        $car->body = 'Легковое';
        $car->color = 'Белый';
        $car->owner()->associate($owner)->save();
        $car->organization()->attach($bdo);
        $car->organization()->attach($mir);
    }
}
