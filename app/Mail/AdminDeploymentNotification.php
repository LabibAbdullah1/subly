<?php

namespace App\Mail;

use App\Models\Deployment;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class AdminDeploymentNotification extends Mailable
{
    use Queueable, SerializesModels;

    public Deployment $deployment;

    /**
     * Create a new message instance.
     */
    public function __construct(Deployment $deployment)
    {
        $this->deployment = $deployment;
    }

    /**
     * Get the message envelope.
     */
    public function envelope(): Envelope
    {
        $subdomainName = $this->deployment->subdomain->name ?? 'Unknown';
        return new Envelope(
            subject: "[Subly] 🚀 Deployment Baru untuk Subdomain {$subdomainName}",
        );
    }

    /**
     * Get the message content definition.
     */
    public function content(): Content
    {
        return new Content(
            view: 'emails.admin.deployment-notification',
        );
    }

    /**
     * Get the attachments for the message.
     *
     * @return array<int, \Illuminate\Mail\Mailables\Attachment>
     */
    public function attachments(): array
    {
        return [];
    }
}
