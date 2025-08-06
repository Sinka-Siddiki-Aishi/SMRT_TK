<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\User;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Create Admin User
        User::create([
            'name' => 'Admin User',
            'email' => 'admin@smarttix.com',
            'password' => bcrypt('password'),
            'role' => 'admin',
            'phone' => '+1-555-0001',
            'address' => '123 Admin Street, New York, NY 10001'
        ]);

        // Create Organizer User
        User::create([
            'name' => 'Event Organizer',
            'email' => 'organizer@smarttix.com',
            'password' => bcrypt('password'),
            'role' => 'organizer',
            'phone' => '+1-555-0002',
            'address' => '456 Organizer Ave, Los Angeles, CA 90210'
        ]);

        // Create Regular User
        User::create([
            'name' => 'John Doe',
            'email' => 'user@smarttix.com',
            'password' => bcrypt('password'),
            'role' => 'user',
            'phone' => '+1-555-0003',
            'address' => '789 User Blvd, Chicago, IL 60601'
        ]);

        // Create additional test users
        User::create([
            'name' => 'Jane Smith',
            'email' => 'jane@example.com',
            'password' => bcrypt('password'),
            'role' => 'user',
            'phone' => '+1-555-0004',
            'address' => '321 Test Lane, Miami, FL 33101'
        ]);

        User::create([
            'name' => 'Mike Johnson',
            'email' => 'mike@example.com',
            'password' => bcrypt('password'),
            'role' => 'organizer',
            'phone' => '+1-555-0005',
            'address' => '654 Event Road, Seattle, WA 98101'
        ]);
    }
}
