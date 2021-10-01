<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\PassRatePlan;
use App\Models\Organization;

class PassRatePlanSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $bdo = Organization::where('name', 'BdoDesigner')->first();
        $plan = new PassRatePlan();
        $plan->name = "Тарифный план д. Солослово";
        $plan->default = 1;
        $plan->organization()->associate($bdo)->save();
    }
}
