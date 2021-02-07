@extends('admin.extensions.mainframe')
@section('page_title')
    detail about {{ $advert->name_of_the_advert}} advert
@endsection
@section('main_content')
    <div class="row">
        <div class="container">
            @if (Session::has('success_notification'))
                @include('admin.Includes.success_notification',['notification' => Session::get('success_notification', 'error occured')])     
            @endif
            @if (Session::has('error_notification'))
                @include('admin.Includes.error_notification',['notification' => Session::get('error_notification', 'error occured')])     
            @endif
        </div>
        <div class="mb-4 col-md-10 bg-dark align-content-center">
            @if ($advert->approve_status)
                 <a href="{{ route('admin.adverts.dis_approve_advert',$advert->id)}}" class="btn btn-primary mr-2 mt-2 mb-2"  > Un Approve Advert</a>
            @else
             <a href="{{ route('admin.adverts.approve_advert',$advert->id) }}" class="btn btn-primary mr-2 mt-2 mb-2"  > Approve Advert</a>
            @endif
            @if ($advert->active_status == 2 || $advert->active_status == 3)
                 <a href="{{ route('admin.adverts.post_the_advert',$advert->id)}}" class="btn btn-success mr-2 mt-2 mb-2"> Post Advert</a>
            @endif
            <a href="{{ route('admin.adverts.view_post_history',$advert->id)}}" class="btn btn-info mr-2 mt-2 mb-2">  View Post History</a>

        </div>
        <div class="col-md-10">
            <div class="card card-success">
                <div class="card-title>
                    <b class="bg-primary" > {{ $advert->name_of_the_advert }} information </b>
                </div>

                <div class="card-body">
                    <div class="card-title">
                        <b class="bg-primary">  Advert Content </b>
                        <div class="list-group-item text-bold align-content-center">
                            <div >
                               <b class="text-success mb-2">Name Of The Advert :</b>
                               <p>
                                   {{ $advert->name_of_the_advert }}
                               </p>
                            </div>

                            <div >
                                <b class="text-success mb-2">Description  Of The Advert :</b>
                                <p>
                                    {{ $advert->description_of_advert }}
                                </p>
                             </div>

                             <div >
                                <b class="text-success mb-2">Main Message  Of The Advert :</b>
                                <p>
                                    {{ $advert->text_message }}
                                </p>
                             </div>
                        </div>
                    </div>
                </div>

                <div class="card-body">
                    <div class="card-title ">
                        <b class="bg-primary mb-2">  Package Information </b>
                    </div><br>
                    
                    
                    <div class="mt-2">
                        <b class="text-success mb-2">Total Price Of The Advert :</b>
                        <p>
                            {{ $advert->amount_of_payment }}
                        </p>
                     </div>

                     <div class="mt-2">
                        <b class="text-success mb-2">Duration Of The Advert :</b>
                        <p>
                            {{ $advert->number_of_days }}
                        </p>
                     </div>

                     <div class="mt-2">
                        <b class="text-success mb-3">Number Of Channels Of The Advert :</b>
                        <p>
                            {{ $advert->number_of_channel }}
                        </p>
                     </div>

                     <div class="mt-2">
                        <b class="text-success mb-3">Number Of Channels Of The Advert :</b>
                        <p>
                            {{ $advert->number_of_channel }}
                        </p>
                     </div>

                     <div class="mt-2">
                        <b class="text-success mb-3">Initial  Posting Time Of The Advert :</b>
                        <p>
                            {{ $advert->initial_time }}
                        </p>
                     </div>

                     <div class="mt-2">
                        <b class="text-success mb-3">One Package Price  Of The Advert :</b>
                        <p>
                            {{ $advert->one_package_price }}
                        </p>
                     </div>

                     <div class="mt-2">
                        <b class="text-success mb-3">One Package Price  Of The Advert :</b>
                        <p>
                            {{ $advert->one_package_price }}
                        </p>
                     </div>

                     <div class="card-body">
                            <b class="bg-primary">  Status Of The Advert </b><br>
                         <div class="list-group-item text-bold align-content-center">
                            <div class="mt-2">
                                <b class="text-success mb-3">Approve Status :</b>
                                <p>
                                    {{ approve_status($advert->approve_status) }}
                                </p>
                             </div>

                             <div class="mt-2">
                                <b class="text-success mb-3">Payment Status :</b>
                                <p>
                                    {{ payment_status($advert->payment_status) }}
                                </p>
                             </div>

                             <div class="mt-2">
                                <b class="text-success mb-3"> Active Status :</b>
                                <p>
                                    {{ active_status($advert->active_status) }}
                                </p>
                             </div>
                         </div>

                    </div>

                    <div class="card-body">
                        <b class="bg-primary">  Date Information Of The Advert </b><br>
                     <div class="list-group-item text-bold align-content-center">
                        <div class="mt-2">
                            <b class="text-success mb-3">Initial Date (Ethiopian Calalndar):</b>
                            <p>
                                {{ ($advert->et_calendar_initial_date) }}
                            </p>
                         </div>

                         <div class="mt-2">
                            <b class="text-success mb-3">Final Date (Ethiopian Calalndar):</b>
                            <p>
                                {{ ($advert->et_calendar_final_date) }}
                            </p>
                         </div>
                         
                         <div class="mt-2">
                            <b class="text-success mb-3"> Initial Date (GC Calalndar):</b>
                            <p>
                                {{ ($advert->gc_calendar_initial_date) }}
                            </p>
                         </div>

                         <div class="mt-2">
                            <b class="text-success mb-3"> Final Date (GC Calalndar):</b>
                            <p>
                                {{ ($advert->gc_calendar_final_date) }}
                            </p>
                         </div>
                     </div>                         
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection