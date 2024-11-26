<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\User;

class UnsuspendUsers extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'users:unsuspend';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Unsuspend users whose suspension period has expired';

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        $users = User::where('status', 'suspended')
            ->where('suspension_until', '<=', now())
            ->get();

        foreach ($users as $user) {
            $user->update(['status' => 'active', 'suspension_until' => null]);
            $this->info("Unsuspended user: {$user->id}");
        }

        $this->info('All expired suspensions have been processed.');
        return 0;
    }
}
