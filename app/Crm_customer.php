<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Crm_customer extends Model
{
    protected $primaryKey = 'id';
    public $incrementing = false;
    protected $keyType = 'string';
}
