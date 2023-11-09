<?php

namespace App\Models\Admin;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class PromotionReward extends Model
{
    use HasFactory, HasUuids;

    const PRODUCT_DISCOUNT = 'product_discount';
    const DEPOSIT_DISCOUNT = 'deposit_discount';

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'promotion_rewards';

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
     * Get the promotion that belong the promotion reward.
     */
    public function promotions(): BelongsToMany
    {
        return $this->belongsToMany(Promotion::class, 'promotion_promotion_reward', 'promotion_reward_id', 'promotion_id')
            ->withTimestamps();
    }

    /**
     * Query for promotion reward that is type 'product_discount'
     *
     * @param Builder $query
     * @return void
     */
    public function scopeTypeProductDiscount(Builder $query)
    {
        return $query->where('type', self::PRODUCT_DISCOUNT);
    }

    /**
     * Query for promotion reward that is type 'deposit_discount'
     *
     * @param Builder $query
     * @return void
     */
    public function scopeTypeDepositDiscount(Builder $query)
    {
        return $query->where('type', self::DEPOSIT_DISCOUNT);
    }

    /**
     * Search for promotion reward that is qualified for certain payment setup
     *
     * @param Builder $query
     * @param string $pay_setup     e.g. full | monthly
     * @return void
     */
    public function scopeSearchQualifiedForPaymentSetup(Builder $query, string $pay_setup)
    {
        switch ($pay_setup) {
            case 'full':
                return $query->whereRaw("JSON_EXTRACT(config, '$.apply_to_full_price') = true");
            case 'monthly':
                return $query->whereRaw("JSON_EXTRACT(config, '$.apply_to_monthly') = true");
            default:
                return $query;
        }
    }
}