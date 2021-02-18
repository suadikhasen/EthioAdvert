@extends('admin.extensions.mainframe')
@section('page_title')
    {{config('adminlte.title').' add new transaction number'}}
@endsection
@section('main_content')
    <div class="row">
        
        <div class="col-md-6">
            @if (Session::has('success_notification'))
                @include('admin.Includes.success_notification',['notification' => Session::get('success_notification', 'error occured')])     
            @endif
            @if (Session::has('error_notification'))
                @include('admin.Includes.error_notification',['notification' => Session::get('error_notification', 'error occured')])     
            @endif
            <b class="text-bold bg-primary">New Transaction </b>
                <form method="POST" action="{{route('admin.transaction_numbers.save_transaction_number')}}">
                    <div class="form-group">
                        <label for="transaction_number">Transaction Number </label>
                        <input class="form-control" id="transaction_number" name="transaction_number" placeholder="enter transaction number" required autofocus>
                        @error('transaction_number')
                            <small class="text-bold text-red">{{$message}}</small>
                        @enderror
                    </div>

                    <div class="form-group">
                        <label for="amount">Amount Of Money </label>
                        <input class="form-control" id="amount" name="amount" placeholder="enter amount" required autofocus>
                        @error('amount')
                            <small class="text-bold text-red">{{$message}}</small>
                        @enderror
                    </div>

                    
                    <div class="form-group">
                        <label for="payment_method">Select Payment Method</label>
                        <select class="form-control" id="payment_method" name="payment_method">
                            @foreach ($payment_method as $payment)
                                <option value="{{$payment->id}}"> {{$payment->bank_name}}</option>
                            @endforeach
                        </select>
                        @error('payment_method')
                            <small class="text-bold text-red">{{$message}}<small>
                        @enderror
                    </div> 
                    @csrf
                    <button class="btn btn-primary" type="submit">Add</button>
                </form>
        </div>
    </div>
        
@endsection