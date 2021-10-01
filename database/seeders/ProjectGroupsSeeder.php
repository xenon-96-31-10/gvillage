<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\ProjectGroup;
use App\Models\Organization;
use App\Models\PassRatePlan;

class ProjectGroupsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $bdo = Organization::where('name', 'BdoDesigner')->first();
        $plan = PassRatePlan::find(1);
        $group = new ProjectGroup();
        $group->name = 'ЖК Огонек';
        $group->organization()->associate($bdo);        
        $group->pass_rate_plan()->associate($plan)->save();

        $group = new ProjectGroup();
        $group->name = 'д. Солослово';
        $group->organization()->associate($bdo);
        $group->pass_rate_plan()->associate($plan)->save();
    }
}
