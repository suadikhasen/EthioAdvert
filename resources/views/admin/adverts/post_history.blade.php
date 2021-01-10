@extends('admin.extensions.mainframe')
@section('page_title')
    post history of  {{ $advert->name_of_the_advert}} advert
@endsection
@section('main_content')
<div class="row align-content-center">
    
   <div class="align-content-center col-md-10">
    @if (($telegram_posts_of_the_advert->isEmpty()))
            <div class="bg-danger text-white text-bold ">
                no post available
            </div>
    @endif
       @foreach ($telegram_posts_of_the_advert as  $single_post)
           <div class="card card-success">
             @foreach ($single_post as $single)
                @if ($loop->iteration === 1)
                <div class="card-title bg-success align-content-center">
                    <a class="text-bold text-align-center" href="{{route('admin.channels.view_channels_advert',$single->channel_id)}}"> on channel : {{ $single->name}}</a> 
                    <span class="ml-3"> {{ $loop->count.' times posted' }}<span>
                </div>
                @endif
                    <div class="card-body">
                        <div class="list-group-item">
                                 <b> {{ $loop->iteration}} </b>
                                <p class="text-bold"> posting time : {{ 'from '.$advert->initial_time.' to'.$advert->final_time}}</p>
                                <p class="text-bold"> postig date : {{ $single->created_at}}</p>
                                <p class="text-bold"> status:
                                    @if ($single->active_status === 1)
                                        Active Now
                                    @elseif($single->active_status === 2 || $single->active_status === 3)
                                    closed 
                                    @endif
                               </p>
                        </div>
                    </div>
               @endforeach
           </div>
       @endforeach
   </div>
</div>
@endsection