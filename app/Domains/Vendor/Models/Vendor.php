<?php

namespace App\Domains\Vendor\Models;

use Illuminate\Database\Eloquent\Model;

class Vendor extends Model
{
    protected $fillable = ['name', 'type', 'score', 'contact_person', 'email'];
}
