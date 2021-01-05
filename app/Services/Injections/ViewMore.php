<?php

namespace App\Services\Injections;


Class ViewMore  {
 
    public function block($channel)
    {
       if($channel->block_status){
           return '<p> Block Status:Blocked</p>'.'<a href="#" class="btn btn-success"> Un Block </a>';

       }
       return '<p> Block Status:Not Blocked</p>'.'<a href="#" class="btn btn-success">  Block </a>';

         

    }
}