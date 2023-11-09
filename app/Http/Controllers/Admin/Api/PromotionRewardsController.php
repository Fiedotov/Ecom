<?php

namespace App\Http\Controllers\Admin\Api;

use App\Http\Controllers\Controller;
use App\Models\Admin\PromotionReward;
use App\Http\Requests\Admin\PromotionRewardRequest;

class PromotionRewardsController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $promotion_rewards = PromotionReward::simplePaginate(15);

        return response()->json($promotion_rewards);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Admin\PromotionReward  $promotion_reward
     * @return \Illuminate\Http\Response
     */
    public function show(PromotionReward $promotion_reward)
    {
        return response()->json([
            'message' => 'Success',
            'data' => $promotion_reward
        ]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \App\Http\Requests\Admin\PromotionRewardRequest  $request
     * @return \Illuminate\Http\Response
     */
    public function store(PromotionRewardRequest $request)
    {
        $promotion_reward = PromotionReward::create([
            'type' => $request->type,
            'config' => $request->config
        ]);

        return response()->json([
            'message' => 'Promotion successfully added.',
            'data' => $promotion_reward
        ]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  App\Http\Requests\Admin\PromotionRewardRequest  $request
     * @param  \App\Models\Admin\PromotionReward  $promotion_reward
     * @return \Illuminate\Http\Response
     */
    public function update(PromotionRewardRequest $request, PromotionReward $promotion_reward)
    {
        $promotion_reward->type = $request?->type ?? $promotion_reward->type;
        $promotion_reward->config = $request?->config ?? $promotion_reward->config;
        $promotion_reward->save();

        return response()->json([
            'message' => 'Promotion reward successfully updated.',
            'data' => $promotion_reward
        ]);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Admin\PromotionReward  $promotion_reward
     * @return \Illuminate\Http\Response
     */
    public function destroy(PromotionReward $promotion_reward)
    {
        $promotion_reward->delete();

        return response([
            'message' => 'Promotion reward successfully deleted.'
        ]);
    }
}
