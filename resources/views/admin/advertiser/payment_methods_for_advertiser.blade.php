@extends('admin.extensions.mainframe')
@section('page_title')
    {{ $page_tittle}}
@endsection
@section('main_content')
   <div class="col-md-6">
    <a class="btn btn-block btn-primary" href="{{route('admin.payments.add_new_payment_method_for_advertiser')}}">New<a>
   </div>
    @include('admin.Includes.table',
    [
        'table_header'        =>     $page_tittle,
        'column_headers'      =>     $column_headers,
        'column_keys'         =>     $column_keys,
        'retrieved_values'    =>     $payment_methods
    ])
@endsection