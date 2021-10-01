<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Organization;

class OrganizationsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $bdo = new Organization();
        $bdo->name = 'BdoDesigner';
        $bdo->code = ''.random_int(10000, 99999);
        $bdo->save();

        $mir = new Organization();
        $mir->name = 'ООО МИР';
        $mir->code = ''.random_int(10000, 99999);
        $mir->save();
    }
}
