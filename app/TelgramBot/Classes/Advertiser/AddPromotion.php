<?php


namespace App\TelgramBot\Classes\Advertiser;


use App\EthioAdvertPost;
use App\TelgramBot\Common\GeneralService;
use App\TelgramBot\Common\Pages;
use App\TelgramBot\Common\Services\Advertiser\NumberOfViewService;
use App\TelgramBot\Database\AdvertsPostRepository;
use App\TelgramBot\Database\BankRepository;
use App\TelgramBot\Database\TemporaryRepository;
use App\TelgramBot\Object\Chat;
use App\Temporary;
use Carbon\Carbon;
use Andegna\DateTime;
use Illuminate\Support\Str;

use Telegram\Bot\Exceptions\TelegramSDKException;

use Telegram\Bot\Keyboard\Keyboard;

/**
 * Class AddPromotion
 * @package App\TelgramBot\Classes\Advertiser
 */
class AddPromotion
{
    /**
     * @var
     */
    protected $response;
    /**
     * @var
     */
    protected $day;
    /**
     * @var
     */
    protected $month;

    /**
     * @param bool $isCommand
     * @throws TelegramSDKException
     */
    protected $invalid_message;


    /**
     * @var
     */
    private $updated_view;
    /**
     * @var array
     */
    private $temporary;
    /**
     * @var float
     */
    private $totalPrice;

    private $bank_account;
    private $advert;


    /**
     * @param bool $isCommand
     * @throws TelegramSDKException
     */
    public  function handle(bool $isCommand = false)
    {
        if ($isCommand)
            $this->processCommand();
        else
            $this->processQuestion(Chat::lastAskedQuestion());
    }

    public function processCommand()
    {
        if (AdvertsPostRepository::checkExistenceOfNonPaidPromotion(Chat::$chat_id))
            Chat::sendTextMessage('You Already Have Pending advert Please Pay The Pending Advert' . "\n" . ' You can edit or delete advert and  order another');
        else {
            Chat::createQuestion('Add_Promotion', 'name_of_the_advert');
            Chat::sendTextMessage('please send name of the promotion not greater than 20 characters (mostly organization name)s');
        }
    }

    /**
     * @param $response
     * @throws TelegramSDKException
     */
    private function processQuestion($response)
    {
        $this->response = $response;
        if ($response->answer === null || $response->question === 'no_view') {
            switch ($response->question) {
                case 'name_of_the_advert':
                    $this->acceptNameOfTheAdvert();
                    break;
                case 'description_of_advert':
                    $this->acceptDescriptionOfAdvert();
                    break;
                case 'text_message':
                    $this->acceptMainMessage();
                    break;
                case 'image_path':
                    $this->acceptImage();
                    break;
                case 'initial_date':
                    $this->acceptInitialDate();
                    break;
                case 'final_date':
                    $this->acceptFinalDate();
                    break;
                case 'no_view':
                    $this->acceptNumberOfView();
                    break;
                case 'payment_code':
                    $this->acceptPaymentMethod();
                    break;
            }
        } else {
        }
    }

    /**
     * @throws TelegramSDKException
     */
    private function  acceptNameOfTheAdvert(): void
    {
        if (strlen(Chat::$text_message) > 20) {
            Chat::sendTextMessage('Please Send A name not greater than 20 characters');
        } else {
            Chat::createAnswer($this->response->id);
            Chat::createQuestion('Add_Promotion', 'description_of_advert');
            Chat::sendTextMessage('Please Send A Small Description Of Promotion');
        }
    }

    /**
     * @throws TelegramSDKException
     */
    private function acceptDescriptionOfAdvert(): void
    {
        if (strlen(Chat::$text_message) > 40) {
            Chat::sendTextMessage('Please Send A description not greater than 40 letters');
        } else {
            Chat::createAnswer($this->response->id);
            Chat::createQuestion('Add_Promotion', 'text_message');
            Chat::sendTextMessage('Please Send Main Message Of Promotion (only send text ,if you have image it will be next))');
        }
    }

    /**
     * @throws TelegramSDKException
     */
    private function acceptMainMessage(): void
    {
        if (strlen(Chat::$text_message) > 400) {
            Chat::sendTextMessage('Please Send A Message not greater than 400 characters');
        } else {
            Chat::createAnswer($this->response->id);
            Chat::createQuestion('Add_Promotion', 'image_path');
            Chat::sendTextMessage('Please Send A photo , It Is Optional You Can Skip It by /skip');
        }
    }

    /**
     *accept image of promotion
     */
    private function acceptImage(): void
    {
        Chat::createAnswer($this->response->id);
        Chat::createQuestion('Add_Promotion', 'initial_date');
        $this->sendInitialDateMessage();
    }


