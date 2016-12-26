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
      @if($previous_trips->count() == 0)
        <div class="panel">
    <div class="panel-header">
      {{ trans('messages.your_trips.previous_trips') }}
    </div>
    <div class="panel-body">
      <p>
        {{ trans('messages.your_trips.no_previous_trips') }}
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
  @if($previous_trips->count() > 0)
  <div class="panel row-space-4">
    <div class="panel-header">
      {{ trans('messages.your_trips.previous_trips') }}
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
        @foreach($previous_trips as $previous_trip)
            <tr>
      <td class="status">
        <span class="label label-orange label-{{ $previous_trip->status_color }}">
          {{ $previous_trip->status }}
        </span>
        <br>
      </td>
      <td class="location">
        <a href="{{ url() }}/rooms/{{ $previous_trip->room_id }}">{{ $previous_trip->rooms->name }}</a>
        <br>
        {{ $previous_trip->rooms->rooms_address->city }}
      </td>
      <td class="host"><a href="{{ url() }}/users/show/{{ $previous_trip->host_id }}">{{ $previous_trip->rooms->users->full_name }}</a></td>
      <td class="dates">{{ $previous_trip->dates }}</td>

      <td>
      <ul class="unstyled button-list list-unstyled">
        <li class="row-space-1">
        <a class="button-steel" href="{{ url() }}/z/q/{{ $previous_trip->id }}">{{ trans('messages.your_reservations.message_history') }}</a>
        </li>
        @if($previous_trip->status != "Cancelled" && $previous_trip->status != "Declined" && $previous_trip->status != "Expired")
        <li class="row-space-1">
        <a class="button-steel" href="{{ url() }}/reservation/itinerary?code={{ $previous_trip->code }}">{{ trans('messages.your_trips.view_itinerary') }}</a>
        </li>
        <li class="row-space-1">
        <a class="button-steel" href="{{ url() }}/reservation/receipt?code={{ $previous_trip->code }}">{{ trans('messages.your_trips.view_receipt') }}</a>
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
</main>

@stop