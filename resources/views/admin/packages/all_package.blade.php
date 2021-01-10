@extends('admin.extensions.mainframe')
@section('page_title')
    {{ $tittle }}
@endsection
@section('main_content')
    <div class="row content-align-center">
        <div class="col-md-10 mb-3 bg-info align-content-center ">
            <b>{{ $table_header }}</b>   
          <a class="btn btn-sm btn-success  ml-5" href="{{route('admin.packages.add_new_package')}}">Add New <a>
        </div>
       <div class="col-md-10">
           <table class="table table-bordered table-dark">
            <tr>
              <th>Name</th>
              <th>Channel Level</th>
              <th>Initial Posting Time</th>
              <th>Final Posting Time</th>
              <th>Price</th>
              <th>Number Of Days</th>
            </tr>
            @foreach ($packages as $package)
                <tr>
                    <td>{{ $package->package_name}}</td>
                    <td> {{ $package->level->level_name}} </td>
                    <td>{{$package->initial_posting_time_in_one_day}}</td>
                    <td>{{$package->final_postig_time_in_one_day}}</td>
                    <td>{{$package->price}}</td>
                    <td>{{$package->number_of_days}}</td>
                </tr>
            @endforeach
           </table>
           {{ $packages->links() }}
        </div> 
    </div>
@endsection