<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Attachment;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;

class SendEmail extends Mailable
{
    use Queueable, SerializesModels;
    public $subject;
    public $msg;
    public $attachment;
    /**
     * Create a new message instance.
     */
    public function __construct($subject, $msg, $attachment = null)
    {
        $this->subject = $subject;
        $this->msg = $msg;
        $this->attachment = $attachment;
    
    }

    /**
     * Get the message envelope.
     */
    public function envelope(): Envelope
    {
        return new Envelope(
            subject: $this->subject,
        );
    }

    /**
     * Get the message content definition.
     */
    public function content(): Content
    {
        // dd($this->message);
        return new Content(
            view: 'pages.email.email',  
        );
    }

    /**
     * Get the attachments for the message.
     *
     * @return array<int, \Illuminate\Mail\Mailables\Attachment>
     */
    public function attachments(): array
    {
        $attachments = [];

        if ($this->attachment && is_file($this->attachment)) { 
            $attachments[] = Attachment::fromPath($this->attachment->getPathname()) 
            ->as($this->attachment->getClientOriginalName()) 
            ->withMime($this->attachment->getClientMimeType()); }
            // dd($attachments);
        return $attachments;
    }
}
