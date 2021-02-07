<?php

namespace App\Http\Controllers\Admin\Levels;

use App\Http\Controllers\Controller;
use App\Http\Requests\AddQuality;
use App\TelgramBot\Database\Admin\ChannelRepository;
use App\TelgramBot\Database\Admin\LevelAttributeRepository;

class QualityController extends Controller
{
     public function addQualityPage($channel_id)
     {  
        $channel = ChannelRepository::findChannel($channel_id); 
        return view('admin.channels.add_quality',compact(['channel']));        
     }

     public function saveQuality(AddQuality $addQuality, $channel_id)
     {
        ChannelRepository::updateChannelQuality($channel_id,$addQuality->quality);
        return back()->with('success_notification','quality added successfully');
     }
}
