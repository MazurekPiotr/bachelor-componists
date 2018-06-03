<?php

namespace App\Mail;

use App\Fragment;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;

class FragmentDeleted extends Mailable
{
    use Queueable, SerializesModels;

    public $fragment;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct(Fragment $fragment)
    {
        $this->fragment = $fragment;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->view('mail.fragmentDeleted', [
            'fragment' => $this->fragment,
        ]);
    }
}
