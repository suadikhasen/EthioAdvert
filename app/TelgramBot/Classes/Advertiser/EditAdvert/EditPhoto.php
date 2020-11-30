<?php


namespace App\TelgramBot\Classes\Advertiser\EditAdvert;


use App\EthioAdvertPost;
use App\TelgramBot\Classes\Advertiser\EditAdvert;
use App\TelgramBot\Common\GeneralService;
use App\TelgramBot\Common\Services\Advertiser\EditAdvertService;
use App\TelgramBot\Common\Services\Advertiser\PhotoService;
use App\TelgramBot\Common\Services\Advertiser\ViewAdvertService;
use App\TelgramBot\Object\Chat;
use Illuminate\Support\Facades\Cache;
use Telegram\Bot\Exceptions\TelegramSDKException;
use App\TelgramBot\Database\AdvertsPostRepository;


/**
 * Class EditPhoto
 * @package App\TelgramBot\Classes\Advertiser\EditAdvert
 */
class EditPhoto
{

    /**
     * @var
     */
    private $advert_id;
    protected $advert;

    /**
     * EditPhoto constructor.
     * @param $advert_id
     */
    public function __construct($advert_id)
    {
        $this->advert_id = $advert_id;
        $this->advert = AdvertsPostRepository::findAdvert($advert_id);
        if(!EditAdvertService::validateForEditing($this->advert)){
            exit;
         }
    }

    /**
     * @param bool $isCommand
     * @throws TelegramSDKException
     */
    public function handle($isCommand=false)
   {
       if ($isCommand)
           $this->handleCommand();
       else
           $this->handleQuestion();
   }

    /**
     *
     * @throws TelegramSDKException
     */
    private function handleCommand()
    {
      Chat::createQuestion('Edit Photo','photo');
      EditAdvertService::putCacheForAdvertId(ViewAdvertService::getIDFromViewKeyboard());
      Chat::sendTextMessage('Please Send The Image');
    }

    /**
     *it accepts the photo message
     */
    private function handleQuestion()
    {
      if (PhotoService::isImage(Chat::$message))
          $this->finishEditing();
      else
          Chat::sendTextMessage('Please Send A photo Not another file or Text');
    }

    /**
     * @throws TelegramSDKException
     */
    private function finishEditing()
    {
        $this->update($this->advert_id);
        Chat::deleteTemporaryData();
        EditAdvertService::removeCacheEditAdvertId();
        EditAdvertService::removeCacheAdvert($this->advert_id);
        Chat::sendTextMessage('Photo Updated Successfully');
    }

    /**update the value of the advert
     * @param $advert_id
     */
    private function update($advert_id)
    {
        EthioAdvertPost::where('id',$advert_id)->update([
           'image_path' => PhotoService::getPhotoID(),
        ]);
    }
}
