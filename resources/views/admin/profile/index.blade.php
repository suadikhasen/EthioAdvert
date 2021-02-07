@extends('admin.extensions.mainframe')
@section('page_title')
   admin profile
@endsection
@section('main_content')
    <div class="col-md-6 align-center">
        <div class="card card-success">
            <div class="card-header">
                {{ $admin->full_name }}
            </div>
            <div class="card-body">
                <a href="{{route('admin.change_password_page')}}" class="btn btn-primary btn-block mr-3 ">Change Password</a>
                <a href="{{route('admin.add_admin_page')}}" class="btn btn-primary btn-block mr-3 ">Add Admin</a>
            </div>
        </div>
    </div>
@endsection