<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Warehouse;
use App\Models\Organization;

class WarehousesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $bdo = Organization::where('name', 'BdoDesigner')->first();

        $w1 = new Warehouse();
        $w1->name = 'Основной склад';
        $w1->address = 'г. Москва, Мичуринский проспект, д. 32, кв. 33';
        $w1->organization()->associate($bdo);
        $w1->save();

        $w2 = new Warehouse();
        $w2->name = 'Малый склад';
        $w2->address = 'г. Москва, Ленинский проспект, д. 32, кв. 33';
        $w2->organization()->associate($bdo);
        $w2->save();
    }
}
