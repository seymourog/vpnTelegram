<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ServiceVpn extends Model
{
    use HasFactory;

    protected $fillable = ['name', 'address', 'privateKey', 'publicKey', 'preSharedKey', 'enabled',];
}
