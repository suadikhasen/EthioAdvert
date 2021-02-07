@extends('admin.extensions.mainframe')
@section('page_title')
 Channels Advert Information
@endsection
@section('main_content')
<div class="row">
    <div class="col-md-12 ">
        <b> Total Number Of Advert: {{ $number_of_advert}}  Total Earning : {{ $total_earning  }}</b>
    </div>
    @foreach ($adverts as $advert)
        <div class="card card-primary">
           <div class="card-title">
              {{ $advert->adverts->name_of_the_advert}}
           </div>
           <div class="card-body">
             <div class="list-group-item text-bold">
                 <div class="text-bold">
                    Advert Id : {{ $advert->adverts->id }}
                 </div>

                 <div class="text-bold">
                    Initial Post Date : {{ $advert->adverts->gc_calendar_initial_date }}
                 </div>

                 <div class="text-bold">
                    Final Post Date : {{ $advert->adverts->gc_calendar_final_date }}
                 </div>

                 <div class="text-bold">
                    Final Post Date : {{ $advert->adverts->gc_calendar_final_date }}
                 </div>
                 <div class="text-bold">
                    Earning  From This Advert: {{ $advert->adverts->one_package_price }}
                 </div>
                 <div class="text-bold">
                   <a href="#" class="btn btn-success"> See More </a>
                 </div>
             </div>
           </div>
        </div>
    @endforeach
    {{ $adverts->links()}}
@endsection