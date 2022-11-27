<?php

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $this->call(AdminUserSeeder::class);
        $this->call(RecursoSeeder::class);
        $this->call(SuperAdminGrupoSeeder::class);
        $this->call(InitialDataSeeder::class);
    }
}
