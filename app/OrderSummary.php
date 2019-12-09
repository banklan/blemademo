<?php

namespace App;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;

class OrderSummary extends Model
{
    protected $fillable = ['order_id', 'user_id', 'item_count', 'services_counts', 'value', 'message', 'order_status', 'pymt_mode', 'pymt_status'];

    protected $table = 'order_summaries';

    protected $appends = ['order_date', 'status', 'date', 'time', 'payment_status', 'short_date'];

    protected $with = ['user'];


    public function order()
    {
        return $this->hasMany('App\Order');
    }

    public function user()
    {
        return $this->belongsTo('App\User');
    }

    public function setMessageAttribute($value)
    {
        $this->attributes['message'] = ucfirst($value);
    }

    public function getOrderDateAttribute($value)
    {
        $created = Carbon::parse($this->created_at)->diffForHumans();
        return $created;
    }

    public function getDateAttribute($value)
    {
        $date = Carbon::parse($this->created_at)->toFormattedDateString();
        // $date = Carbon::parse($this->created_at)->toDayDateTimeString();
        return $date;
    }

    public function getShortDateAttribute($value)
    {
        $date = Carbon::parse($this->created_at)->format('d/m/Y');

        return $date;
    }

    public function getTimeAttribute($value)
    {
        $time = Carbon::parse($this->created_at)->format('H:ia');
        return $time;
    }

    public function getStatusAttribute($value)
    {
        if($this->order_status === '1'){
            return 'Order has been received.';
        }

        if($this->order_status === '2'){
            return 'Order is been processed.';
        }

        if($this->order_status === '3'){
            return 'Order is on transit to you.';
        }

        if($this->order_status === '7'){
            return 'Order Delivered.';
        }
    }

    public function getPaymentStatusAttribute($value)
    {
        if($this->pymt_status == 0){
            return 'Payment Not Made Yet';
        }else{
            return 'Paid';
        }
    }
}
