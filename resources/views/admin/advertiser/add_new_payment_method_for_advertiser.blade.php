@extends('admin.extensions.mainframe')
@section('page_title')
    add new payment method for advertiser
@endsection
@section('main_content')
    <div class="row">
        <div class="col-md-6">
            @if (Session::has('success_notification'))
                <div class="alert alert-success bg-success">
                    {{Session::get('success_notification')}}
                </div>
            @endif
            <form method="POST" action="{{route('admin.payments.save_new_payment_method_for_advertiser')}}">

                <div class="form-group">
                    <label for="payment_method_name">Payment Method Name</label>
                    <input class="form-control" id="payment_method_name" name="payment_method_name" placeholder="enter payment method name" required autofocus>
                    @error('payment_method_name')
                        <small class="text-bold text-red">{{$message}}<small>
                    @enderror
                </div>

                <div class="form-group">
                    <label for="payment_method_holder_name">Payment Method Holder Name</label>
                    <input class="form-control" id="payment_method_holder_name" name="payment_method_holder_name" placeholder="enter payment method holder name" required autofocus>
                    @error('payment_method_holder_name')
                        <small class="text-bold text-red">{{$message}}<small>
                    @enderror
                </div>

                <div class="form-group">
                    <label for="payment_method_holder_identification_number">Payment Method Holder Identification Number</label>
                    <input class="form-control" id="payment_method_holder_identification_number" name="payment_method_holder_identification_number" placeholder="enter payment method holder identification number" required autofocus>
                    @error('payment_method_holder_identification_number')
                        <small class="text-bold text-red">{{$message}}<small>
                    @enderror
                </div>
                @csrf
               <button class="btn btn-dark btn-block" type="submit"> Add </button>
            </form>
        </div>
    </div>
@endsection