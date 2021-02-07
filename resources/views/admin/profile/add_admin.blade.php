@extends('admin.extensions.mainframe')
@section('page_title')
    {{ config('adminlte.title').' Admin Adding'}}
@endsection
@section('main_content')
<div class="row align-content-center">
    <div class="col-md-5">
        @if (Session::has('success_notification'))
          @include('admin.Includes.success_notification',['notification' => Session::get('success_notification', 'error occured')])     
        @endif
        @if (Session::has('error_notification'))
          @include('admin.Includes.error_notification',['notification' => Session::get('error_notification', 'error occured')])     
        @endif
        <p >Add Another  Admin</p>
       <form method="POST" action="{{route('admin.add_admin')}}">
          
        <div class="form-group">
            <label for="full_name"> Email </label>
            <input type="string" class="form-control @error('full_name') is-invalid @enderror" placeholder="Enter Full Name" id="full_name" name="full_name" autofocus required>
            @error('full_name')
              <small class="text-danger">{{ $message }}</small>
            @enderror
        </div>

           <div class="form-group">
               <label for="email"> Email </label>
               <input type="email" class="form-control @error('email') is-invalid @enderror" placeholder="Enter Email" id="email" name="email" autofocus required>
               @error('email')
                 <small class="text-danger">{{ $message }}</small>
               @enderror
           </div>
            <div class="form-group ">
              <label for="password"> Password </label>
              <input type="password" class="form-control @error('password') is-invalid @enderror" placeholder="Password" id="password" name="password" autofocus required>
                @error('password')
                    <small class="text-danger">{{ $message }}</small>
                @enderror
            </div>
            <div class="form-group ">
                <label for="password_confirmation"> Confirm   Password </label>
                <input type="password" class="form-control @error('password_confirmation') is-invalid @enderror" placeholder="Confirms Password" id="password_confirmation" name="password_confirmation" autofocus required>
                @error('password_confirmation')
                    <small class="text-danger">{{ $message }}</small>
                @enderror
            </div>   
           @csrf
           <button type="submit" class="btn btn-primary"> Add </button>
       </form>
    </div>
</div>
@endsection
