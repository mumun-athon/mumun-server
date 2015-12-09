<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class IlegalReport extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = ['name', 'ktp', 'description', 'longitude', 'latitude', 'photo'];
}
