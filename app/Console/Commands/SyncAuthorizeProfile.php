<?php

namespace App\Console\Commands;

use App\Jobs\SyncAuthorizeProfile as SyncJob;
use App\Models\User;
use Illuminate\Console\Command;

class SyncAuthorizeProfile extends Command
{
    /** @var string */
    protected $signature = 'authorize:sync-profile {lookup}';

    /** @var string */
    protected $description = 'A console command to sync Authorize.net customer profile for a user';

    public function handle(): int
    {
        $user = $this->getUser();

        $this->line(sprintf('Syncing Authorize.net profile data for <info>%s</info>', $user->email));

        dispatch(new SyncJob($user));

        return Command::SUCCESS;
    }

    private function getUser(): ?User
    {
        return User::query()
            ->where('id', $this->argument('lookup'))
            ->orWhere('email', $this->argument('lookup'))
            ->first();
    }
}
