<?php

namespace App\Notifications\Backend;

use App\Classes\Email;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;

class EmployeePassword extends Notification
{
    use Queueable;

    /**
     * Create a new notification instance.
     *
     * @return void
     */
    public $user ;
    public $password;
    public $hospital;

    public function __construct($user, $password, $hospital)
    {
        $this->user         = $user;
        $this->password     = $password;
        $this->hospital     = $hospital;
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
            ->subject( Email::makeSubject('New Employee Of '. $this->hospital->title) )
            ->greeting($this->user->full_name_decrypted)
            ->line('Congratulations! Your account as the admin of the hospital '.$this->hospital->title.' has been created below are your credentials')
            ->line("Email: ".$this->user->email_decrypted)
            ->line("Password: ".$this->password)
            ->line("Click on the button to go to admin panel")
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
