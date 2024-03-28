<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class ProductSeeder extends Seeder
{
    public function run()
    {
        $batchSize = 10000;
        $totalProducts = 500000;

        $this->command->getOutput()->progressStart($totalProducts);

        $insertData = [];
        for ($i = 0; $i < $totalProducts; $i++) {
            $insertData[] = [
                'title' => Str::random(20), // Random string of length 20
                'description' => Str::random(100), // Random string of length 100
                'price' => mt_rand(1000, 10000) / 100, // Random price between 10 and 100
                'stock' => mt_rand(0, 100),
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
            info('Remaining Product inserting: '.count($insertData));
            $this->insertBatch($insertData);
        }

        $this->command->getOutput()->progressFinish();
    }

    private function insertBatch(array $data): void
    {
        $values = implode(',', array_map(function ($product) {
            return "(" . implode(',', array_map(function ($value) {
                    return "'" . addslashes($value) . "'";
                }, $product)) . ")";
        }, $data));

        $query = "INSERT INTO products (title, description, price, stock, user_id, created_at, updated_at) VALUES $values";

        DB::statement($query);
    }
}
