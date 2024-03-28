<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class BlogSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $batchSize = 10000;
        $totalBlogs = 50000;

        $this->command->getOutput()->progressStart($totalBlogs);

        $insertData = [];
        for ($i = 0; $i < $totalBlogs; $i++) {
            $insertData[] = [
                'title' => Str::random(20), // Random string of length 20
                'body' => Str::random(1000), // Random string of length 1000
                'thumbnail' => $this->generateRandomImageUrl(),
                'user_id' => mt_rand(1, 100000),
                'created_at' => now(),
                'updated_at' => now()
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

    private function insertBatch(array $data): void
    {
        $values = implode(',', array_map(function ($blog) {
            return "(" . implode(',', array_map(function ($value) {
                    return "'" . addslashes($value) . "'";
                }, $blog)) . ")";
        }, $data));

        $query = "INSERT INTO blogs (title, body, thumbnail, user_id, created_at, updated_at) VALUES $values";

        DB::statement($query);
    }

    private function generateRandomImageUrl(): string
    {
        return "https://via.placeholder.com/150"; // Example placeholder image URL
    }
}
