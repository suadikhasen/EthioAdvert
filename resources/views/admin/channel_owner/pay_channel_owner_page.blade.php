@extends('admin.extensions.mainframe')
@section('page_title')
  payment for {{ $user->full_name.'  with amount '.$pending_payment}}
@endsection
@section('main_content')
    <div class="row ">
        <div class="col-md-5">
            <div class="card card-success">
                <div class="card-title  align-content-center">
                  <b class="text-bold text-info ml-2 mt-2 mb-2"> User Basic Information </b>
                </div>
                    <div class="card-body">
                        <div class="list-group-item">
                            <p class="text-bold"> Full Name : {{ $user->full_name}}</p>
                            <p class="text-bold"> Phone Number : {{ $user->phone_number}}</p>
                            <p class="text-bold"> Chat Id :{{ $user->chat_id}}</p>
                        </div>
                    </div>
            </div>
        </div>

        <div class="col-md-5">
            <div class="card card-success">
                <div class="card-title  align-content-center">
                  <b class="text-bold text-info ml-2 mt-2 mb-2"> Payment Information </b>
                </div>
                    <div class="card-body">
                        @if ($user->payment_method !== null)
                            <div class="list-group-item">
                                <p class="text-bold"> Payment Method : {{ $user->payment_method->bank->bank_name}}</p>
                                <p class="text-bold"> Payment Holder Identification Number : {{ $user->payment_method->bank->account_number}}</p>
                                <p class="text-bold"> Payment Holder Name :{{ $user->payment_method->bank->bank_name}}</p>
                                <p class="text-bold"> Paid Amount:{{ $pending_payment}}</p>
                            </div>
                            <a class="btn btn-secondary btn-block mt-2" href="{{route('admin.channel_owners.pay_to_channel_owners',[$user->chat_id,$pending_payment])}}">Pay</a>
                        @else
                          <p class="text-bold bg-danger"> No Payment Method</p>  
                        @endif
                    </div>
            </div>
        </div>
    </div>
@endsection