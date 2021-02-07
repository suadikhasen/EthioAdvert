@extends('admin.extensions.mainframe')
@section('page_title')
  list of transaction numbers
@endsection
@section('main_content')
<div class="row ">
    <div class="col-md-12">
            @if (Session::has('success_notification'))
                @include('admin.Includes.success_notification',['notification' => Session::get('success_notification', 'error occured')])     
            @endif
            @if (Session::has('error_notification'))
                @include('admin.Includes.error_notification',['notification' => Session::get('error_notification', 'error occured')])     
            @endif
        <a href="{{route('admin.transaction_numbers.add_new_transaction_number')}}" class="btn btn-primary btn-block">Add New Transaction</a>
       <table class="table table-dark table-bordered">
          <thead class="text-bold"><b>List Of Transaction Numbers</b>  </thead>
          <tr>
              <th>Id</th>
              <th>Transaction Numbers</th>
              <th>Payment Methods</th>
              <th>Used Status</th>
              <th>Payment Date</th>
              <th>Options</th>
          </tr>
          @foreach ($transaction_numbers as $transaction)
              <tr>
                  <td>{{$transaction->id}}</td>
                  <td>{{$transaction->ref_number}}</td>
                  <td>{{$transaction->paymentMethod->bank_name}}</td>
                  <td>
                  @if ($transaction->used_status)
                      used
                      @else
                      not used
                  @endif</td>
                  <td>{{$transaction->created_at}}</td>
                  <td>
                      <a href="{{route('admin.transaction_numbers.edit_transaction_page',$transaction->id)}}" class="btn btn-sm btn-primary">Edit</a>
                      <a href="{{route('admin.transaction_numbers.delete_transaction',$transaction->id)}}" class="btn btn-sm btn-danger">  Delete</a></td>
              </tr>
          @endforeach
       </table>
       {{$transaction_numbers->links()}}
    </div>

</div>
@endsection