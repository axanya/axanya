@extends('template')

{!! Html::style('css/shared_itinerary.css') !!}
{!! Html::style('css/shared_itinerary_print.css',array('media' => 'print')) !!}

@section('main')
    <main role="main" id="site-content">

        <div class="shared-itinerary-container">
            <div class="page-container-responsive">
                <div class="show-sm space-4 listing-photo-main-sm">
                    <div class="hide-print">
                        <img src="" class="media-photo media-photo-block img-responsive listing-large-photo">
                    </div>
                </div>
                <section class="space-top-8 space-top-sm-4 space-4">
                    <div class="row">
                        <div class="col-9">
                            <h2 class="text-center-on-sm">{{ trans('messages.your_trips.you_are_gonna') }} {{ $reservation_details->rooms->rooms_address->city }}
                                !</h2>
                            <div class="text-center-on-sm">
                                <span>{{ trans('messages.your_trips.reservation_code') }}:</span>
                                <span> </span>
                                <span>{{ $reservation_details->code }}</span>
                                <span>. </span>
                                <br class="show-sm">
                                <span class="hide-print">
<span>
<span>
<a href="{{ url() }}/reservation/receipt?code={{ $reservation_details->code }}">
<span>{{ trans('messages.your_trips.view_receipt') }}</span>
</a>
</span>
    <!-- <span> or </span> -->
<!-- <span>
<a href="{{ url() }}/reservation/change?code={{ $reservation_details->code }}">
<span>make a change to the reservation</span>
</a>
</span> -->
    <span>.</span>
</span>
</span>
                            </div>
                        </div>
                    </div>
                </section>
                <div class="show-md">
                    <div class="hide-print">
                        <img src="{{ url('images/'.$reservation_details->rooms->photo_name) }}"
                             class="media-photo media-photo-block img-responsive listing-large-photo">
                    </div>
                </div>
                <div class="row space-8">
                    <div class="col-lg-7">
                        <div class="itinerary-card">
                            <div class="panel-white panel">
                                <div class="panel-body">
                                    <div class="row row-table checkin-checkout">
                                        <div class="show-sm">
                                            <div class="col-sm-6">
                                                <strong>{{ trans('messages.home.checkin') }}</strong>
                                                <br>
                                                <span>{{ $reservation_details->checkin_dmd }}</span>
                                                <br>
                                                <span>{{ trans('messages.your_reservations.flexible_checkin_time') }}</span>
                                            </div>
                                            <div class="col-sm-6">
                                                <strong>{{ trans('messages.home.checkout') }}</strong>
                                                <br>
                                                <span>{{ $reservation_details->checkout_dmd }}</span>
                                                <br>
                                                <span>{{ trans('messages.your_reservations.flexible_checkout_time') }}</span>
                                            </div>
                                        </div>
                                        <div class="hide-sm">
                                            <div class="col-3">
                                                <strong>{{ trans('messages.home.checkin') }}</strong>
                                            </div>
                                            <div class="col-3">
                                                <span>{{ $reservation_details->checkin_dmd }}</span>
                                                <br>
                                                <span>{{ trans('messages.your_reservations.flexible_checkin_time') }}</span>
                                            </div>
                                            <div class="col-3">
                                                <strong>{{ trans('messages.home.checkout') }}</strong>
                                            </div>
                                            <div class="col-3">
                                                <span>{{ $reservation_details->checkout_dmd }}</span>
                                                <br>
                                                <span>{{ trans('messages.your_reservations.flexible_checkout_time') }}</span>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="panel-body text-center-on-sm">
                                    <div class="row row-table">
                                        <div class="col-3">
                                            <strong>{{ trans('messages.account.address') }}</strong>
                                        </div>
                                        <div class="col-9">
                                            <div>{{ $reservation_details->rooms->rooms_address->address_line_1 }}
                                                <br>{{ $reservation_details->rooms->rooms_address->city }}
                                                , {{ $reservation_details->rooms->rooms_address->state }} {{ $reservation_details->rooms->rooms_address->postal_code }}
                                                <br>{{ $reservation_details->rooms->rooms_address->country_name }}<br>
                                            </div>
                                            <a class="hide-print" target="_blank"
                                               href="http://google.com/maps/place/{{ str_replace(' ','+',$reservation_details->rooms->rooms_address->address_line_1.' '.$reservation_details->rooms->rooms_address->city.', '.$reservation_details->rooms->rooms_address->state.' '.$reservation_details->rooms->rooms_address->postal_code.' '.$reservation_details->rooms->rooms_address->country_name) }}">{{ trans('messages.your_trips.get_directions') }}</a>
                                            <span class="mini-divider">
