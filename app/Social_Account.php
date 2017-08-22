<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Social_Account extends Model
{
    protected $table = 'social_accounts';

    protected $fillable = ['user_id', 'provider', 'provider_id'];

    public function user() {

        return $this->belongsTo(User::class)->first();
    }
}
