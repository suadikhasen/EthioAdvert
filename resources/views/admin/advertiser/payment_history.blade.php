@extends('admin.extensions.mainframe')
@section('page_title')
  payment history of a user
@endsection
@section('main_content')
    <div class="row">
        <div class="mb-4 col-md-10    align-content-center">
            <div>
                <span class="text-bold"> name:{{ $advertiser->full_name}} </span>
                <span class="text-bold"> chat id : {{ $advertiser->chat_id}}  </span>
                <span class="text-bold"> phone number : {{ $advertiser->phone_number }}   </span>
                <span class="text-bold"> Total Payment : {{ $total_payment }}  </span>
            </div>
            @foreach ($payment_histories as $payment)
                <div class="card card-success">
                    <div class="card-title  align-content-center">
                      <b class="text-bold text-info ml-2 mt-2 mb-2"> {{ $payment->adverts->name_of_the_advert}} advert payment </b>
                    </div>
                    
                        <div class="card-body">
                            <div class="list-group-item">
                                <p class="text-bold"> Payment Method : {{ $payment->paymentMethod->bank_name}}</p>
                                <p class="text-bold"> Transaction Number : {{ $payment->ref_number}}</p>
                                <p class="text-bold"> Paid For Advert :<a href="{{route('admin.adverts.detail_about_advrt',$payment->post_id)}}"> {{ $payment->adverts->name_of_the_advert }}</a></p>
                                <p class="text-bold"> Paid Amount:{{ $payment->amount}}</p>
                                <p class="text-bold"> payment verification date : {{ $payment->created_at}}</p>
                            </div>
                        </div>
                   
                    
                </div>
                @endforeach
                {{ $payment_histories->links()}}
        </div>
        
    </div>
@endsection