@extends('admin.extensions.mainframe')
@section('page_title')
  payment history of a channel owner
@endsection
@section('main_content')
    <div class="row">
        <div class="mb-4 col-md-10    align-content-center">
            <div class="bg-info mb-2 mt-2">
                <span class="text-bold"> name:{{ $channel_owner->full_name}} </span>
                <span class="text-bold"> chat id : {{ $channel_owner->chat_id}}  </span>
                <span class="text-bold"> phone number : {{ $channel_owner->phone_number }}   </span>
                <span class="text-bold"> Total Earning : {{ $total_earning }}  </span>
                <span class="text-bold"> Monthly Earning : {{ $monthly_earning }}  </span>
                <span class="text-bold"> Total Paid Amount : {{ $total_paid_amount }}  </span>
                <span class="text-bold"> Pending Amount : {{ $pending_amount }}  </span>
            </div>
            @foreach ($payments as $payment)
                <div class="card card-success">
                    <div class="card-title  align-content-center">
                      <b class="text-bold text-info ml-2 mt-2 mb-2"> Payment at {{ $payment->created_at}} </b>
                    </div>
                        <div class="card-body">
                            <div class="list-group-item">
                                <p class="text-bold"> Payment Method : {{ $payment->payment_method_name}}</p>
                                <p class="text-bold"> Payment Holder Identification Number : {{ $payment->identification_number}}</p>
                                <p class="text-bold"> Payment Holder Name :{{ $payment->payment_holder_name}}</p>
                                <p class="text-bold"> Paid Amount:{{ $payment->paid_amount}}</p>
                                <p class="text-bold"> Transaction Id:{{ $payment->id}}</p>
                            </div>
                        </div>
                </div>
            @endforeach
                {{ $payments->links()}}
        </div>
    </div>
@endsection