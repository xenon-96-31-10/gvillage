<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\ProjectStatus;

class ProjectStatusesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $status = new ProjectStatus();
        $status->name = 'Новый';
        $status->slug = 'new';
        $status->save();

        $status = new ProjectStatus();
        $status->name = 'В работе';
        $status->slug = 'work';
        $status->save();

        $status = new ProjectStatus();
        $status->name = 'Завершен';
        $status->slug = 'finished';
        $status->save();
    }
}
