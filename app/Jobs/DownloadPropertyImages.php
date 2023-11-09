<?php

namespace App\Jobs;

use App\Models\Property;
use App\Models\PropertyImage;
use App\Salesforce\Api\Client;
use App\Salesforce\PropertyImageDownloader;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Collection;

class DownloadPropertyImages implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    private Property $property;

    public function __construct(Property $property)
    {
        $this->property = $property;
    }

    public function handle(Client $salesforce): void
    {
        $salesforce
            ->getPropertyImages($this->property->name)
            ->pluck('src')
            ->pipe(function (Collection $images) {
                PropertyImage::query()
                    ->where('property_id', $this->property->id)
                    ->whereNotIn('salesforce_url', $images)
                    ->delete();

                return $images;
            })
            ->each(function (string $url) {
                (new PropertyImageDownloader())->download($this->property, $url);
            });
    }
}
