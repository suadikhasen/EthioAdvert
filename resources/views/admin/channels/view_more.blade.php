@inject('view_more', 'App\Services\Injections\ViewMore')
@extends('admin.extensions.mainframe')
@section('page_title')
    Detail information about {{ $channel->name}}
@endsection
@section('main_content')
<div class="row">
   <div class="col-md-10 align-content-center">
       <div class="card card-info card-blue align-content-center">
        <div class="card-title card-green mb-1 align-content-center">
            <b class="align-content-center"> {{$channel->name}} channel basic information </b>
         </div><br>
          <div class="card-body">
            <div class="list-group">
              <div class="list-group-item text-bold align-content-center">
              <p>Name : {{ $channel->name}}</p>
              <p>channel Id : {{ $channel->channel_id}}</p>
              <p>username : {{ $channel->username}}</p>
              <p>
                Channel Level : {{channelLevel($channel)}}<br>
              </p>
              <p>
                number of members : {{ $channel->number_of_member}}
              </p>
              <p>per day posts : {{ $channel->per_day_posts}}</p>
              <p>
                approve status : {{ approve_status($channel->channel_id)}}
                @if(!$channel->approve_status)
                <a href="#" class="btn btn-success">  approve </a>
                @endif
              </p>
              <p>removed status : {{ channel_removed_status($channel->remove_status )}}</p>
              @if ($channel->block_status)
              <p> Block Status:Blocked</p>
              <a href="{{ route('admin.channels.unblock_channel',$channel->channel_id)}}" class="btn btn-success"> Un Block </a>
              @else
               <p> Block Status:Not Blocked</p>
               <div>
               <a href="{{ route('admin.channels.block_channel',$channel->channel_id)}}" class="btn btn-danger">  Block </a>
               @endif 
               <a href="{{route('admin.channels.view_channels_advert',$channel->channel_id)}}" class="btn btn-primary">  View Adverts </a>
                 <a href="{{ route('admin.channels.update_information',$channel->channel_id) }}" class="btn btn-dark btn-medium"> Update Information </a>
                 <a href="#" class="btn btn-primary"> Assign / Update Level </a>
               </div>
              </div>
            </div>
          </div>
       </div>
   </div>
   
</div>
    
@endsection