<?php

namespace App\Console\Commands;

use App\Models\User;
use Illuminate\Console\Command;

class SyncSalesforceData extends Command
{
    /** @var string */
    protected $signature = 'salesforce:sync-data {lookup}';

    /** @var string */
    protected $description = 'A console command to sync Salesforce data for a user';

    public function handle(): int
    {
        $user = $this->getUser();

        $this->line(sprintf('Syncing Salesforce data for <info>%s</info>', $user->email));

        dispatch(new \App\Jobs\SyncSalesforceData($user));

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
