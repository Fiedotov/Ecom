<?php

namespace App\Salesforce\Api;

use App\Models\PropertyInquiry;
use App\Salesforce\Actions\StoreAccount;
use App\Salesforce\Actions\StoreContact;
use App\Salesforce\Actions\StoreContract;
use App\Salesforce\Actions\UpdateProperty;
use BadMethodCallException;
use Carbon\Carbon;
use Exception;
use Illuminate\Http\Client\Response;
use Illuminate\Support\Arr;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;
use stdClass;

/**
 * @method Collection getAccountDescription()
 * @method Collection getContactDescription()
 * @method Collection getContractDescription()
 * @method Collection getLeadDescription()
 * @method Collection getPropertyDescription()
 */
class Client
{
    use HasSalesforceToken {
        getSalesforceToken as getToken;
    }

    protected array $propertyFields = [
        'Acreage__c',
        'After_Zoning_Text__c',
        'Annual_HOA_Fees__c',
        'Annual_Tax_Amount__c',
        'Annual_Tax_HOA_Amount_Add_l_Fee__c',
        'APN__c',
        'Audit_Road_Access__c',
        'Cash_Price_Current__c',
        'Cash_Sale_Price__c',
        'City__c',
        'County__c',
        'CTA_Text__c',
        'Down_Payment__c',
        'Down__c',
        'Elevation__c',
        'GPS_Coordinates__c',
        'GPS_Corner_Coordinates__c',
        'Id',
        'Images_Link__c',
        'Legal_Description__c',
        'Merged_Status__c',
        'Name',
        'New_Property_Description__c',
        'New_Property_Title__c',
        'Original_Cash_Price__c',
        'Payment_1__c',
        'Payment_2__c',
        'Payment_3__c',
        'Power_Connection__C',
        'Property_Description__c',
        'Property_List_Price__c',
        'Property_Status__c',
        'Property_Usage__c',
        'Property_Video_Tour__c',
        'Road_Access__c',
        'Sewer_Connection__C',
        'State__c',
        'Street__c',
        'Term_1__c',
        'Term_2__c',
        'Term_3__c',
        'Terrain__c',
        'Time_Limit__c',
        'Water_Connection__c',
        'Zip_Code__c',
        'Zone_Item_1__c',
        'Zone_Item_2__c',
        'Zone_Item_3__c',
        'Zone_Item_4__c',
        'Zoning__c',
        'Zoning_Headline__c',
    ];

    public function createLead(PropertyInquiry $inquiry): array
    {
        $response = Http::withToken($this->getToken())
            ->post(
                $this->url('/data/v53.0/sobjects/Lead'),
                $inquiry->toApiPayload()
            );

        return $response->json();
    }

    public function getAccountByAuthorizeNetPaymentProfileId(string $profileId): ?Account
    {
        $response = $this->queryAll(sprintf("SELECT Id FROM Account Where Payment_Profile_Id__c = '%s'", $profileId));

        if (!$accountId = Arr::get($response->json('records'), '0.Id')) {
            return null;
        }

        $response = Http::withToken($this->getToken())->get($this->url("/data/v53.0/sobjects/Account/{$accountId}"));

        return Account::fromPayload($response->json());
    }

    public function getAccountByEmail(string $email): ?Account
    {
        $response = $this->queryAll(sprintf("SELECT Id FROM Account Where EMAIL__c = '%s'", $email));

        if (!$accountId = Arr::get($response->json('records'), '0.Id')) {
            return null;
        }

        $response = Http::withToken($this->getToken())->get($this->url("/data/v53.0/sobjects/Account/{$accountId}"));

        return Account::fromPayload($response->json());
    }

    public function getContactByEmail(string $email): ?Contact
    {
        $response = $this->queryAll(sprintf("SELECT Id FROM Contact Where Email = '%s'", $email));
        $contactId = Arr::get($response->json('records'), '0.Id');

        if (!$contactId) {
            return null;
        }

        $response = Http::withToken($this->getToken())->get($this->url("/data/v53.0/sobjects/Contact/{$contactId}"));

        return Contact::fromPayload($response->json());
    }

    public function getContractsByEmail(string $email): Collection
    {
        $response = $this->queryAll(sprintf("SELECT Id, EMAIL__c FROM Contract Where EMAIL__c = '%s'", $email));

        return collect($response->json('records'))
            ->map(function (array $contract) {
                $url = Str::after(Arr::get($contract, 'attributes.url'), '/services');
                $response = Http::withToken($this->getToken())->get($this->url($url));

                if ($response->json('0.errorCode')) {
                    return null;
                }

                return Contract::fromPayload($response->json());
            })
            ->filter()
            ->values();
    }

    private function getNextRecords(string $url): array
    {
        $response = $this->queryByUrl($url);
        $records = $response->json('records');

        if ($response->json('nextRecordsUrl')) {
            return array_merge($records, $this->getNextRecords($response->json('nextRecordsUrl')));
        }

        return $records;
    }

