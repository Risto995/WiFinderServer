<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class WiFi extends Model
{
    protected $table = 'wifis';

    protected $fillable = [
        'name', 'password', 'created_by', 'latitude', 'longitude'
    ];

    /**
     * Get the user that has created this WiFi spot
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function user()
    {
        return $this->belongsTo('App\User', 'created_by');
    }
}
