<?php

namespace Tests\Feature;

use App\Mail\UserByRoleNotification;
use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Database\Eloquent\Collection;
use App\Models\User;
use Spatie\Permission\Models\Role;

class NotifyUsersTest extends TestCase
{
    use RefreshDatabase;

    /**
     * A basic feature test example.
     */
    public function test_notify_users(): void
    {
        Mail::fake();

        Artisan::call('db:seed');

        /** @var Collection $users */
        $users = User::query()->with('roles')->get();
        $randomRole = $users->pluck('roles')->flatten(1)->random(1)->first();
        $usersByRandomRole = $users->filter(fn(User $u) => $u->roles->where(fn(Role $r) => $r->name)->first() != null);

        Artisan::call('app:notify-users ' . $randomRole->name);

        foreach ($usersByRandomRole as $user) {
            Mail::assertQueued(UserByRoleNotification::class, $user->mail);
        }
    }
}
