<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Fragment extends Model
{
    use SoftDeletes;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'user_id', 'topic_id', 'body',
    ];

    /**
     *
     * @return Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function topic ()
    {
        return $this->belongsTo(Project::class);
    }

    /**
     *
     * @return Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function user ()
    {
        return $this->belongsTo(User::class);
    }

}
