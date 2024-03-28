<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $batchSize = 5000;
        $totalUsers = 100000;
        $password = Hash::make('password');
        $created_at = now();
        $updated_at = now();

        $this->command->getOutput()->progressStart($totalUsers);

        $insertData = [];
        for ($i = 0; $i < $totalUsers; $i++) {
            $insertData[] = [
                'name' => Str::random(6).' '.Str::random(5),
                'email' => Str::random(6)."$i@gmail.com",
                'password' => $password,
                'created_at' => $created_at,
                'updated_at' => $updated_at
            ];

            if (($i + 1) % $batchSize === 0) {
                $this->insertBatch($insertData);
                $insertData = [];
            }

            $this->command->getOutput()->progressAdvance();
        }

        // Insert any remaining records
        if (!empty($insertData)) {
            $this->insertBatch($insertData);
        }

        $this->command->getOutput()->progressFinish();
    }
    private function insertBatch(array $data): void // this version present sql injection by doing sanitization
    {
        $placeholders = rtrim(str_repeat('(?, ?, ?, ?, ?),', count($data)), ',');
        $values = array_reduce($data, function ($carry, $user) {
            return array_merge($carry, array_values($user));
        }, []);

        $query = "INSERT INTO users (name, email, password, created_at, updated_at) VALUES $placeholders";

        DB::statement($query, $values);
    }
}
