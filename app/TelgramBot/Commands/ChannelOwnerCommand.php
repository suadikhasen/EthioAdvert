<?php


namespace App\TelgramBot\Commands;
use App\Services\Command as MyCommand;
use App\TelgramBot\Classes\ChannelOwner;
use App\TelgramBot\Object\Chat;
use Telegram\Bot\Commands\Command;

class ChannelOwnerCommand extends Command
{
    protected $name = 'channel owner';
    protected $description = 'to register as channel owner';
    /**
     * {@inheritdoc}
     */
    public function handle($arguments)
    {
        if (isRegistered(Chat::$chat_id)){
            return;
        }
        Chat::deleteTemporaryData();
        $advertiser = (new ChannelOwner())->handle();
        $command = new MyCommand();
        $command->isCommand = true;
    }
}
