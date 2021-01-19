@extends('admin.extensions.mainframe')
@section('page_title')
    add new level
@endsection
@section('main_content')
    <div class="row align-content-center">
        <div class="col-md-5 mt-5">
            @if (Session::has('success_notification'))
             @include('admin.Includes.success_notification',['notification' => Session::get('success_notification', 'error occured')])     
            @endif
            <p >Last Added Level:{{ $last_level }}</p><br>
           <form method="POST" action="{{route('admin.levels.save_level')}}">
               <div class="form-group">
                   <label for="level_number"> Level Number</label>
                   <input type="number" class="form-control" placeholder="Enter Level Number" id="level_number" name="level_number" autofocus required>
                   <small id="level_number_help" class="form-text tex-muted">
                       enter number greater than 0 and must not skip last level number successer
                    </small>
               </div>
               @csrf
               <button type="submit" class="btn btn-primary"> Add </button>
           </form>
        </div>
    </div>
@endsection