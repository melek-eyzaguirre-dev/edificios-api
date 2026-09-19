<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class AdminUserSeeder extends Seeder
{
    public function run(): void
    {
        User::firstOrCreate(
            ['email' => 'admin@edificios.test'],
            [
                'name' => 'Admin',
                'password' => Hash::make('admin1234'),
                'rol' => 'super_admin',
            ]
        );

        User::firstOrCreate(
            ['email' => 'conserje@edificios.test'],
            [
                'name' => 'Conserje Demo',
                'password' => Hash::make('conserje1234'),
                'rol' => 'conserje',
            ]
        );

        User::firstOrCreate(
            ['email' => 'residente@edificios.test'],
            [
                'name' => 'Residente Demo',
                'password' => Hash::make('residente1234'),
                'rol' => 'residente',
            ]
        );

        $this->command->info('Cuentas demo: admin@edificios.test / admin1234, conserje@edificios.test / conserje1234, residente@edificios.test / residente1234');
    }
}
