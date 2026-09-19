<?php

namespace Database\Seeders;

use App\Models\Administradora;
use App\Models\Condominio;
use App\Models\EspacioComun;
use App\Models\Estacionamiento;
use App\Models\Unidad;
use App\Models\User;
use Illuminate\Database\Seeder;

class DemoCondominioSeeder extends Seeder
{
    public function run(): void
    {
        $administradora = Administradora::firstOrCreate(
            ['rut' => '76.123.456-7'],
            ['nombre' => 'Administradora Demo', 'email_contacto' => 'contacto@edificios.test', 'activo' => true],
        );
        $condominio = Condominio::firstOrCreate(
            ['administradora_id' => $administradora->id, 'nombre' => 'Edificio Parque Central'],
            ['direccion' => 'Av. Principal 123', 'comuna' => 'Santiago', 'ciudad' => 'Santiago', 'activo' => true],
        );

        $unidades = collect([
            ['numero' => '101', 'torre' => 'A'],
            ['numero' => '202', 'torre' => 'A'],
            ['numero' => '301', 'torre' => 'B'],
        ])->map(fn (array $data) => Unidad::firstOrCreate(['condominio_id' => $condominio->id, ...$data], ['prorrateo' => 1]));

        foreach ([['nombre' => 'Quincho', 'capacidad' => 20], ['nombre' => 'Salón multiuso', 'capacidad' => 50]] as $espacio) {
            EspacioComun::firstOrCreate(['condominio_id' => $condominio->id, 'nombre' => $espacio['nombre']], [
                'capacidad' => $espacio['capacidad'], 'duracion_maxima_horas' => 4, 'anticipacion_minima_horas' => 1, 'requiere_aprobacion' => true, 'activo' => true,
            ]);
        }

        foreach (['E-01', 'E-02', 'E-03', 'V-01', 'V-02'] as $index => $codigo) {
            Estacionamiento::firstOrCreate(['condominio_id' => $condominio->id, 'codigo' => $codigo], [
                'tipo' => str_starts_with($codigo, 'V-') ? 'visita' : 'fijo',
                'unidad_id' => str_starts_with($codigo, 'V-') ? null : $unidades[$index % $unidades->count()]->id,
                'disponible' => true,
            ]);
        }

        $admin = User::where('email', 'admin@edificios.test')->first();
        $conserje = User::where('email', 'conserje@edificios.test')->first();
        $residente = User::where('email', 'residente@edificios.test')->first();
        $aseo = User::firstOrCreate(['email' => 'aseo@edificios.test'], ['name' => 'Aseo Demo', 'password' => bcrypt('aseo1234'), 'rol' => 'personal_aseo', 'activo' => true]);

        $condominio->staff()->syncWithoutDetaching([$admin->id, $conserje->id, $aseo->id]);
        $residente->unidades()->syncWithoutDetaching([$unidades->first()->id => ['tipo' => 'propietario']]);
        $conserje->unidades()->syncWithoutDetaching([]);

        $this->command->info('Demo creado: Edificio Parque Central, unidades, espacios, estacionamientos y personal de prueba.');
    }
}