<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Faker\Factory as Faker;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class BlogCommentSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $batchSize = 10000;
        $totalComments = 500000;

        $faker = Faker::create();

        $this->command->getOutput()->progressStart($totalComments);

        $insertData = [];
        for ($i = 0; $i < $totalComments; $i++) {
            $insertData[] = [
                'body' => Str::random(3).' '.Str::random(5),
                'blog_id' => rand(1, 50000),
                'user_id' => rand(1, 100000),
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
        $values = implode(',', array_map(function ($comment) {
            return "(" . implode(',', array_map(function ($value) {
                    return "'" . addslashes($value) . "'";
                }, $comment)) . ")";
        }, $data));

        $query = "INSERT INTO blog_comments (body, blog_id, user_id, created_at, updated_at) VALUES $values";

        DB::statement($query);
    }
}
