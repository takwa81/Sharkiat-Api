<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Config extends Model
{
    use HasFactory;
    protected $fillable = ['address', 'telephone', 'phone', 'whatsapp', 'instagram', 'facebook', 'twitter', 'close', 'open', 'ads'];

}
