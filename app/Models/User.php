<?php
namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Cashier\Billable;
use Laravel\Sanctum\HasApiTokens;
use Spatie\Permission\Traits\HasRoles;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable, HasRoles, SoftDeletes, Billable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'role',
        'email',
        'password',
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
        'password'          => 'hashed',
    ];

    public function currentSubscription()
    {
        $subscription = $this->subscription('default');
        if($subscription){
        $price_id     = $subscription->stripe_price;
        $price        = Price::with('product')->where('stripe_id', $price_id)->first();

        $endDate = Carbon::createFromTimestamp($subscription->asStripeSubscription()->current_period_end);
        $endDate = $endDate->toFormattedDateString();

        return [
            'subscription' => $subscription,
            'price' => $price,
            'product' => $price->product,
            'endDate' => $endDate,
        ];
    }
    return '';
    }
}
