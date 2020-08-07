<?php


namespace App\TelgramBot\Object;


class Bank
{


    /**
     * @param $bank_code
     * @return mixed
     */
    public  function bankName($bank_code)
  {
      switch ($bank_code){
          case 1:
              return $this->listOfBanks()[1];
              break;
          case 2:
              return $this->listOfBanks()[2];
              break;

      }
  }

    /**
     * @return array
     */
    public function listOfBanks():array
  {
    return [
        1   =>   'Commercial Bank Of Ethiopia',
        2  =>   'Dashen Bank'
    ];
  }

    /**return bank code by accepting name of the bank
     * @param $answer
     * @return int
     */
    public function bakCode($answer): ?int
    {
        if ($answer === 'Commercial Bank Of Ethiopia') {
            return 1;
        }

        if ($answer === 'Dashen Bank') {
            return 2;
        } else{
            return null;
        }
    }
}
