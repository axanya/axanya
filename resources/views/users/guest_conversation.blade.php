@extends('template')
 
@section('main')

<main id="site-content" role="main" ng-controller="conversation">

@include('common.subheader')  

<div class="page-container page-container-responsive row-space-top-4">
    <div class="row">
      <div class="col-md-7 col-md-push-5 messaging-thread-main">
        <div id="message_friction_react" class="thread-list-item">
        </div>
        <div class="js-messaging-react-container messaging-thread-container">
        <div>
        <input type="hidden" value="{{ $messages[0]->reservation_id }}" id="reservation_id">
        @if($messages[0]->message_type == 1)
        <div class="text-center panel-body banner-status space-6">
        <div class="h4 space-1">
        <strong>
        <span>{{ trans('messages.payments.request_sent') }}</span>
        </strong>
        </div>
        <div>
        <span>{{ trans('messages.inbox.reservation_isnot_confirmed') }}</span>
        </div>
        </div>
        @endif
        @if($messages[0]->message_type == 2)
        <div class="text-center panel-body banner-status space-6">
        <div class="h4 space-1">
        <strong>
        <span>{{ trans('messages.inbox.reservation_confirmed_place') }} {{ $messages[0]->reservation->rooms->rooms_address->city }}, {{ $messages[0]->reservation->rooms->rooms_address->country_name }}</span>
        </strong>
        </div>
        <a href="{{ url() }}/reservation/itinerary?code={{ $messages[0]->reservation->code }}" class="btn space-top-3">
        <span>{{ trans('messages.your_trips.view_itinerary') }}</span>
        </a>
        <!-- <a href="{{ url() }}/reservation/change?code={{ $messages[0]->reservation->code }}" class="btn space-top-3">
        <span>Change or Cancel</span>
        </a> -->
        </div>
        @endif
        @if($messages[0]->message_type == 3)
        <div class="text-center panel-body banner-status space-6">
        <div class="h4 space-1">
        <strong>
        <span>{{ trans('messages.inbox.request_declined') }}</span>
        </strong>
        </div>
        <div>
        <span>{{ trans('messages.inbox.more_places_available') }}</span>
        </div>
        <a class="btn space-top-3" href="{{ url() }}/s?location={{ $messages[0]->reservation->rooms->rooms_address->city }}">
        <span>{{ trans('messages.inbox.keep_searching') }}</span>
        </a>
        </div>
        @endif
        
        @if($messages[0]->special_offer_id)
        <div class="panel action-status row-space-6">
        <div class="panel-body text-center">
        <div class="h4 space-1">
        <span>{{ ucfirst($messages[0]->reservation->rooms->users->first_name) }} {{ trans('messages.inbox.pre_approved_trip') }}</span>
        </div>
        <div class="space-top-3">
        <a href="{{ url() }}/payments/book?checkin={{ $messages[0]->special_offer->checkin }}&amp;checkout={{ $messages[0]->special_offer->checkout }}&amp;room_id={{ $messages[0]->special_offer->room_id }}&amp;number_of_guests={{ $messages[0]->special_offer->number_of_guests }}&amp;ref=qt2_preapproved&amp;special_offer_id={{ $messages[0]->special_offer_id }}" class="btn btn-primary">
        <span>{{ trans('messages.inbox.book_now') }}</span>
        </a>
        </div>
        </div>
        </div>
        @endif
        <div id="post_message_box" data-key="guest_conversation" class="row row-condensed row-space-6 send-message-box"><div class="col-sm-10"><div class="panel-quote-flush panel-quote panel-quote-right panel"><div class="panel-body text-left text-medium-gray"><textarea rows="3" placeholder="" class="send-message-textarea" id="message_text" name="message"></textarea></div><div class="panel-body panel-dark text-right"><button class="btn" id="reply_message" ng-click="reply_message('guest_conversation')">{{ trans('messages.your_reservations.send_message') }}</button></div></div></div><div class="col-sm-2 text-right"><div class="media-photo media-round" style="width:70px; height:70px;"><span style="background-image:url({{ Auth::user()->user()->profile_picture->src }}); width:70px; height:70px;"></span></div></div></div>
        <div id="thread-list">
        <div>
        <div id="thread-list">
        @for($i=0; $i<count($messages); $i++)
        @if($messages[$i]->message_type == 9)
        <div class="inline-status text-branding space-6">
        <div class="horizontal-rule-text">
        <span class="horizontal-rule-wrapper">
        <span>
        <span>{{ trans('messages.inbox.contact_request_sent') }} </span>
        <span>{{ $messages[$i]->created_time }}</span>
        </span>
        </span>
        </div>
        </div>
        @endif
        @if($messages[$i]->message_type == 2)
        <div class="inline-status text-branding space-6">
        <div class="horizontal-rule-text">
        <span class="horizontal-rule-wrapper">
        <span>
        <span>{{ trans('messages.inbox.reservation_confirmed') }} </span>
        <span>{{ $messages[$i]->created_time }}</span>
        </span>
        </span>
        </div>
        </div>
        @endif
        @if($messages[$i]->message_type == 3)
        <div class="inline-status text-branding space-6">
        <div class="horizontal-rule-text">
        <span class="horizontal-rule-wrapper">
        <span>
        <span>{{ trans('messages.inbox.reservation_declined') }} </span>
        <span>{{ $messages[$i]->created_time }}</span>
        </span>
        </span>
        </div>
        </div>
        @endif
        @if($messages[$i]->message_type == 4)
        <div class="inline-status text-branding space-6">
        <div class="horizontal-rule-text">
        <span class="horizontal-rule-wrapper">
        <span>
        <span>{{ trans('messages.inbox.reservation_expired') }} </span>
        <span>{{ $messages[$i]->created_time }}</span>
        </span>
        </span>
        </div>
        </div>
        @endif
        @if($messages[$i]->message_type == 6)
        <div class="inline-status text-branding space-6">
        <div class="horizontal-rule-text">
        <span class="horizontal-rule-wrapper">
        <span>
        <span>{{ $messages[$i]->reservation->rooms->users->first_name }} {{ trans('messages.inbox.pre_approved_you') }} </span>
        <span>{{ $messages[$i]->created_time }}</span>
        </span>
        </span>
        </div>
        </div>
        @endif
        @if($messages[$i]->message_type == 7)
        <div class="inline-status text-branding space-6">
        <div class="horizontal-rule-text">
        <span class="horizontal-rule-wrapper">
        <span>
        <span>{{ $messages[$i]->reservation->rooms->users->first_name }} {{ trans('messages.inbox.sent_special_offer') }} </span>
        <span>{{ $messages[$i]->special_offer->currency->symbol.$messages[$i]->special_offer->price }}/{{ ucfirst(trans_choice('messages.inbox.night',1)) }}</span>
        </span>
        </span>
        </div>
        </div>
        @endif
        @if($messages[$i]->message_type == 8)
        <div class="inline-status text-branding space-6">
        <div class="horizontal-rule-text">
        <span class="horizontal-rule-wrapper">
        <span>
        <span>{{ trans('messages.inbox.date_unavailable') }}</span>
        <span>{{ $messages[$i]->created_time }}</span>
        </span>
        </span>
        </div>
        </div>
        @endif
        @if($messages[$i]->user_from != Auth::user()->user()->id && $messages[$i]->message != '')
        <div>
        <div>
        <div class="row row-condensed row-space-6 post">
        <div class="col-sm-2 text-left">
        <div class="media-photo media-round" style="width:70px; height:70px;">
        <span style="background-image:url({{ $messages[$i]->user_details->profile_picture->src }}); width:70px; height:70px;" class="user-profile-photo"></span>
        </div>
        </div>
        <div class="col-sm-10">
        <div class="panel-quote-flush panel-quote panel">
        <div class="panel-body">
        <div>
        <div>
        <div class="pull-right">
        <a data-prevent-default="true" title="Report this message" class="flag-trigger link-reset" href="#">
        <!-- <i class="icon icon-flag h4"> -->
        </i>
        </a>
        </div>
        </div>
        </div>
        <div>
        <span class="message-text">{{ $messages[$i]->message }}</span>
        </div>
        <div class="space-top-2 text-muted">
        <span class="time">{{ $messages[$i]->created_time }}</span>
        </div>
        </div>
        </div>
        </div>
        </div>
        </div>
        </div>
        @endif
        @if($messages[$i]->user_from == Auth::user()->user()->id)
        <div>
        <div>
        <div class="row row-condensed row-space-6 post">
        <div class="col-sm-10">
        <div class="panel-quote-flush panel-quote panel panel-quote-right panel-quote-dark">
        <div class="panel-body panel-dark">
        <div>
        <span class="message-text">{{ $messages[$i]->message }}</span>
        </div>
        <div class="space-top-2 text-muted">
        <span class="time">{{ $messages[$i]->created_time }}</span>
        </div>
        </div>
        </div>
        </div>
        <div class="col-sm-2 text-right">
        <div class="media-photo media-round" style="width:70px; height:70px;">
        <span style="background-image:url({{ Auth::user()->user()->profile_picture->src }}); height:70px; width:70px;" class="user-profile-photo"></span>
        </div>
        </div>
        </div>
        </div>
        </div>
        @endif
        @endfor
        </div>
        </div>

        </div>
        </div>
        </div>
      </div>

