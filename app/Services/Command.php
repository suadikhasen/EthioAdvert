<?php


namespace App\Services;


class Command
{
   public  $isCommand = false;

   public  function setIsCommand($value): void
   {
       $this->isCommand = true;
   }

   public function getIsCommand():bool
   {
      return $this->isCommand;
   }
}
