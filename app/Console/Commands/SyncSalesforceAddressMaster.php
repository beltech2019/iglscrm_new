<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Services\SalesforceService;

class SyncSalesforceAddressMaster extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'salesforce:sync-address-master';

    /**
     * The console command description.
     *
     * @var string
     */
      protected $description = 'Sync Salesforce Address Master to local database';

    /**
     * Execute the console command.
     */
    public function handle(SalesforceService $salesforce)
    {
        try {

            $this->info('Starting Salesforce Address Master sync...');

            $count = $salesforce->syncAddressMaster();

            $this->info(
                "Successfully synced {$count} Address Master records."
            );

            return Command::SUCCESS;

        } catch (\Exception $e) {

            $this->error(
                'Address Master sync failed: '
                . $e->getMessage()
            );

            return Command::FAILURE;
        }
    }
}
