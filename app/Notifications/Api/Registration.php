<?php

namespace App\Notifications\Api;

use App\Classes\Email;
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
            ->subject( Email::makeSubject('Account Verification Process') )
            ->greeting($this->user->full_name);

        $mailMessage
            ->line('We have received your registration details on ' . constants('global.site.name') . '. Please wait for the admin to approve your account. We will notify you upon activation.');

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
