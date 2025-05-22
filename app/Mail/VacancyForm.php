<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;
use Illuminate\Mail\Mailables\Address;
use Illuminate\Mail\Mailables\Attachment;

class VacancyForm extends Mailable
{
    use Queueable, SerializesModels;

    public function __construct(public $formData)
    {
    }

    public function envelope(): Envelope
    {
        return new Envelope(
            from: new Address(config('mail.from.address'), config('mail.from.name')),
            subject: "Vacancy Application" . ($this->formData['position'] ? ": " . $this->formData['position'] : '')
        );
    }

    public function content(): Content
    {
        return new Content(
            view: 'emails.vacancy',
        );
    }

    public function attachments(): array
    {
        $fileCv = $this->formData['cv'];
        $fileCoverLetter = $this->formData['cover_letter'];

        return [
            Attachment::fromPath($fileCv->path())
                ->as("cv." . $fileCv->getClientOriginalExtension())
                ->withMime($fileCv->getClientMimeType()),
            Attachment::fromPath($fileCoverLetter->path())
                ->as("cover_letter." . $fileCoverLetter->getClientOriginalExtension())
                ->withMime($fileCoverLetter->getClientMimeType())
        ];
    }
}
