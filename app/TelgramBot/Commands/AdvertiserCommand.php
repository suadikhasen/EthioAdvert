<?php


namespace App\TelgramBot\Commands;

use App\TelgramBot\Object\Chat;
use Telegram\Bot\Commands\Command;
use App\Services\Command as MyCommand;
use App\TelgramBot\Classes\Advertiser;
/**
 * Class AdvertiserCommand
 * @package App\TelgramBot\Commands
 */
class AdvertiserCommand extends Command
{
    /**name of the command
     * @var string
     */
    protected $name = 'advertiser';
    /**description of the command
     * @var string
     */
    protected $description = 'for registering as advertiser';




    /**
     * {@inheritdoc}
     */
    public function handle($arguments):void
    {
        if (isRegistered(Chat::$chat_id)){
            $this->replyWithMessage([
                'text' => 'you are already registered'
            ]);

            return;
        }
        Chat::deleteTemporaryData();
        $advertiser = (new Advertiser())->handle();
        Chat::$isCommand = true;
    }
}
