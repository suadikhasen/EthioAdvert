<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class EthioAdvertPost extends Model
{
    protected $table ='ethio_advert_posts';
    protected $guarded = [];
    protected $dates = [
        'initial_date',
        'final_date'
    ];


}
