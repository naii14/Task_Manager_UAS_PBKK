<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Task;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $user = User::firstOrCreate(
            ['email' => 'admin@example.com'],
            [
                'name' => 'Naya',
                'password' => Hash::make('password')
            ]
        );

        $tasksData = [
            [
                'user_id' => $user->id,
                'judul' => 'Membuat Laporan Bulanan',
                'deskripsi' => 'Laporan bulanan terkait performa aplikasi',
                'deadline' => now()->addDays(2),
                'status' => 'Proses',
            ],
            [
                'user_id' => $user->id,
                'judul' => 'Rapat Tim Proyek',
                'deskripsi' => 'Rapat mingguan dengan tim',
                'deadline' => now()->subDays(1),
                'status' => 'Selesai',
            ],
            [
                'user_id' => $user->id,
                'judul' => 'Revisi Desain Aplikasi',
                'deskripsi' => 'Revisi desain UI/UX untuk aplikasi internal',
                'deadline' => now()->addDays(5),
                'status' => 'Tertunda',
            ],
            [
                'user_id' => $user->id,
                'judul' => 'Persiapan Presentasi',
                'deskripsi' => 'Menyiapkan slide presentasi',
                'deadline' => now()->subDays(3),
                'status' => 'Selesai',
            ]
        ];

        foreach ($tasksData as $data) {
            Task::create($data);
        }
    }
}
