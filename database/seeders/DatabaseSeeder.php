<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        // \App\Models\User::factory(10)->create();
        $this->call(OrganizationsSeeder::class);
        $this->call(ActivitiesSeeder::class);
        $this->call(PermissionSeeder::class);
        $this->call(RoleSeeder::class);
        $this->call(FamiliesSeeder::class);
        $this->call(GroupsSeeder::class);
        $this->call(PassRatePlanSeeder::class);
        $this->call(ProjectGroupsSeeder::class);
        $this->call(ProjectTypesSeeder::class);
        $this->call(ProjectStatusesSeeder::class);
        $this->call(UserSeeder::class);
        $this->call(ProfilesSeeder::class);
        $this->call(GuardPostsSeeder::class);
        $this->call(GuardPostEntersSeeder::class);
        $this->call(PersonalAccountsSeeder::class);
        $this->call(ProjectsSeeder::class);
        $this->call(CarsSeeder::class);
        $this->call(PassRatesSeeder::class);
        $this->call(MechanizmsSeeder::class);
        $this->call(WarehousesSeeder::class);
        $this->call(EquipmentSeeder::class);

    }
}
