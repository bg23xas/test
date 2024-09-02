<?php

namespace Tests\Feature;

use App\Models\User;
use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Storage;

class ImportUsersTest extends TestCase
{
    use RefreshDatabase;

    /**
     * A basic feature test example.
     */
    public function test_import_users(): void
    {
        $path = Storage::path('users.csv');

        Artisan::call('app:import-users ' . $path);

        $file = fopen($path, 'r');

        fgetcsv($file);
        
        while (($line = fgetcsv($file)) !== FALSE) {
            [$name, $email, $password] = [$line[0], $line[1], $line[2]];
            
            $user = User::query()->where([
                'name' => $name,
                'email' => $email,
            ])->first();

            $this->assertIsObject($user);
        }
    }
}
