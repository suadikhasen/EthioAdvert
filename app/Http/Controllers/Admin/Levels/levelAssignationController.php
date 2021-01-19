<?php

namespace App\Http\Controllers\Admin\Levels;

use App\Http\Controllers\Controller;
use App\Services\Common\LevelAssignService;
use App\Services\Common\TelegramBot;
use Illuminate\Support\Str;

class levelAssignationController extends Controller
{
    private $chat_id;
    private $channel_id;
    private $quality;

    public function __construct($channel_id,$chat_id,$quality)
    {
        $this->chat_id    = $chat_id;
        $this->channel_id = $channel_id;
        $this->quality    = $quality;
    }

    public function assignLevel()
    {  
        
       if(TelegramBot::checkChannelAuthorization($this->channel_id)){
           return back()->with('error_notification','Telegram Bot Has No Authorization on this Channel');
       }else{
           return $this->getResponseFromService();
       }
    }

    private function getResponseFromService()
    {
        $level_assign = LevelAssignService::assignLevel($this->chat_id,$this->channel_id,$this->quality);
        if($level_assign === 'not assigned'){
           return back()->with('error_notification','coud not satisfy the parametre');
        }elseif($level_assign === 'no post'){
            return back()->with('error_notification','channel has no post');
        }elseif(Str::startsWith($level_assign, 'level')){
            return back()->with('success_notification',$level_assign.' assigned successfullu');
        }else{
            return back()->with('error_notification','something went wrong');
        }
    }

   
    
}
