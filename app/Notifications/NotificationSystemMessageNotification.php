<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class NotificationSystemMessageNotification extends Notification implements ShouldQueue
{
    use Queueable;

    private $notification_system_message;

    /**
     * Create a new notification instance.
     *
     * @return void
     */
    public function __construct($notification_system_message)
    {
        $this->notification_system_message = $notification_system_message;
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
                    ->subject($this->notification_system_message->custom_subject)
                    ->greeting($this->notification_system_message->custom_subject)
                    ->line($this->notification_system_message->message)
                    ->salutation('Melhores cumprimentos,<br>Expercom');
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
