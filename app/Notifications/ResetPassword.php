<?php

namespace App\Notifications;

use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;
use Illuminate\Support\Facades\Lang;

class ResetPassword extends Notification
{
  /**
   * The password reset token.
   *
   * @var string
   */
  public $token;

  /**
   * The callback that should be used to create the reset password URL.
   *
   * @var \Closure|null
   */
  public static $createUrlCallback;

  /**
   * The callback that should be used to build the mail message.
   *
   * @var \Closure|null
   */
  public static $toMailCallback;

  /**
   * Create a notification instance.
   *
   * @param  string  $token
   * @return void
   */
  public function __construct($token)
  {
      $this->token = $token;
  }

  /**
   * Get the notification's channels.
   *
   * @param  mixed  $notifiable
   * @return array|string
   */
  public function via($notifiable)
  {
      return ['mail'];
  }

  /**
   * Build the mail representation of the notification.
   *
   * @param  mixed  $notifiable
   * @return \Illuminate\Notifications\Messages\MailMessage
   */
  public function toMail($notifiable)
  {
      if (static::$toMailCallback) {
          return call_user_func(static::$toMailCallback, $notifiable, $this->token);
      }

      if (static::$createUrlCallback) {
          $url = call_user_func(static::$createUrlCallback, $notifiable, $this->token);
      } else {
          $url = url(route('password.reset', [
              'token' => $this->token,
              'email' => $notifiable->getEmailForPasswordReset(),
          ], false));
      }

      return $this->buildMailMessage($url);
  }

  /**
   * Get the reset password notification mail message for the given URL.
   *
   * @param  string  $url
   * @return \Illuminate\Notifications\Messages\MailMessage
   */
  protected function buildMailMessage($url)
  {
      return (new MailMessage)
          ->subject(Lang::get('Уведомление сброса пароля'))
          ->line(Lang::get('По вашему требованию мы выслали ссылку на восстановления пароля. Что бы продолжить нажмите "Восстановить пароль".'))
          ->action(Lang::get('Восстановить пароль'), $url)
          ->line(Lang::get('Эта ссылка действительна в течении :count минут.', ['count' => config('auth.passwords.'.config('auth.defaults.passwords').'.expire')]))
          ->line(Lang::get('Если Вы не отправляли запрос на восстановление пароля, проигнорируйте это сообщение.'));
  }

  /**
   * Set a callback that should be used when creating the reset password button URL.
   *
   * @param  \Closure  $callback
   * @return void
   */
  public static function createUrlUsing($callback)
  {
      static::$createUrlCallback = $callback;
  }

  /**
   * Set a callback that should be used when building the notification mail message.
   *
   * @param  \Closure  $callback
   * @return void
   */
  public static function toMailUsing($callback)
  {
      static::$toMailCallback = $callback;
  }
}
