<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use App\Models\User;

class UserRolesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $users = User::all();
        $roles = Role::all();

        foreach ($users as $user) {
            /** @var User $user */
            $user->assignRole(fake()->randomElement($roles));
        }
    }
}
