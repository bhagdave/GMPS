<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class InviteParticipant extends Mailable
{
    use Queueable, SerializesModels;

    private $emailData;

    public function __construct($emailData)
    {
        $this->emailData = $emailData;
    }

    public function build()
    {
        return $this->from('test@gmps.org', 'GMPS')
            ->subject('Invite to join GMPS')
            ->markdown('emails.inviteParticipant')
            ->with($this->emailData);
    }
}
