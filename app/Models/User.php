<?php

namespace App\Models;

use App\AuthorizeNet\CustomerProfile;
use App\Notifications\ResetPassword;
use App\Salesforce\Contract;
use App\Salesforce\Payment;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasManyThrough;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

/**
 * @property ?string $authorize_net_profile_id
 */
class User extends Authenticatable
{
    use HasApiTokens;
    use HasFactory;
    use Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'email',
        'email_verified_at',
        'password',
        'sf_account',
        'sf_contact',
        'sf_contracts',
        'sf_properties',
        'authorize_net_profile_id',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
        'sf_contact' => 'object',
        'sf_account' => 'object',
        'sf_contracts' => 'object',
        'sf_properties' => 'object',
        'tracking' => 'object',
    ];

    public static function findByEmail(string $email): ?static
    {
        return static::query()->where('email', $email)->first();
    }

    public function sendPasswordResetNotification($token): void
    {
        $this->notify(new ResetPassword($token));
    }

    public function contracts(): HasMany
    {
        return $this->hasMany(Contract::class, 'user_id', 'id');
    }

    public function customerProfile(): HasOne
    {
        return $this->hasOne(CustomerProfile::class, 'user_id', 'id');
    }

    public function payments(): HasManyThrough
    {
        return $this->hasManyThrough(Payment::class, Contract::class, 'user_id', 'contract_id', 'id', 'contract_id');
    }
}
