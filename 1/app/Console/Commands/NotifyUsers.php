<?php

namespace App\Console\Commands;

use App\Mail\UserByRoleNotification;
use Illuminate\Console\Command;
use Illuminate\Database\Eloquent\Builder;
use App\Models\User;
use Illuminate\Support\Facades\Mail;

class NotifyUsers extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:notify-users {role}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Notify users with given role';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $role = $this->argument('role');

        $users = User::query()
            ->whereHas('roles', function (Builder $query) use ($role) {
                $query->where('name', $role);
            })
            ->get();

        foreach ($users as $user) {
            /** @var User $user */
            Mail::to($user->email)->queue(new UserByRoleNotification());
        }
    }
}
