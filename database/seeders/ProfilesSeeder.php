<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

use App\Models\Profile;
use App\Models\User;
use App\Models\Group;
use App\Models\Family;
use App\Models\Organization;
use Carbon\Carbon;

class ProfilesSeeder extends Seeder
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

      $managers = Group::where('slug', 'managers')->first();
      $owners = Group::where('slug', 'owners')->first();
      $workers = Group::where('slug', 'workers')->first();

      $profile = new Profile();
      $profile->fio = "Иванов Сергей Николаевич";
      $profile->phone = '9093751695';
      $profile->email = 'admin@gvillage.ru';
      $profile->sex = "Мужской";
      $profile->address = "Без уточнения";
      $profile->role = "Администратор";
      $profile->code = $bdo->code.'_'.random_int(10000, 99999);
      $profile->dateofbirth = Carbon::now()->subYears(35);
      $user = User::find(1);
      $profile->user()->associate($user)->save();
      $profile->organization()->attach($bdo);
      $profile->group()->attach($managers);

      //Собственники
      $family = Family::find(1);
      $profile = new Profile();
      $profile->fio = "Лунин Андрей Николаевич";
      $profile->phone = '9093751696';
      $profile->email = 'arsen963110@mail.ru';
      $profile->sex = "Мужской";
      $profile->address = "г Москва, ул Профсоюзная, д 40, кв 12";
      $profile->role = "Собственник";
      $profile->code = $bdo->code.'_'.random_int(10000, 99999);
      $profile->dateofbirth = Carbon::now()->subYears(25);
      $user = User::find(2);
      $profile->user()->associate($user)->save();
      $profile->organization()->attach($bdo);
      $profile->group()->attach($owners);
      $profile->family()->attach($family);


      $family = Family::find(2);
      $profile = new Profile();
      $profile->fio = "Комов Петр Васильевич";
      $profile->phone = '9093751697';
      $profile->email = 'owner2@gvillage.ru';
      $profile->sex = "Мужской";
      $profile->address = "г Москва, ул Профсоюзная, д 10, кв 12";
      $profile->dateofbirth = Carbon::now()->subYears(37);
      $profile->role = "Собственник";
      $profile->code = $bdo->code.'_'.random_int(10000, 99999);
      $user = User::find(3);
      $profile->user()->associate($user)->save();
      $profile->group()->attach($owners);
      $profile->family()->attach($family);
      $profile->organization()->attach($bdo);

      $family = Family::find(3);
      $profile = new Profile();
      $profile->fio = "Ушаков Алексей Юрьевич";
      $profile->phone = '9093751698';
      $profile->email = 'luka963110@mail.ru';
      $profile->sex = "Мужской";
      $profile->address = "г Москва, д Солослово, ул Уварова, д 10, кв 35";
      $profile->dateofbirth = Carbon::now()->subYears(27);
      $profile->role = "Собственник";
      $profile->code = $bdo->code.'_'.random_int(10000, 99999);
      $user = User::find(4);
      $profile->user()->associate($user)->save();
      $profile->group()->attach($owners);
      $profile->family()->attach($family);
      $profile->organization()->attach($bdo);

      //Охранник поста
      $profile = new Profile();
      $profile->fio = "Кушаков Кирилл Валерьевич";
      $profile->phone = '9093751699';
      $profile->email = 'guardpost@gvillage.ru';
      $profile->sex = "Мужской";
      $profile->address = "Без уточнения";
      $profile->dateofbirth = Carbon::now()->subYears(32);
      $profile->role = "Охранник поста";
      $profile->code = $bdo->code.'_'.random_int(10000, 99999);
      $user = User::find(5);
      $profile->user()->associate($user)->save();
      $profile->organization()->attach($bdo);
      $profile->group()->attach($workers);

      //Диспетчер поста
      $profile = new Profile();
      $profile->fio = "Павленко Сергей Валерьевич";
      $profile->phone = '9093751700';
      $profile->email = 'dispatcher@gvillage.ru';
      $profile->sex = "Мужской";
      $profile->address = "Без уточнения";
      $profile->dateofbirth = Carbon::now()->subYears(22);
      $profile->role = "Диспетчер";
      $profile->code = $bdo->code.'_'.random_int(10000, 99999);
      $user = User::find(6);
      $profile->user()->associate($user)->save();
      $profile->organization()->attach($bdo);
      $profile->group()->attach($workers);

      //Менеджер
      $profile = new Profile();
      $profile->fio = "Ткаченко Михаил Петрович";
      $profile->phone = '9093751701';
      $profile->email = 'manager@gvillage.ru';
      $profile->sex = "Мужской";
      $profile->address = "Без уточнения";
      $profile->dateofbirth = Carbon::now()->subYears(25);
      $profile->role = "Менеджер";
      $profile->code = $mir->code.'_'.random_int(10000, 99999);
      $user = User::find(7);
      $profile->user()->associate($user)->save();
      $profile->group()->attach($managers);
      $profile->organization()->attach($mir);

      //Охранник поста 2
      $profile = new Profile();
      $profile->fio = "Иванов Валентин Яковлевич";
      $profile->phone = '9093751799';
      $profile->email = 'guardpost2@gvillage.ru';
      $profile->sex = "Мужской";
      $profile->address = "Без уточнения";
      $profile->dateofbirth = Carbon::now()->subYears(32);
      $profile->role = "Охранник поста";
      $profile->code = $bdo->code.'_'.random_int(10000, 99999);
      $user = User::find(8);
      $profile->user()->associate($user)->save();
      $profile->organization()->attach($bdo);
      $profile->group()->attach($workers);

      //Охранник поста 3
      $profile = new Profile();
      $profile->fio = "Смирнов Петр Андреевич";
      $profile->phone = '9093752799';
      $profile->email = 'guardpost3@gvillage.ru';
      $profile->sex = "Мужской";
      $profile->address = "Без уточнения";
      $profile->dateofbirth = Carbon::now()->subYears(32);
      $profile->role = "Охранник поста";
      $profile->code = $bdo->code.'_'.random_int(10000, 99999);
      $user = User::find(9);
      $profile->user()->associate($user)->save();
      $profile->organization()->attach($bdo);
      $profile->group()->attach($workers);
    }
}
