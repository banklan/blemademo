<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class OrderFee extends Model
{
    protected $fillable = ['amount', 'fee'];
}
