<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class NewUser extends Notification
{
    use Queueable;

    public $pass;
    /**
     * Create a new notification instance.
     *
     * @return void
     */
    public function __construct($pass)
    {
        $this->pass =$pass;
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
                    ->subject('Регистрация в сервисе BDODesign')
                    ->greeting('Здравствуйте!')
                    ->line('Временный пароль для доступа к сервису: '. $this->pass.'')
                    ->line('Спасибо большое!')
                    ->line('Незабудьте изменить пароль в лк кабинете.')
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
            //
        ];
    }
}
