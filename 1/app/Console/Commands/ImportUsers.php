<?php

namespace App\Console\Commands;

use App\Models\User;
use Illuminate\Console\Command;

class ImportUsers extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:import-users {path}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Import users from csv file';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $path = $this->argument('path');

        if (! is_readable($path)) {
            $this->error('file is not readable: ' . $path);
            return 1;
        }

        $file = fopen($path, 'r');
        
        if (! $file) {
            $this->error('failed to read file: ' . $path);
            return 1;
        }

        fgetcsv($file);
        
        while (($line = fgetcsv($file)) !== FALSE) {
            [$name, $email, $password] = [$line[0], $line[1], $line[2]];

            User::query()->updateOrCreate([
                'email' => $email,
            ], [
                'name' => $name,
                'password' => bcrypt($password),
            ]);
        }

        return 0;
    }
}
