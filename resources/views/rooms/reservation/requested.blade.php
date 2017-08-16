 @extends('template')

@section('main')

<main role="main" id="site-content">

  <div class="share-itinerary" id="p5-app-container">
  <div>
  <div class="cover-photo-container show-sm show-md">
  <img src="{{ url('images/'.$reservation_details->rooms->photo_name) }}">
  </div>
  <div class="page-container-responsive">
  <div class="row space-top-lg-8 space-lg-8 space-top-md-4 space-md-4 space-sm-2 space-top-sm-2">
  <div class="col-lg-12 col-md-11 col-center">
  <div>
  </div>
  @if($reservation_details->status == 'Pending')
  <h1 class="hide-sm space-1">
  <span>
  <span class="hide-sm">{{ trans('messages.payments.your_request_sent') }}</span>
  <span class="show-sm">{{ trans('messages.payments.request_sent') }}!</span>
  </span>
  </h1>
  @endif
  @if($reservation_details->status == 'Accepted')
  <h1 class="hide-sm space-1">
  <span>
  <span class="hide-sm">{{ trans('messages.payments.get_ready_for') }} {{ $reservation_details->rooms->rooms_address->city }}!</span>
  </span>
  </h1>
  @endif
  <div class="row space-lg-8 space-md-4">
  <div class="col-lg-6 col-md-12">
  <div class="show-sm">
  <h5 class="space-top-1 space-2">{{ $reservation_details->rooms->room_type_name }} {{ trans('messages.payments.in') }} {{ $reservation_details->rooms->rooms_address->city }}</h5>
  </div>
  @if($reservation_details->status == 'Pending')
  <div class="space-top-1 space-sm-2">
  <span>
  <span class="hide-sm">{{ trans('messages.payments.isnot_confirmed_reservation', ['first_name'=>$reservation_details->rooms->users->first_name]) }}</span>
  </span>
  </div>
  @endif
  @if($reservation_details->status == 'Accepted')
  <div class="space-top-1 space-sm-2">
  <span>
  <span class="hide-sm">{{ trans('messages.payments.confirmed_reservation', ['first_name'=>$reservation_details->rooms->users->first_name,'email'=>$reservation_details->users->email]) }}</span>
  </span>
  </div>
  @endif
  </div>
  </div>
  <hr class="divider show-sm space-sm-4">
  <form autocomplete="off" method="post" action="{{ url('reservation/itinerary_friends') }}" id="share-itinerary-form">
  {!! Form::token() !!}
  <input type="hidden" value="{{ $reservation_details->code }}" name="code">
  <input type="hidden" value="additional_guests" name="page5_action">
  <div class="row">
  <div class="col-lg-6 col-md-12">
  <div>
  <div class="space-lg-4 space-md-2 space-sm-2">
  <noscript>
  </noscript>
  </div>
  <div class="row space-lg-6 space-md-6 space-sm-4">
  <div class="col-md-10 col-sm-12">
  <div>
  <h3>{{ trans('messages.payments.email_itinerary') }}</h3>
  @if($reservation_details->status == 'Pending')
  <p>{{ trans('messages.payments.send_trip_details_to_friends') }}</p>
  @endif
  <div>
  <div class="space-top-2 add-friend">
  <div class="row row-space-8 add-text-email">
  <div class="col-md-10 col-sm-12">
  <div class="friend-email space-2">
  <div data-email-tagging="false" data-typeahead-type="recent" class="email-input-typeahead-container">
  <span class="twitter-typeahead" style="position: relative; display: inline-block;">
  <input type="email" placeholder="{{ trans('messages.dashboard.email_address') }}" name="friend_address[]" autocomplete="none" class="typeahead  tt-input" spellcheck="false" dir="auto" style="position: relative; vertical-align: top;">
  <pre aria-hidden="true" style="position: absolute; visibility: hidden; white-space: pre; font-family: Circular,&quot;Helvetica Neue&quot;,Helvetica,Arial,sans-serif; font-size: 14px; font-style: normal; font-variant: normal; font-weight: 400; word-spacing: 0px; letter-spacing: 0px; text-indent: 0px; text-rendering: optimizelegibility; text-transform: none;">
  </pre>
  <div class="tt-menu" style="position: absolute; top: 100%; left: 0px; z-index: 100; display: none;">
  <div class="tt-dataset tt-dataset-email-typeahead">
  </div>
  </div>
  </span>
  </div>
  </div>
  </div>
  </div>
  <div class="row row-space-8">
  <div class="col-md-10 col-sm-12">
  <div class="friend-email space-2">
  <div data-email-tagging="false" data-typeahead-type="recent" class="email-input-typeahead-container">
  <span class="twitter-typeahead" style="position: relative; display: inline-block;">
  <input type="email" placeholder="{{ trans('messages.dashboard.email_address') }}" name="friend_address[]" autocomplete="none" class="typeahead  tt-input" spellcheck="false" dir="auto" style="position: relative; vertical-align: top;">
  <pre aria-hidden="true" style="position: absolute; visibility: hidden; white-space: pre; font-family: Circular,&quot;Helvetica Neue&quot;,Helvetica,Arial,sans-serif; font-size: 14px; font-style: normal; font-variant: normal; font-weight: 400; word-spacing: 0px; letter-spacing: 0px; text-indent: 0px; text-rendering: optimizelegibility; text-transform: none;">
  </pre>
  <div class="tt-menu" style="position: absolute; top: 100%; left: 0px; z-index: 100; display: none;">
  <div class="tt-dataset tt-dataset-email-typeahead">
  </div>
  </div>
  </span>
  </div>
  </div>
  </div>
  </div>
  </div>
  <a data-prevent-default="true" id="add_another" href="javascript:void(0);">{{ trans('messages.payments.add_another') }}</a>
  </div>
  </div>
  </div>
  </div>
  <div class="space-md-4 space-sm-2">
  <button type="submit" class="btn btn-primary">{{ trans('messages.lys.continue') }}</button>
  </div>
  </div>
  </div>
  <div class="col-sm-5 col-sm-offset-1 listing-card hide-md hide-sm">
  <div>
  <div class="text-center">
  <div class="space-4">
  <a target="_blank" href="{{ url() }}/rooms/{{ $reservation_details->room_id }}">
  <img src="{{ url('images/'.$reservation_details->rooms->photo_name) }}" width="350">
  </a>
  </div>
  </div>
  <div>
  <div class="col-sm-5 text-center">
  <a class="link-reset" href="{{ url() }}/users/show/{{ $reservation_details->host_id }}">
  <div class="space-2">
  <span style="background-image:url({{ $reservation_details->rooms->users->profile_picture->header_src }}); width:68px; height:68px; margin: 0 auto;" class="media-photo media-round"></span>
  </div>
  <div class="space-1">
  <h5>{{ $reservation_details->rooms->users->first_name }}</h5>
  </div>
  </a>
  <div class="space-1">
  <small>
  <div class="star-rating text-left">
  <div class="foreground">
  </div>
  <div>
  </div>
  </div>
  </small>
  </div>
  </div>
  <div class="col-sm-7">
  <h3 class="listing-card-spacing">
  <a class="link-reset" target="_blank" href="{{ url() }}/rooms/{{ $reservation_details->room_id }}">{{ $reservation_details->rooms->name }}</a>
  </h3>
  <h4 class="listing-card-spacing">{{ $reservation_details->dates }}</h4>
  <h4 class="text-kazan">
  @if($reservation_details->status == 'Accepted')
<div><span>{{ $reservation_details->rooms->rooms_address->address_line_1 }}</span></div><div><span>{{ $reservation_details->rooms->rooms_address->city }}, {{ $reservation_details->rooms->rooms_address->state }} {{ $reservation_details->rooms->rooms_address->postal_code }}</span></div>
  @endif
  </h4>
  </div>
  </div>
  </div>
  </div>
  </div>
  </form>
  </div>
  </div>
  </div>
  </div>
  </div>

  </main>
@push('scripts')
<script>
$(document).ready(function() {
  $('#add_another').click(function() {
    $(".add-text-email:first").clone().appendTo(".add-friend").find('input[type="email"]').val('');
  });
})
</script>
@endpush
@stop