    public function getPaymentsByContract(string $contractId): Collection
    {
        $fields = implode(',', [
            'Id',
            'Contract__c',
            'Payment_Date__c',
            'Payment_Due_Date__c',
            'Payment_Status__c',
            'Payment_Type__c',
            'Total_Outstanding_Amount_with_Fees__c',
            'Total_Paid_Amount__c',
        ]);

        $response = $this->queryAll(
            sprintf('SELECT %s FROM Payment__c WHERE Contract__c = \'%s\'', $fields, $contractId)
        );

        return Collection::make($response->json('records'));
    }

    public function getProperties(): Collection
    {
        $response = $this->queryAll($this->selectPropertiesQuery());
        $records = $response->json('records');

        if ($response->json('nextRecordsUrl')) {
            $records = array_merge($records, $this->getNextRecords($response->json('nextRecordsUrl')));
        }

        return collect($records)->map(fn(array $record) => new Property($record));
    }

    public function getPropertiesByEmail(string $email): Collection
    {
        $response = Http::withToken($this->getToken())
            ->get(
                $this->url(
                    sprintf(
                        '/data/v53.0/search/?q=FIND {%s} IN ALL FIELDS RETURNING Account (Id, Name), Contact, Contract, Property__c',
                        $email
                    )
                )
            );

        $properties = collect($response->json('searchRecords'))
            ->filter(fn(array $item) => Arr::get($item, 'attributes.type') === 'Property__c')
            ->values();

        return $properties->map(function (array $property) {
            $response = Http::withToken($this->getToken())
                ->get($this->url("/data/v53.0/sobjects/Property__c/{$property['Id']}"));

            return new Property($response->json());
        });
    }

    public function getProperty(string $id): stdClass
    {
        $response = Http::withToken($this->getToken())->get($this->url("/data/v53.0/sobjects/Property__c/$id"));

        return (object)collect($response->json())->sortKeys()->toArray();
    }

    public function getPropertyImages(string $propertyNumber): Collection
    {
        $response = Http::withToken($this->getToken())
            ->post($this->url('/apexrest/getpropertyimages/v1'), ['propertyNumber' => $propertyNumber]);

        return collect($response->json('images'));
    }

    public function storeAccount(StoreAccount $account): Response
    {
        return Http::withToken($this->getToken())
            ->post($this->url('/data/v53.0/sobjects/Account'), $account->toApiPayload());
    }

    public function storeContact(StoreContact $contact): Response
    {
        return Http::withToken($this->getToken())
            ->post($this->url('/apexrest/contactupdate/v1'), $contact->toApiPayload());
    }

    public function storeContract(StoreContract $contract): Response
    {
        Log::info('Storing contract...', $contract->toApiPayload());

        return Http::withToken($this->getToken())
            ->post($this->url('/data/v53.0/sobjects/Contract'), $contract->toApiPayload());
    }

    public function updatePayment(string $paymentId, float $amount): array
    {
        $transaction = Http::withToken($this->getToken())
            ->post($this->url('/data/v53.0/sobjects/Payment_Transaction_Object__c/'), [
                'Payment__c' => $paymentId,
                'Transaction_Amount__c' => $amount,
                'Transaction_Status__c' => 'Success'
            ]);

        if ($transaction->status() !== 201) {
            throw new Exception('Failed to create salesforce transaction');
        }

        $payment = Http::withToken($this->getToken())
            ->patch($this->url(sprintf('/data/v53.0/sobjects/Payment__c/%s', $paymentId)), [
                'Payment_Status__c' => 'Paid',
                'Payment_Date__c' => Carbon::now()->format('Y-m-d'),
            ]);

        if ($payment->status() !== 204) {
            throw new Exception('Failed to update salesforce payment object');
        }

        return [
            'transaction' => ['response' => $transaction->json(), 'status' => $transaction->status()],
            'payment' => ['response' => $payment->json(), 'status' => $payment->status()],
        ];
    }

    public function updateProperty(UpdateProperty $update, string $propertyId): Response
    {
        return Http::withToken($this->getToken())
            ->patch($this->url("/data/v53.0/sobjects/Property__c/{$propertyId}"), $update->toApiPayload());
    }

    public function queryAll(string $query): Response
    {
        return Http::withToken($this->getToken())->get($this->url('/data/v53.0/queryAll'), ['q' => $query]);
    }

    public function __call(string $name, array $arguments)
    {
        $entities = ['Account', 'Contact', 'Contract', 'Property__c', 'Lead', 'Payment__c'];
        $entity = str_replace(['get', 'Description'], '', $name);

        if (!in_array($entity, $entities)) {
            throw new BadMethodCallException(sprintf('Unknown method %s', $name));
        }

        return $this->getObjectDescription($entity);
    }

    private function getObjectDescription(string $key): Collection
    {
        $response = Http::withToken($this->getToken())->get($this->url("/data/v53.0/sobjects/$key/describe"));

        return collect($response->json('fields'))->sortBy(fn(array $field) => $field['name']);
    }

    private function queryByUrl(string $queryUrl): Response
    {
        $url = Str::replace('/services', '', config('salesforce.services_base_url')) . $queryUrl;

        return Http::withToken($this->getToken())->get($url);
    }

    private function selectPropertiesQuery(): string
    {
        $fields = implode(', ', $this->propertyFields);

        return sprintf('SELECT %s from Property__c', $fields);
    }

    private function url(string $relativeUrl): string
    {
        return config('salesforce.services_base_url') . $relativeUrl;
    }
}