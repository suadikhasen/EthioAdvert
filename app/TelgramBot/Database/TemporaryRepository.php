<?php


namespace App\TelgramBot\Database;


use App\TelgramBot\Object\Chat;
use App\Temporary;

class TemporaryRepository
{
  public static function deleteTemporary($type,$question): void
  {
      Temporary::where('type',$type)->where('question',$question)->delete();
  }

  public static function emptyAnswer(string $type, string $question)
  {
     Temporary::where('type',$type)->where('question',$question)->update([
         'answer' => null
     ]);
  }

  public static function findPreviousOfLastActivity()
  {
     return Temporary::where('chat_id',Chat::$chat_id)->where('type','Edit Date')->orderBy('id','DESC')->first();
  }


}
