<?php


namespace App\TelgramBot\Classes\Advertiser\EditAdvert;

use App\EthioAdvertPost;
use App\TelgramBot\Classes\Advertiser\ViewAdverts;
use App\TelgramBot\Common\GeneralService;
use App\TelgramBot\Common\Services\Advertiser\EditAdvertService;
use App\TelgramBot\Common\Services\Advertiser\NumberOfViewService;
use App\TelgramBot\Database\AdvertsPostRepository;
use App\TelgramBot\Object\Chat;
use Telegram\Bot\Exceptions\TelegramSDKException;

/**
 * Class EditNumberOfView
 * @package App\TelgramBot\Classes\Advertiser\EditAdvert
 */
class EditNumberOfView
{

    private $advert_id;
    protected $number_of_view;
    private $advert;

    public function __construct($advert_id)
    {
        $this->advert_id = $advert_id;
        $this->advert = AdvertsPostRepository::findAdvert($this->advert_id);
        if (!EditAdvertService::validateForEditing($this->advert)) {
            exit;
        }
    }

    /**
     * @param $isCommand
     * @throws TelegramSDKException
     */
    public function handle($isCommand)
    {
        if ($isCommand)
            $this->processCommand();
        else
            $this->processQuestion(Chat::lastAskedQuestion());
    }

    /**
     *handle the edit view  button
     */
    private function processCommand()
    {
        Chat::createQuestion('Edit_Number_Of_View', 'no_view');
        $this->number_of_view = AdvertsPostRepository::findAdvert($this->advert_id)->no_view;
        EditAdvertService::putCacheForAdvertId($this->advert_id);

        NumberOfViewService::sendViewMessage(
            $this->number_of_view,
            GeneralService::checkTotalPriceOfAdvert($this->number_of_view),
            GeneralService::calculateMaximumView(),
            GeneralService::per_view_price,
            false
        );
        GeneralService::answerCallBackQuery('editing number of view started');
    }

    /**handle the coming view buttons
     * @param $response
     * @throws TelegramSDKException
     */
    private function processQuestion($response)
    {
        $no_view_keyboard = NumberOfViewService::getNumberOfViewFromInLineButtons(GeneralService::getCallBackQueryData());
        if ($no_view_keyboard === 'Confirm') {

            $this->setNumberOfView($response);
            $this->update($this->advert_id);
            Chat::deleteTemporaryData();
            EditAdvertService::removeCacheAdvert($this->advert_id);
            EditAdvertService::removeCacheEditAdvertId();
            GeneralService::answerCallBackQuery('view updated successfully');
            //           Chat::sendTextMessage('view updated successfully');
            Chat::sendEditTextMessage('view updated successfully', null, Chat::$chat_id, GeneralService::getMessageIDFromCallBack());
        } else {
            NumberOfViewService::sendEditedViewMessage($no_view_keyboard, $response, true);
        }
    }

    /**update value of view of  the advert
     * @param $advert_id
     */
    public function update($advert_id)
    {
        EthioAdvertPost::where('id', $advert_id)->update([
            'no_view'            => $this->number_of_view,
            'amount_of_payment'  => GeneralService::checkTotalPriceOfAdvert($this->number_of_view),
        ]);
    }

    /**assign value to $no_view_keyboard
     * @param $response
     */
    private function setNumberOfView($response)
    {
        $this->number_of_view = NumberOfViewService::getNumberOfViewForEditing($response);
    }
}
