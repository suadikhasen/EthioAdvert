@inject('payment', 'App\Services\Injections\Payment')
@extends('admin.extensions.mainframe')
@section('page_title')
    list of channel owners pending payment
@endsection
@section('main_content')

    @if (Session::has('payment_success'))
     <div class="bg-success"> Payment Paid Completed Successfully</div>
    @endif
    <b >Total {{ $total }}</b><br>
    <table class="table table-bordered">
        <thead>
            <b>
                list of channel owners pending payment
            </b> <br>
        </thead>
        <tr>
          <th>Name</th>
          <th>Chat Id</th>
          <th>Phone Number</th>
          <th>Total Pending Payments</th>
          <th>Pay</th>
        </tr>
        @foreach ($pending_payments as $pending_payment)
            <tr>
                <td>{{ $pending_payment->full_name }}</td>
                <td>{{ $pending_payment->chat_id}}</td>
                <td>{{($pending_payment->phone_number)}}</td>
                <td>{{ $pending_payment->pending_payment}}</td>
                <td><a class="btn btn-primary btn-sm" href="{{route('admin.channel_owners.go_to_payment_page',[$pending_payment->chat_id,$pending_payment->pending_payment])}}"> pay</a></td> 
            </tr>
        @endforeach
        {{ $pending_payments->links() }}
    </table>
@endsection