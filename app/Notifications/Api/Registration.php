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

    public $password;

    /**
     * Create a new notification instance.
     *
     * @return void
     */
    public function __construct($user, $password)
    {
        $this->user = $user;
        $this->password = $password;
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
            ->subject( Email::makeSubject('Account Registered - Wait for Approval') )
            ->greeting($this->user->full_name);

        $mailMessage
            ->line('We have received your registration details on ' . constants('global.site.name') . '. Please find your login credentials below.')
            ->line(sprintf('Email / Password: %s / %s', $this->user->email, $this->password))
            ->line('Currently your account is in pending state and requires admin to approve first. We will notify you upon activation.');

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
