@extends('admin.extensions.mainframe')
@section('page_title')
    {{ $tittle }}
@endsection
@section('main_content')
    @if (Session::has('success_notification'))
    @include('admin.Includes.success_notification',['notification' => Session::get('success_notification', 'error occured')])     
    @endif
    @if (Session::has('error_notification'))
        @include('admin.Includes.error_notification',['notification' => Session::get('error_notification', 'error occured')])     
    @endif
    <table class="table table-bordered table-dark">
        <thead>
            <b>{{ $table_header }}</b> <br>
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
                <td>{{ $channel->channel_id}}</td>
                <td>{{approve_status($channel->approve_status)}}</td>
                <td>{{channelLevel($channel)}}</td>
            <td><a href="{{ route('admin.detail_about_advert',['id' => $channel->channel_id])}}" class="btn btn-sm btn-success"> View More</a></td>
            </tr>
        @endforeach
        
    </table>
    {{ $channels->links()}}
@endsection