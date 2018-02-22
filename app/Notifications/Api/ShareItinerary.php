<?php

namespace App\Notifications\Api;

use App\Classes\Email;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class ShareItinerary extends Notification
{
    use Queueable;

    /**
     * Complete TripRideShare object.
     *
     * @var string
     */
    public $rideShared;

    /**
     * Create a new notification instance.
     *
     * @return void
     */
    public function __construct($rideShared)
    {
        $this->rideShared = $rideShared;
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
        return (new MailMessage)
            ->subject( Email::makeSubject('Track Your Loved One!') )
            ->line('Your loved one wants to share their real-time location with you because they know you care for them.')
            ->action('Track Ride', route('track.ride', [
                'rideShared' => $this->rideShared->id,
            ]));
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
