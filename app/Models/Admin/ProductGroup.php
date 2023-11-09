<?php

namespace App\Models\Admin;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use App\Jobs\RemoveProductGroupIdInPromotionRuleConfig;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class ProductGroup extends Model
{
    use HasFactory;

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'product_groups';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'label',
    ];

    /**
     * Attributes that are hidden by default in serialized JSON
     *
     * @var array
     */
    protected $hidden = [
        'pivot'
    ];


    public static function boot()
    {
        parent::boot();

        self::deleting(function (self $product_group) {
            $promotion_rules = $product_group
                ->promotionRules()
                ->get();
            foreach ($promotion_rules as $promotion_rule) {
                $config = $promotion_rule->config;
                $new_config = array_filter($config['product_group_ids'], function ($value) use ($product_group) {
                    return $value !== $product_group->id;
                });
                $config['product_group_ids'] = $new_config;
                $promotion_rule->config = $config;
                dd($promotion_rule->config);
                // $promotion_rule->save();
            }
            // find all promotion rules related to the product group
            // then update the promotion rule config by removing the id of the product group in it
            // process it in the job
            // RemoveProductGroupIdInPromotionRuleConfig::dispatch($product_group);
        });
    }

    /**
     * Get the properties that belongs to the product group.
     */
    public function properties(): BelongsToMany
    {
        return $this->belongsToMany(\App\Models\Property::class, 'product_group_property', 'product_group_id', 'property_id')
            ->withTimestamps();
    }

    /**
     * Get the promotion rules that the product group belongs to.
     */
    public function promotionRules(): BelongsToMany
    {
        return $this->belongsToMany(PromotionRule::class, 'product_group_promotion_rule', 'product_group_id', 'promotion_rule_id')
            ->withTimestamps();
    }
}
