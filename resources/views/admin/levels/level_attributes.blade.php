@extends('admin.extensions.mainframe')
@section('page_title')
 {{ $page_tittle}}
@endsection
@section('main_content')
    @if (Session::has('success_notification'))
      @include('admin.Includes.success_notification',['notification' => Session::get('success_notification', 'error occured')])     
    @endif
    <div class="mb-2">
       <a href="{{route('admin.levels.add_new_level_attribute')}}" class="btn btn-primary btn-block">AddNew</a>
       <span class="text-bold">Total Percentage Added:{{'   '.$total_percents}}</span>

    </div>
     @include('admin.Includes.table',
     [

         'column_headers'   =>    $column_headers,
         'column_keys'      =>    $column_keys,
         'retrieved_values' =>    $attributes,
         'table_header'     =>    $page_tittle
    ])
@endsection