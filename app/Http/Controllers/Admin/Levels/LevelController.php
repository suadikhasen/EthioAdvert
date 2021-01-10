<?php

namespace App\Http\Controllers\Admin\Levels;

use App\Http\Controllers\Controller;
use App\Http\Requests\LevelStoreRequest;
use App\TelgramBot\Database\Admin\LevelRepository;

class LevelController extends Controller
{
    public function allLevel()
    {
        $levels = LevelRepository::fetchAllLevel();
        $number_of_levels = LevelRepository::countLevels();
        return view('admin.channels.list_of_level_of_channels',compact(['levels','number_of_levels']));
    }

    public function addNewLevel()
    {   
        $last_level = LevelRepository::findLastAddedLevel();
        return view('admin.channels.add_new_level',compact('last_level'));
    }

    public function saveLevel(LevelStoreRequest $request)
    {
        LevelRepository::createLevel($request->level_number);
        return back()->with('success_notification','level added successfully');
    }

    public function seeLevelChannels($level_id)
    {
        $channels = LevelRepository::listOfChannelsByLevel($level_id);
        $level   = LevelRepository::findLevel($level_id);
        $tittle = $level->level_name.'  channels';
        $table_header = 'list of '.$level->level_name.' channels';
        return view('admin.channels.list_of_channels',compact(['channels','tittle','table_header']));
    }
}
