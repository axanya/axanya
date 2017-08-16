@extends('template')

@section('main')
    <main id="site-content" role="main">

    @include('common.subheader')

  <div class="page-container-responsive space-top-4 space-4">
    <div class="row">
      <div class="col-md-3">

        @include('common.sidenav')

      </div>

      <div class="col-md-9">
        <div class="your-listings-flash-container"></div>

  <div class="panel" id="print_area">
  @if($reservations->count() == 0 && $code != 1)
  <div class="panel-body">
      <p>
          {{ trans('messages.your_reservations.no_upcoming_reservations') }}
      </p>
        <a href="{{ url() }}/my_reservations?all=1">{{ trans('messages.your_reservations.view_past_reservation_history') }}</a>
    </div>
  @elseif($reservations->count() == 0 && $code == 1)
    <div class="panel-body">
      <p>
          {{ trans('messages.your_reservations.no_reservations') }}
      </p>
        <a href="{{ url() }}/rooms/new" class="btn btn-special list-your-space-btn" id="list-your-space">{{ trans('messages.your_listing.add_new_listings') }}</a>
    </div>
  @else
    <div class="panel-header">
      <div class="row row-table">
        <div class="col-md-6 col-middle">
            {{ ($code == 1) ? trans('messages.your_reservations.all') : trans('messages.your_reservations.upcoming') }} {{ trans('messages.inbox.reservations') }}
        </div>
        <div class="col-md-6 col-middle">

          <a class="btn pull-right" href="{{ url() }}/my_reservations?all={{ $code }}&amp;print={{ $code }}">
            <i class="icon icon-description"></i>
            {{ trans('messages.your_reservations.print_this_page') }}
</a>        </div>
      </div>
    </div>
    <div class="table-responsive">
      <table style="background-color:white" class="table panel-body space-1">
        <tbody><tr>
          <th>{{ trans('messages.your_reservations.status') }}</th>
          <th>{{ trans('messages.your_reservations.dates_location') }}</th>
          <th>{{ trans_choice('messages.home.guest',1) }}</th>
          <th>{{ trans('messages.your_reservations.details') }}</th>
        </tr>
        @foreach($reservations as $row)
          <tr data-reservation-id="{{ $row->id }}" class="reservation">
  <td>
    <span class="label label-{{ $row->status_color }}">
      {{ $row->status }}
    </span>
  </td>

  <td>
    {{ $row->dates }}
    <br>
    <a locale="en" href="{{ url() }}/rooms/{{ $row->room_id }}">{{ $row->rooms->name }}</a>
    <br>
        {{ $row->rooms->rooms_address->address_line_1 }}
    <br>
        {{ $row->rooms->rooms_address->city.', '.$row->rooms->rooms_address->state.' '.$row->rooms->rooms_address->postal_code }}
    <br>
  </td>

  <td>
    <div class="media va-container">
      <a class="pull-left media-photo media-round" href="{{ url() }}/users/show/{{ $row->users->id }}">
        <span title="{{ $row->users->first_name }}" style="baclground-image:url({{ $row->users->profile_picture->src }}); width:50px; height:50px;" ></span>
</a>      <div class="va-top">
        <a class="text-normal" href="{{ url() }}/users/show/{{ $row->users->id }}">{{ $row->users->full_name }}</a>
        <br>
        @if($row->status == 'Accepted')
        <a href="{{ url() }}/messaging/qt_with/{{ $row->id }}" class="text-normal">
          <i class="icon icon-envelope"></i>
          {{ trans('messages.your_reservations.send_message') }}
        </a>
        <!-- <br>
          +91 -->
          <br>
          <a href="mailto:{{ $row->users->email }}">
            {{ trans('messages.your_reservations.contact_by_email') }}
          </a>
            <i class="icon icon-question" id="anonymous-email-118017996-tooltip-trigger"></i>
          @endif
          <br>
      </div>
    </div>
  </td>

  <td>
      {{ $row->currency->symbol.$row->subtotal }} {{ trans('messages.your_reservations.total') }}


    <ul class="list-unstyled">
    <li>
        <a href="{{ url() }}/messaging/qt_with/{{ $row->id }}">{{ trans('messages.your_reservations.message_history') }}</a>
      </li>
      @if($row->status == "Accepted")
        <li>
          <a target="_blank" href="{{ url() }}/reservation/itinerary?code={{ $row->code }}">{{ trans('messages.your_reservations.print_confirmation') }}</a>
        </li>
        @if(!$row->checkout_cross)
          <li>
            <a href="#" id="{{$row->id}}-trigger">{{ trans('messages.your_reservations.cancel') }}</a>
          </li>
        @endif
      @endif
    </ul>

  </td>
