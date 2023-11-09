<?php

namespace App\Models;

use Illuminate\Support\Arr;
use Illuminate\Support\Carbon;
use App\Models\Admin\ProductGroup;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Factories\HasFactory;

/**
 * @property float $annual_property_taxes
 * @property float $annual_tax_hoa_amount_additional_fee
 * @property string $apn
 * @property ?float $cash_price_current
 * @property ?float $cash_sale_price
 * @property string $county
 * @property ?float $down_payment
 * @property string $google_maps_url
 * @property float $hoa_poa_annual_fee
 * @property float $latitude
 * @property float $longitude
 * @property string $name
 * @property float $payment_1
 * @property ?float $payment_2
 * @property ?float $payment_3
 * @property bool $power_connection
 * @property string $property_id
 * @property ?float $property_list_price
 * @property bool $sewer_connection
 * @property string $state
 * @property string $status
 * @property float $term_1
 * @property ?float $term_2
 * @property ?float $term_3
 * @property bool $water_connection
 */
class Property extends Model
{
    use HasFactory;

    protected $table = 'properties';
    protected $guarded = [];
    protected $casts = [
        'annual_property_taxes' => 'float',
        'cash_sale_price' => 'float',
        'corner_coordinates' => 'array',
        'hoa_poa_annual_fees' => 'float',
        'latitude' => 'float',
        'longitude' => 'float',
        'payment_1' => 'float',
        'payment_2' => 'float',
        'payment_3' => 'float',
        'cash_price_current' => 'float',
        'property_list_price' => 'float',
    ];
    
    /**
     * Attributes that are hidden by default in serialized JSON
     *
     * @var array
     */
    protected $hidden = [
        'pivot'
    ];

    public const STATUS_AVAILABLE = 'Available - Website';
    public const STATUS_SOLD = 'Sold - Terms';
    public const STATUS_SOLD_CASH = 'Sold - Cash';
    public const STATUS_SOLD_MLS_TITLE_SALE = 'Sold - MLS Title Sale';
    public const STATUS_SOLD_PAID_OFF_TERMS = 'Sold - Paid Off Terms';
    public const STATUS_SOLD_WHOLESALE = 'Sold - Wholesale';

    public const STATUS_PENDING_WHOLESALE = 'Pending - Wholesale';
    public const STATUS_PENDING_VA_RESEARCH = 'Pending - VA Research';
    public const STATUS_PENDING_TITLE_SALE = 'Pending - Title Sale';
    public const STATUS_PENDING_TERMS_SALE = 'Pending - Terms Sale';
    public const STATUS_PENDING_READY_FOR_TITLE_DESCRIPTION = 'Pending - Ready for Title/Description';
    public const STATUS_PENDING_PRICING_NEEDED = 'Pending - Pricing Needed';
    public const STATUS_PENDING_MLS_TITLE_SALE = 'Pending - MLS Title Sale';
    public const STATUS_PENDING_FINAL_APPROVAL = 'Pending - Final Approval';
    public const STATUS_PENDING_CASH_PRICE_CHANGED = 'Pending - Cash Price Changed';

    public function getGoogleMapsUrlAttribute(): string
    {
        $query = http_build_query([
            'size' => '600x400',
            'location' => "{$this->getAttribute('latitude')},{$this->getAttribute('longitude')}",
            'fov' => 80,
            'heading' => 70,
            'pitch' => 0,
            'key' => config('google-maps.api_key'),
        ]);

        return 'https://maps.googleapis.com/maps/api/streetview?' . $query;
    }

    public function isDiscounted(): bool
    {
        return ($this->cash_sale_price ?? PHP_INT_MAX) < $this->property_list_price;
    }

    public function getDiscount(): float
    {
        return $this->property_list_price - ($this->cash_sale_price ?? $this->property_list_price);
    }

    public function images(): HasMany
    {
        return $this->hasMany(PropertyImage::class, 'property_id', 'id');
    }

    public static function findByApn(string $apn): ?self
    {
        return Property::query()->where('apn', $apn)->with(['images'])->firstOrFail();
    }

    public function getOriginalPriceAttribute(): float
    {
        $payments = array_filter($this->only(['payment_3', 'payment_2', 'payment_1']));
        $terms = array_filter($this->only(['term_3', 'term_2', 'term_1']));

        return Arr::first($payments) * Arr::first($terms);
    }

    public function getDiscountAmountAttribute(): float
    {
        return abs($this->getOriginalPriceAttribute() - $this->getAttribute('cash_price_current'));
    }

    public function getCashTotalAttribute(): float
    {
        return $this->getAttribute('cash_price_current') + config('discount.document_fee');
    }

    public function getEscrowAndFees(): float
    {
        $total = $this->annual_property_taxes + $this->hoa_poa_annual_fee + $this->annual_tax_hoa_amount_additional_fee;

        return round($total / 12, 2);
    }

    public function getDownPayment(): float
    {
        return round($this->down_payment ?? 0, 2);
    }

    public function getDocumentFee(): int
    {
        return config('discount.document_fee');
    }

    public function isAvailable(): bool
    {
        return $this->status === self::STATUS_AVAILABLE;
    }

    public function isSold(): bool
    {
        return in_array($this->status, [
            self::STATUS_SOLD,
            self::STATUS_SOLD_CASH,
            self::STATUS_SOLD_MLS_TITLE_SALE,
            self::STATUS_SOLD_PAID_OFF_TERMS,
            self::STATUS_SOLD_WHOLESALE,
        ]);
    }

    public function isPending(): bool
    {
        return in_array($this->status, [
            self::STATUS_PENDING_WHOLESALE,
            self::STATUS_PENDING_VA_RESEARCH,
            self::STATUS_PENDING_TITLE_SALE,
            self::STATUS_PENDING_TERMS_SALE,
            self::STATUS_PENDING_READY_FOR_TITLE_DESCRIPTION,
            self::STATUS_PENDING_PRICING_NEEDED,
            self::STATUS_PENDING_MLS_TITLE_SALE,
            self::STATUS_PENDING_FINAL_APPROVAL,
            self::STATUS_PENDING_CASH_PRICE_CHANGED,
        ]);
    }

    public function lastImageUpdate(): Carbon
    {
        return Carbon::parse($this->images()->min('updated_at'));
    }

    /**
     * Get the product groups that the property belongs to
     *
     * @return void
     */
    public function productGroups()
    {
        return $this->belongsToMany(ProductGroup::class)
            ->withTimestamps();
    } 
}
