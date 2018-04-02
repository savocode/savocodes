<?php

namespace App\Notifications\Backend;

use App\Classes\Email;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;

class EmployeeUpdate extends Notification
{
    use Queueable;

    public $user;
    public $is_password;

    public function __construct($user, $is_password)
    {
        $this->user             = $user;
        $this->is_password      = $is_password;
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
            ->subject( Email::makeSubject('Employee Updated') )
            ->greeting($this->user->full_name_decrypted)
            ->line('Your account  has been updated by the '.constants('global.site.name'). ' admin');
        if($this->is_password)
        {
            $mailMessage->line('Admin also update your password and emailed you');
        }

         $mailMessage->line("Click on the button to go to admin panel")
            ->action(constants('global.site.name').' Employee Panel', url('backend/login') )
            ->line("Please login and check every things works fine");

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
