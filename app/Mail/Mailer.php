<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class Mailer extends Mailable
{
    use Queueable, SerializesModels;
    public $subject,$view;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct(string $subject, string $view) 
    {
       $this->subject = $subject;
       $this->view = $view;
     
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->subject($this->subject)->view($this->view);
    }
}