    /**
     * @throws TelegramSDKException
     */
    private function acceptInitialDate(): void
    {
        if ($this->validateUserInputDate()) {
            $initial_date   =  Carbon::create(GeneralService::getEthiopianCurrentyear(), $this->month, $this->day);
            if ($this->validateInitialDate()) {
                Chat::$text_message = $initial_date;
                Chat::createAnswer($this->response->id);
                $this->sendFinalDateMessage();
            } else {
                $this->sendDateError();
            }
        } else {
            Chat::sendTextMessage('please send correct date format as shown before');
        }
    }

    private function validateInitialDate()
    {
        $tomorrow = (new DateTime(Carbon::tomorrow()))->getDay();
        if($this->day === $tomorrow){
            if(GeneralService::getEthiopianCurrentHour() < 14){
                return true;
            }
        }elseif($this->day < $tomorrow){
            return false;
        }
       
        return true;
        
    }

    private function sendInitialDateMessage(): void
    {
        $text = 'Please Send The Initial Date On Which The advert is Posted like' . "\n" .
            '<strong>DD/MM' . "\n\n\n" . 'the date must be in Ethiopian Calendar</strong>';
        Chat::sendTextMessage($text);
    }

    /**
     * @throws TelegramSDKException
     */
    private function acceptFinalDate(): void
    {
        if ($this->validateUserInputDate()) {
            $final_date   =  Carbon::create(GeneralService::getEthiopianCurrentyear(), $this->month, $this->day);
            $initial_date =  Carbon::parse($this->getInitialDate()->answer);
            if($this->validateFinalDate($initial_date,$final_date)){
                Chat::$text_message = $final_date;
                Chat::createAnswer($this->response->id);
            }else{
                Chat::sendTextMessage('Final Date Must Be Greater Than Or Equal To Initial Date');
            }
        } else {
            Chat::sendTextMessage('please send correct date format as shown before');
        }
    }

    private function validateFinalDate(Carbon $initial_date, Carbon $final_date)
    {
        if ($initial_date->diffInDays($final_date, false) >1) {
            return true;
        } 
        return false;
    }

    private function processFinalAndInitialDate(Carbon $initial_date, Carbon $final_date): void
    {
        if ($initial_date->diffInDays($final_date, false) < 0) {
            Chat::sendTextMessage('Final Date Must Be Greater Than Or Equal To Initial Date');
        } else {
            $this->processPostAvailability($initial_date, $final_date);
        }
    }
    private function processPostAvailability(Carbon $initial_date, Carbon $final_date): void
    {
        if (GeneralService::isFreeSpaceAvailable($initial_date, $final_date)) {
            Chat::$text_message = $final_date;
            Chat::createAnswer($this->response->id);
            Chat::createQuestion('Add_Promotion', 'no_view');
            NumberOfViewService::sendViewMessage(
                GeneralService::default_number_of_view,
                GeneralService::checkTotalPriceOfAdvert(GeneralService::default_number_of_view),
                GeneralService::calculateMaximumView(),
                GeneralService::per_view_price,
                false
            );
        } else {
            $this->advertNotAvailable();
        }
    }

    /**
     * @throws TelegramSDKException
     */
    private function sendDateError(): void
    {
        Chat::sendTextMessage('Your initial Date Must Be at least 8 hours greater to next day');
    }


    /**
     * @throws TelegramSDKException
     */
    private function sendFinalDateMessage(): void
    {
        $text = 'Please Send The final Date On Which The advert is Posted like' . "\n" .
            '<strong>DD/MM' . "\n\n\n" . 'the date must be in Gregorian Calendar</strong>';
        Chat::sendTextMessage($text);
        Chat::createQuestion('Add_Promotion', 'final_date');
    }

    /**
     * @return bool|null
     */
    private function validateUserInputDate(): ?bool
    {
        $day          =  Str::before(Chat::$text_message, '/');
        $month        =  Str::after(Chat::$text_message, '/');
        $digit_string = '0123456789';
        if (strlen($day) < 2) {
            $day = '0' . $day;
        }
        if (strlen($month) < 2) {
            $month = '0' . $month;
        }
        $array_day = str_split($day);
        $array_month = str_split($month);
        if (Str::containsAll($digit_string, [$array_day[0], $array_day[1], $array_month[0], $array_month[1]])) {
            $this->day     =     $day;
            $this->month   =     $month;

            return true;
        } else {
            return false;
        }
    }

    /**
     * @return mixed
     *
     */
    public function getInitialDate()
    {
        return Temporary::where('chat_id', Chat::$chat_id)->where('type', 'Add_Promotion')->where('question', 'initial_date')->latest()->first();
    }

