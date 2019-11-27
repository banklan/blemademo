<?php

namespace App;

use Carbon\Carbon;
use Illuminate\Notifications\Notifiable;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Foundation\Auth\User as Authenticatable;
use App\Notifications\UserPasswordResetNotification;

class User extends Authenticatable
{
    use Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 'email', 'password', 'phone', 'alt_phone', 'address', 'location_id', 'status'
    ];

    protected $with = ['location'];

    protected $appends = ['slug', 'unread_messages', 'user_status', 'join_date'];


    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token',
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    public function location()
    {
        return $this->belongsTo('App\Location');
    }

    public function message()
    {
        return $this->hasMany('App\Message', 'sender_id');
    }

    public function order_summary()
    {
        return $this->hasMany('App\OrderSummary');
    }

    public function getSlugAttribute($value)
    {
        $slug = str_slug($this->name);

        return $slug;
    }

    public function getUnreadMessagesAttribute($value)
    {
        return $this->message()->where('status', 0)->count();
    }

    public function getUserStatusAttribute($value)
    {
        if($this->status == 1){
            return 'Enabled';
        }
        return 'Disabled';
    }

    public function getJoinDateAttribute($value)
    {
        $date = Carbon::parse($this->created_at)->toFormattedDateString();
        return $date;
    }

    public function charges()
    {
        return $this->hasMany('App\Charges');
    }

    public function special_order()
    {
        return $this->hasMany('App\SpecialOrder');
    }

    public function payment()
    {
        return $this->hasMany('App\Payment');
    }

    public function sendPasswordResetNotification($token)
    {
        $this->notify(new UserPasswordResetNotification($token));
    }
}
