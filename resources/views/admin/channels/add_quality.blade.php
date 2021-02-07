@extends('admin.extensions.mainframe')
@section('page_title')
    {{ config('adminlte.title').' add quality for channels' }}
@endsection
@section('main_content')
<div class="container col-md-6">
            @if (Session::has('success_notification'))
                @include('admin.Includes.success_notification',['notification' => Session::get('success_notification', 'error occured')])     
            @endif
            @if (Session::has('error_notification'))
                @include('admin.Includes.error_notification',['notification' => Session::get('error_notification', 'error occured')])     
            @endif
    <div class="container">
        <p>Adding Quality for channel {{ $channel->name}}</p>
    </div>
    <form method="POST" action="">
        <div class="form-group">
            <label for="quality"> Quality </label>
            <input type="number" class="form-control" placeholder="Enter quality " id="quality" name="quality" autofocus required>
            @error('quality')
            <small  class="text-bold text-danger">
                {{ $message }}
            </small>
            @enderror
        </div>
       @csrf
       <button class="btn btn-success" type="submit"> Assign </button>
     </form>
</div>
     
@endsection