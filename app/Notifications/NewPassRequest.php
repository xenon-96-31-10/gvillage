<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class NewPassRequest extends Notification
{
    use Queueable;

    public $applicant;
    /**
     * Create a new notification instance.
     *
     * @return void
     */
    public function __construct($applicant)
    {
        $this->applicant = $applicant;
    }

    /**
     * Get the notification's delivery channels.
     *
     * @param  mixed  $notifiable
     * @return array
     */
    public function via($notifiable)
    {
        return ['database'];
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
                    ->subject('Новый заказ на оформление пропуска')
                    ->greeting('Здравствуйте!')
                    ->line('Перейдите в систему и утвердите новый пропуск.')
                    ->line('Спасибо большое!')
                    ->action('Перейти в сервис', url('/login'))
                    ->line('Спасибо, что выбрали нас!');
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
            'text' => 'Новый запрос на оформление пропуска( От: '.$this->applicant.')! Поспешите и утвердите тариф.',
        ];
    }
}
