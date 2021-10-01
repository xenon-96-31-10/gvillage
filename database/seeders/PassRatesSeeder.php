<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\PassRate;
use App\Models\PassRatePlan;


class PassRatesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $plan = PassRatePlan::find(1);


        $rate = new PassRate();
        $rate->pass = "temporary";
        $rate->visitor_type = "cars";
        $rate->visitor = "Легковое";
        $rate->role = "По умолчанию";
        $rate->time = "1 въезд";
        $rate->price = 100;
        $rate->description = "Для легкового авто по умолчанию";
        $rate->pass_rate_plan()->associate($plan)->save();

        $rate = new PassRate();
        $rate->pass = "temporary";
        $rate->visitor_type = "cars";
        $rate->visitor = "Легковое";
        $rate->role = "Арендатор";
        $rate->time = "1 въезд";
        $rate->price = 120;
        $rate->description = "Для легкового авто арендатора";
        $rate->pass_rate_plan()->associate($plan)->save();

        $rate = new PassRate();
        $rate->pass = "temporary";
        $rate->visitor_type = "cars";
        $rate->visitor = "Легковое";
        $rate->role = "Собственник";
        $rate->time = "1 въезд";
        $rate->price = 0;
        $rate->description = "Для легкового авто собтсвенника";
        $rate->pass_rate_plan()->associate($plan)->save();

        $rate = new PassRate();
        $rate->pass = "temporary";
        $rate->visitor_type = "cars";
        $rate->visitor = "Грузовое";
        $rate->role = "По умолчанию";
        $rate->time = "1 въезд";
        $rate->price = 150;
        $rate->description = "Для грузового авто по умолчанию";
        $rate->pass_rate_plan()->associate($plan)->save();

        $rate = new PassRate();
        $rate->pass = "temporary";
        $rate->visitor_type = "mechanizms";
        $rate->visitor = "Спецтехника до 1,5 т";
        $rate->time = "1 въезд";
        $rate->role = "По умолчанию";
        $rate->description = "Для спецтехники до 1,5 т";
        $rate->price = 100;
        $rate->pass_rate_plan()->associate($plan)->save();

        $rate = new PassRate();
        $rate->pass = "temporary";
        $rate->visitor_type = "mechanizms";
        $rate->visitor = "Спецтехника от 1,5 т до 3 т";
        $rate->time = "1 въезд";
        $rate->role = "По умолчанию";
        $rate->description = "Для спецтехники от 1,5 т до 3 т";
        $rate->price = 200;
        $rate->pass_rate_plan()->associate($plan)->save();

        $rate = new PassRate();
        $rate->pass = "temporary";
        $rate->visitor_type = "mechanizms";
        $rate->visitor = "Спецтехника от 3 т до 7 т";
        $rate->time = "1 въезд";
        $rate->role = "По умолчанию";
        $rate->description = "Для спецтехники от 3 т до 7 т";
        $rate->price = 300;
        $rate->pass_rate_plan()->associate($plan)->save();

        $rate = new PassRate();
        $rate->pass = "temporary";
        $rate->visitor_type = "mechanizms";
        $rate->visitor = "Спецтехника свыше 7 т";
        $rate->time = "1 въезд";
        $rate->role = "По умолчанию";
        $rate->description = "Для спецтехники свыше 7 т";
        $rate->price = 900;
        $rate->pass_rate_plan()->associate($plan)->save();

        $rate = new PassRate();
        $rate->pass = "temporary";
        $rate->visitor_type = "mechanizms";
        $rate->visitor = "Грузовой";
        $rate->time = "1 въезд";
        $rate->role = "По умолчанию";
        $rate->description = "Для гузовой техники";
        $rate->price = 500;
        $rate->pass_rate_plan()->associate($plan)->save();

        $rate = new PassRate();
        $rate->pass = "temporary";
        $rate->visitor_type = "mechanizms";
        $rate->visitor = "Длинномер/Еврофура";
        $rate->time = "1 въезд";
        $rate->role = "По умолчанию";
        $rate->description = "Для длинномера/еврофуры";
        $rate->price = 1200;
        $rate->pass_rate_plan()->associate($plan)->save();

        $rate = new PassRate();
        $rate->pass = "temporary";
        $rate->visitor_type = "profiles";
        $rate->visitor = "Человек";
        $rate->time = "1 въезд";
        $rate->role = "По умолчанию";
        $rate->description = "Для всех людей по умолчанию";
        $rate->price = 50;
        $rate->pass_rate_plan()->associate($plan)->save();

        $rate = new PassRate();
        $rate->pass = "temporary";
        $rate->visitor_type = "profiles";
        $rate->visitor = "Человек";
        $rate->time = "1 въезд";
        $rate->role = "Собственник";
        $rate->description = "Для собственника";
        $rate->price = 0;
        $rate->pass_rate_plan()->associate($plan)->save();

        $rate = new PassRate();
        $rate->pass = "permanent";
        $rate->visitor_type = "cars";
        $rate->visitor = "Легковое";
        $rate->role = "По умолчанию";
        $rate->time = "1 месяц";
        $rate->price = 200;
        $rate->description = "Для легкового авто по умолчанию";
        $rate->pass_rate_plan()->associate($plan)->save();

        $rate = new PassRate();
        $rate->pass = "permanent";
        $rate->visitor_type = "cars";
        $rate->visitor = "Легковое";
        $rate->role = "По умолчанию";
        $rate->time = "полгода";
        $rate->price = 1100;
        $rate->description = "Для легкового авто по умолчанию";
        $rate->pass_rate_plan()->associate($plan)->save();

        $rate = new PassRate();
        $rate->pass = "permanent";
        $rate->visitor_type = "cars";
        $rate->visitor = "Легковое";
        $rate->time = "полгода";
        $rate->role = "Арендатор";
        $rate->description = "Для авто арендатора";
        $rate->price = 2770;
        $rate->pass_rate_plan()->associate($plan)->save();

        $rate = new PassRate();
        $rate->pass = "permanent";
        $rate->visitor_type = "cars";
        $rate->visitor = "Грузовое";
        $rate->role = "По умолчанию";
        $rate->time = "1 месяц";
        $rate->price = 300;
        $rate->description = "Для грузового авто по умолчанию";
        $rate->pass_rate_plan()->associate($plan)->save();

        $rate = new PassRate();
        $rate->pass = "permanent";
        $rate->visitor_type = "cars";
        $rate->visitor = "Грузовое";
        $rate->role = "По умолчанию";
        $rate->time = "полгода";
        $rate->price = 1700;
        $rate->description = "Для грузового авто по умолчанию";
        $rate->pass_rate_plan()->associate($plan)->save();

        $rate = new PassRate();
        $rate->pass = "permanent";
        $rate->visitor_type = "profiles";
        $rate->visitor = "Человек";
        $rate->role = "По умолчанию";
        $rate->time = "1 месяц";
        $rate->price = 50;
        $rate->description = "Для человека по умолчанию";
        $rate->pass_rate_plan()->associate($plan)->save();

        $rate = new PassRate();
        $rate->pass = "permanent";
        $rate->visitor_type = "profiles";
        $rate->visitor = "Человек";
        $rate->role = "По умолчанию";
        $rate->time = "полгода";
        $rate->price = 250;
        $rate->description = "Для человека по умолчанию";
        $rate->pass_rate_plan()->associate($plan)->save();

        $rate = new PassRate();
        $rate->pass = "permanent";
        $rate->visitor_type = "profiles";
        $rate->visitor = "Арендатор";
        $rate->time = "полгода";
        $rate->role = "Арендатор";
        $rate->description = "Для арендатора";
        $rate->price = 1770;
        $rate->pass_rate_plan()->associate($plan)->save();
    }
}
