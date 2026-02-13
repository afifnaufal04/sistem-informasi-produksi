<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class ResetPasswordNotification extends Notification
{
    use Queueable;

    public $token;

    /**
     * Create a new notification instance.
     */
    public function __construct($token)
    {
        $this->token = $token;
    }

    /**
     * Get the notification's delivery channels.
     */
    public function via(object $notifiable): array
    {
        return ['mail'];
    }

    /**
     * Get the mail representation of the notification.
     */
    public function toMail(object $notifiable): MailMessage
    {
        // Membuat Link Reset Password
        // Pastikan route 'password.reset' ada di routes/auth.php Anda
        $url = url(route('password.reset', [
            'token' => $this->token,
            'email' => $notifiable->getEmailForPasswordReset(),
        ], false));

        return (new MailMessage)
            ->subject('Permintaan Reset Password') // Judul Email
            ->greeting('Halo, ' . $notifiable->username . '!')
            ->line('Kami menerima permintaan untuk mereset password akun Anda.') // Ganti teks paragraf 1
            ->action('Atur Ulang Password', $url) // Ganti tulisan di Tombol
            ->line('Link reset password ini hanya berlaku selama 60 menit.') // Ganti paragraf 2
            ->line('Jika Anda tidak merasa meminta reset password, abaikan saja email ini. Akun Anda tetap aman.') // Ganti paragraf 3
            ->salutation('Salam Hangat, Tim ' . config('app.name')); // Ganti "Regards"
    }

    /**
     * Get the array representation of the notification.
     */
    public function toArray(object $notifiable): array
    {
        return [
            //
        ];
    }
}