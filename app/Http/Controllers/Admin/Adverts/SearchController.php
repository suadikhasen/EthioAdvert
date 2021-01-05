<?php

namespace App\Http\Controllers\Admin\Adverts;

use App\Http\Controllers\Controller;
use App\TelgramBot\Database\Admin\AdvertRepository;
use Illuminate\Http\Request;

class SearchController extends Controller
{
    public function search(Request $request)
    {
        if($request->advert_options === 'PostingAdverts')
          return $this->postingAdvert();
        elseif($request->advert_options === 'Closing Adverts')
           $this->closingAdverts();
        elseif($request->advert_options === 'Active Adverts')
           $this->activeAdverts();
        elseif($request->advert_options === 'Closed Adverts')
           $this->closedAdverts();   
        elseif($request->advert_options === 'Expired Adverts')
           $this->expiredAdverts();   
        elseif($request->advert_options === 'Opened Adverts')
          $this->openedAdverts();
        elseif($request->advert_options === 'Pending Adverts')
           $this->pendingAdverts();   
        elseif($request->advert_options === 'Advert Id')
           $this->uniqueAdverts();
    }

    public function postingAdvert() 
    {
        $adverts = AdvertRepository::findPostingAdverts();
        $number_of_advert = AdvertRepository::numberOfPostingAdverts();
        return view('admin.adverts.list_of_adverts',['adverts' => $adverts,'number_of_advert' => $number_of_advert]);
    }
}
