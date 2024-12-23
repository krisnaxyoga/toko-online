<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Roles;
use App\Models\User;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $this->call([
            ProvinceSeeder::class,
            CitySeeder::class,
            RoleSeeder::class,
            UserSeeder::class,

        ]);
    }
}

class RoleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Roles::create(['role_name' => 'admin']);
        Roles::create(['role_name' => 'customer']);
    }
}

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $roles = Roles::pluck('id')->toArray();
        $users = [
            [
                'name' => 'Admin',
                'email' => 'admin@example.com',
                'phone' => '091213131',
                'password' => bcrypt('admin123'),
                'role_id' => $roles[0],
            ],
            [
                'name' => 'Customer',
                'email' => 'customer@example.com',
                'phone' => '091213131',
                'password' => bcrypt('customer123'),
                'role_id' => $roles[1],
            ],
        ];

        foreach ($users as $user) {
            User::create($user);
        }
    }
}