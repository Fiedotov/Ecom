<?php

namespace App\Models\Admin;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class PromotionRule extends Model
{
    use HasFactory, HasUuids;

    const TYPE_COUPON = 'coupon';
    const TYPE_PRODUCT_IN_GROUP = 'product_in_group';

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'promotion_rules';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'type',
        'config',
    ];

    /**
     * Attributes that are hidden by default in serialized JSON
     *
     * @var array
     */
    protected $hidden = [
        'pivot'
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'config' => 'array',
    ];

    /**
     * Get the promotions that belong the promotion rule.
     */
    public function promotions(): BelongsToMany
    {
        return $this->belongsToMany(Promotion::class, 'promotion_promotion_rule', 'promotion_rule_id', 'promotion_id')
            ->withTimestamps();
    }

    /**
     * Get the product groups that belong the promotion rule.
     */
    public function productGroups(): BelongsToMany
    {
        return $this->belongsToMany(ProductGroup::class, 'product_group_promotion_rule', 'promotion_rule_id', 'product_group_id')
            ->withTimestamps();
    }

    /**
     * Search for promotion rule that has the coupon code
     *
     * @param Builder $query
     * @param string $coupon_code
     * @return void
     */
    public function scopeSearchCouponCode(Builder $query, string $coupon_code)
    {
        return $query
            ->typeCoupon()
            ->whereRaw("JSON_EXTRACT(config, '$.coupon_code') = '$coupon_code'");
    }

    /**
     * Query promotion rules that are of type "coupon"
     *
     * @param Builder $query
     * @return void
     */
    public function scopeTypeCoupon(Builder $query)
    {
        return $query->where('type', self::TYPE_COUPON);
    }

    /**
     * Query promotion rules that are of type "product_in_group"
     *
     * @param Builder $query
     * @return void
     */
    public function scopeTypeProductInGroup(Builder $query)
    {
        return $query->where('type', self::TYPE_PRODUCT_IN_GROUP);
    }
}