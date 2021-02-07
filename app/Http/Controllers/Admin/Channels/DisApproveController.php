<?php

namespace App\Http\Controllers\Admin\Channels;

use App\Http\Controllers\Controller;
use App\Services\Common\TelegramBot;
use App\TelgramBot\Database\Admin\ChannelRepository;
use App\TelgramBot\Object\Chat;
use Illuminate\Http\Request;
use App\Http\Controllers\Admin\ChannelsController;

class DisApproveController extends Controller
{
    public function disApprove($channel_owner_id,$channel_id)
    {
        ChannelRepository::deleteChannel($channel_id);
        $text_message = TelegramBot::makeDissApproveMessage();
        TelegramBot::sendNotification($text_message,$channel_owner_id);
        return redirect()->action([ChannelsController::class,'listOfChannels'])->with('success_notification','channel dis approved successfully');
    }

    
}