    /**
     * @throws TelegramSDKException
     */
    private function acceptNumberOfView(): void
    {
        if (GeneralService::checkCallBackQuery()) {
            $no_view_keyboard  = NumberOfViewService::getNumberOfViewFromInLineButtons(GeneralService::getCallBackQueryData());
            if ($no_view_keyboard !== 'Confirm') {
                NumberOfViewService::sendEditedViewMessage($no_view_keyboard, $this->response);
            } elseif ($no_view_keyboard = 'Confirm') {
                $this->sendSuccessfulMessageForView();
                $this->sendPaymentMethod();
            }
        } else {
            Chat::deleteTemporaryData();
            Pages::advertiserPage();
        }
    }





    /**
     * @param int $no_view_keyboard
     * @throws TelegramSDKException
     */
    private function sendEditedViewMessage(int $no_view_keyboard): void
    {
        Chat::$chat_id = GeneralService::getChatIdFromCallBack();
        $updated_view = $this->calculateUpdateView($no_view_keyboard);
        if ($updated_view < 0) {
            Chat::sendTextMessage('View Can not be Negative Try Again');
        } elseif ($updated_view < GeneralService::calculateMaximumView()) {
            Chat::sendTextMessage('Number Of View Must Be Less Than Maximum Estimated View');
        } else {
            Chat::$text_message = $updated_view;
            Chat::createAnswer($this->response->id);
            NumberOfViewService::sendViewMessage($updated_view, GeneralService::checkTotalPriceOfAdvert($updated_view), GeneralService::calculateMaximumView(), GeneralService::per_view_price, true);
        }
    }

    /**
     * @param int $no_view_keyboard
     * @return int
     */
    private function calculateUpdateView(int $no_view_keyboard): int
    {
        $old_view = $this->response->answer;
        if ($old_view !== null) {
            return (int) $old_view + $no_view_keyboard;
        }
        return $no_view_keyboard + GeneralService::default_number_of_view;
    }

    /**
     *
     */
    private function displayTotalInformation(): void
    {
        $temporary_info   =   $this->getTemporaryInfo();
        $text_information =   $this->makeAdvertInfoTextMessage($temporary_info);
        $keyboard         =   $this->makeInlineKeyboardForTotalInformation();
        Chat::createQuestion('Add_Promotion', 'total_information');
        if ($temporary_info['image_path'] !== null) {
            Chat::sendPhoto(Chat::$chat_id, $temporary_info['image_path'], $text_information, $keyboard);
        } else {
            Chat::sendTextMessageWithInlineKeyboard($text_information, $keyboard);
        }
    }
    private  function makeInlineKeyboardForTotalInformation(): Keyboard
    {
        return Keyboard::make()->inline()->row(
            Keyboard::inlineButton([
                'text'           => 'Continue',
                'callback_data' => 'Continue'
            ]),
            Keyboard::inlineButton([
                'text'           => 'Cancel',
                'callback_data' => 'Cancel'
            ])
        );
    }
    private function getTemporaryInfo(): array
    {
        $temporary = Temporary::where('chat_id', Chat::$chat_id)->where('type', 'Add_Promotion')->get();
        $array = [];
        foreach ($temporary as $single) {
            $array[$single->question] = $single->answer;
        }
        return $array;
    }

    /**make text format information for total advert information
     * @param array $temporary_info
     * @return string
     */
    private function makeAdvertInfoTextMessage(array $temporary_info): string
    {
        $initial_date = Carbon::parse($temporary_info['initial_date'])->toDateString();
        $final_date   = Carbon::parse($temporary_info['final_date'])->toDateString();
        return '<b>Information Of Current Advert:</b>' . "\n" . "\n" .
            '<strong>Name :</strong>' . "\n" .
            $temporary_info['name_of_the_advert'] . "\n" . "\n" .
            '<strong>Description :</strong>' . "\n" . "\n" .
            $temporary_info['description_of_advert'] . "\n" . "\n" .
            '<strong>Main Message :</strong>' . "\n" . "\n" .
            $temporary_info['text_message'] . "\n" . "\n" .
            '<strong>Initial Date :</strong>' . "\n" . "\n" .
            $initial_date . "\n" .
            '<strong>Final Date :</strong>' . "\n" . "\n" .
            $final_date .
            '<strong>Number Of View :</strong>' . "\n" . "\n" .
            $temporary_info['no_view'] . "\n" . "\n" .
            '<strong>Total Price:</strong>' . "\n" . "\n" .
            GeneralService::checkTotalPriceOfAdvert($temporary_info['no_view']);
    }

