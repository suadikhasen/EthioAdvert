<?php

namespace  App\TelgramBot\Database\Admin;

use App\User;

class ChannelOwnerRepository
{

    public static function listOfChannelOwners()
    {
        return User::where('type','Channel Owner')->simplePaginate(10);
    }
}