@extends('admin.extensions.mainframe')
@section('page_title')
    add new package
@endsection
@section('main_content')
    <div class="row align-content-center">
        <div class="col-md-6 bg-info">
            <div class="">
               <b class="text-bold">Package Adding</b>
            </div>
           <form method="POST" action="{{ route('admin.packages.save_package')}}">
               <div class="form-group">
                   <label for="package_name"> Name</label>
                   <input class="form-control" id="package_name" name="package_name" required autofocus placeholder="Enter Package Name Here">
               </div>

               <div class="form-group">
                <label for="package_price"> Price </label>
                <input class="form-control" id="package_price" name="package_price" required autofocus >
               </div>

               <div class="form-group">
                <label for="package_number_of_days"> Number Of Days</label>
                <input class="form-control" id="package_number_of_days" name="package_number_of_days" required autofocus >
               </div>

               <div class="form-group">
                <label for="package_initial_posting_time"> Initial Posting Time</label>
                <input class="form-control" id="package_initial_posting_time" name="package_initial_posting_time" required autofocus >
               </div>

               <div class="form-group">
                <label for="package_final_posting_time"> Final Posting Time</label>
                <input class="form-control" id="package_final_posting_time" name="package_final_posting_time" required autofocus >
               </div>
                <div class="form-group">
                    <label for="package_level"> Level </label>
                    <select name="package_level" id="package_level" class="form-control">
                        @foreach ($levels as $level)
                          <option>{{ $level->level_name }}</option>                       
                        @endforeach
                    </select>
                </div>
                @csrf
             <button class="btn btn-success" type="submit">Add</button>
           </form>
        </div>
    </div>
@endsection