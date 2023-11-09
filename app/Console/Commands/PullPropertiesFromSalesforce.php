<?php

namespace App\Console\Commands;

use App\Jobs\DownloadPropertyImages;
use App\Models\Property;
use App\Salesforce\Api\Client;
use App\Salesforce\Api\HasSalesforceToken;
use App\Salesforce\Api\Property as SalesforceProperty;
use Exception;
use Illuminate\Console\Command;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;

class PullPropertiesFromSalesforce extends Command
{
    use HasSalesforceToken;

    /**@var string */
    protected $signature = 'salesforce:pull-properties {--images}';

    /**@var string */
    protected $description = 'Pulls properties data from Salesforce and persists to database';

    private Client $salesforce;

    public function __construct(Client $salesforce)
    {
        parent::__construct();
        $this->salesforce = $salesforce;
    }

    public function handle(): int
    {
        try {
            $properties = $this->salesforce->getProperties();
        } catch (Exception $e) {
            $this->error($e->getMessage());

            return 1;
        }

        $this->line("Found <info>{$properties->count()}</info> properties...");

        $properties = $properties->map(
            function (SalesforceProperty $property) {
                $this->line("Updating property <info>{$property->getId()}</info>");

                return Property::query()->updateOrCreate(['property_id' => $property->getId()], [
                    'name' => $property->getName(),
                    'status' => $property->getStatus(),
                    'acreage' => $property->getAcreage(),
                    'zoning' => $property->getZoning(),
                    'property_description' => $property->getDescription(),
                    'apn' => $property->getApn(),
                    'water_connection' => $property->getWaterConnection(),
                    'sewer_connection' => $property->getSewerConnection(),
                    'power_connection' => $property->getPowerConnection(),
                    'annual_property_taxes' => $property->getAnnualTaxAmount(),
                    'hoa_poa_annual_fee' => $property->getHoaPoaAnnualFee(),
                    'annual_tax_hoa_amount_additional_fee' => $property->getAnnualTaxHoaAmountAdditionalFee(),
                    'road_access' => $property->getRoadAccess(),

                    'street' => $property->getStreet(),
                    'city' => $property->getCity(),
                    'county' => $property->getCounty(),
                    'state' => $property->getState(),
                    'zip_code' => $property->getZipCode(),
                    'latitude' => $property->getLatitude(),
                    'longitude' => $property->getLongitude(),
                    'corner_coordinates' => $property->getCornerCoordinates(),

                    'payment_1' => $property->getPayment(1),
                    'payment_2' => $property->getPayment(2),
                    'payment_3' => $property->getPayment(3),
                    'term_1' => $property->getTerm(1),
                    'term_2' => $property->getTerm(2),
                    'term_3' => $property->getTerm(3),
                    'down_payment' => $property->getDownPayment(),
                    'elevation' => $property->getElevation(),
                    'terrain' => $property->getTerrain(),
                    'time_limit' => $property->getTimeLimit(),

                    'cash_price_current' => $property->getCashPriceCurrent(),
                    'cash_sale_price' => $property->getCashSalePrice(),
                    'original_cash_price' => $property->getOriginalCashPrice(),
                    'property_list_price' => $property->getPropertyListPrice(),
                    'legal_description' => $property->getLegalDescription(),

                    'usage' => $property->getUsage(),
                    'description' => $property->getNewDescription(),
                    'title' => $property->getTitle(),
                    'video_tour_url' => $property->getVideoTourUrl(),
                    'cta_text' => trim($property->getCtaText()),
                    'after_zoning_text' => $property->getAfterZoningText(),
                    'zone_item_4' => $property->getZoningItem(1),
                    'zone_item_3' => $property->getZoningItem(2),
                    'zone_item_2' => $property->getZoningItem(3),
                    'zone_item_1' => $property->getZoningItem(4),
                    'zoning_headline' => $property->getZoningHeadline(),
                ]);
            }
        );

        $this->line('Removing missing properties...');
        $this->removeProperties($properties);

        if ($this->option('images')) {
            $this->line('Adding property images...');
            $this->addImages($properties);
        }

        return 0;
    }

    protected function addImages(Collection $properties): void
    {
        $properties
            ->filter(fn (Property $property) => str_contains($property->status, 'Available'))
            ->values()
            ->each(function (Property $property) {
                DownloadPropertyImages::dispatch($property);
            });
    }

    protected function removeProperties(Collection $properties): void
    {
        $propertyIds = DB::table('properties')
            ->whereNotIn('property_id', $properties->pluck('property_id'))
            ->pluck('id');

        Property::query()->whereIn('id', $propertyIds)->update(['status' => 'Unavailable']);
    }
}
