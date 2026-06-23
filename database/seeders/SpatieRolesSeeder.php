<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;

class SpatieRolesSeeder extends Seeder
{
    public function run(): void
    {
        // Ensure roles used by tests exist.
        $roles = [
            'Tenaga Teknik',
        ];

        foreach ($roles as $roleName) {
            Role::firstOrCreate(['name' => $roleName, 'guard_name' => 'web']);

        }
    }
}

