<?php

namespace App\Models\Admin;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Promotion extends Model
{
    use HasFactory, HasUuids;

    const RULES_MATCH_OR = 'OR';
    const RULES_MATCH_AND = 'AND';

    const STATUS_ACTIVE = 'ACTIVE';
    const STATUS_INACTIVE = 'INACTIVE';

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'promotions';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'label',
        'date_to',
        'date_from',
        'rules_match',
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
     * Get the promotion rules that belongs to the promotion.
     */
    public function promotionRules(): BelongsToMany
    {
        return $this->belongsToMany(PromotionRule::class, 'promotion_promotion_rule', 'promotion_id', 'promotion_rule_id')
            ->withTimestamps();
    }

    /**
     * Get the promotion rewards that belongs the promotion.
     */
    public function promotionRewards(): BelongsToMany
    {
        return $this->belongsToMany(PromotionReward::class, 'promotion_promotion_reward', 'promotion_id', 'promotion_reward_id')
            ->withTimestamps();
    }

    /**
     * Query promotions with active status
     *
     * @param Builder $query
     * @return void
     */
    public function scopeActive(Builder $query)
    {
        return $query->where('status', self::STATUS_ACTIVE);
    }

    /**
     * Query promotions with inactive status
     *
     * @param Builder $query
     * @return void
     */
    public function scopeInactive(Builder $query)
    {
        return $query->where('status', self::STATUS_INACTIVE);
    }

    /**
     * Query for promotions that are valid today
     *
     * @param Builder $query
     * @return void
     */
    public function scopeValid(Builder $query)
    {
        return $query
            ->where(function ($q) {
                $q->where(function ($q) {
                    $q
                        ->where('date_from', '>=', now())
                        ->where('date_to', '<=', now());
                })
                ->orWhere(function ($q) {
                    // have not yet expired
                    $q
                        ->whereNull('date_from')
                        ->where('date_to', '<=', now());
                })
                ->orWhere(function ($q) {
                    // no expiry
                    $q
                        ->where('date_from', '>=', now())
                        ->whereNull('date_to');
                })
                ->orWhere(function ($q) {
                    //  unlimited
                    $q
                        ->whereNull('date_from')
                        ->whereNull('date_to');
                });
            });
    }

    /**
     * Query promotions that are expired
     *
     * @param Builder $query
     * @return void
     */
    public function scopeExpired(Builder $query)
    {
        return $query
            ->where(function ($q) {
                // past the qualified dates
                $q
                    ->where('date_from', '<', now())
                    ->orWhere('date_to', '>', now());
            });
    }

    /**
     * Query promotions that has "OR" rules match
     *
     * @param Builder $query
     * @return void
     */
    public function scopeRulesMatchOr(Builder $query)
    {
        return $query->where('rules_match', Promotion::RULES_MATCH_OR);
    }

    /**
     * Query promotions that has "AND" rules match
     *
     * @param Builder $query
     * @return void
     */
    public function scopeRulesMatchAnd(Builder $query)
    {
        return $query->where('rules_match', Promotion::RULES_MATCH_AND);
    }
}
