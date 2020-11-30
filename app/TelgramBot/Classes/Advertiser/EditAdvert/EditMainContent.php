<?php


namespace App\TelgramBot\Classes\Advertiser\EditAdvert;
use App\EthioAdvertPost;
use App\TelgramBot\Common\GeneralService;
use App\TelgramBot\Common\Services\Advertiser\EditAdvertService;
use App\TelgramBot\Database\AdvertsPostRepository;
use App\TelgramBot\Object\Chat;
use Illuminate\Support\Facades\Cache;

class EditMainContent
{
    /**
     * @var integer
     */
    protected $advert_id;
    /**
     * @var string
     */
    protected $edited_main_content;

    protected $advert;

    /**
     * EditName constructor.
     * @param $advert_id
     */
    public function __construct($advert_id)
    {
        $this->advert_id = $advert_id;
        $this->advert = AdvertsPostRepository::findAdvert($this->advert_id);
        $this->edited_main_content = $this->advert->text_message;
        if(!EditAdvertService::validateForEditing($this->advert)){
            exit;
         }
    }

    public function sendEditMainContentMessage()
    {
        Chat::createQuestion('Edit Main Content',$this->advert_id);
        EditAdvertService::putCacheForAdvertId($this->advert_id);
        Chat::sendTextMessage('Please Send The Edit Message'."\n".'the current main message  is '."\n\n".$this->edited_main_content);
        GeneralService::answerCallBackQuery('editing main message started');
    }

    /**used to handle change main content
     *handle function
     */
    public function handle()
    {
        if($this->validate()) {
            $this->finishEditing();
        }
        else
            Chat::sendTextMessage('Please Send A Main Message not greater than 500 characters');
    }

    private function finishEditing()
    {
        $this->update();
        EditAdvertService::removeCacheEditAdvertId();
        EditAdvertService::removeCacheAdvert($this->advert_id);
        Chat::deleteTemporaryData();
        Chat::sendTextMessage('Main Message Updated Successfully');
    }

    /**
     *validate input description
     */
    protected function validate()
    {
        if (strlen(Chat::$text_message ) > 500)
            return false;
        return true;
    }

    /**description of the advert
     *update database value
     */
    protected  function update()
    {
        EthioAdvertPost::where('id',$this->advert_id)->update([
            'text_message'  => Chat::$text_message,
        ]);
    }
}