<div class="col-md-4 col-md-pull-7 bg-white qt-sidebar-redesign space-4">
<form accept-charset="UTF-8" action="{{ url() }}/messaging/qt_reply/{{ $messages[0]->reservation_id }}" method="post">
<div class="text-center mini-profile media">
  <div class="panel-image">
    <div class="verified-badge-aligner">
      <a href="{{ url() }}/users/show/{{ $messages[0]->reservation->rooms->users->id }}" class="media-photo media-round">
        <span style="background-image:url({{ $messages[0]->reservation->rooms->users->profile_picture->src }}); height:150px; width:150px;"></span>
      </a>
    </div>
  </div>

  <div class="space-top-3 text-center">
    <div class="h4">
      <a href="{{ url() }}/users/show/{{ $messages[0]->reservation->rooms->users->id }}" class="text-normal">{{ $messages[0]->reservation->rooms->users->first_name }}</a>
    </div>
    <div class="text-medium-gray row-space-top-1">
       {{ $messages[0]->reservation->rooms->users->live }}
    </div>
  </div>

  @if($messages[0]->reservation->rooms->users->about)
    <div class="space-top-1 text-center text-wrap">
      <div class="expandable expandable-trigger-more expanded">
          <div class="expandable-content">
            <p>{{ $messages[0]->reservation->rooms->users->about }}</p>
            <div class="expandable-indicator expandable-indicator-light">
            </div>
          </div>
              <a class="expandable-trigger-more" href="#">
      <strong>+ {{ trans('messages.profile.more') }}</strong>
    </a>
        </div>
    </div>
  @endif

  @if($messages[0]->reservation->status == 'Accepted')
  <div class="space-top-3 text-left">
      <div class="text-medium-gray">
        {{ trans('messages.login.email') }}
      </div>
      <div class="space-top-1">
        {{ $messages[0]->reservation->rooms->users->email }}
      </div>
  </div>
  @endif
