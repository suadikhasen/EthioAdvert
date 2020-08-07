<?php


namespace App\TelgramBot\Classes\Advertiser\EditAdvert;


use App\EthioAdvertPost;
use App\TelgramBot\Common\GeneralService;
use App\TelgramBot\Common\Services\Advertiser\EditAdvertService;
use App\TelgramBot\Database\AdvertsPostRepository;
use App\TelgramBot\Object\Chat;
use Illuminate\Support\Facades\Cache;

/**
 * Class EditName
 * @package App\TelgramBot\Classes\Advertiser\EditAdvert
 */
class EditName
{
    /**
     * @var
     */
    protected $advert_id;
    /**
     * @var
     */
    protected $edited_name;

    /**
     * EditName constructor.
     * @param $advert_id
     */
    public function __construct($advert_id)
   {
       $this->advert_id = $advert_id;
       $advert = AdvertsPostRepository::findAdvert($advert_id);
       $this->edited_name = $advert->name_of_the_advert;
   }

   public function sendEditNameMessage()
   {
       Chat::createQuestion('Edit Name',$this->advert_id);
       EditAdvertService::putCacheForAdvertId($this->advert_id);
       Chat::sendTextMessage('Please Send The Edited Message'."\n".'the current name is '."\n".'<strong>'.$this->edited_name.'</strong>');
       GeneralService::answerCallBackQuery('editing name started');
   }

    /**used to change name
     *handle function
     */
    public function handle()
   {
      if($this->validate()) {
          $this->update();
          EditAdvertService::removeCacheAdvert($this->advert_id);
          EditAdvertService::removeCacheEditAdvertId();
          Chat::sendTextMessage('Name Updated Successfully');
      }
      else
          Chat::sendTextMessage('Please Send A name not greater than 20 characters');
   }

    /**validate input name
     *validate input date
     */
    protected function validate()
   {
       if (strlen(Chat::$text_message ) > 20)
           return false;
       return true;
   }

    /**update name of the advert
     *update database value
     */
    protected  function update()
   {
     EthioAdvertPost::where('id',$this->advert_id)->update([
         'name_of_the_advert'  => Chat::$text_message
     ]);
   }

}
