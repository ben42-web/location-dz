<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Property;
use App\Models\Booking;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        $this->call([
            AmenitySeeder::class,
        ]);

        // Host user
        $host = User::create([
            'name' => 'Youcef',
            'email' => 'youcef@location-dz.dz',
            'password' => Hash::make('password'),
            'email_verified_at' => now(),
        ]);

        // Guest user
        $guest = User::create([
            'name' => 'Amina',
            'email' => 'amina@location-dz.dz',
            'password' => Hash::make('password'),
            'email_verified_at' => now(),
        ]);

        // Properties
        $props = [
            [
                'user_id' => $host->id,
                'title' => 'Appartement moderne centre-ville Alger',
                'description' => 'Bel appartement meublé au coeur d\'Alger, proche de la plage et des commerces.',
                'type' => 'apartment',
                'address' => 'Rue Didouche Mourad',
                'city' => 'Alger',
                'wilaya' => 'Alger',
                'latitude' => 36.7538,
                'longitude' => 3.0588,
                'price_per_night' => 6500,
                'max_guests' => 4,
                'bedrooms' => 2,
                'bathrooms' => 1,
            ],
            [
                'user_id' => $host->id,
                'title' => 'Villa avec piscine Oran',
                'description' => 'Grande villa avec piscine privée, idéale pour les familles.',
                'type' => 'villa',
                'address' => 'Boulevard Emir Abdelkader',
                'city' => 'Oran',
                'wilaya' => 'Oran',
                'latitude' => 35.6969,
                'longitude' => -0.6331,
                'price_per_night' => 15000,
                'max_guests' => 8,
                'bedrooms' => 4,
                'bathrooms' => 3,
            ],
            [
                'user_id' => $host->id,
                'title' => 'Studio cosy Constantine',
                'description' => 'Petit studio charmant avec vue sur le pont Sidi M\'Cid.',
                'type' => 'studio',
                'address' => 'Rue Larbi Ben M\'hidi',
                'city' => 'Constantine',
                'wilaya' => 'Constantine',
                'latitude' => 36.3650,
                'longitude' => 6.6147,
                'price_per_night' => 3500,
                'max_guests' => 2,
                'bedrooms' => 1,
                'bathrooms' => 1,
            ],
            [
                'user_id' => $host->id,
                'title' => 'Maison traditionnelle Tlemcen',
                'description' => 'Maison à la architecture traditionnelle, près de la Grande Mosquée.',
                'type' => 'house',
                'address' => 'Rue de la Mosquée',
                'city' => 'Tlemcen',
                'wilaya' => 'Tlemcen',
                'latitude' => 34.8828,
                'longitude' => -1.3167,
                'price_per_night' => 5000,
                'max_guests' => 6,
                'bedrooms' => 3,
                'bathrooms' => 2,
            ],
            [
                'user_id' => $host->id,
                'title' => 'Appartement vue mer Annaba',
                'description' => 'Appartement moderne avec vue directe sur la Méditerranée.',
                'type' => 'apartment',
                'address' => 'Boulevard Front de Mer',
                'city' => 'Annaba',
                'wilaya' => 'Annaba',
                'latitude' => 36.9000,
                'longitude' => 7.7667,
                'price_per_night' => 7000,
                'max_guests' => 3,
                'bedrooms' => 1,
                'bathrooms' => 1,
            ],
        ];

        foreach ($props as $p) {
            Property::create($p);
        }

        // Attach amenities to first property
        $prop1 = Property::first();
        $prop1->amenities()->attach([1, 2, 4, 6, 11]);

        $prop2 = Property::where('city', 'Oran')->first();
        $prop2->amenities()->attach([1, 2, 3, 7, 8, 9, 11]);

        // Some bookings
        Booking::create([
            'property_id' => $prop1->id,
            'user_id' => $guest->id,
            'check_in' => now()->addDays(5),
            'check_out' => now()->addDays(8),
            'guests' => 2,
            'total_price' => 19500,
            'status' => 'confirmed',
        ]);

        Booking::create([
            'property_id' => $prop2->id,
            'user_id' => $guest->id,
            'check_in' => now()->addDays(10),
            'check_out' => now()->addDays(12),
            'guests' => 4,
            'total_price' => 30000,
            'status' => 'pending',
        ]);
    }
}
