<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use App\RaidLocation;

class Raid extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = ['doc_number', 'start_date', 'end_date', 'description'];

    /**
     * The attributes that are hidden from returning json.
     *
     * @var array
     */
    protected $hidden = ['user_id'];

    /**
     * A raid belongs to a user
     *
     * @return BelongsTo
     */
    public function user()
    {
      return $this->belongsTo(User::class);
    }

    /**
     * A raid has many locations.
     *
     * @return HasMany
     */
    public function locations()
    {
	    return $this->hasMany(RaidLocation::class);
    }

    /**
     * Scope a query to only include popular users.
     *
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeWithLocations($query)
    {
        return $query->with('locations');
    }
}
