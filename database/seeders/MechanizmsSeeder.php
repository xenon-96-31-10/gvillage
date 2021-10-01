<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Mechanizm;
use App\Models\Organization;

class MechanizmsSeeder extends Seeder
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

        $mechanizm = new Mechanizm();
        $mechanizm->model = 'Toyota';
        $mechanizm->number = 'А100АА77';
        $mechanizm->color = 'Белый';
        $mechanizm->type = 'Спецтехника до 1,5 т';
        $mechanizm->name = 'Насос';
        $mechanizm->save();
        $mechanizm->organization()->attach($bdo);

        $mechanizm = new Mechanizm();
        $mechanizm->model = 'Toyota';
        $mechanizm->number = 'А101АА77';
        $mechanizm->color = 'Белый';
        $mechanizm->type = 'Спецтехника от 1,5 т до 3 т';
        $mechanizm->name = 'Манипулятор';
        $mechanizm->save();
        $mechanizm->organization()->attach($bdo);
        $mechanizm->organization()->attach($mir);

        $mechanizm = new Mechanizm();
        $mechanizm->model = 'Toyota';
        $mechanizm->number = 'А102АА77';
        $mechanizm->color = 'Белый';
        $mechanizm->type = 'Спецтехника от 3 т до 7 т';
        $mechanizm->name = 'Миксер';
        $mechanizm->save();
        $mechanizm->organization()->attach($bdo);

        $mechanizm = new Mechanizm();
        $mechanizm->model = 'Toyota';
        $mechanizm->number = 'А103АА77';
        $mechanizm->color = 'Белый';
        $mechanizm->type = 'Спецтехника свыше 7 т';
        $mechanizm->name = 'Кран';
        $mechanizm->save();
        $mechanizm->organization()->attach($bdo);

        $mechanizm = new Mechanizm();
        $mechanizm->model = 'Toyota';
        $mechanizm->number = 'А104АА77';
        $mechanizm->color = 'Белый';
        $mechanizm->type = 'Грузовой';
        $mechanizm->name = 'Грузовик снабжения';
        $mechanizm->save();
        $mechanizm->organization()->attach($bdo);
        $mechanizm->organization()->attach($mir);

        $mechanizm = new Mechanizm();
        $mechanizm->model = 'Toyota';
        $mechanizm->number = 'А105АА77';
        $mechanizm->color = 'Белый';
        $mechanizm->type = 'Длинномер/Еврофура';
        $mechanizm->name = 'Длинномер';
        $mechanizm->save();
        $mechanizm->organization()->attach($bdo);
        $mechanizm->organization()->attach($mir);
    }
}
