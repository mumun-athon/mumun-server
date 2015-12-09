<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use App\Raid;

class RaidLocation extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = ['longitude', 'latitude'];

    /**
     * Distance value in meters.
     *
     * @var int
     */
    private static $distance = 50;

    /**
     * Get distance value in miles from meters.
     *
     * @param int $meters
     * @return numeric
     */
    public static function getDistanceInMiles($meters = 0)
    {
        return 0.000621371 * ($meters ? $meters : static::$distance);
    }

    /**
     * A raid location belongs to a raid
     *
     * @return BelongsTo
     */
    public function raid()
    {
	    return $this->belongsTo(Raid::class);
    }
}
