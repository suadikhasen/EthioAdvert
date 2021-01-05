<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\TelgramBot\Database\ChannelRepository;
use Illuminate\Http\Request;

class ChannelsController extends Controller
{
    
    private $channel_id;

    public function listOfChannels()
    {
       $channels = ChannelRepository::allChannel();
       return view('admin.channels.list_of_channels',['channels' => $channels]);
    }

    public function viewMore($id)
    {
        $channel  = ChannelRepository::getChannelById($id);
        return  view('admin.channels.view_more',['channel' => $channel]);
    }
}
