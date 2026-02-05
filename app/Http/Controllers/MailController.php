<?php

namespace App\Http\Controllers;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Http\Request;

class MailController extends Mailable
{
    use Queueable, SerializesModels;
   
   /**
     * Create a new message instance.
     *
     * @return void
     */
    public function build($sender,$subject,$view,$body)
    {
        return $this->view($view)->subject($sender);
    }
}
