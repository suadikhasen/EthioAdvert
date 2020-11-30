<?php

namespace App\TelgramBot\Database;

use App\LevelOfChaannel;

class LevelOfChannelReopoksitory

{
    public static function getPaginatedLevel($per_page,$page_number)
    {
      return LevelOfChaannel::paginate($per_page,['*'],'page',$page_number);
    }

}