</div>

  <div class="js-messaging-sidebar-react-container">
  <div>
  <hr class="space-top-6">
  <div>
  <div class="row-space-6 row-space-top-6">
  <div class="qt-reservation-info clearfix">
  <div class="row-space-6 h4">
  <a class="text-normal" href="{{ url() }}/rooms/{{ $messages[0]->reservation->room_id }}">{{ $messages[0]->reservation->rooms->name }}</a>
  </div>
  <div class="reservation-info-section pull-left">
  <div class="text-medium-gray row-space-1">
  <span>{{ trans('messages.your_reservations.checkin') }}</span>
  </div>
  <div class="row-space-3 h4">{{ $messages[0]->reservation->checkin_dmy_slash }}</div>
  </div>
  <div class="reservation-info-section pull-left">
  <div class="text-medium-gray row-space-1">
  <span>{{ trans('messages.your_reservations.checkout') }}</span>
  </div>
  <div class="row-space-3 h4">{{ $messages[0]->reservation->checkout_dmy_slash }}</div>
  </div>
  <div class="reservation-info-section pull-left">
  <div class="text-medium-gray row-space-1">
  <span>{{ trans_choice('messages.home.guest',2) }}</span>
  </div>
  <div class="row-space-3 h4">{{ $messages[0]->reservation->number_of_guests }}</div>
  </div>
  </div>
  </div>
  </div>
  <hr>
  <div class="row-space-top-6 qt-payment-info">
  <div class="h4 row-space-6">
  <span>{{ trans('messages.payments.payment') }}</span>
  </div>
  <div class="row-space-top-6">
  <div>
  <div class="row text-emphasis-gray">
  <div class="col-sm-8 text-left">
  <span>
  <span>
  <span>{{ $messages[0]->reservation->currency->symbol.$messages[0]->reservation->per_night }}</span>
  </span>
  <span> x {{ $messages[0]->reservation->nights }} {{ trans_choice('messages.rooms.night',$messages[0]->reservation->nights) }}</span>
  </span>
  </div>
  <div class="col-sm-4 text-right">
  <span>
  <span>{{ $messages[0]->reservation->currency->symbol.$messages[0]->reservation->per_night*$messages[0]->reservation->nights }}</span>
  </span>
  </div>
  </div>
  </div>
  @if($messages[0]->reservation->additional_guest != 0)
  <div class="row text-emphasis-gray row-space-2 row-space-top-2">
  <div class="col-sm-8 text-left">
  <span>{{ trans('messages.rooms.addtional_guest_fee') }}</span>
  </div>
  <div class="col-sm-4 text-right">
  <span>
  <span>{{ $messages[0]->reservation->currency->symbol.$messages[0]->reservation->additional_guest }}</span>
  </span>
  </div>
  </div>
  @endif
  @if($messages[0]->reservation->cleaning != 0)
  <div class="row text-emphasis-gray row-space-2 row-space-top-2">
  <div class="col-sm-8 text-left">
  <span>{{ trans('messages.rooms.cleaning_fee') }}</span>
  </div>
  <div class="col-sm-4 text-right">
  <span>
  <span>{{ $messages[0]->reservation->currency->symbol.$messages[0]->reservation->cleaning }}</span>
  </span>
  </div>
  </div>
  @endif
  @if($messages[0]->reservation->security != 0)
  <div class="row text-emphasis-gray row-space-2 row-space-top-2">
  <div class="col-sm-8 text-left">
  <span>{{ trans('messages.rooms.security_fee') }}</span>
  </div>
  <div class="col-sm-4 text-right">
  <span>
  <span>{{ $messages[0]->reservation->currency->symbol.$messages[0]->reservation->security }}</span>
  </span>
  </div>
  </div>
  @endif
  <div class="row text-emphasis-gray row-space-2 row-space-top-2">
  <div class="col-sm-8 text-left">
  <span>{{ trans('messages.rooms.service_fee') }}</span>
  </div>
  <div class="col-sm-4 text-right">
  <span>
  <span>{{ $messages[0]->reservation->currency->symbol.$messages[0]->reservation->service }}</span>
  </span>
  </div>
  </div>
  @if($messages[0]->reservation->coupon_amount != 0)
  <div class="row text-emphasis-gray row-space-2 row-space-top-2">
  <div class="col-sm-8 text-left">
  <span>{{ trans('messages.payments.coupon_amount') }}</span>
  </div>
  <div class="col-sm-4 text-right">
  <span>
  <span>-{{ $messages[0]->reservation->currency->symbol.$messages[0]->reservation->coupon_amount }}</span>
  </span>
  </div>
  </div>
  @endif
  <hr>
  <div class="row row-space-3 row-space-top-2">
  <div class="col-sm-8 text-left">
  <strong>
  <span>{{ trans('messages.rooms.total') }}</span>
  </strong>
  </div>
  <div class="col-sm-4 text-right">
  <strong>
  <span>
  <span>{{ $messages[0]->reservation->currency->symbol.$messages[0]->reservation->total }}</span>
  </span>
  </strong>
  </div>
  </div>
  </div>
  <div class="row-space-6 row-space-top-4 text-gray">
  <span>{{ trans('messages.inbox.protect_your_payments') }}</span>
  <span>&nbsp;</span>
  <span>{{ trans('messages.inbox.never_pay_outside',['site_name'=>$site_name]) }}</span>
  <span>&nbsp;</span>
  <i class="icon icon-question tns-payment-tooltip-trigger">
  </i>
  </div>
  </div>
  </div>
  </div>
</form>      
</div>
</div>
</div>
</main>
@stop