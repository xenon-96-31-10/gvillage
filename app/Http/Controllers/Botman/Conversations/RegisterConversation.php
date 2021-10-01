<?php

namespace App\Http\Controllers\Botman\Conversations;

use BotMan\BotMan\Messages\Incoming\Answer;
use BotMan\BotMan\Messages\Outgoing\Question;
use BotMan\BotMan\Messages\Outgoing\Actions\Button;
use BotMan\BotMan\Messages\Conversations\Conversation;

use App\Models\User;
use App\Models\Telegram;

class RegisterConversation extends Conversation
{
    /**
     * Start the conversation.
     *
     * @return mixed
     */
    public function run()
    {
        $this->welcomeUser();
    }

    private function welcomeUser()
    {
        $this->say('Привет '.$this->bot->getUser()
                ->getFirstName().' 👋. Давай начнем регистрацию!');
        $this->bot->typesAndWaits(1);
        $this->askIfReady();
    }

    private function askIfReady()
    {

        $this->ask('Укажите пожалуйста email, на который зарегестрирован Ваш аккаунт на сайте?', function (Answer $answer) {
            $this->bot->typesAndWaits(1);
            $this->user = User::where('email', $answer->getText())->first();
            if($this->user != null){
              $this->say($this->user->profile->fio);
              $this->confirmUser();
            }else{
              $this->say('Такого нет 👀. Чтобы повторить нажмите или пропишете команду /register');
            }
        }, [
            'parse_mode' => 'Markdown',
        ]);
    }

    private function confirmUser(){

      $question = Question::create('Это Вы, '.$this->user->profile->fio.' ?')
          ->addButtons([
              Button::create('Да')->value('yes'),
              Button::create('Нет')->value('no'),
          ]);

          $this->ask($question, function (Answer $answer) {
              $this->bot->typesAndWaits(1);
              if ($answer->getValue() === 'yes') {
                  $this->say('Perfect!');
                  $this->bot->typesAndWaits(1);
              }
          }, [
              'parse_mode' => 'Markdown',
          ]);
    }
}
