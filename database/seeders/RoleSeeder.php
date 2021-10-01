<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Role;
use App\Models\Permission;

class RoleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {

      $admin = new Role();
      $admin->name = 'Администратор';
      $admin->slug = 'admin';
      $admin->save();
      $permissions = Permission::find([1,2,3,4,10,11,13,15,16,19, 20]);
      $admin->permissions()->attach($permissions);

      $manager = new Role();
      $manager->name = 'Менеджер';
      $manager->slug = 'manager';
      $manager->save();
      $permissions = Permission::find([1,2,3,4,10,11,13,15,16,19, 20]);
      $manager->permissions()->attach($permissions);

      $owner = new Role();
      $owner->name = 'Собственник';
      $owner->slug = 'owner';
      $owner->save();
      $permissions = Permission::find([1, 5, 8, 12, 14, 17, 18, 21]);
      $owner->permissions()->attach($permissions);

      $family = new Role();
      $family->name = 'Член семьи';
      $family->slug = 'family';
      $family->save();
      $permissions = Permission::find([1, 5, 8, 12, 14, 17, 18, 21]);
      $family->permissions()->attach($permissions);

      $ownersguard = new Role();
      $ownersguard->name = 'Охранник территории/дома';
      $ownersguard->slug = 'ownersguard';
      $ownersguard->save();
      $permissions = Permission::find([1, 5, 7, 8, 12, 14, 17, 18]);
      $ownersguard->permissions()->attach($permissions);

      $owners_representative = new Role();
      $owners_representative->name = 'Представитель собственника';
      $owners_representative->slug = 'owners-rep';
      $owners_representative->save();
      $permissions = Permission::find([1, 5, 8, 12, 14, 17, 18, 21]);
      $owners_representative->permissions()->attach($permissions);

      $renter = new Role();
      $renter->name = 'Арендатор';
      $renter->slug = 'renter';
      $renter->save();
      $permissions = Permission::find([1, 5, 8, 12, 14, 17, 18, 21]);
      $renter->permissions()->attach($permissions);

      $worker = new Role();
      $worker->name = 'Работник';
      $worker->slug = 'worker';
      $worker->save();
      $permissions = Permission::find([1, 17, 18]);
      $worker->permissions()->attach($permissions);

      $watcher = new Role();
      $watcher->name = 'Наблюдатель';
      $watcher->slug = 'watcher';
      $watcher->save();
      $permissions = Permission::find([1, 17, 18]);
      $watcher->permissions()->attach($permissions);

      $executor = new Role();
      $executor->name = 'Исполнитель';
      $executor->slug = 'executor';
      $executor->save();
      $permissions = Permission::find([1, 17, 18]);
      $executor->permissions()->attach($permissions);

      $staff = new Role();
      $staff->name = 'Персонал';
      $staff->slug = 'staff';
      $staff->save();
      $permissions = Permission::find([1, 17, 18]);
      $staff->permissions()->attach($permissions);

      $overseer = new Role();
      $overseer->name = 'Надзирающий';
      $overseer->slug = 'overseer';
      $overseer->save();
      $permissions = Permission::find([1, 17, 18]);
      $overseer->permissions()->attach($permissions);

      $guard = new Role();
      $guard->name = 'Охранник';
      $guard->slug = 'guard';
      $guard->save();
      $permissions = Permission::find([1, 5, 7, 8, 12, 14, 17, 18]);
      $guard->permissions()->attach($permissions);

      $guard_post = new Role();
      $guard_post->name = 'Охранник на посту';
      $guard_post->slug = 'guard-post';
      $guard_post->save();
      $permissions = Permission::find([1, 5, 7, 8, 12, 14, 17, 18]);
      $guard_post->permissions()->attach($permissions);

      $dispatcher = new Role();
      $dispatcher->name = 'Диспетчер';
      $dispatcher->slug = 'dispatcher';
      $dispatcher->save();
      $permissions = Permission::find([1, 2, 5, 6, 7, 8, 9, 10, 11, 12, 14, 17, 18, 21]);
      $dispatcher->permissions()->attach($permissions);
    }
}
