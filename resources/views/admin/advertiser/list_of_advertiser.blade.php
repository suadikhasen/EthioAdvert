@extends('admin.extensions.mainframe')
@section('page_title')
    list of advertiserss
@endsection
@section('main_content')
    <table class="table table-bordered">
        <thead>
            <b>
                list of advertiserss
            </b> <br>
        </thead>
        <tr>
          <th>Name</th>
          <th>Chat Id</th>
          <th>Phone Number</th>
          <th> Registration Date</th>
          <th>Adverts</th>
          <th> Payment History</th>
        </tr>
        @foreach ($advertisers as $advertiser)
            <tr>
                <td>{{ $advertiser->full_name }}</td>
                <td>{{ $advertiser->chat_id}}</td>
                <td>{{($advertiser->phone_number)}}</td>
                <td>{{ $advertiser->created_at }}</td>
            <td>
                <a href="{{ route('admin.advertiser.list_of_advertisers_advert',$advertiser->chat_id) }}" class="btn btn-primary"> View Adverts</a>
            </td>
            <td>
                <a href="{{ route('admin.advertiser.list_of_payment_history',$advertiser->chat_id)}}" class="btn btn-dark"> View Payment History</a>
            </td>
            </tr>
        @endforeach
        {{ $advertisers->links() }}
    </table>
@endsection