<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Role;
use App\Models\User;
use App\Models\Profile;
use App\Models\Car;
use App\Models\Organization;
use App\Models\ProjectType;
use App\Models\Project;
use App\Models\ProjectStatus;
use Carbon\Carbon;
use App\Models\ProjectGroup;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
      $admin = Role::where('slug','admin')->first();
      $manager = Role::where('slug','manager')->first();
      $owner = Role::where('slug','owner')->first();
      $guard_post = Role::where('slug','guard-post')->first();
      $dispatcher = Role::where('slug','dispatcher')->first();

      $bdo = Organization::find(1);
      $mir = Organization::find(2);

      //Администратор
      $user = new User();
      $user->login = "admin";
      $user->password = bcrypt('admin');
      $user->status = 'Новый пользователь';
      $user->save();
      $user->roles()->attach($admin);
      $user->organization()->attach($bdo);

      //Собственник 1
      $user = new User();
      $user->login = "owner1";
      $user->password = bcrypt('owner');
      $user->status = 'Новый пользователь';
      $user->save();
      $user->roles()->attach($owner);
      $user->organization()->attach($bdo);

      //Собственник 2
      $user = new User();
      $user->login = "owner2";
      $user->password = bcrypt('owner');
      $user->status = 'Новый пользователь';
      $user->save();
      $user->roles()->attach($owner);
      $user->organization()->attach($bdo);

      //Собственник 3
      $user = new User();
      $user->login = "owner3";
      $user->password = bcrypt('owner');
      $user->status = 'Новый пользователь';
      $user->save();
      $user->roles()->attach($owner);
      $user->organization()->attach($bdo);

      //Охранник поста
      $user = new User();
      $user->login = "guardpost";
      $user->password = bcrypt('guardpost');
      $user->status = 'Новый пользователь';
      $user->save();
      $user->roles()->attach($guard_post);
      $user->organization()->attach($bdo);

      //Диспетчер 6
      $user = new User();
      $user->login = "dispatcher";
      $user->password = bcrypt('dispatcher');
      $user->status = 'Новый пользователь';
      $user->save();
      $user->roles()->attach($dispatcher);
      $user->organization()->attach($bdo);

      //Менеджер 7 
      $user = new User();
      $user->login = "manager";
      $user->password = bcrypt('manager');
      $user->status = 'Новый пользователь';
      $user->save();
      $user->roles()->attach($manager);
      $user->organization()->attach($mir);

      //Охранник поста 2
      $user = new User();
      $user->login = "guardpost2";
      $user->password = bcrypt('guardpost2');
      $user->status = 'Новый пользователь';
      $user->save();
      $user->roles()->attach($guard_post);
      $user->organization()->attach($bdo);

      //Охранник поста 3
      $user = new User();
      $user->login = "guardpost3";
      $user->password = bcrypt('guardpost3');
      $user->status = 'Новый пользователь';
      $user->save();
      $user->roles()->attach($guard_post);
      $user->organization()->attach($bdo);
    }
}
