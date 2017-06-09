<?php

namespace App\Mail;

use App\Fragment;
use App\Project;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;

class UserPostedOnProject extends Mailable
{
    use Queueable, SerializesModels;

    public $project;
    public $fragment;
    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct(Project $project, Fragment $fragment)
    {
        $this->project = $project;
        $this->fragment = $fragment;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->view('mail.posted', [
            'project' => $this->topic,
            'post' => $this->post,
        ]);
    }
}
