<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Role;
use App\Models\ParkingSpace;
use App\Models\Price; // Importamos el modelo de Precios
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // 1. Array con los roles por defecto
        $roles = [
            'Administrador',
            'Cajero',
            'Operador'
        ];

        // Recorremos el array y creamos cada rol en la base de datos
        foreach ($roles as $rol) {
            Role::create([
                'name' => $rol
            ]);
        }

        // 2. Creamos el usuario Administrador maestro para pruebas
        User::create([
            'name' => 'Victor Jesus',
            'email' => 'vjgg101@gmail.com',
            'role' => 'Administrador',
            'password' => Hash::make('Vjgg8544'),
        ]);

        // 3. Sembrar puestos del 1 al 45 basándonos en el sistema original
        for ($i = 1; $i <= 45; $i++) {
            ParkingSpace::create([
                'space_number' => str_pad($i, 2, '0', STR_PAD_LEFT), // Crea 01, 02, 03... 45
                'status' => 'DISPONIBLE'
            ]);
        }

        // 4. Sembrar Tarifa Base inicial ($3 por día)
        Price::create([
            'quantity' => 1,
            'detail' => 'DIAS',
            'amount' => 3.00
        ]);
    }
}
