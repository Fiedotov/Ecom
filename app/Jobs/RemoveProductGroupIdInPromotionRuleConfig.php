<?php

namespace App\Jobs;

use App\Models\Admin\ProductGroup;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class RemoveProductGroupIdInPromotionRuleConfig implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    private $promotion_rules;
    private int $product_group_id;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct(ProductGroup $product_group)
    {
        $this->product_group_id = $product_group->id;
        $this->promotion_rules = $product_group
            ->promotionRules()
            ->get();

        // nothing to process
        if ($this->promotion_rules->isEmpty()) {
            return;
        }
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        // update each promotion rule config, removing the product group id
        // that's about to be deleted - in its array of product group ids
        foreach ($this->promotion_rules as $promotion_rule) {
            $config = $promotion_rule->config;
            $new_config = array_filter($config['product_group_ids'], function ($value) {
                return $value !== $this->product_group_id;
            });
            $config['product_group_ids'] = $new_config;
            $promotion_rule->config = $config;
            $promotion_rule->save();
        }
    }
}
