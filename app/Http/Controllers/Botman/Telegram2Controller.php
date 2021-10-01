<?php

namespace App\Http\Controllers\Botman;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Telegram;

use BotMan\BotMan\BotMan;
use BotMan\BotMan\BotManFactory;
use BotMan\BotMan\Drivers\DriverManager;
use BotMan\BotMan\Cache\LaravelCache;
use BotMan\BotMan\Messages\Incoming\Answer;
use BotMan\BotMan\Messages\Outgoing\Question;
use BotMan\BotMan\Messages\Outgoing\Actions\Button;
use BotMan\BotMan\Messages\Conversations\Conversation;
// use BotMan\Drivers\Telegram\Extensions\Keyboard;
// use BotMan\Drivers\Telegram\Extensions\KeyboardButton;



class TelegramController extends Controller{

    protected $user;

    public function handle(){

      $config = [
          // Your driver-specific configuration
          "telegram" => [
             "token" => "1723691014:AAF20NSsh1s8XlyXdujdevrDVZVWEWEeYf8"
          ]
      ];

      // Load the driver(s) you want to use
      DriverManager::loadDriver(\BotMan\Drivers\Telegram\TelegramDriver::class);

      // Create an instance
      $botman = BotManFactory::create($config, new LaravelCache());

      // Give the bot something to listen for.
      $botman->hears('/start', function (BotMan $bot) {
        $chat_id = $bot->getUser()->getId();
        if(Telegram::where('chat_id', $chat_id)->first() != null){

        }else{
          $bot->reply('Добро пожаловать к нам! Наш бот может помочь в решение разных задач, но прежде чем начать давайте свяжем Ваш аккаунт на сайте с чатом в телеграмме!🍀');
          $this->askEmailForRegister($bot);
        }


      });

      $botman->hears('Привет', function (BotMan $bot) {
          $this->askForDatabase($bot);
      });

      $botman->hears('Номер чата', function (BotMan $bot) {
          $bot->reply($bot->getUser()->getId());
      });

      // Start listening
      $botman->listen();

    }
    // $question = Question::create('Это Вы, '.$this->user->profile->fio.'?')
    //     ->fallback('Unable to create a new database')
    //     ->callbackId('create_database')
    //     ->addButtons([
    //         Button::create('Да')->value('yes'),
    //         Button::create('Нет')->value('no'),
    //     ]);
    //
    // $this->ask($question, function (Answer $answer) {
    //     // Detect if button was clicked:
    //     if ($answer->isInteractiveMessageReply()) {
    //         $selectedValue = $answer->getValue(); // will be either 'yes' or 'no'
    //         $selectedText = $answer->getText(); // will be either 'Of course' or 'Hell no!'
    //     }
    //
    //     if($selectedValue == 'yes' || $selectedText == 'Да'){
    //       $this->say('Такого нет 👀. Чтобы повторить нажмите или пропишете команду /register');
    //     }else{
    //       $this->say('Нет так нет 👀. Давайте сначала, чтобы повторить нажмите или пропишете команду /register');
    //     }
    // });
    public function askEmailForRegister($bot){
        $bot->ask('Укажите пожалуйста email, на который зарегестрирован Ваш аккаунт на сайте?', function(Answer $answer) {
            // Save result
            $this->user = User::where('email', $answer->getText())->first();
            if($this->user != null){
              $this->say($this->user->profile->fio);
            }
        });
    }

    public function confirmUser($bot){

      $question = Question::create('Это Вы ?')
          ->fallback('Unable to create a new database')
          ->callbackId('create_database')
          ->addButtons([
              Button::create('Да')->value('yes'),
              Button::create('Нет')->value('no'),
          ]);

      $bot->ask($question, function (Answer $answer) {
          // Detect if button was clicked:
          if ($answer->isInteractiveMessageReply()) {
              $selectedValue = $answer->getValue(); // will be either 'yes' or 'no'
              $selectedText = $answer->getText(); // will be either 'Of course' or 'Hell no!'
          }
      });
    }

    public function askForDatabase($bot)
    {
        $question = Question::create('Укажите ?')
            ->fallback('Unable to create a new database')
            ->callbackId('create_database')
            ->addButtons([
                Button::create('Of course')->value('yes'),
                Button::create('Hell no!')->value('no'),
            ]);

        $bot->ask($question, function (Answer $answer) {
            // Detect if button was clicked:
            if ($answer->isInteractiveMessageReply()) {
                $selectedValue = $answer->getValue(); // will be either 'yes' or 'no'
                $selectedText = $answer->getText(); // will be either 'Of course' or 'Hell no!'
            }
        });
    }
}