</span>
                                            <a href="{{ url('rooms/'.$reservation_details->room_id) }}">{{ trans('messages.your_trips.view_listing') }}</a>
                                        </div>
                                    </div>
                                </div>
                                <div class="panel-body text-center-on-sm">
                                    <div class="row row-table">
                                        <div class="col-3">
                                            <strong>{{ trans('messages.your_trips.host') }}</strong>
                                        </div>
                                        <div class="col-3 show-sm">
                                            <a class="show-sm"
                                               href="{{ url() }}/users/show/{{ $reservation_details->host_id }}">
                                                <img src="{{ $reservation_details->rooms->users->profile_picture->header_src }}"
                                                     alt="{{ $reservation_details->rooms->users->full_name }}"
                                                     width="50" height="50" class="media-photo media-round host-photo">
                                            </a>
                                        </div>
                                        <div class="col-6">
                                            <span>{{ $reservation_details->rooms->users->full_name }}</span>
                                            <br>
                                            <!-- <div>+91</div> -->
                                            <div class="hide-print">
                                                <a href="{{ url() }}/z/q/{{ $reservation_details->id }}">{{ trans('messages.your_trips.msg_host') }}</a>
                                            </div>
                                            <div class="show-print">{{ $reservation_details->rooms->users->email }}</div>
                                        </div>
                                        <div class="col-3 hide-sm">
                                            <a href="{{ url() }}/users/show/{{ $reservation_details->host_id }}">
                                                <span class="img-responsive"
                                                      style="background-image:url({{ $reservation_details->rooms->users->profile_picture->header_src }});height:60px;width:60px;background-size: cover;display:inline-block;border-radius:50%;"
                                                      alt="{{ $reservation_details->rooms->users->full_name }}"
                                                      width="50" height="50"
                                                      class="media-photo media-round pull-right"></span>
                                            </a>
                                        </div>
                                    </div>
                                </div>
                                <div class="panel-body text-center-on-sm">
                                    <div class="row row-table">
                                        <div class="col-3">
                                            <strong>{{ trans('messages.your_trips.billing') }}</strong>
                                        </div>
                                        <div class="col-9">
                                            <table>
                                                <tbody>
                                                <tr>
                                                    <td class="billing-table-cell">{{ $reservation_details->nights }} {{ trans_choice('messages.rooms.night',$reservation_details->nights) }}</td>
                                                    <td>{{ $reservation_details->currency->symbol.$reservation_details->total }}</td>
                                                </tr>
                                                </tbody>
                                            </table>
                                            <div class="hide-print">
                                                <a href="{{ url() }}/reservation/receipt?code={{ $reservation_details->code }}">{{ trans('messages.your_trips.detailed_receipt') }}</a>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-5">
                        <div class="show-lg">
                            <div class="hide-print">
                                <img src="{{ url('images/'.$reservation_details->rooms->photo_name) }}"
                                     class="media-photo media-photo-block img-responsive listing-large-photo">
                            </div>
                        </div>
                        <section class="space-6">
                            <div class="listing-info-text">
                            </div>
                        </section>
                    </div>
                </div>
            </div>
        </div>

    </main>

    <script>
        function print_itinerary()
        {
            window.print();
        }
    </script>

@stop