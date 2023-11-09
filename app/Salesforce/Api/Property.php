<?php

namespace App\Salesforce\Api;

use Illuminate\Support\Arr;
use Illuminate\Support\Collection;
use stdClass;

class Property
{
    protected array $data;

    public function __construct(array $data)
    {
        $this->data = $data;
    }

    public function toArray(): array
    {
        return $this->data;
    }

    public function getAcreage(): ?float
    {
        return $this->getField('Acreage') ?? 0;
    }

    public function getAfterZoningText(): ?string
    {
        return $this->getField('After_Zoning_Text');
    }

    public function getAnnualTaxAmount(): ?float
    {
        return $this->getField('Annual_Tax_Amount') ?? 0;
    }

    public function getAnnualTaxHoaAmountAdditionalFee(): ?float
    {
        return $this->getField('Annual_Tax_HOA_Amount_Add_l_Fee') ?? 0;
    }

    public function getApn(): string
    {
        return $this->getField('APN') ?? '';
    }

    public function getAuditRoadAccess()
    {
        return $this->getField('Audit_Road_Access');
    }

    public function getCashSalePrice(): ?float
    {
        return $this->getField('Cash_Sale_Price');
    }

    public function getCashPriceCurrent(): ?float
    {
        return $this->getField('Cash_Price_Current');
    }

    public function getCornerCoordinates(): array
    {
        if (! $coordinates = $this->getField('GPS_Corner_Coordinates')) {
            return [];
        }

        return collect(explode(PHP_EOL, $coordinates))
            ->map(fn(string $line) => trim($line))
            ->map(fn(string $coordinate) => collect(explode(",", $coordinate))
            ->pipe(
                fn(Collection $coordinates) => (object) [
                    'lat' => (float) $coordinates->get(0),
                    'lng' => (float) $coordinates->get(1)
                ])
            )
            ->toArray();
    }

    public function getCity(): ?string
    {
        return $this->getField('City');
    }

    public function getCounty(): ?string
    {
        return $this->getField('County');
    }

    public function getCtaText(): ?string
    {
        return $this->getField('CTA_Text');
    }

    public function getDescription(): ?string
    {
        return $this->getField('Property_Description');
    }

    public function getDownPayment(): ?float
    {
        return $this->getField('Down');
    }

    public function getElevation(): ?string
    {
        return $this->getField('Elevation');
    }

    public function getHoaPoaAnnualFee(): ?float
    {
        return $this->getField('Annual_HOA_Fees') ?? 0;
    }

    public function getId(): string
    {
        return $this->data['Id'];
    }

    public function getLatitude(): ?float
    {
        if (! $latitude = $this->getCoordinates()->latitude ?? null) {
            return null;
        }

        if ($latitude < -90 || $latitude > 90) {
            return null;
        }

        return $latitude;
    }

    public function getLegalDescription(): ?string
    {
        return $this->getField('Legal_Description');
    }

    public function getLongitude(): ?float
    {
        return $this->getCoordinates()->longitude ?? null;
    }

    public function getOriginalCashPrice(): ?float
    {
        return $this->getField('Original_Cash_Price');
    }

    public function getName(): string
    {
        return $this->data['Name'];
    }

    public function getNewDescription(): ?string
    {
        return $this->getField('New_Property_Description');
    }

    public function getPayment(int $number): ?float
    {
        $payments = array_unique(
            array_filter([
                $this->data['Payment_1__c'],
                $this->data['Payment_2__c'],
                $this->data['Payment_3__c']
            ])
        );
        sort($payments, SORT_REGULAR);

        return Arr::get($payments, $number - 1);
    }

    public function getPowerConnection(): ?string
    {
        return $this->getField('Power_Connection');
    }

    public function getPropertyListPrice(): float
    {
        return $this->getField('Property_List_Price') ?? 0.00;
    }

    public function getRoadAccess(): bool
    {
        return $this->getField('Road_Access');
    }

    public function getStatus(): string
    {
        return $this->getField('Merged_Status') ?? '';
    }

    public function getStreet(): ?string
    {
        return $this->getField('Street');
    }

    public function getState(): ?string
    {
        return $this->getField('State');
    }

    public function getSewerConnection(): ?string
    {
        return $this->getField('Sewer_Connection');
    }

    public function getTerrain(): ?string
    {
        return $this->getField('Terrain');
    }

    public function getTerm(int $number)
    {
        $terms = array_unique(
            array_filter(
                array_map(fn($term) => (int) $term, [
                    $this->data['Term_1__c'],
                    $this->data['Term_2__c'],
                    $this->data['Term_3__c'],
                ])
            )
        );
        usort($terms, fn($a, $b) => $b <=> $a);

        return Arr::get($terms, $number - 1);
    }

    public function getTimeLimit(): ?string
    {
        return $this->getField('Time_Limit');
    }

    public function getTitle(): ?string
    {
        return $this->getField('New_Property_Title');
    }

    public function getUsage(): ?string
    {
        return $this->getField('Property_Usage');
    }

    public function getVideoTourUrl(): ?string
    {
        $matches = [];

        preg_match(
            '/(http|ftp|https):\/\/([\w_-]+(?:(?:\.[\w_-]+)+))([\w.,@?^=%&:\/~+#-]*[\w@?^=%&\/~+#-])/',
            $this->getField('Property_Video_Tour'),
            $matches
        );

        return ! empty($matches) ? $matches[0] : null;
    }

    public function getWaterConnection(): ?string
    {
        return $this->getField('Water_Connection');
    }

    public function getZipCode(): ?string
    {
        return $this->getField('Zip_Code');
    }

    public function getZoning(): ?string
    {
        return $this->getField('Zoning') ?? '';
    }

    public function getZoningHeadline(): ?string
    {
        return $this->getField('Zoning_Headline');
    }

    public function getZoningItem(int $number): ?string
    {
        return $this->getField(sprintf('Zone_Item_%s', $number));
    }

    protected function getConnection(string $key): string
    {
        return $this->data[$key];
    }

    protected function getField(string $key)
    {
        return Arr::get($this->data, "{$key}__c");
    }

    private function getCoordinates(): ?stdClass
    {
        if (is_null($this->data['GPS_Coordinates__c'])) {
            return null;
        }

        $parts = collect(explode(",", $this->data['GPS_Coordinates__c']))
            ->map(fn(string $part) => trim($part))
            ->toArray();

        if (!isset($parts[1])) {
            $parts = explode(" ", $parts[0]);
        }

        if (!isset($parts[1])) {
            return null;
        }

        return (object)[
            'latitude' => (float)$parts[0],
            'longitude' => (float)$parts[1],
        ];
    }
}