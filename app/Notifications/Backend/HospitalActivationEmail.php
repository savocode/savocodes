<?php

namespace App\Notifications\Backend;

use App\Classes\Email;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;

class HospitalActivationEmail extends Notification
{
    use Queueable;

    public $hospital;
    public $is_active;

    public function __construct($hospital, $is_active)
    {
        $this->hospital  = $hospital;
        $this->is_active = $is_active;
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
        $temp = $this->is_active == 0?'Deactivated':'Activated';
        $mailMessage = (new MailMessage)
            ->subject( Email::makeSubject('Hospital '. $temp) )
            ->greeting($this->hospital->title);

        if($this->is_active == 0)
        {
            $mailMessage->line('This hospital has been blocked by the '.constants('global.site.name'). ' admin')
                        ->line('Your account are no longer active on the employee panel');
        }
        else
        {

            $mailMessage->line('This hospital has been activates by the '.constants('global.site.name'). ' admin')
                        ->line("Click on the button to go to admin panel")
                        ->action(constants('global.site.name').' Employee Panel', url('backend/login') )
                        ->line("Please login and check every things works fine");
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
