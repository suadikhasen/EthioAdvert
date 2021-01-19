@extends('admin.extensions.mainframe')
@section('page_title')
  list of transaction numbers
@endsection
@section('main_content')
<div class="row ">
    <div class="col-md-12">
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
                      <a href="#" class="btn btn-sm btn-primary">Edit</a>
                    <a href="#" class="btn btn-sm btn-danger">  Delete</a></td>
              </tr>
          @endforeach
       </table>
       {{$transaction_numbers->links()}}
    </div>

</div>
@endsection