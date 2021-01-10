@extends('admin.extensions.mainframe')
@section('page_title')
    list of level of channels
@endsection
@section('main_content')
<table class="table table-bordered table-dark">
    <thead >
        <div class="mb-1">
        <b>List Of Level of Channels </b>      <span class="align-right ml-5">Total Levels : {{$number_of_levels}}</span> <a class="btn btn-success btn-sm ml-5" href="{{route('admin.levels.add_new_level')}}">Add New </a>  <br>
        </div>
    </thead>
    <tr>
      <th>Name</th>
      <th>Level Number</th>
      <th>Number Of Channels</th>
      <th>Options</th>
    </tr>
    @foreach ($levels as $level)
        <tr>
            <td>{{ $level->level_name}}</td>
            <td>{{ $level->level}}</td>
            <td>{{$level->number_of_channels}}</td>
        <td>
            <a href="{{route('admin.levels.list_of_channels_by_level',$level->id)}}" class="btn btn-sm btn-success"> See Channels </a>
        </td>
        </tr>
    @endforeach
    {{ $levels->links()}}
</table>
@endsection