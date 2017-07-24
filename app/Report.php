<?php

namespace App;

use App\Fragment;
use App\Project;
use Illuminate\Database\Eloquent\Model;

class Report extends Model
{

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'user_id', 'content_id', 'type',
    ];

    /**
     * Each Report belongs to a User.
     *
     * @return Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Returns true if a Report belongs to a Fragment.
     *
     * @return boolean
     */
    public function isFragment()
    {
        return (str_replace('App\\', '', $this->type) === 'Fragment') ? true : false;
    }

    /**
     * Returns a Fragment, based on its id.
     *
     * @param  int    $fragmentId
     * @return App\Fragment
     */
    protected function getFragment(int $fragmentId)
    {
        return Fragment::where('id', $fragmentId)->first();
    }

    /**
     * Returns a the slug of a Project, that a Fragment belongs to.
     *
     * @return string
     */
    public function getProjectForFragment(int $fragmentId)
    {
        $fragment = $this->getFragment($fragmentId);
        return $fragment->project->slug;
    }

    /**
     * Returns the slug of a Project.
     *
     * @return string
     */
    public function getProjectSlug()
    {
        return Project::where('id', $this->content_id)->first()->slug;
    }

    /**
     * Returns the body of a Fragment.
     *
     * @return string
     */
    public function getFragmentBody(int $fragmentId)
    {
        return $this->getFragment($fragmentId)->body;
    }

    /**
     * Returns whether a Project or Fragment exists.
     *
     * @return mixed App\Fragment | App\Projec
     */
    public function contentExists()
    {
        if ($this->isFragment()) {
            return Fragment::where('id', $this->content_id)->first();
        } else {
            return Project::where('id', $this->content_id)->first();
        }
    }

}
