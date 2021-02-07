<?php

namespace App\Http\Controllers\Admin\Channels;

use App\Http\Controllers\Controller;
use App\Services\Common\TelegramBot;
use App\TelgramBot\Database\Admin\ChannelRepository;
use App\TelgramBot\Object\Chat;
use Illuminate\Http\Request;

class ApproveController extends Controller
{
    
    public function approve($channel_owner_id,$channel_id)
    {   
        
        if(TelegramBot::checkChannelAuthorization($channel_id)){
            ChannelRepository::updateApproveStatus($channel_id,true);
            TelegramBot::sendNotification('✅ congradulations !! '."\n".'your channel is approved',$channel_owner_id);
            return back()->with('success_notification','channel approved successfully');  
        }else{
            TelegramBot::sendNotification('❌ you didnt add your channel as admin ❌ '."\n".'please add the bot as an admin to your channel to be approved',$channel_owner_id);
            return back()->with('error_notification','channel not approved because the bot is not the admin');  
        }
    }
}
