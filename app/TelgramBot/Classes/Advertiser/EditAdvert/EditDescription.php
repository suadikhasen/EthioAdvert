<?php


namespace App\TelgramBot\Classes\Advertiser\EditAdvert;


use App\EthioAdvertPost;
use App\TelgramBot\Common\GeneralService;
use App\TelgramBot\Common\Services\Advertiser\EditAdvertService;
use App\TelgramBot\Database\AdvertsPostRepository;
use App\TelgramBot\Object\Chat;
use Illuminate\Support\Facades\Cache;

class EditDescription
{
    /**
     * @var integer
     */
    protected $advert_id;
    /**
     * @var string
     */
    protected $edited_description;
    protected $advert;

    /**
     * EditName constructor.
     * @param $advert_id
     */
    public function __construct($advert_id)
    {
        $this->advert_id = $advert_id;
        $this->advert = AdvertsPostRepository::findAdvert($this->advert_id);
        $this->edited_description = $this->advert->description_of_advert;
        if(!EditAdvertService::validateForEditing($this->advert)){
            exit;
         }
    }

    public function sendEditDescriptionMessage()
    {
        Chat::createQuestion('Edit Description',$this->advert_id);
        EditAdvertService::putCacheForAdvertId($this->advert_id);
        Chat::sendTextMessage('Please Send The Edited Message'."\n".'the current description is '."\n".'<strong>'.$this->edited_description.'</strong>');
        GeneralService::answerCallBackQuery('edit description started');
    }

    /**used to change name
     *handle function
     */
    public function handle()
    {
        if($this->validate()) {
          $this->finishEditing();
        }
        else
            Chat::sendTextMessage('Please Send A Description not greater than 40 characters');
    }

    private function finishEditing()
    {
        $this->update();
        EditAdvertService::removeCacheEditAdvertId();
        EditAdvertService::removeCacheAdvert($this->advert_id);
        Chat::deleteTemporaryData();
        Chat::sendTextMessage('Description Updated Successfully');
    }

    /**
     *validate input description
     */
    protected function validate()
    {
        if (strlen(Chat::$text_message ) > 40)
            return false;
        return true;
    }

    /**description of the advert
     *update database value
     */
    protected  function update()
    {
        EthioAdvertPost::where('id',$this->advert_id)->update([
            'description_of_advert'  => Chat::$text_message,
        ]);
    }
}
