@extends('admin.extensions.mainframe')
@section('page_title')
    Add new Payment Method for Channel owners
@endsection
@section('main_content')
    <div class="row">
        <div class="col-md-6">
            @if (Session::has('success_notification'))
                <div class="alert alert-success bg-success">
                    {{Session::get('success_notification')}}
                </div>
            @endif
            <form method="POST" action="{{route('admin.payments.save_new_payment_method_for_channel_owners')}}">
                <div class="form-group">
                    <label for="payment_method_name">Payment Method Name</label>
                    <input class="form-control" placeholder="please enter payment method name"  id="payment_method_name" name="payment_method_name" required autofocus>
                    @error('payment_method_name')
                        <small class="text-bold text-danger">{{ $message }}</small>
                    @enderror
                </div>
                @csrf
                <button class="btn btn-dark btn-block"> Add </button>
            </form>
        </div>
    </div>
@endsection