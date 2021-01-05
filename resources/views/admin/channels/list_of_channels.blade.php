@extends('admin.extensions.mainframe')
@section('page_title')
    list of channels
@endsection
@section('main_content')
    <table class="table table-bordered">
        <thead>
            <b>List Of Channels</b> <br>
        </thead>
        <tr>
          <th>Name</th>
          <th>User Name</th>
          <th>Id</th>
          <th>Status</th>
          <th>Level</th>
          <th>View More</th>
        </tr>
        @foreach ($channels as $channel)
            <tr>
                <td>{{ $channel->name}}</td>
                <td>{{ $channel->username}}</td>
                <td>{{$channel->channel_id}}</td>
                <td>{{approve_status($channel->approve_status)}}</td>
                <td>{{channelLevel($channel)}}</td>
            <td><a href="{{ route('admin.detail_about_advert',['id' => $channel->channel_id])}}" class="btn btn-block"> View More</a></td>
            </tr>
        @endforeach
        {{ $channels->links()}}
    </table>
@endsection