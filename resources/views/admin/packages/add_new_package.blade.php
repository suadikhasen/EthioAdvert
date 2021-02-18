@extends('admin.extensions.mainframe')
@section('page_title')
    add new package
@endsection
@section('main_content')
    <div class="row align-content-center">
        <div class="col-md-8">
          @if(Session::has('success_notification'))
            @include('admin.Includes.success_notification',['notification' => Session::get('success_notification')])
          @endif
          @if(Session::has('error_notification'))
            @include('admin.Includes.error_notification',['notification' => Session::get('error_notification')])
          @endif
        </div>
        <div class="col-md-6 bg-dark">
            
            <div class="">
               <b class="text-bold">Package Adding</b>
            </div>
           <form method="POST" action="{{ route('admin.packages.save_package')}}">
               <div class="form-group">
                   <label for="package_name"> Name</label>
                   <input class="form-control @error('package_name') is-invalid  @enderror" id="package_name" name="package_name" required autofocus placeholder="Enter Package Name Here" value="{{old('package_name')}}">
                   @error('package_name')
                       <small class="text-bold text-danger">{{$message}}</small>
                   @enderror
               </div>

               <div class="form-group">
                <label for="package_price"> Price </label>
                <input class="form-control @error('package_price') is-invalid  @enderror" id="package_price" name="package_price" required autofocus placeholder="Enter Package Price" value="{{old('package_price')}}">
                  @error('package_price')
                       <small class="text-bold text-danger">{{$message}}</small>
                   @enderror
               </div>
               <div class="form-group">
                   <label for="package_number_of_days"> Number Of Days</label>
                   <input class="form-control @error('package_number_of_days') is-invalid  @enderror" id="package_number_of_days" name="package_number_of_days" required autofocus placeholder="Enter Number Of Days" value="{{old('package_number_of_days')}}">
                   @error('package_number_of_days')
                       <small class="text-bold text-danger">{{$message}}</small>
                   @enderror
               </div>
               <div class="form-group">
                  <label for="package_initial_posting_time"> Initial Posting Time</label>
                  <input class="form-control @error('package_initial_posting_time') is-invalid  @enderror" id="package_initial_posting_time" name="package_initial_posting_time" required autofocus placeholder="Enter Initial Posting Time like 12:00" value="{{old('package_initial_posting_time')}}">
                  @error('package_initial_posting_time')
                       <small class="text-bold text-danger">{{$message}}</small>
                   @enderror
               </div>
               <div class="form-group">
                   <label for="package_final_posting_time"> Final Posting Time</label>
                   <input class="form-control  @error('package_final_posting_time') is-invalid  @enderror" " id="package_final_posting_time" name="package_final_posting_time" required autofocus placeholder="Enter Final Posting Time" value="{{old('package_final_posting_time')}}"  >
                   @error('package_final_posting_time')
                     <small class="text-bold text-danger">{{$message}}</small>
                   @enderror
               </div>
                <div class="form-group">
                    <label for="package_level"> Level </label>
                    <select name="package_level" id="package_level" class="form-control 
                    @error('package_level')
                        is-invalid
                    @enderror" 
                    value="{{old('package_level')}}">
                        @foreach ($levels as $level)
                          <option >{{ $level->level_name }}</option>                       
                        @endforeach
                    </select>
                    @error('package_level')
                      <small class="text-bold text-danger">{{$message}}</small>
                    @enderror
                </div>
                @csrf
             <button class="btn btn-success" type="submit">Add</button>
           </form>
        </div>
    </div>
@endsection