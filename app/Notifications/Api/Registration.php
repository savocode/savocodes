<?php

namespace App\Notifications\Api;

use App\Classes\Email;
use App\Classes\RijndaelEncryption;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class Registration extends Notification
{
    /**
     * Complete user object.
     *
     * @var string
     */
    public $user;

    /**
     * Create a new notification instance.
     *
     * @return void
     */
    public function __construct($user)
    {
        $this->user = $user;
    }

    /**
     * Get the notification's delivery channels.
     *
     * @param  mixed  $notifiable
     * @return array
     */
    public function via($notifiable)
    {
        return ['mail'];
    }

    /**
     * Get the mail representation of the notification.
     *
     * @param  mixed  $notifiable
     * @return \Illuminate\Notifications\Messages\MailMessage
     */
    public function toMail($notifiable)
    {
        $mailMessage = (new MailMessage)
            ->subject( Email::makeSubject('Verify your Account') )
            ->greeting($this->user->full_name_decrypted);

        if ( $this->user->email_verification != '1' ) {
            $mailMessage
                ->line('We have received your registration details on ' . constants('global.site.name') . '. Please click the following button to verify your account')
                ->action('Verify Account', route('api.verification.email', ['code' => $this->user->email_verification]) );
        } else {
            $mailMessage
                ->line('We have received your registration details on ' . constants('global.site.name') . '.');
        }

        $mailMessage
            ->line('Thank you for using our application.');

        return $mailMessage;
    }

    /**
     * Get the array representation of the notification.
     *
     * @param  mixed  $notifiable
     * @return array
     */
    public function toArray($notifiable)
    {
        return [
            //
        ];
    }
}
