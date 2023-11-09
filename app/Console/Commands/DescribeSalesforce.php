<?php

namespace App\Console\Commands;

use App\Salesforce\Api\Client;
use App\Salesforce\Api\HasSalesforceToken;
use Illuminate\Console\Command;

class DescribeSalesforce extends Command
{
    use HasSalesforceToken;

    /**@var string */
    protected $signature = 'salesforce:describe {entity}';

    protected array $entities = [
        'Account',
        'Contact',
        'Contract',
        'Property__c',
        'Lead',
        'Payment__c',
    ];

    /**@var string */
    protected $description = 'Displays description of an entity in Salesforce';

    private Client $salesforce;

    public function __construct(Client $salesforce)
    {
        parent::__construct();
        $this->salesforce = $salesforce;
    }

    public function handle(): int
    {
        if (! in_array($this->argument('entity'), $this->entities)) {
            $this->error(sprintf('Invalid entity - <info>%s</info>', $this->argument('entity')));
            return 1;
        }

        $this->line(sprintf('Describing Salesforce Entity - <info>%s</info>', $this->argument('entity')));

        $method = "get{$this->argument('entity')}Description";

        $this->table(['Name', 'Type', 'Label'],
            $this->salesforce->$method()
                ->map(fn(array $field) => [
                    'Name' => $field['name'],
                    'Type' => $field['type'],
                    'Label' => $field['label'],
                ])
        );

        return 0;
    }
}
