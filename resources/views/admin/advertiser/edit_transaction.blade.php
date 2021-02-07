@extends('admin.extensions.mainframe')
@section('page_title')
    {{config('adminlte.title').' edit transaction number'}}
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
            <b class="text-bold bg-primary">Edit Transaction </b>
                <form method="POST" action="{{route('admin.transaction_numbers.edit_transaction',$transaction->id)}}">
                    <div class="form-group">
                        <label for="transaction_number"> Transaction Number </label>
                        <input class="form-control" id="transaction_number" name="transaction_number" placeholder="enter transaction number" required autofocus value="{{ $transaction->ref_number }}">
                        @error('transaction_number')
                            <small class="text-bold text-red">{{$message}}</small>
                        @enderror
                    </div>
                    <div class="form-group">
                        <label for="payment_method"> Payment Method </label>
                        <select class="form-control" id="payment_method" name="payment_method">
                            @foreach ($payment_method as $payment)
                                <option value="{{$payment->id}}" @if($transaction->payment_method_code === $payment->id) selected @endif> {{$payment->bank_name}}</option>
                            @endforeach
                        </select>
                        @error('payment_method')
                            <small class="text-bold text-red">{{$message}}<small>
                        @enderror
                    </div> 
                    @csrf
                    <button class="btn btn-primary" type="submit">Edit</button>
                </form>
        </div>
    </div>     
@endsection