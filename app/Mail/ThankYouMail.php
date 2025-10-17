<?php

namespace App\Mail;

use App\Models\Registration;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Mail\Mailables\Attachment;
use Illuminate\Queue\SerializesModels;

use Endroid\QrCode\Builder\Builder;
use Endroid\QrCode\Encoding\Encoding;
use Endroid\QrCode\ErrorCorrectionLevel;
use Endroid\QrCode\Label\LabelAlignment;
use Endroid\QrCode\Label\Font\OpenSans;
use Endroid\QrCode\RoundBlockSizeMode;
use Endroid\QrCode\Writer\PngWriter;

class ThankYouMail extends Mailable
{
    use Queueable, SerializesModels;

    public string $logoPath;
    public string $qrCodeString;
    public string $ticketId;

    /**
     * Create a new message instance.
     */
    public function __construct(public Registration $registration)
    {
        $this->logoPath = public_path('medicallaw.png');
        $this->ticketId = $this->registration->ticket_id;
        
        $ticketUrl = url('/member/' . $this->registration->ticket_id);
        
        $builder = new Builder(
            writer: new PngWriter(),
            writerOptions: [],
            validateResult: false,
            data: $ticketUrl,
            encoding: new Encoding('UTF-8'),
            errorCorrectionLevel: ErrorCorrectionLevel::High,
            size: 300,
            margin: 10,
            roundBlockSizeMode: RoundBlockSizeMode::Margin,
            logoPath: $this->logoPath,
            logoResizeToWidth: 50,
            logoPunchoutBackground: true,
            labelText: 'Scan để xem vé',
            labelFont: new OpenSans(20),
            labelAlignment: LabelAlignment::Center
        );
        
        $result = $builder->build();
        $this->qrCodeString = $result->getString();
    }

    /**
     * Get the message envelope.
     */
    public function envelope(): Envelope
    {
        return new Envelope(
            from: new \Illuminate\Mail\Mailables\Address(
                config('mail.from.address'), 
                'Hội nghị Quốc tế M&A Y Tế Việt Nam 2025'
            ),
            subject: 'Xác nhận đăng ký và vé tham dự Hội nghị M&A Y Tế 2025',
        );
    }

    /**
     * Get the message content definition.
     */
    public function content(): Content
    {
        return new Content(
            view: 'emails.thank_you',
            with: [
                'logoPath' => $this->logoPath,
                'registration' => $this->registration,
            ],
        );
    }

    /**
     * Get the attachments for the message.
     */
    public function attachments(): array
    {
        return [
            Attachment::fromData(
                fn () => $this->qrCodeString,
                'qr-code-' . $this->ticketId . '.png'
            )->withMime('image/png')
        ];
    }
}
