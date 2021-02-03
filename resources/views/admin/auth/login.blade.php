@extends('adminlte::auth.login')
    @if(Session::has('error_notification'))
     @include('admin.Includes.error_notification',['notification' => Session::get('error_notification')])
    @endif
    @if(Session::has('success_notification'))
       @include('admin.Includes.success_notification',['notification' => Session::get('success_notification')])
    @endif
