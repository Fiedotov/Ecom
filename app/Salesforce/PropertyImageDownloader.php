<?php

namespace App\Salesforce;

use App\Models\Property;
use Illuminate\Http\Client\Response;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Str;

class PropertyImageDownloader
{
    public function download(Property $property, string $salesforceUrl): void
    {
        $response = Http::get($salesforceUrl);
        $size = $response->handlerStats()['size_download'];
        if ($size < 1000) {
            return;
        }

        $filename = $this->getFilename($response, $property);

        File::put(storage_path('app/public/property-images/' . $filename), $response->body());

        $property->images()->updateOrCreate(['url' => $filename, 'size' => $size, 'salesforce_url' => $salesforceUrl]);
    }

    private function getFilename(Response $response, Property $property): string
    {
        $filename = Str::between($response->header('Content-Disposition'), 'filename="', '"');

        return str_replace('%', '', sprintf('%s-%s', $property->name, $filename));
    }
}