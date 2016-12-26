@extends('template')

@section('main')
<main role="main" id="site-content">
<div class="page-container page-container-responsive row-space-top-8">
  <div class="row row-condensed row-table row-space-4" id="receipt-id">
    <div class="col-3 col-bottom">
      {{ $reservation_details->receipt_date }}<br>
      {{ trans('messages.your_trips.receipt') }} # {{ $reservation_details->id }}<br>
    </div>
    <div class="col-3 col-bottom">
    </div>
    <div class="col-6 col-bottom text-right">
    </div>
  </div>

  <div class="row">
    <div class="panel">
      <div class="panel-body">
        <h2>{{ trans('messages.your_trips.customer_receipt') }}</h2>
        <div class="pull-right hide-print">
          <a id="print_receipt" onclick="print_receipt()" class="btn" href="#">{{ trans('messages.your_trips.receipt') }}</a>
        </div>

        <div class="h6 row-space-1">
          {{ trans('messages.your_reservations.confirmation_code') }}
        </div>
        <div class="h4">
          {{ $reservation_details->code }}
        </div>
      </div>

      <div class="panel-body">

        <div class="row row-space-condensed row-space-3">
          <div class="col-3">
            <h6>
              {{ trans('messages.payments.name') }}
            </h6>
            <p class="text-lead">
              {{ $reservation_details->users->full_name }}
            </p>
          </div>
          <div class="col-3">
            <h6>
              {{ trans('messages.your_trips.travel_destination') }}
            </h6>
            <p class="text-lead">
              {{ $reservation_details->rooms->rooms_address->city }}
            </p>
          </div>
          <div class="col-3">
            <h6>
              {{ trans('messages.your_trips.duration') }}
            </h6>
            <p class="text-lead">
              {{ $reservation_details->nights }} {{ ucfirst(trans_choice('messages.rooms.night',$reservation_details->nights)) }}
            </p>
          </div>
          <div class="col-3">
            <h6>
              {{ trans('messages.your_trips.accommodation_type') }}
            </h6>
            <p class="text-lead">
              {{ $reservation_details->rooms->room_type_name }}
            </p>
          </div>
        </div>

        <div class="row row-space-condensed">
          <div class="col-3">
            <h6>
              {{ trans('messages.your_trips.accommodation_address') }}
            </h6>
            <p class="text-lead">
              <strong>{{ $reservation_details->rooms->name }}</strong>
            </p>
              <p class="text-lead">{{ $reservation_details->rooms->rooms_address->address_line_1 }}<br>{{ $reservation_details->rooms->rooms_address->city }}, {{ $reservation_details->rooms->rooms_address->state }} {{ $reservation_details->rooms->rooms_address->postal_code }}<br>{{ $reservation_details->rooms->rooms_address->country_name }}<br></p>
          </div>
          <div class="col-3">
            <h6>
              {{ trans('messages.your_trips.accommodation_host') }}
            </h6>
            <p class="text-lead">
              {{ $reservation_details->rooms->users->full_name }}
            </p>
          </div>
          <div class="col-3">
            <h6>
              {{ trans('messages.home.checkin') }}
            </h6>
            <p class="text-lead">
              {{ $reservation_details->checkin_dmy }}<br>{{ trans('messages.your_reservations.flexible_checkin_time') }}
            </p>
          </div>
          <div class="col-3">
            <h6>
              {{ trans('messages.home.checkout') }}
            </h6>
            <p class="text-lead">
              {{ $reservation_details->checkout_dmy }}<br>{{ trans('messages.your_reservations.flexible_checkout_time') }}
            </p>
          </div>
        </div>
      </div>
      <div class="panel-body">
        <div class="row row-space-condensed row-space-top-4">
          <div class="col-12">
            <h2>
              {{ trans('messages.your_trips.reservation_charges') }}
            </h2>

              <table class="table table-bordered payment-table">
  <tbody>
    <tr>
      <th class="receipt-label">{{ $reservation_details->currency->symbol.$reservation_details->per_night }} x {{ $reservation_details->nights }} {{ trans_choice('messages.rooms.night',$reservation_details->nights) }}</th>
      <td class="receipt-amount">
        {{ $reservation_details->currency->symbol.$reservation_details->per_night * $reservation_details->nights }}
      </td>
    </tr>
     @if($reservation_details->additional_guest)
      <tr>
        <th class="receipt-label">
          {{ trans('messages.rooms.addtional_guest_fee') }}
        </th>
        <td class="receipt-amount">{{ $reservation_details->currency->symbol.$reservation_details->additional_guest }}</td>
      </tr>
      @endif
      @if($reservation_details->cleaning)
      <tr>
        <th class="receipt-label">
          {{ trans('messages.your_reservations.cleaning_fee') }}
        </th>
        <td class="receipt-amount">{{ $reservation_details->currency->symbol.$reservation_details->cleaning }}</td>
      </tr>
      @endif
      @if($reservation_details->security)
      <tr>
        <th class="receipt-label">
          {{ trans('messages.your_reservations.security_fee') }}
        </th>
        <td class="receipt-amount">{{ $reservation_details->currency->symbol.$reservation_details->security }}</td>
      </tr>
      @endif
       @if($reservation_details->coupon_amount)
      <tr>
        <th class="receipt-label">
          {{ trans('messages.payments.coupon_amount') }}
        </th>
        <td class="receipt-amount">-{{ $reservation_details->currency->symbol.$reservation_details->coupon_amount }}</td>
      </tr>
      @endif
      <tr>
        <th class="receipt-label">
          {{ $site_name }} {{ trans('messages.your_reservations.service_fee') }}
        </th>
        <td class="receipt-amount">{{ $reservation_details->currency->symbol.$reservation_details->service }}</td>
      </tr>
  </tbody>
  <tfoot>
    <tr>
      <th class="receipt-label">{{ trans('messages.rooms.total') }}</th>
      <td class="receipt-amount">{{ $reservation_details->currency->symbol.$reservation_details->total }}</td>
    </tr>
  </tfoot>
</table>

                <table class="table table-bordered payment-table">
  <tbody>
      <tr>
          <th class="receipt-label">
            {{ trans('messages.your_trips.payment_received') }}:
            {{ $reservation_details->receipt_date }}
            
          </th>
        <td class="receipt-amount">
          {{ $reservation_details->currency->symbol.$reservation_details->total }}
        </td>
      </tr>
  </tbody>
</table>

          </div>
        </div>
      </div>
    </div>
  </div>
    <div class="row-space-top-4" id="legal-disclaimer">
        <p>
          {{ trans('messages.your_trips.authorized_to_accept',['site_name'=>$site_name]) }}
        </p>
    </div>
</div>

    </main>

<script>
function print_receipt()
{
  window.print();
}
</script>

@stop