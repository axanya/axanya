@extends('template')

{!! Html::style('css/itinerary.css') !!}
{!! Html::style('css/print.css',array('media' => 'print')) !!}

@section('main')
    <main id="site-content" role="main">

        <div class="page-container page-container-responsive">
            <div class="col-center col-9 panel row-space-top-2 row-space-4">
                <div class="panel-body clearfix">
                    <div class="row row-table">
                        <div class="col-6 col-top">
                            <h1 class="h2">
                                {{ trans('messages.your_reservations.itinerary') }}
                            </h1>
                            {{ trans('messages.your_reservations.confirmation_code') }}
                            : {{ $reservation_details->code }}
                        </div>

                        <div class="col-6 col-bottom">
                            <div class="row row-table">
                                <div class="banner-button-list-item hide-print col-4 col-bottom print_itinerary hide-sm">
                                    <a class="icon-banner-button" onclick="print_itinerary()" href="#">
                                        {{ trans('messages.your_reservations.print_itinerary') }}
                                    </a>
                                </div>

                            </div>
                        </div>
                    </div>
                </div>

                <div class="panel-body">
                    <div class="row row-table row-space-2">
                        <div class="col-4 col-top">
                            <strong>{{ trans('messages.home.checkin') }}</strong>
                            <div class="h4 row-space-top-1">{{ $reservation_details->checkin_dmy }}</div>
                            <div class="text-muted">{{ trans('messages.your_reservations.flexible_checkin_time') }}</div>
                        </div>

                        <div class="col-4 col-top">
                            <strong>{{ trans('messages.home.checkout') }}</strong>
                            <div class="h4 row-space-top-1">{{ $reservation_details->checkout_dmy }}</div>
                            <div class="text-muted">{{ trans('messages.your_reservations.flexible_checkout_time') }}</div>
                        </div>
                        <div class="col-2 col-bottom">
                            <div class="h4">{{ $reservation_details->nights }}</div>
                            <div class="text-muted">{{ ucfirst(trans_choice('messages.rooms.night',1)) }}</div>
                        </div>
                        <div class="col-2 col-bottom">
                            <div class="h4">{{ $reservation_details->number_of_guests }}</div>
                            <div class="text-muted">{{ trans_choice('messages.home.guest',1) }}</div>
                        </div>
                        <div class="col-3 spacer"></div>
                    </div>


                <!-- <div class="row row-space-4 row-space-top-4">
    <div class="col-12">
        <a href="{{ url() }}/reservation/change?code={{ $reservation_details->code }}" class="btn btn-block">Change or Cancel</a>
    </div>
  </div> -->
                </div>

                <div class="panel-body">
                    <div class="row">
                        <div class="col-6">
                            <strong><a href="{{ url() }}/rooms/{{ $reservation_details->room_id }}">{{ $reservation_details->rooms->name }}</a></strong><br>
                            {{ $reservation_details->rooms->rooms_address->address_line_1 }}
                            <br>{{ $reservation_details->rooms->rooms_address->city.', '.$reservation_details->rooms->rooms_address->state.' '.$reservation_details->rooms->rooms_address->postal_code }}
                            <br>{{ $reservation_details->rooms->rooms_address->country_name }}<br>
                        </div>

                        <div class="col-6">
                            <div class="media">
                                <a href="{{ url() }}/users/show/{{ $reservation_details->user_id }}"
                                   class="pull-left media-photo img-round">
                                    <span style="background-image:url({{ $reservation_details->users->profile_picture->src }}); width:50px; height:50px;"
                                          title="{{ $reservation_details->users->first_name }}"></span></a>
                                <div class="media-body">
                                    <strong><a href="{{ url() }}/users/show/{{ $reservation_details->user_id }}">{{ $reservation_details->users->full_name }}</a></strong><br>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="panel-body column-two">

                    <div class="column-item text-wrap row-space-3">
                        <h4>{{ trans('messages.payments.payment') }}</h4>
                        {{ trans('messages.your_reservations.see_transaction_history') }}
                    </div>

                    <div class="column-item text-wrap row-space-3">
                        <h4>{{ trans('messages.payments.cancellation_policy') }}</h4>
                        {{ trans('messages.your_reservations.flexible_desc') }}
                    </div>

                    <div class="row-space-3">
                        <h4>{{ trans('messages.account.payout') }}</h4>

                        <table class="table payment-table">
                            <tbody>
                            <tr>
                                <th class="receipt-label">{{ $reservation_details->currency->symbol.$reservation_details->per_night }}
                                    x {{ $reservation_details->nights }} {{ trans_choice('messages.rooms.night',$reservation_details->nights) }}</th>
                                <td class="receipt-amount">
                                    {{ $reservation_details->currency->symbol.$reservation_details->per_night * $reservation_details->nights }}
                                </td>
                            </tr>
                            @if($reservation_details->additional_guest != 0)
                                <tr>
                                    <th class="receipt-label">{{ trans('messages.your_reservations.additional_guest') }}</th>
                                    <td class="receipt-amount">
                                        {{ $reservation_details->currency->symbol.$reservation_details->additional_guest }}
                                    </td>
                                </tr>
                            @endif
                            @if($reservation_details->cleaning != 0)
                                <tr>
                                    <th class="receipt-label">{{ trans('messages.your_reservations.cleaning_fee') }}</th>
                                    <td class="receipt-amount">
                                        {{ $reservation_details->currency->symbol.$reservation_details->cleaning }}
                                    </td>
                                </tr>
                            @endif
                            @if($reservation_details->security != 0)
                                <tr>
                                    <th class="receipt-label">{{ trans('messages.your_reservations.security_fee') }}</th>
                                    <td class="receipt-amount">
                                        {{ $reservation_details->currency->symbol.$reservation_details->security }}
                                    </td>
                                </tr>
                            @endif
                            @if($reservation_details->host_fee != 0)
                                <tr class="host_fee receipt-line-item-negative">
                                    <th class="receipt-label">
                                        {{ $site_name }} {{ trans('messages.your_reservations.service_fee') }}
                                    </th>
                                    <td class="receipt-amount">
                                        ({{ $reservation_details->currency->symbol.$reservation_details->host_fee }})
                                    </td>
                                </tr>
                            @endif
                            </tbody>
                            <tfoot>
                            <tr>
                                <th class="receipt-label">{{ trans('messages.your_reservations.total_payout') }}</th>
                                <td class="receipt-amount">{{ $reservation_details->currency->symbol.$reservation_details->host_payout }}</td>
                            </tr>
                            </tfoot>
                        </table>

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