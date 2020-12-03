<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\TelgramBot\Database\ChannelRepository;
use Illuminate\Http\Request;

class ChannelsController extends Controller
{
    public function listOfChannels()
    {
       $channels = ChannelRepository::allChannel();
       return view('admin.channels.list_of_channels',['channels' => $channels]);
    }
}
