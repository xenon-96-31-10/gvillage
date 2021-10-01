<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Project;
use App\Models\ProjectGroup;
use App\Models\ProjectType;
use App\Models\ProjectStatus;
use App\Models\Organization;
use App\Models\Profile;

class ProjectsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {


      $group = ProjectGroup::find(1);
      $type1 = ProjectType::find(1);
      $type2 = ProjectType::find(2);
      $status = ProjectStatus::find(1);
      $bdo = Organization::find(1);


      $project = new Project();
      $project->name = 'Проект загородного дома';
      $project->description = 'Без описания';
      $project->address = 'г Москва, пр-кт Ленинский, д 7, кв 12';
      $project->totalarea = 300.5;
      $project->livingarea = 265;
      $profile = Profile::find(2);
      $project->owner()->associate($profile);
      $project->personal_account()->associate($profile->account);
      $project->project_status()->associate($status);
      $project->organization()->associate($bdo);
      $project->project_group()->associate($group);
      $project->project_type()->associate($type2)->save();
      $project->profiles()->attach($profile);

      $project = new Project();
      $project->name = 'Ремонт 4к квартиры';
      $project->description = 'Без описания';
      $project->address = 'г Москва, ул Профсоюзная, д 40, кв 12';
      $project->totalarea = 170.5;
      $project->livingarea = 150;
      $project->project_status()->associate($status);
      $project->owner()->associate($profile);
      $project->personal_account()->associate($profile->account);
      $project->organization()->associate($bdo);
      $project->project_type()->associate($type1)->save();
      $project->profiles()->attach($profile);

      $group = ProjectGroup::find(2);
      $profiles = Profile::find([3,6]);
      $project = new Project();
      $project->name = 'Проект 2x этажного дома';
      $project->description = 'Без описания';
      $project->address = 'Московская область, д Солослово, КИЗ Горки-8 тер, д 105';
      $project->totalarea = 200.5;
      $project->livingarea = 165;
      $project->project_status()->associate($status);
      $profile = Profile::find(3);
      $project->owner()->associate($profile);
      $project->personal_account()->associate($profile->account);
      $project->project_status()->associate($status);
      $project->project_group()->associate($group);
      $project->organization()->associate($bdo);
      $project->project_type()->associate($type2)->save();
      $project->profiles()->attach($profiles);

      $profile = Profile::find([4,6]);
      $project = new Project();
      $project->name = 'Проект 1 этажного дома';
      $project->description = 'Без описания';
      $project->address = 'Московская область, д Солослово, КИЗ Горки-8 тер, д 106';
      $project->totalarea = 200.5;
      $project->livingarea = 165;
      $profile = Profile::find(4);
      $project->owner()->associate($profile);
      $project->personal_account()->associate($profile->account);
      $project->project_status()->associate($status);
      $project->project_group()->associate($group);
      $project->organization()->associate($bdo);
      $project->project_type()->associate($type2)->save();
      $project->profiles()->attach($profiles);
    }
}
