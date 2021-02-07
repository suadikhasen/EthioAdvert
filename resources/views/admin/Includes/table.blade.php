<table class="table table-bordered table-dark">
    <thead>
        <b>
            {{ $table_header }}
        </b> <br>
    </thead>
    <tr>
        @foreach ($column_headers as $column)
            <th> {{  $column }} </th>
        @endforeach
        @if(Route::currentRouteName() === 'admin.levels.list_of_level_attributes')
          <th> {{  $column }} </th>
        @endif
    </tr>
    @foreach ($retrieved_values as $item)
        <tr>
          @foreach ($column_keys as $single_key)
              <td>{{ $item[$single_key] }}</td>
          @endforeach  
          @if(Route::currentRouteName() === 'admin.levels.list_of_level_attributes')
            <td>
                <a href="{{ route('admin.levels.edit_level_of_attributes',$item->id)}}" class="btn btn-sm btn-primary"> Edit</a>
                <a href="{{ route('admin.levels.delete_level_of_attributes',$item->id)}}" class="btn btn-sm btn-danger">   Delete</a>
            </td>
          @elseif(Route::currentRouteName() === 'admin.payments.list_of_payment_methods_for_channel_owners')   
          <td>
           <a href="{{ route('admin.payments.delete_payment_method_of_channel_owners',$item->id)}}" class="btn btn-sm btn-danger">   Delete </a>
          </td>
          @endif
        </tr>
    @endforeach
    
<table>