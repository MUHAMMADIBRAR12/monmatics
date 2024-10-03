<?php

namespace App\Models\Sales;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SaleOrder extends Model
{
    use HasFactory;
    
    protected $table = 'sal_sale_orders';
    public $incrementing = false;
    protected $keyType = 'string';
    // protected $fillable = [ 'user_id', 'fname', 'lname', 'email', 'phone', 'msg'];
    protected $guarded = [];  
}
