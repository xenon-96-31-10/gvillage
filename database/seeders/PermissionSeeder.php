<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Permission;

class PermissionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        //1
        $control_profile = new Permission();
        $control_profile->name = 'Управлять профилем';
        $control_profile->slug = 'control-profile';
        $control_profile->save();

        //2
        $control_profiles = new Permission();
        $control_profiles->name = 'Управлять доступными профилями пользователей сайта';
        $control_profiles->slug = 'control-profiles';
        $control_profiles->save();

        //3
        $control_profiles = new Permission();
        $control_profiles->name = 'Создавать профиль и генерировать ссылку для приглашения к регистрации';
        $control_profiles->slug = 'create-profile';
        $control_profiles->save();

        //4
        $control_projects = new Permission();
        $control_projects->name = 'Управлять объектами';
        $control_projects->slug = 'control-projects';
        $control_projects->save();

        //5
        $create_temporary_pass = new Permission();
        $create_temporary_pass->name = 'Заказывать временный пропуск';
        $create_temporary_pass->slug = 'create-temporary-pass';
        $create_temporary_pass->save();

        //6
        $edit_temporary_pass = new Permission();
        $edit_temporary_pass->name = 'Редактировать временный пропуск';
        $edit_temporary_pass->slug = 'edit-temporary-pass';
        $edit_temporary_pass->save();

        //7
        $control_pass = new Permission();
        $control_pass->name = 'Управлять пропусками';
        $control_pass->slug = 'control-passes';
        $control_pass->save();

        //8
        $create_permanent_pass = new Permission();
        $create_permanent_pass->name = 'Заказывать постоянный пропуск';
        $create_permanent_pass->slug = 'create-permanent-pass';
        $create_permanent_pass->save();

        //9
        $edit_permanent_pass = new Permission();
        $edit_permanent_pass->name = 'Редактировать постоянный пропуск';
        $edit_permanent_pass->slug = 'edit-permanent-pass';
        $edit_permanent_pass->save();

        //10
        $create_personal_account = new Permission();
        $create_personal_account->name = 'Создавать лицевой счет';
        $create_personal_account->slug = 'create-personal-account';
        $create_personal_account->save();

        //11
        $refill_personal_account = new Permission();
        $refill_personal_account->name = 'Пополнять лицевой счет';
        $refill_personal_account->slug = 'refill-personal-account';
        $refill_personal_account->save();

        //12
        $view_pass = new Permission();
        $view_pass->name = 'Просмотривать пропуски';
        $view_pass->slug = 'view-passes';
        $view_pass->save();

        //13
        $control_pass_rate_plan = new Permission();
        $control_pass_rate_plan->name = 'Управлять тарифными планами и тарифами';
        $control_pass_rate_plan->slug = 'control-pass-rate-plan';
        $control_pass_rate_plan->save();

        //14
        $view_pass_rate_plan = new Permission();
        $view_pass_rate_plan->name = 'Просматривать тарифные планы и тарифы';
        $view_pass_rate_plan->slug = 'view-pass-rate-plan';
        $view_pass_rate_plan->save();

        //15
        $control_cars = new Permission();
        $control_cars->name = 'Управлять автомобилями';
        $control_cars->slug = 'control-cars';
        $control_cars->save();

        //16
        $control_mechanizms = new Permission();
        $control_mechanizms->name = 'Управлять техникой';
        $control_mechanizms->slug = 'control-mechanizms';
        $control_mechanizms->save();

        //17
        $view_profiles = new Permission();
        $view_profiles->name = 'Просматривать профили связанных пользователей';
        $view_profiles->slug = 'view-profiles';
        $view_profiles->save();

        //18
        $view_projects = new Permission();
        $view_projects->name = 'Просматривать связанные с профилем объекты';
        $view_projects->slug = 'view-projects';
        $view_projects->save();

        //19
        $control_groups = new Permission();
        $control_groups->name = 'Управлять группами объектов';
        $control_groups->slug = 'control-project-groups';
        $control_groups->save();

        //20
        $control_guardposts = new Permission();
        $control_guardposts->name = 'Управлять охранными постами';
        $control_guardposts->slug = 'control-guardposts';
        $control_guardposts->save();

        //21
        $view_guardposts = new Permission();
        $view_guardposts->name = 'Просматривать охранные посты';
        $view_guardposts->slug = 'view-guardposts';
        $view_guardposts->save();
      }
}
