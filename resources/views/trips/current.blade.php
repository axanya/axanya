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
      @if($pending_trips->count() == 0 && $current_trips->count() == 0 && $upcoming_trips->count() == 0)
        <div class="panel">
    <div class="panel-header">
      {{ trans('messages.header.your_trips') }}
    </div>
    <div class="panel-body">
      <p>
        {{ trans('messages.your_trips.no_current_trips') }}
      </p>
      <div class="row">
        <div class="col-8">
          <form method="get" class="row" action="{{ url() }}/s" accept-charset="UTF-8"><div style="margin:0;padding:0;display:inline"><input type="hidden" value="✓" name="utf8"></div>
  <div class="col-8">
    <input type="text" placeholder="{{ trans('messages.header.where_are_you_going') }}" name="location" id="location" autocomplete="off" class="location">
  </div>
  <div class="col-4">
    <button id="submit_location" class="btn btn-primary" type="submit">
      {{ trans('messages.home.search') }}
    </button>
  </div>
</form>
        </div>
      </div>
    </div>
  </div>
  @endif
  @if($pending_trips->count() > 0)
  <div class="panel row-space-4">
    <div class="panel-header">
      {{ trans('messages.your_trips.pending_trips') }}
    </div>
    <div class="table-responsive">
      <table class="table panel-body panel-light">
        <tbody><tr>
          <th>{{ trans('messages.your_reservations.status') }}</th>
          <th>{{ trans('messages.your_trips.location') }}</th>
          <th>{{ trans('messages.your_trips.host') }}</th>
          <th>{{ trans('messages.your_trips.dates') }}</th>
          <th>{{ trans('messages.your_trips.options') }}</th>
        </tr>
        @foreach($pending_trips as $pending_trip)
            <tr>
      <td class="status">
        <span class="label label-orange label-{{ $pending_trip->status_color }}">
          {{ $pending_trip->status }}
        </span>
        <br>
      </td>
      <td class="location">
        <a href="{{ url() }}/rooms/{{ $pending_trip->room_id }}">{{ $pending_trip->rooms->name }}</a>
        <br>
        {{ $pending_trip->rooms->rooms_address->city }}
      </td>
      <td class="host"><a href="{{ url() }}/users/show/{{ $pending_trip->host_id }}">{{ $pending_trip->rooms->users->full_name }}</a></td>
      <td class="dates">{{ $pending_trip->dates }}</td>

      <td>
      <ul class="unstyled button-list list-unstyled">
        <li class="row-space-1">
        <a class="button-steel" href="{{ url() }}/z/q/{{ $pending_trip->id }}">{{ trans('messages.your_reservations.message_history') }}</a>
        </li>
        <li class="row-space-1">
        <!-- <a target="_blank" rel="nofollow" data-method="post" data-confirm="Are you sure that you want to cancel the request? Any money transacted will be refunded." class="button-steel" href="{{ url() }}/reservation/cancel?code={{ $pending_trip->code }}">{{ trans('messages.your_trips.cancel_request') }}</a> -->
        <a rel="nofollow" data-method="post" data-confirm="Are you sure that you want to cancel the request? Any money transacted will be refunded." id="{{ $pending_trip->id }}-trigger" class="button-steel" href="#">{{ trans('messages.your_trips.cancel_request') }}</a>
        </li>
      </ul>
    </td>
    </tr>
    @endforeach
    </tbody>
    </table>
    </div>
  </div>
  @endif
  @if($current_trips->count() > 0)
  <div class="panel row-space-4">
    <div class="panel-header">
      {{ trans('messages.your_trips.current_trips') }}
    </div>
    <div class="table-responsive">
      <table class="table panel-body panel-light">
        <tbody><tr>
          <th>{{ trans('messages.your_reservations.status') }}</th>
          <th>{{ trans('messages.your_trips.location') }}</th>
          <th>{{ trans('messages.your_trips.host') }}</th>
          <th>{{ trans('messages.your_trips.dates') }}</th>
          <th>{{ trans('messages.your_trips.options') }}</th>
        </tr>
        @foreach($current_trips as $current_trip)
            <tr>
      <td class="status">
        <span class="label label-orange label-{{ $current_trip->status_color }}">
          {{ $current_trip->status }}
        </span>
        <br>
      </td>
      <td class="location">
        <a href="{{ url() }}/rooms/{{ $current_trip->room_id }}">{{ $current_trip->rooms->name }}</a>
        <br>
        {{ $current_trip->rooms->rooms_address->city }}
      </td>
      <td class="host"><a href="{{ url() }}/users/show/{{ $current_trip->host_id }}">{{ $current_trip->rooms->users->full_name }}</a></td>
      <td class="dates">{{ $current_trip->dates }}</td>

      <td>
      <ul class="unstyled button-list list-unstyled">
        <li class="row-space-1">
        <a class="button-steel" href="{{ url() }}/z/q/{{ $current_trip->id }}">{{ trans('messages.your_reservations.message_history') }}</a>
        </li>
        @if($current_trip->status != "Cancelled" && $current_trip->status != "Declined" && $current_trip->status != "Expired")
        <li class="row-space-1">
        <a class="button-steel" href="{{ url() }}/reservation/itinerary?code={{ $current_trip->code }}">{{ trans('messages.your_trips.view_itinerary') }}</a>
        </li>
        <li class="row-space-1">
        <a class="button-steel" href="{{ url() }}/reservation/receipt?code={{ $current_trip->code }}">{{ trans('messages.your_trips.view_receipt') }}</a>
        </li>
        <li class="row-space-1">
        <a class="button-steel" href="#" id="{{$current_trip->id}}-trigger">{{ trans('messages.your_reservations.cancel') }}</a>
        </li>
        @endif
      </ul>
    </td>
    </tr>
    @endforeach
    </tbody>
    </table>
    </div>
  </div>
  @endif
  @if($upcoming_trips->count() > 0)
  <div class="panel row-space-4">
    <div class="panel-header">
      {{ trans('messages.your_trips.upcoming_trips') }}
    </div>
    <div class="table-responsive">
      <table class="table panel-body panel-light">
        <tbody><tr>
          <th>{{ trans('messages.your_reservations.status') }}</th>
          <th>{{ trans('messages.your_trips.location') }}</th>
          <th>{{ trans('messages.your_trips.host') }}</th>
          <th>{{ trans('messages.your_trips.dates') }}</th>
          <th>{{ trans('messages.your_trips.options') }}</th>
        </tr>
        @foreach($upcoming_trips as $upcoming_trip)
            <tr>
      <td class="status">
        <span class="label label-orange label-{{ $upcoming_trip->status_color }}">
          {{ $upcoming_trip->status }}
        </span>
        <br>
      </td>
      <td class="location">
        <a href="{{ url() }}/rooms/{{ $upcoming_trip->room_id }}">{{ $upcoming_trip->rooms->name }}</a>
        <br>
        {{ $upcoming_trip->rooms->rooms_address->city }}
      </td>
      <td class="host"><a href="{{ url() }}/users/show/{{ $upcoming_trip->host_id }}">{{ $upcoming_trip->rooms->users->full_name }}</a></td>
      <td class="dates">{{ $upcoming_trip->dates }}</td>

      <td>
      <ul class="unstyled button-list list-unstyled">
        <li class="row-space-1">
        <a class="button-steel" href="{{ url() }}/z/q/{{ $upcoming_trip->id }}">{{ trans('messages.your_reservations.message_history') }}</a>
        </li>
        @if($upcoming_trip->status != "Cancelled")
        <li class="row-space-1">
        <a class="button-steel" href="{{ url() }}/reservation/itinerary?code={{ $upcoming_trip->code }}">{{ trans('messages.your_trips.view_itinerary') }}</a>
        </li>
        <li class="row-space-1">
        <a class="button-steel" href="{{ url() }}/reservation/receipt?code={{ $upcoming_trip->code }}">{{ trans('messages.your_trips.view_receipt') }}</a>
        </li>
        <li class="row-space-1">
        <a class="button-steel" href="#" id="{{$upcoming_trip->id}}-trigger">{{ trans('messages.your_reservations.cancel') }}</a>
        </li>
         @endif
      </ul>
    </td>
    </tr>
    @endforeach
    </tbody>
    </table>
    </div>
  </div>
  @endif

    </div>
      
    </div>
  </div>
   @if($upcoming_trips->count() > 0 || $current_trips->count() > 0)
  <div class="modal" role="dialog" id="cancel-modal" aria-hidden="true">
  <div class="modal-table">
    <div class="modal-cell">
      <div class="modal-content">
        <form accept-charset="UTF-8" action="{{ url('trips/guest_cancel_reservation') }}" id="cancel_reservation_form" method="post" name="cancel_reservation_form">
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
                <strong>
                  {{ trans('messages.your_trips.response_not_shared_host') }}
                </strong>
              </p>
              <div class="select">
                <select id="cancel_reason" name="cancel_reason"><option value="">Why are you declining?</option>
<option value="no_longer_need_accommodations">I no longer need accommodations</option>
<option value="travel_dates_changed">My travel dates changed</option>
<option value="made_the_reservation_by_accident">I made the reservation by accident</option>
<option value="I_have_an_extenuating_circumstance">I have an extenuating circumstance</option>
<option value="my_host_needs_to_cancel">My host needs to cancel</option>
<option value="uncomfortable_with_the_host">I’m uncomfortable with the host</option>
<option value="place_not_okay">The place isn't what I was expecting</option>
<option value="other">Other</option></select>
              </div>

              <div id="cancel_reason_other_div" class="hide row-space-top-2">
                <label for="cancel_reason_other">
                  {{ trans('messages.your_reservations.why_cancel') }}
                </label>
                <textarea id="decline_reason_other" name="decline_reason_other" rows="4"></textarea>
              </div>
             
            </div>

            <label for="cancel_message" class="row-space-top-2">
              {{ trans('messages.your_trips.type_msg_host') }}...
            </label>
            <textarea cols="40" id="cancel_message" name="cancel_message" rows="10"></textarea>
            <input type="hidden" name="id" id="reserve_code" value="">
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
@endif
</main>

@stop

