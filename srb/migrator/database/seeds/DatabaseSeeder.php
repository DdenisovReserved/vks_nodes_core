<?php
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Seeder;
use Illuminate\Database\Eloquent\Model;

class DatabaseSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Model::unguard();
        //define root attendance
        DB::table('attendance')->insert([
            'name' => 'Сбербанк корневой',
            'parent_id' => Null,
            'container' => 1,
            'active' => 1,
            'created_at' => date_create(),
            'updated_at' => date_create()
        ]);


        $this->call(Misc::class);
        Model::reguard();
    }
}

class Misc extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('initiators')->insert([
            'name' => 'ЦА',
        ]);

        DB::table('initiators')->insert([
            'name' => 'ТБ (это шаблон, редактируется в настройках)',
        ]);

        DB::table('departments')->insert([
            'prefix' => '1',
            'name' => 'Тестовый департамент',
        ]);
    }
}
