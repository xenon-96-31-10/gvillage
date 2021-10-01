<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Warehouse;
use App\Models\Equipment;

class EquipmentSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
      $w1 = Warehouse::where('name','Основной склад')->first();
      $w2 = Warehouse::where('name','Малый склад')->first();

      $perf = new Equipment();
      $perf->name = 'Перфоратор';
      $perf->value = 10;
      $perf->totalvalue = 10;
      $perf->warehouse()->associate($w1)->save();

      $hammer = new Equipment();
      $hammer->name = 'Молоток';
      $hammer->value = 100;
      $hammer->totalvalue = 100;
      $hammer->warehouse()->associate($w1)->save();

      $pc = new Equipment();
      $pc->name = 'Компьютер';
      $pc->value = 10;
      $pc->totalvalue = 10;
      $pc->warehouse()->associate($w2)->save();
    }
}
