<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Property;
use App\Models\Booking;
use App\Models\Review;
use App\Models\Message;
use App\Models\Favorite;
use App\Models\PropertyType;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        $this->call([
            AmenitySeeder::class,
        ]);

        // Property types
        $types = [
            ['name' => 'Appartement', 'slug' => 'apartment'],
            ['name' => 'Maison', 'slug' => 'house'],
            ['name' => 'Chambre', 'slug' => 'room'],
            ['name' => 'Studio', 'slug' => 'studio'],
            ['name' => 'Villa', 'slug' => 'villa'],
        ];
        foreach ($types as $t) {
            PropertyType::create($t + ['is_active' => true, 'created_at' => now(), 'updated_at' => now()]);
        }

        // Admin
        $admin = User::create([
            'name' => 'Admin',
            'email' => 'admin@location-dz.dz',
            'password' => Hash::make('admin123'),
            'role' => 'admin',
            'email_verified_at' => now(),
        ]);

        // Host user
        $host = User::create([
            'name' => 'Youcef',
            'email' => 'youcef@location-dz.dz',
            'password' => Hash::make('password'),
            'role' => 'host',
            'email_verified_at' => now(),
        ]);

        // Guest user
        $guest = User::create([
            'name' => 'Amina',
            'email' => 'amina@location-dz.dz',
            'password' => Hash::make('password'),
            'role' => 'guest',
            'email_verified_at' => now(),
        ]);

        // Properties
        $props = [
            [
                'user_id' => $host->id,
                'property_type_id' => PropertyType::where('slug', 'apartment')->value('id'),
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
                'property_type_id' => PropertyType::where('slug', 'villa')->value('id'),
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
                'property_type_id' => PropertyType::where('slug', 'studio')->value('id'),
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
                'property_type_id' => PropertyType::where('slug', 'house')->value('id'),
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
                'property_type_id' => PropertyType::where('slug', 'apartment')->value('id'),
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

        // More bookings
        $prop3 = Property::where('city', 'Constantine')->first();
        $prop4 = Property::where('city', 'Tlemcen')->first();
        $prop5 = Property::where('city', 'Annaba')->first();

        $pastBooking1 = Booking::create([
            'property_id' => $prop1->id,
            'user_id' => $guest->id,
            'check_in' => now()->subDays(20),
            'check_out' => now()->subDays(16),
            'guests' => 2,
            'total_price' => 26000,
            'status' => 'completed',
        ]);

        $pastBooking2 = Booking::create([
            'property_id' => $prop3->id,
            'user_id' => $guest->id,
            'check_in' => now()->subDays(10),
            'check_out' => now()->subDays(8),
            'guests' => 1,
            'total_price' => 7000,
            'status' => 'completed',
        ]);

        $pastBooking3 = Booking::create([
            'property_id' => $prop5->id,
            'user_id' => $guest->id,
            'check_in' => now()->subDays(30),
            'check_out' => now()->subDays(27),
            'guests' => 2,
            'total_price' => 21000,
            'status' => 'completed',
        ]);

        Booking::create([
            'property_id' => $prop4->id,
            'user_id' => $guest->id,
            'check_in' => now()->subDays(5),
            'check_out' => now()->subDays(3),
            'guests' => 3,
            'total_price' => 10000,
            'status' => 'cancelled',
        ]);

        // Attach amenities to more properties
        $prop3->amenities()->attach([1, 4, 6, 11]);
        $prop4->amenities()->attach([1, 3, 9, 11, 12]);
        $prop5->amenities()->attach([1, 2, 4, 6, 12]);

        // Reviews
        Review::create([
            'property_id' => $prop1->id,
            'user_id' => $guest->id,
            'booking_id' => $pastBooking1->id,
            'rating' => 5,
            'comment' => 'Séjour parfait ! L\'appartement est exactement comme sur les photos, très propre et bien situé. Youcef est un hôte très accueillant. Je recommande vivement.',
        ]);

        Review::create([
            'property_id' => $prop3->id,
            'user_id' => $guest->id,
            'booking_id' => $pastBooking2->id,
            'rating' => 4,
            'comment' => 'Très joli studio avec une vue superbe sur le pont. Le quartier est calme. Petit bémol : le wifi était un peu lent.',
        ]);

        Review::create([
            'property_id' => $prop5->id,
            'user_id' => $guest->id,
            'booking_id' => $pastBooking3->id,
            'rating' => 5,
            'comment' => 'Incroyable vue sur la mer ! L\'appartement est moderne et confortable. On a adoré se réveiller avec cette vue. On y retournera.',
        ]);

        // Favorites
        Favorite::create(['user_id' => $guest->id, 'property_id' => $prop2->id]);
        Favorite::create(['user_id' => $guest->id, 'property_id' => $prop4->id]);

        // Messages
        Message::create([
            'sender_id' => $guest->id,
            'receiver_id' => $host->id,
            'property_id' => $prop1->id,
            'content' => 'Bonjour Youcef ! Je suis intéressée par votre appartement à Alger. Est-ce qu\'il est disponible du 15 au 20 août ?',
            'is_read' => true,
            'created_at' => now()->subDays(2),
        ]);

        Message::create([
            'sender_id' => $host->id,
            'receiver_id' => $guest->id,
            'property_id' => $prop1->id,
            'content' => 'Bonjour Amina ! Oui, le appartement est libre ces dates. Souhaitez-vous que je vous réserve ?',
            'is_read' => true,
            'created_at' => now()->subDays(2)->addHour(),
        ]);

        Message::create([
            'sender_id' => $guest->id,
            'receiver_id' => $host->id,
            'property_id' => $prop1->id,
            'content' => 'Parfait, oui merci ! Est-ce qu\'il y a un parking à proximité ?',
            'is_read' => true,
            'created_at' => now()->subDays(2)->addHours(2),
        ]);

        Message::create([
            'sender_id' => $host->id,
            'receiver_id' => $guest->id,
            'property_id' => $prop1->id,
            'content' => 'Il y a un parking public à 2 minutes à pied, et je peux vous réserver une place dans le garage du bâtiment pour 500 DA/nuit en plus.',
            'is_read' => false,
            'created_at' => now()->subDays(2)->addHours(3),
        ]);

        Message::create([
            'sender_id' => $guest->id,
            'receiver_id' => $host->id,
            'property_id' => $prop2->id,
            'content' => 'Bonjour ! La piscine de la villa, est-ce qu\'elle est chauffée ?',
            'is_read' => true,
            'created_at' => now()->subDays(5),
        ]);

        Message::create([
            'sender_id' => $host->id,
            'receiver_id' => $guest->id,
            'property_id' => $prop2->id,
            'content' => 'Bonjour Amina ! Non, la piscine n\'est pas chauffée, mais en août l\'eau est très agréable. La villa a aussi une climatisation puissante pour se rafraîchir.',
            'is_read' => true,
            'created_at' => now()->subDays(5)->addHour(),
        ]);
    }
}