    /**
     *send list of payment Method
     */
    private function sendPaymentMethod(): void
    {
        Chat::$chat_id = GeneralService::getChatIdFromCallBack();
        Chat::createQuestion('Add_Promotion', 'payment_code');
        Pages::$text = 'Please Select Payment Method';
        Pages::paymentMethodPage();
    }

    private function acceptPaymentMethod(): void
    {

        $bank = BankRepository::getBankInformationByName(Chat::$text_message);
        if ($bank->id !== null) {
            Chat::$text_message = $bank->id;
            /*           $this->displayTotalInformation();*/
            Chat::createAnswer($this->response->id);
            $this->saveAdvert();
            $this->sendSuccessfulOrderMessage();
        } else {
            Chat::sendTextMessage('please select correct payment method');
        }
    }

    private function advertNotAvailable(): void
    {
        TemporaryRepository::deleteTemporary('Add_Promotion', 'final_date');
        TemporaryRepository::emptyAnswer('Add_Promotion', 'initial_date');
        $this->sendUnavailableMessage();
        $this->sendInitialDateMessage();
    }
    private function sendUnavailableMessage(): void
    {
        Chat::sendTextMessage('Advert Un Available Please Select Another Day');
    }

    private function sendSuccessfulMessageForView(): void
    {
        $text_message = $this->makeSuccessfulMessageForView($this->getNumberOfView(), GeneralService::checkTotalPriceOfAdvert($this->getNumberOfView()), GeneralService::per_view_price);
        Chat::sendEditTextMessage($text_message, null, GeneralService::getChatIdFromCallBack(), GeneralService::getMessageIDFromCallBack());
    }

    private function makeSuccessfulMessageForView($number_of_view, $total_price, $per_view): string
    {
        return '<strong>Total Number Of View :</strong> ' . $number_of_view . "\n" .
            '<strong>Total Price:</strong>' . $total_price . "\n" .
            '<strong>Price Per View:</strong> ' . $per_view . "\n" . "\n" .
            '<strong>View And Price Selected Successfully</strong>';
    }
    public function getNumberOfView(): int
    {
        if ($this->response->answer === null) {
            return GeneralService::default_number_of_view;
        }
        return $this->response->answer;
    }

    public function saveAdvert(): void
    {
        $this->temporary = $this->getTemporaryInfo();
        $this->totalPrice = GeneralService::checkTotalPriceOfAdvert($this->temporary['no_view']);
        $chat_id = Chat::$chat_id;
        $this->advert = EthioAdvertPost::create([
            'advertiser_id'           =>   $chat_id,
            'text_message'            =>   $this->temporary['text_message'],
            'image_path'              =>   $this->temporary['image_path'],
            'initial_date'            =>   $this->temporary['initial_date'],
            'final_date'              =>   $this->temporary['final_date'],
            'no_view'                 =>   $this->getNumberOfView(),
            'name_of_the_advert'      =>   $this->temporary['name_of_the_advert'],
            'description_of_advert'   =>   $this->temporary['description_of_advert'],
            'payment_code'            =>   $this->temporary['payment_code'],
            'payment_per_view'        =>   GeneralService::per_view_price,
            'amount_of_payment'       =>   $this->totalPrice,
            'active_status'           =>   false,
            'approve_status'          =>   false,
            'payment_status'          =>   false,
        ]);
    }

    private function sendSuccessfulOrderMessage(): void
    {
        $text =  $this->makeSuccessfulMessage();
        Chat::deleteTemporaryData();
        Chat::sendTextMessage($text);
    }

    private function makeSuccessfulMessage(): string
    {
        $this->bank_account = BankRepository::getBankInformation($this->temporary['payment_code']);
        return '______<b >Order Successful !!</b>________' . "\n" .
            '      <i>Make The Payment now to activate your advert</i>            ' . "\n" .
            '______<b> Order Summary !</b>_______' . "\n" .
            '<b>Advert Name: </b> ' . $this->advert->name_of_the_advert . "\n" .
            '<b>Advert ID:</b>' . $this->advert->id . "\n" .
            '<b>Price:</b>' . $this->advert->amount_of_payment . "\n\n" . "\n" .
            '__________<b>Bank Account Detail !</b>______' . "\n" .
            '<b>Bank :</b>' . $this->bank_account->bank_name . "\n" .
            '<b>Acc Name:</b>' . $this->bank_account->account_holder_name . "\n" .
            '<b>Acc Number:</b>' . $this->bank_account->account_number . "\n\n" .
            '<i><b >Notice !!</b></i>' . "\n" .
            '<i>payment will be verified by entering ref number,hold your recipient after you make payment </i>' . "\n\n" .
            '<i> use My Promotions Button To See Advert Info and To Edit The Advert</i>';
    }
}
