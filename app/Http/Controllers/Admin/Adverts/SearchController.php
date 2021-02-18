<?php

namespace App\Http\Controllers\Admin\Adverts;

use App\Http\Controllers\Controller;
use App\TelgramBot\Database\Admin\PaymentRepository;
use App\TelgramBot\Database\AdvertsPostRepository;
use Illuminate\Http\Request;
use App\TelegramPost;
use Illuminate\Support\Carbon;

class SearchController extends Controller
{
    public function search(Request $request)
    {
        if($request->advert_options === 'Posting Adverts')
          return $this->postingAdvert();
        elseif($request->advert_options === 'Closing Adverts')
         return  $this->closingAdverts();
        elseif($request->advert_options === 'Active Adverts')
          return $this->activeAdverts();
        elseif($request->advert_options === 'Closed Adverts')
           $this->closedAdverts();   
        elseif($request->advert_options === 'Expired Adverts')
           $this->expiredAdverts();   
        elseif($request->advert_options === 'Opened Adverts')
          $this->openedAdverts();
        elseif($request->advert_options === 'Pending Adverts')
          return $this->pendingAdverts();   
        elseif($request->advert_options === 'Advert Id')
           $this->uniqueAdverts();
    }

    public function activeAdverts() 
    {
      $adverts = AdvertsPostRepository::findActiveAdverts();
      return view('admin.adverts.list_of_adverts',['adverts' => $adverts]);
    }

    public function postingAdvert() 
    {
        $adverts = AdvertsPostRepository::findAdvertsForPosting();
        $collection = collect();
        foreach($adverts as $advert){
           if(!$this->checkTodayPostExistence($advert->id)){
              $collection->push($advert);
           }
        }
        $adverts = PaymentRepository::paginate($collection,10);
        return view('admin.adverts.list_of_adverts',['adverts' => $adverts]);
    }

    public function closingAdverts()
    {
       $adverts = AdvertsPostRepository::findAdvertsForPosting();
       $collection = collect();
       foreach($adverts as $advert){
         if($this->checkNotClosdAdvertExistence($advert->id)){
            $collection->push($advert);
         }
      }
      $adverts = PaymentRepository::paginate($collection,10);
      return view('admin.adverts.list_of_adverts',['adverts' => $adverts]);
    }

    private function checkTodayPostExistence($advert_id)
    {
      return  TelegramPost::whereDate('created_at',Carbon::today())
      ->where('ethio_advert_post_id',$advert_id)
      ->exists();
    }

    private function checkNotClosdAdvertExistence($advert_id)
    {
      return  TelegramPost::whereDate('created_at',Carbon::today())
      ->where('ethio_advert_post_id',$advert_id)
      ->where('active_status',1)->exists();
    }

    public function pendingAdverts()
    {
       $adverts = AdvertsPostRepository::pendingAdverts();
       return view('admin.adverts.list_of_adverts',['adverts' => $adverts]);
    }

    

    
}
