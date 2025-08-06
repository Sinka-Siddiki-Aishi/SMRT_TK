<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Event;
use App\Models\Category;
use Carbon\Carbon;

class EventSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Get the organizer user created by UserSeeder
        $organizer = \App\Models\User::where('email', 'organizer@smarttix.com')->first();

        $musicCategory = Category::where('name', 'Music')->first();
        $sportsCategory = Category::where('name', 'Sports')->first();
        $theaterCategory = Category::where('name', 'Theater')->first();
        $conferenceCategory = Category::where('name', 'Conference')->first();

        $events = [
            // Music Events
            [
                'title' => 'Summer Music Festival',
                'description' => 'Experience the best of summer with top artists and amazing vibes. Join us for three days of non-stop music, food, and fun.',
                'date' => Carbon::now()->addDays(15)->setTime(18, 0),
                'location' => 'Central Park',
                'venue' => 'Great Lawn',
                'address' => 'Central Park, Manhattan',
                'city' => 'New York',
                'state' => 'NY',
                'category_id' => $musicCategory->id,
                'organizer_id' => $organizer->id,
                'price' => 45.00,
                'vip_price' => 75.00,
                'premium_price' => 120.00,
                'capacity' => 5000,
                'available_tickets' => 5000,
                'status' => 'approved',
                'featured' => true,
                'rating' => 4.8,
                'rating_count' => 156,
                'booking_count' => 1200,
                'performers' => ['The Weeknd', 'Dua Lipa', 'Ed Sheeran']
            ],
            [
                'title' => 'Jazz Night at Blue Note',
                'description' => 'An intimate evening of smooth jazz featuring renowned artists in the legendary Blue Note venue.',
                'date' => Carbon::now()->addDays(8)->setTime(20, 0),
                'location' => 'Blue Note',
                'venue' => 'Blue Note Jazz Club',
                'address' => '131 W 3rd St',
                'city' => 'New York',
                'state' => 'NY',
                'category_id' => $musicCategory->id,
                'organizer_id' => $organizer->id,
                'price' => 75.00,
                'vip_price' => 120.00,
                'capacity' => 300,
                'available_tickets' => 300,
                'status' => 'approved',
                'rating' => 4.6,
                'rating_count' => 89,
                'booking_count' => 180,
                'performers' => ['Wynton Marsalis', 'Diana Krall']
            ],
            [
                'title' => 'Rock Concert Extravaganza',
                'description' => 'Get ready to rock with the biggest names in rock music. A night you will never forget!',
                'date' => Carbon::now()->addDays(25)->setTime(19, 30),
                'location' => 'Madison Square Garden',
                'venue' => 'Madison Square Garden',
                'address' => '4 Pennsylvania Plaza',
                'city' => 'New York',
                'state' => 'NY',
                'category_id' => $musicCategory->id,
                'organizer_id' => $organizer->id,
                'price' => 120.00,
                'vip_price' => 200.00,
                'premium_price' => 350.00,
                'capacity' => 20000,
                'available_tickets' => 18500,
                'status' => 'approved',
                'featured' => true,
                'rating' => 4.9,
                'rating_count' => 342,
                'booking_count' => 1500,
                'performers' => ['Foo Fighters', 'Green Day', 'Red Hot Chili Peppers']
            ],

            // Sports Events
            [
                'title' => 'Championship Basketball Game',
                'description' => 'Watch the season finale as two top teams battle it out for the championship title.',
                'date' => Carbon::now()->addDays(12)->setTime(19, 0),
                'location' => 'Barclays Center',
                'venue' => 'Barclays Center',
                'address' => '620 Atlantic Ave',
                'city' => 'Brooklyn',
                'state' => 'NY',
                'category_id' => $sportsCategory->id,
                'organizer_id' => $organizer->id,
                'price' => 85.00,
                'vip_price' => 150.00,
                'premium_price' => 250.00,
                'capacity' => 17732,
                'available_tickets' => 15000,
                'status' => 'approved',
                'rating' => 4.7,
                'rating_count' => 234,
                'booking_count' => 2732,
                'performers' => ['Brooklyn Nets', 'Los Angeles Lakers']
            ],
            [
                'title' => 'Soccer World Cup Viewing Party',
                'description' => 'Join fellow soccer fans for an exciting viewing party with big screens, food, and drinks.',
                'date' => Carbon::now()->addDays(20)->setTime(15, 0),
                'location' => 'Sports Bar & Grill',
                'venue' => 'Champions Sports Bar',
                'address' => '123 Sports Ave',
                'city' => 'Manhattan',
                'state' => 'NY',
                'category_id' => $sportsCategory->id,
                'organizer_id' => $organizer->id,
                'price' => 25.00,
                'capacity' => 200,
                'available_tickets' => 150,
                'status' => 'approved',
                'rating' => 4.2,
                'rating_count' => 45,
                'booking_count' => 50
            ],

            // Theater Events
            [
                'title' => 'Broadway Show Night',
                'description' => 'An unforgettable evening of world-class theater performances featuring the best of Broadway.',
                'date' => Carbon::now()->addDays(28)->setTime(20, 0),
                'location' => 'Broadway Theater',
                'venue' => 'Majestic Theatre',
                'address' => '245 W 44th St',
                'city' => 'New York',
                'state' => 'NY',
                'category_id' => $theaterCategory->id,
                'organizer_id' => $organizer->id,
                'price' => 85.00,
                'vip_price' => 150.00,
                'premium_price' => 250.00,
                'capacity' => 1200,
                'available_tickets' => 800,
                'status' => 'approved',
                'rating' => 4.8,
                'rating_count' => 167,
                'booking_count' => 400,
                'performers' => ['Hamilton Cast', 'Lin-Manuel Miranda']
            ],
            [
                'title' => 'Shakespeare in the Park',
                'description' => 'Experience the magic of Shakespeare under the stars in this outdoor theatrical performance.',
                'date' => Carbon::now()->addDays(18)->setTime(19, 0),
                'location' => 'Central Park',
                'venue' => 'Delacorte Theater',
                'address' => 'Central Park West & 81st St',
                'city' => 'New York',
                'state' => 'NY',
                'category_id' => $theaterCategory->id,
                'organizer_id' => $organizer->id,
                'price' => 0.00,
                'capacity' => 1800,
                'available_tickets' => 1800,
                'status' => 'approved',
                'featured' => true,
                'rating' => 4.5,
                'rating_count' => 89,
                'booking_count' => 0,
                'performers' => ['Shakespeare Company']
            ],

            // Conference Events
            [
                'title' => 'Tech Conference 2024',
                'description' => 'Join industry leaders and innovators for the biggest tech event of the year. Learn about the latest trends and technologies.',
                'date' => Carbon::now()->addDays(22)->setTime(9, 0),
                'location' => 'Convention Center',
                'venue' => 'Moscone Center',
                'address' => '747 Howard St',
                'city' => 'San Francisco',
                'state' => 'CA',
                'category_id' => $conferenceCategory->id,
                'organizer_id' => $organizer->id,
                'price' => 120.00,
                'vip_price' => 200.00,
                'premium_price' => 350.00,
                'capacity' => 2000,
                'available_tickets' => 1500,
                'status' => 'approved',
                'featured' => true,
                'rating' => 4.7,
                'rating_count' => 123,
                'booking_count' => 500,
                'performers' => ['Elon Musk', 'Sundar Pichai', 'Tim Cook']
            ],
            [
                'title' => 'Digital Marketing Summit',
                'description' => 'Discover the latest digital marketing strategies and network with industry professionals.',
                'date' => Carbon::now()->addDays(35)->setTime(10, 0),
                'location' => 'Marriott Hotel',
                'venue' => 'Chicago Marriott Downtown',
                'address' => '540 N Michigan Ave',
                'city' => 'Chicago',
                'state' => 'IL',
                'category_id' => $conferenceCategory->id,
                'organizer_id' => $organizer->id,
                'price' => 95.00,
                'vip_price' => 150.00,
                'capacity' => 500,
                'available_tickets' => 350,
                'status' => 'approved',
                'rating' => 4.3,
                'rating_count' => 67,
                'booking_count' => 150
            ],
            [
                'title' => 'Startup Pitch Competition',
                'description' => 'Watch innovative startups pitch their ideas to investors and compete for funding.',
                'date' => Carbon::now()->addDays(30)->setTime(14, 0),
                'location' => 'Innovation Hub',
                'venue' => 'Austin Convention Center',
                'address' => '500 E Cesar Chavez St',
                'city' => 'Austin',
                'state' => 'TX',
                'category_id' => $conferenceCategory->id,
                'organizer_id' => $organizer->id,
                'price' => 50.00,
                'vip_price' => 100.00,
                'capacity' => 300,
                'available_tickets' => 200,
                'status' => 'approved',
                'rating' => 4.1,
                'rating_count' => 34,
                'booking_count' => 100
            ]
        ];

        foreach ($events as $event) {
            Event::create($event);
        }

        // Create some sample deals
        $this->createSampleDeals();
    }

    private function createSampleDeals()
    {
        $events = Event::all();

        foreach ($events->take(5) as $event) {
            // Early bird deal
            \App\Models\Deal::create([
                'title' => 'Early Bird Special',
                'description' => 'Book early and save 20%!',
                'event_id' => $event->id,
                'type' => 'early_bird',
                'discount_percentage' => 20.00,
                'start_date' => now(),
                'end_date' => $event->date->subDays(7),
                'usage_limit' => 100,
                'active' => true,
            ]);
        }
    }
}
