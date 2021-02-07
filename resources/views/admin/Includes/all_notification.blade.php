@if (Session::has('success_notification'))

    <div class="alert alert-success bg-success">
        {{Session::get('success_notification')}}
    </div>
    
@endif