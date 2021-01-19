@inject('service', 'App\Services\Injections\LevelAttributeService')
@extends('admin.extensions.mainframe')
@section('page_title')
    {{ $page_tittle }}
@endsection
@section('main_content')
    @if (Session::has('succes_notification'))
       @include('admin.Includes.success_notification',['notification' => Session::get('succes_notification')])     
    @endif
    @if (Session::has('error_notification'))
       @include('admin.Includes.error_notification',['notification' => Session::get('error_notification')])     
    @endif
    <div class="col-md-6">
        <form action="@if($service->checkRouteIsEditAttribute()) {{route('admin.levels.save_edit_level_attribute',$attribute->id)}} @else {{route('admin.levels.save_new_level_attribute')}} @endif" method="POST">
            <div class="form-group">
                <label for="attribute_name">Attribute Name</label>
                <input class="form-control" type="text" name="attribute_name" id="attribute_name" placeholder="enter attribute name" required autofocus value="@if($service->checkRouteIsEditAttribute()) {{ $attribute->attributes_name}} @else {{old('attribute_name')}} @endif">
                @error('attribute_name')
                    <small class="text-bold text-danger">{{$message}}</small>
                @enderror
            </div>

            <div class="form-group">
                <label for="attribute_maximum_value">Attribute Maximum Values</label>
                <input class="form-control" type="text" name="attribute_maximum_value" id="attribute_maximum_value" placeholder="enter attribute name" required autofocus value="@if($service->checkRouteIsEditAttribute()) {{ $attribute->attribute_maximum_value}} @else {{old('attribute_maximum_value')}} @endif">
                @error('attribute_maximum_value')
                    <small class="text-bold text-danger">{{$message}}</small>
                @enderror
            </div>

            <div class="form-group">
                <label for="attribute_percentage_value">Attribute Percentage Value</label>
                <input class="form-control" type="text" name="attribute_percentage_value" id="attribute_percentage_value" placeholder="enter attribute name" required autofocus value="@if($service->checkRouteIsEditAttribute()) {{ $attribute->attribute_percentage_value}} @else {{old('attribute_percentage_value')}} @endif">
                @error('attribute_percentage_value')
                     <small class="text-bold text-danger">{{$message}}</small>
                @enderror
            </div>
            @csrf

            <button type="submit" class="btn btn-primary btn-block">
                @if($service->checkRouteIsEditAttribute())
                   Edit
                @else
                  Add   
                @endif
            </button>
        </form>
    </div>
@endsection
