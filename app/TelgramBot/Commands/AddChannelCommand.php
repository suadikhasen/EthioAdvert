<?php


namespace App\TelgramBot\Commands;
use App\Services\Command as MyCommand;
use App\TelgramBot\Classes\AddChannel;
use App\TelgramBot\Common\Controllers;
use App\TelgramBot\Object\Chat;
use Telegram\Bot\Commands\Command;

/**
 * Class AddChannelCommand
 * @package App\TelgramBot\Commands
 */
class AddChannelCommand extends Command
{
    /**
     * @var string
     */
    protected $name = 'add channel';
    /**
     * @var string
     */
    protected $description = 'to add channel';
    /**
     * {@inheritdoc}
     */


    public function handle($arguments)
    {  Chat::deleteTemporaryData();
      if (Controllers::checkChannelOwnerAuthentication()){
          Chat::deleteTemporaryData();
          $advertiser = (new AddChannel())->handle();

      }else{
          Chat::sendTextMessage('You Are Not Authorized To Access This Page');
      }
        $command = new MyCommand();
        $command->isCommand = true;
    }
}
