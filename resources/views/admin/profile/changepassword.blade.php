@extends('admin.extensions.mainframe')
@section('page_title')
    {{ config('adminlte.title').' Admin Log In'}}
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
            <p >Change Your Password</p>
           <form method="POST" action="{{route('admin.change_password')}}">
               <div class="form-group">
                   <label for="old_password"> Old Password </label>
                   <input type="password" class="form-control @error('old_password') is-invalid @enderror" placeholder="Enter Old Password" id="old_password" name="old_password" autofocus required>
                   @error('old_password')
                     <small class="text-danger">{{ $message }}</small>
                   @enderror
               </div>
                <div class="form-group ">
                  <label for="current_password"> Current  Password </label>
                  <input type="password" class="form-control @error('current_password') is-invalid @enderror" placeholder="Current Password" id="current_password" name="current_password" autofocus required>
                    @error('current_password')
                        <small class="text-danger">{{ $message }}</small>
                    @enderror
                </div>
                <div class="form-group ">
                    <label for="current_password_confirmation"> Confirm Current  Password </label>
                    <input type="password" class="form-control @error('current_password_confirmation') is-invalid @enderror" placeholder="Confirms Password" id="current_password_confirmation" name="current_password_confirmation" autofocus required>
                    @error('current_password_confirmation')
                        <small class="text-danger">{{ $message }}</small>
                    @enderror
                </div>   
               @csrf
               <button type="submit" class="btn btn-primary"> Change </button>
           </form>
        </div>
    </div>
@endsection