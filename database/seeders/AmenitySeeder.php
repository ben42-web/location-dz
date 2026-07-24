<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class AmenitySeeder extends Seeder
{
    public function run(): void
    {
        $amenities = [
            ['name' => 'Wi-Fi', 'icon' => 'wifi', 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'Climatisation', 'icon' => 'snowflake', 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'Chauffage', 'icon' => 'flame', 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'Cuisine équipée', 'icon' => 'utensils', 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'Machine à laver', 'icon' => 'tshirt', 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'TV', 'icon' => 'tv', 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'Parking', 'icon' => 'car', 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'Piscine', 'icon' => 'water', 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'Gardien', 'icon' => 'shield', 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'Ascenseur', 'icon' => 'arrow-up', 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'Meublé', 'icon' => 'couch', 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'Eau chaude', 'icon' => 'droplet', 'created_at' => now(), 'updated_at' => now()],
        ];

        DB::table('amenities')->insert($amenities);
    }
}
