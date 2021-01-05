@extends('admin.extensions.mainframe')
@section('page_title')
    list of adverts
@endsection
@section('main_content')
   <div class="mb-2">
    <form method="get" action="{{route('admin.adverts.search_adverts')}}" class="mb-2">
        <select name="advert_options" class="form-select">
            <option selected>PostingAdverts<option>
            <option>Closing Adverts</option>
            <option>Active Adverts </option>
            <option>Closed Adverts </option>
            <option>Expired Adverts <option>
            <option>Opened Adverts </option>
            <option>Pending Adverts</option>
        </select>
        <button class="btn btn-primary btn-sm" type="submit" class="form-submit"> Search</button>
        <form>  
   </div>
    
    <table class="table table-bordered">
        <thead>
            <b>List Of Adverts</b> <br>
        </thead>
        <tr>
          <th>Name</th>
          <th>Advert Id</th>
          <th>Approve Status</th>
          <th>View More</th>
        </tr>
        @foreach ($adverts as $advert)
            <tr>
                <td>{{ $advert->name_of_the_advert }}</td>
                <td>{{ $advert->id}}</td>
                <td>{{approve_status($advert->approve_status)}}</td>
            <td>
                <a href="{{ route('admin.adverts.detail_about_advrt',$advert->id)}}" class="btn btn-block btn-primary"> View More</a>
            </td>
            </tr>
        @endforeach
        {{ $adverts->links()}}
    </table>
@endsection