</tr>
@endforeach

      </tbody></table>
    </div>
    @if($code == '0' || $code == '')
      <div class="panel-body">
        <a href="{{ url() }}/my_reservations?all=1">{{ trans('messages.your_reservations.view_all_reservation_history') }}</a>
      </div>
     @else
      <div class="panel-body">
        <a href="{{ url() }}/my_reservations?all=0">{{ trans('messages.your_reservations.view_upcoming_reservations') }}</a>
      </div>
     @endif
  @endif
  </div>


      </div>
    </div>
  </div>
<input type="hidden" value="{{ $print }}" id="print">

<div class="modal" role="dialog" id="cancel-modal" aria-hidden="true">
  <div class="modal-table">
    <div class="modal-cell">
      <div class="modal-content">
        <form accept-charset="UTF-8" action="{{ url('reservation/host_cancel_reservation') }}" id="cancel_reservation_form" method="post" name="cancel_reservation_form">
        {!! Form::token() !!}
          <div class="panel-header">
            <a href="#" class="modal-close" data-behavior="modal-close"></a>
            {{ trans('messages.your_reservations.cancel_this_reservation') }}
          </div>
          <div class="panel-body">
            <div id="decline_reason_container">
              <p>
                {{ trans('messages.your_reservations.reason_cancel_reservation') }}
              </p>
              <p>

              </p>
              <div class="select">
                <select id="cancel_reason" name="cancel_reason"><option value="">Why are you Cancelling?</option>
<option value="no_longer_available">My place is no longer available</option>
<option value="offer_a_different_listing">I want to offer a different listing or change the price</option>
<option value="need_maintenance">My place needs maintenance</option>
<option value="I_have_an_extenuating_circumstance">I have an extenuating circumstance</option>
<option value="my_guest_needs_to_cancel">My guest needs to cancel</option>
<option value="other">Other</option></select>
              </div>

             <!--  <div id="cancel_reason_other_div" class="hide row-space-top-2">
                <label for="cancel_reason_other">
                  {{ trans('messages.your_reservations.why_cancel') }}
                </label>
                <textarea id="decline_reason_other" name="decline_reason_other" rows="4"></textarea>
              </div>
 -->

            </div>

            <label for="cancel_message" class="row-space-top-2">
              {{ trans('messages.your_reservations.type_msg_guest') }}...
            </label>
            <textarea cols="40" id="cancel_message" name="cancel_message" rows="10"></textarea>
            <input type="hidden" name="id" id="reserve_id" value="">
          </div>
          <div class="panel-footer">
            <input type="hidden" name="decision" value="decline">
            <input class="btn btn-primary" id="cancel_submit" name="commit" type="submit" value="Cancel My Reservation">
            <button class="btn" data-behavior="modal-close">
              {{ trans('messages.home.close') }}
            </button>
          </div>
</form>      </div>
    </div>
  </div>
</div>
</main>

<script>
if(document.getElementById('print').value >= '0')
{
  window.print();
  window.onfocus=function(){ window.location.href=APP_URL+'/my_reservations'; }
}
</script>

@if($print >= '0')
<style>
body * {
  visibility: hidden;
}
#print_area, #print_area * {
  visibility: visible;
}
#print_area {
  position: fixed;
  left: 0px;
  top: 0px;
}
a[href]:after {
  content: none !important;
}
</style>
@endif

@stop
