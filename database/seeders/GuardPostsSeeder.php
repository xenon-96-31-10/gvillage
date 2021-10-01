<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\GuardPost;
use App\Models\ProjectGroup;
use App\Models\Organization;
use App\Models\Profile;
use App\Models\User;

class GuardPostsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {   $bdo = Organization::where('name', 'BdoDesigner')->first();
        $chief1 = Profile::find(8);
        $chief2 = Profile::find(9);
        $guards = User::find([5,9]);

        $project_group = ProjectGroup::find(1);
        $post = new GuardPost();
        $post->name = 'Пост охраны ЖК Огонек';
        $post->phone = '375169844';
        $post->organization()->associate($bdo);
        $post->chief()->associate($chief1);
        $post->project_group()->associate($project_group)->save();
        $post->guards()->attach($chief1);


        $project_group = ProjectGroup::find(2);
        $post = new GuardPost();
        $post->name = 'Пост охраны д. Солослово';
        $post->phone = '375169842';
        $post->organization()->associate($bdo);
        $post->chief()->associate($chief2);
        $post->project_group()->associate($project_group)->save();
        $post->guards()->attach($guards);
    }
}
