<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Admin\Auth\AuthController;
use App\Http\Controllers\Admin\Api\ProductGroupsController;
use App\Http\Controllers\Admin\Api\AdminUsersController;
use App\Http\Controllers\Admin\Api\PromotionsController;
use App\Http\Controllers\Admin\Api\PromotionRulesController;
use App\Http\Controllers\Admin\Api\PromotionRewardsController;

Route::prefix('admin')->group(function () {
    // Auth / Login
    Route::post('/auth/login', [AuthController::class, 'login']); 
    Route::delete('/auth/logout', [AuthController::class, 'logout']);
    Route::get('/auth/profile', [AuthController::class, 'profile']);

    Route::middleware('auth:api-admin')->group(function() {
        // Admin Users
        Route::get('/users', [AdminUsersController::class, 'index']);
        Route::get('/users/{admin_user}', [AdminUsersController::class, 'show']);
        Route::post('/users', [AdminUsersController::class, 'store']);
        Route::patch('/users/{admin_user}', [AdminUsersController::class, 'update']);
        Route::delete('/users/{admin_user}', [AdminUsersController::class, 'destroy']);
    });

    // Product Groups
    Route::get('product-groups', [ProductGroupsController::class, 'index']);
    Route::get('product-groups/{product_group}', [ProductGroupsController::class, 'show']);
    Route::post('product-groups', [ProductGroupsController::class, 'store']);
    Route::patch('product-groups/{product_group}', [ProductGroupsController::class, 'update']);
    Route::delete('product-groups/{product_group}', [ProductGroupsController::class, 'destroy']);

    // Promotion Rules
    Route::get('promotion-rules', [PromotionRulesController::class, 'index']);
    Route::get('promotion-rules/{promotion_rule}', [PromotionRulesController::class, 'show']);
    Route::post('promotion-rules', [PromotionRulesController::class, 'store']);
    Route::patch('promotion-rules/{promotion_rule}', [PromotionRulesController::class, 'update']);
    Route::delete('promotion-rules/{promotion_rule}', [PromotionRulesController::class, 'destroy']);

    // Promotion Rewards
    Route::get('promotion-rewards', [PromotionRewardsController::class, 'index']);
    Route::get('promotion-rewards/{promotion_reward}', [PromotionRewardsController::class, 'show']);
    Route::post('promotion-rewards', [PromotionRewardsController::class, 'store']);
    Route::patch('promotion-rewards/{promotion_reward}', [PromotionRewardsController::class, 'update']);
    Route::delete('promotion-rewards/{promotion_reward}', [PromotionRewardsController::class, 'destroy']);

    // Promotions
    Route::get('promotions', [PromotionsController::class, 'index']);
    Route::get('promotions/{promotion}', [PromotionsController::class, 'show']);
    Route::post('promotions', [PromotionsController::class, 'store']);
    Route::patch('promotions/{promotion}', [PromotionsController::class, 'update']);
    Route::delete('promotions/{promotion}', [PromotionsController::class, 'destroy']);
});