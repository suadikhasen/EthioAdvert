@extends('admin.extensions.mainframe')
@section('page_title')
    list of channel owners
@endsection
@section('main_content')
    <table class="table table-bordered">
        <thead>
            <b>
                list of channel owners
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
        @foreach ($channel_owners as $channel_owner)
            <tr>
                <td>{{ $channel_owner->full_name }}</td>
                <td>{{ $channel_owner->chat_id}}</td>
                <td>{{($channel_owner->phone_number)}}</td>
                <td>{{ $channel_owner->created_at }}</td>
            <td>
                <a href="{{ route('admin.channel_owners.list_of_channel_owners_channel',$channel_owner->chat_id) }}" class="btn btn-primary"> View Channels</a>
            </td>
            <td>
                <a href="{{ route('admin.channel_owners.channel_owners_payment_history',$channel_owner->chat_id)}}" class="btn btn-dark"> View Payment History</a>
            </td>
            </tr>
        @endforeach
        {{ $channel_owners->links() }}
    </table>
@endsection