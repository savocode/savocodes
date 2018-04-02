<?php

namespace App\Notifications\Backend;

use App\Classes\Email;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;

class UserActivationEmail extends Notification
{
    use Queueable;

    public $user;
    public $is_active;

    public function __construct($user, $is_active)
    {
        $this->is_active = $is_active;
        $this->user      = $user;
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
        $temp = $this->is_active == true?'Activate':'Deactivate';
        $mailMessage = (new MailMessage)
            ->subject( Email::makeSubject('User '. $temp) )
            ->greeting($this->user->full_name_decrypted);

        if($this->is_active)
        {
            $mailMessage->line('Your account  has been activated by the '.constants('global.site.name'). ' admin')
                ->line("Click on the button to go to admin panel")
                ->action(constants('global.site.name').' Employee Panel', url('backend/login') )
                ->line("Please login and check every things works fine");
        }
        else
        {
            $mailMessage->line('Your account  has been de-activated by the '.constants('global.site.name'). ' admin');
        }

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
