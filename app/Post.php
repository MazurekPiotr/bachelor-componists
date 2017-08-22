<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use App\User;
use App\Project;

class Post extends Model
{
        protected $fillable = [
            'user_id', 'project_id', 'body',
        ];

        /**
         *
         * @return Illuminate\Database\Eloquent\Relations\BelongsTo
         */
        public function user()
        {
            return $this->belongsTo(User::class);
        }

        public function project()
        {
            return $this->belongsTo(Project::class);
        }

}
