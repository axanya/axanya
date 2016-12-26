@extends('template')

@section('main')
<main id="site-content" role="main" ng-controller="conversation">

@include('common.subheader')  

<div class="page-container page-container-responsive row-space-top-4">

    <h1 class="h2 row-space-4">
      {{ trans('messages.inbox.conversation_with') }} {{ $messages[0]->reservation->users->first_name }}
    </h1>

@if($messages[0]->reservation->status == 'Accepted')
    <div class="row">
      <div class="col-lg-12">
        
  <div class="alert alert-with-icon alert-success alert-block row-space-4">
  <i class="icon alert-icon icon-star-circled"></i>
  
    <div class="h5 row-space-1">
      {{ trans('messages.inbox.accepted') }}
    </div>

    <p>
      {{ trans('messages.inbox.you_have_accepted_reservation',['site_name'=>$site_name, 'first_name'=>$messages[0]->reservation->users->first_name]) }} <a class="alert-link" href="mailto:{{ $messages[0]->reservation->users->email }}">{{ trans('messages.inbox.email') }}</a>
        <i class="icon icon-question"></i>
  </p>

      <div>
      <a class="alert-link" href="{{ url() }}/reservation/itinerary?code={{ $messages[0]->reservation->code }}">{{ trans('messages.your_trips.view_itinerary') }}</a>
      <span id="conversation_alert_link_divider">|</span>
        <!-- <a class="alert-link" href="{{ url() }}/reservation/change?code={{ $messages[0]->reservation->code }}">Change or Cancel</a> -->
      </div>
</div>
      </div>
    </div>
  @endif

    <div class="row">
      <div class="col-lg-8">

<ul class="list-unstyled">

  <li class="thread-list-item" id="message_friction_react"></li>

  <li id="post_message_box" class="thread-list-item">
  <div class="row row-condensed row-space-4">
    <div class="col-sm-12 col-md-10 col-md-push-1">
      <div class="panel-quote-flush panel">

        <div class="panel-body normal-form-fields">
        
        <form id="non_special_offer_form" data-key="non_special_offer_form" class="message_form clearfix">

    <input type="hidden" value="{{ $messages[0]->reservation_id }}" name="inquiry_post_id" id="reservation_id">
    <input type="hidden" value="{{ $messages[0]->reservation->room_id }}" name="room_id" id="room_id">

  <input type="hidden" value="" name="template">
  <textarea style="resize: none; height: 62px; overflow: hidden; word-wrap: break-word;" placeholder="{{ trans('messages.inbox.add_personal_msg') }}" name="message" id="message_text" class="row-space-2 input-resize-vert"></textarea>

  <div class="pull-right">
    @if($messages[0]->reservation->type != 'contact')
      <a class="btn offer_attach row-space-1" href="javascript:void(0);">
          {{ trans('messages.inbox.attach_special_offer') }}
      </a>
    @endif
      @if($messages[0]->reservation->type == 'contact')
      <a class="btn pre_approve row-space-1" href="javascript:void(0);">
          {{ trans('messages.inbox.pre_approve') }} / {{ trans('messages.your_reservations.decline') }}
      </a>
      @endif

    <input type="button" value="{{ trans('messages.your_reservations.send_message') }}" class="btn btn-primary" ng-click="reply_message('non_special_offer_form')">
  </div>
</form>
            </div>

<div class="inquiry-form-fields hide">
    <div class="panel-body panel-dark">
      <div class="row">
        <div class="col-md-8">
          <div class="h4">
            {{ $messages[0]->reservation->rooms->name }}
            <br>
            <small>
              {{ $messages[0]->reservation->dates }} ({{ $messages[0]->reservation->nights }} {{ trans_choice('messages.rooms.night',1) }}{{ ($messages[0]->reservation->nights > 1) ? 's' : '' }})
              ·
              {{ $messages[0]->reservation->number_of_guests }} {{ trans_choice('messages.home.guest',$messages[0]->reservation->number_of_guests) }}
            </small>
          </div>
        </div>
        <div class="col-md-4">

            <div class="h2 pull-right text-right">
              <sup class="h4">
                {{ $messages[0]->reservation->currency->symbol }}
              </sup>{{ $messages[0]->reservation->subtotal }}
            </div>
        </div>
      </div>
    </div>

  <div class="panel-body">
    <ul class="list-unstyled option-list">
        <li data-tracking-section="accept" class="option-list-item positive">

          <a class="option-link h3" href="javascript:void(0);">
            {{ trans('messages.inbox.allow_guest_book') }}
          </a>

          <form class="message_form positive" id="allow_guest">
              <input type="hidden" value="{{ $messages[0]->reservation_id }}" name="inquiry_post_id">

            <ul class="list-unstyled action-list hide">
              <!-- 1 -->
              <li data-key="pre-approve" class="action-list-item template_1 row-space-top-1">
                <hr>
                <label class="action-label">
                  <div class="row row-condensed">
                    <div class="col-sm-1">
                      <input type="radio" value="1" name="template">
                    </div>
                    <div class="col-sm-11">
                      <strong>{{ trans('messages.inbox.pre_approve_book',['first_name'=>$messages[0]->reservation->users->first_name]) }}</strong>

                        <a href="javascript:void(0);" class="pull-right">
                          {{ trans('messages.inbox.what_this') }}?
                        </a>
                    </div>
                  </div>
                </label>

                <div class="row row-condensed">
                  <div class="col-sm-offset-1 col-sm-11">
                    <div class="drawer hide">
                        <p class="description">{{ trans('messages.inbox.pre_approve_desc',['first_name'=>$messages[0]->reservation->users->first_name]) }}</p>

                      <textarea style="resize: none; height: 162px; overflow: hidden; word-wrap: break-word;" class="row-space-2 input-resize-vert" placeholder="{{ trans('messages.inbox.include_msg',['first_name'=>$messages[0]->reservation->users->first_name]) }}" name="message"></textarea>

                      <div class="pull-right">
                        <input type="submit" value="{{ trans('messages.inbox.pre_approve') }}" class="btn btn-primary" ng-click="reply_message('pre-approve')">
                      </div>
                    </div>
                  </div>
                </div>
              </li>
              <!-- 2 -->
              <li data-key="special_offer" class="action-list-item template_2 row-space-top-1">
                <hr>
                <label class="action-label">
                  <div class="row row-condensed">
                    <div class="col-sm-1">
                      <input type="radio" value="2" name="template">
                    </div>
                    <div class="col-sm-11">
                      <strong>{{ trans('messages.inbox.send_a_special_offer',['first_name'=>$messages[0]->reservation->users->first_name]) }}</strong>
                        <a href="javascript:void(0);" class="pull-right">
                          {{ trans('messages.inbox.what_this') }}?
                        </a>
                    </div>
                  </div>
                </label>

                <div class="row row-condensed">
                  <div class="col-sm-offset-1 col-sm-11">
                    <div class="drawer hide">
                        <p class="description">{{ trans('messages.inbox.special_offer_desc',['first_name'=>$messages[0]->reservation->users->first_name]) }}</p>

                        <fieldset class="available-special-offer">
    <label for="pricing_room_id">{{ trans('messages.lys.listing') }}</label>
    <div class="select select-block row-space-1">
      {!! Form::select('pricing[hosting_id]', $rooms, $messages[0]->reservation->room_id, ['id'=>'pricing_room_id']); !!}
    </div>

    <div class="special-offer-date-fields">
      <div class="row row-condensed row-space-1">
        <div class="col-4">
          <label for="pricing_start_date">{{ trans('messages.your_reservations.checkin') }}</label>
          <input type="text" value="{{ $messages[0]->reservation->checkin_datepicker }}" name="pricing[start_date]" id="pricing_start_date" class="checkin ui-datepicker-target">
        </div>
        <div class="col-4">
          <label for="pricing_end_date">{{ trans('messages.your_reservations.checkout') }}</label>
          <input type="text" value="{{ $messages[0]->reservation->checkout_datepicker }}" name="pricing[end_date]" id="pricing_end_date" class="checkout ui-datepicker-target">
        </div>
        <div class="col-4">
          <label for="pricing_guests">{{ trans_choice('messages.home.guest',2) }}</label>
          <div class="select select-block">
            <select name="pricing[guests]" id="pricing_guests">
            @for($i=1;$i<= $messages[0]->reservation->rooms->accommodates;$i++)
              <option value="{{ $i }}">{{ $i }}</option>
            @endfor
</select>
          </div>
          <input type="hidden" value="nightly" name="pricing[unit]" id="pricing_unit">
        </div>
      </div>
    </div>

    <div id="availability_warning" class="alert alert-with-icon alert-info  row-space-top-2 hide">
  <i class="icon alert-icon icon-comment"></i>
      {{ trans('messages.inbox.already_marked_dates') }}
</div>

    <div class="row row-condensed row-space-2">
      <div class="col-4">
        <label for="pricing_price">{{ trans('messages.inbox.price') }}</label>
        <div class="input-addon pricing-field">
          <span class="input-prefix">{{ $messages[0]->reservation->currency->symbol }}</span>
          <input type="text" value="{{ $messages[0]->reservation->subtotal }}" name="pricing[price]" id="pricing_price" class="input-stem">
        </div>
      </div>
      <div class="col-4">
        <label for="pricing_price_type">&nbsp;</label>
        <div class="select hide">
          <select name="pricing[price_type]" id="pricing_price_type" disabled="">
            <option value="total">{{ trans('messages.inbox.subtotal_price') }}</option>
            <option value="per_unit">{{ trans('messages.rooms.per_month') }}</option>
          </select>
        </div>
      </div>
    </div>

    <div class="row-space-2">
      {{ trans('messages.inbox.price_include_additional_fees') }}
    </div>

    <div class="row-space-2" id="price-breakdown"></div>
</fieldset>

                      <textarea style="resize: none; height: 162px; overflow: hidden; word-wrap: break-word;" class="row-space-2 input-resize-vert" placeholder="{{ trans('messages.inbox.include_msg',['first_name'=>$messages[0]->reservation->users->first_name]) }}" name="message"></textarea>

                      <div class="pull-right">
                        <input type="submit" value="{{ trans('messages.inbox.send_offer') }}" class="btn btn-primary" ng-click="reply_message('special_offer')">
                      </div>
                    </div>
                  </div>
                </div>
              </li>
            </ul>
</form>        </li>
      
        <li data-tracking-section="decline" class="option-list-item negative">
            <hr>

          <a class="option-link h3" href="javascript:void(0);">
            {{ trans('messages.inbox.tell_listing_unavailable') }}
          </a>

          <form class="message_form negative" id="decline">
              <input type="hidden" value="" name="inquiry_post_id">

            <ul class="list-unstyled action-list hide">
              <!-- NOT_AVAILABLE -->
              <li data-key="dates_not_available" class="action-list-item template_NOT_AVAILABLE row-space-top-1">
                <hr>
                <label class="action-label">
                  <div class="row row-condensed">
                    <div class="col-sm-1">
                      <input type="radio" value="NOT_AVAILABLE" name="template">
                    </div>
                    <div class="col-sm-11">
                      <strong>{{ trans('messages.inbox.dates_not_available_block',['dates'=>$messages[0]->reservation->dates]) }}</strong>

                    </div>
                  </div>
                </label>

                <div class="row row-condensed">
                  <div class="col-sm-offset-1 col-sm-11">
                    <div class="drawer hide">
                        <p class="description">{{ trans('messages.inbox.calc_marked_unavailable',['dates'=>$messages[0]->reservation->dates]) }}</p>

                      <textarea style="resize: none; height: 82px; overflow: hidden; word-wrap: break-word;" class="row-space-2 input-resize-vert" placeholder="{{ trans('messages.inbox.optional_msg') }}" name="message"></textarea>

                      <div class="pull-right">
                        <input type="submit" value="{{ trans('messages.inbox.send') }}" class="btn btn-primary" ng-click="reply_message('dates_not_available')">
                      </div>
                    </div>
                  </div>
                </div>
              </li>
              <!-- 9 -->
              <li data-key="not_comfortable" class="action-list-item template_9 row-space-top-1">
                <hr>
                <label class="action-label">
                  <div class="row row-condensed">
                    <div class="col-sm-1">
                      <input type="radio" value="9" name="template">
                    </div>
                    <div class="col-sm-11">
                      <strong>{{ trans('messages.inbox.donot_feel_comfortable') }}</strong>

                    </div>
                  </div>
                </label>

                <div class="row row-condensed">
                  <div class="col-sm-offset-1 col-sm-11">
                    <div class="drawer hide">

                      <textarea style="resize: none; height: 82px; overflow: hidden; word-wrap: break-word;" class="row-space-2 input-resize-vert " placeholder="{{ trans('messages.inbox.optional_msg') }}" name="message"></textarea>

                      <div class="pull-right">
                        <input type="submit" value="{{ trans('messages.inbox.send') }}" class="btn btn-primary" ng-click="reply_message('not_comfortable')">
                      </div>
                    </div>
                  </div>
                </div>
              </li>
              <!-- 9 -->
              <li data-key="not_a_good_fit" class="action-list-item template_9 row-space-top-1">
                <hr>
                <label class="action-label">
                  <div class="row row-condensed">
                    <div class="col-sm-1">
                      <input type="radio" value="9" name="template">
                    </div>
                    <div class="col-sm-11">
                      <strong>{{ trans('messages.inbox.listing_not_good_fit') }}</strong>

                    </div>
                  </div>
                </label>

                <div class="row row-condensed">
                  <div class="col-sm-offset-1 col-sm-11">
                    <div class="drawer hide">

                      <textarea style="resize: none; height: 82px; overflow: hidden; word-wrap: break-word;" class="row-space-2 input-resize-vert " placeholder="{{ trans('messages.inbox.optional_msg') }}" name="message"></textarea>

                      <div class="pull-right">
                        <input type="submit" value="{{ trans('messages.inbox.send') }}" class="btn btn-primary" ng-click="reply_message('not_a_good_fit')">
                      </div>
                    </div>
                  </div>
                </div>
              </li>
              <!-- 9 -->
              <li data-key="waiting_for_better_reservation" class="action-list-item template_9 row-space-top-1">
                <hr>
                <label class="action-label">
                  <div class="row row-condensed">
                    <div class="col-sm-1">
                      <input type="radio" value="9" name="template">
                    </div>
                    <div class="col-sm-11">
                      <strong>{{ trans('messages.inbox.waiting_attractive_reservation') }}</strong>

                    </div>
                  </div>
                </label>

                <div class="row row-condensed">
                  <div class="col-sm-offset-1 col-sm-11">
                    <div class="drawer hide">

                      <textarea style="resize: none; height: 82px; overflow: hidden; word-wrap: break-word;" class="row-space-2 input-resize-vert " placeholder="{{ trans('messages.inbox.optional_msg') }}" name="message"></textarea>

                      <div class="pull-right">
                        <input type="submit" value="{{ trans('messages.inbox.send') }}" class="btn btn-primary" ng-click="reply_message('waiting_for_better_reservation')">
                      </div>
                    </div>
                  </div>
                </div>
              </li>
              <!-- 9 -->
              <li data-key="different_dates_than_selected" class="action-list-item template_9 row-space-top-1">
                <hr>
                <label class="action-label">
                  <div class="row row-condensed">
                    <div class="col-sm-1">
                      <input type="radio" value="9" name="template">
                    </div>
                    <div class="col-sm-11">
                      <strong>{{ trans('messages.inbox.guest_asking_different_dates') }}</strong>

                    </div>
                  </div>
                </label>

                <div class="row row-condensed">
                  <div class="col-sm-offset-1 col-sm-11">
                    <div class="drawer hide">

                      <textarea style="resize: none; height: 82px; overflow: hidden; word-wrap: break-word;" class="row-space-2 input-resize-vert " placeholder="{{ trans('messages.inbox.optional_msg') }}" name="message"></textarea>

                      <div class="pull-right">
                        <input type="submit" value="{{ trans('messages.inbox.send') }}" class="btn btn-primary" ng-click="reply_message('different_dates_than_selected')">
                      </div>
                    </div>
                  </div>
                </div>
              </li>
              <!-- 9 -->
              <li data-key="spam" class="action-list-item template_9 row-space-top-1">
                <hr>
                <label class="action-label">
                  <div class="row row-condensed">
                    <div class="col-sm-1">
                      <input type="radio" value="9" name="template">
                    </div>
                    <div class="col-sm-11">
                      <strong>{{ trans('messages.inbox.msg_is_spam') }}</strong>

                    </div>
                  </div>
                </label>

                <div class="row row-condensed">
                  <div class="col-sm-offset-1 col-sm-11">
                    <div class="drawer hide">

                      <textarea style="resize: none; height: 82px; overflow: hidden; word-wrap: break-word;" class="row-space-2 input-resize-vert " placeholder="{{ trans('messages.inbox.optional_msg') }}" name="message"></textarea>

                      <div class="pull-right">
                        <input type="submit" value="{{ trans('messages.inbox.send') }}" class="btn btn-primary" ng-click="reply_message('spam')">
                      </div>
                    </div>
                  </div>
                </div>
              </li>
              <!-- 9 -->
              <li data-key="other" class="action-list-item template_9 row-space-top-1">
                <hr>
                <label class="action-label">
                  <div class="row row-condensed">
                    <div class="col-sm-1">
                      <input type="radio" value="9" name="template">
                    </div>
                    <div class="col-sm-11">
                      <strong>{{ trans('messages.profile.other') }}</strong>

                    </div>
                  </div>
                </label>

                <div class="row row-condensed">
                  <div class="col-sm-offset-1 col-sm-11">
                    <div class="drawer hide">

                      <textarea style="resize: none; height: 82px; overflow: hidden; word-wrap: break-word;" class="row-space-2 input-resize-vert " placeholder="{{ trans('messages.inbox.optional_msg') }}" name="message"></textarea>

                      <div class="pull-right">
                        <input type="submit" value="{{ trans('messages.inbox.send') }}" class="btn btn-primary" ng-click="reply_message('other')">
                      </div>
                    </div>
                  </div>
                </div>
              </li>
            </ul>
</form>        </li>

        <li data-tracking-section="discussion" class="option-list-item neutral">
            <hr>

          <a class="option-link h3" href="javascript:void(0);">
            {{ trans('messages.inbox.write_back_to_learn') }}
          </a>

          <form class="message_form neutral" id="discussion">
              <input type="hidden" value="" name="inquiry_post_id">

            <ul class="list-unstyled action-list hide">
              <!-- 7 -->
              <li data-key="discussion" class="action-list-item template_7 row-space-top-1">
                <hr>
                <label class="action-label">
                  <div class="row row-condensed">
                    <div class="col-sm-1">
                      <input type="radio" value="7" name="template">
                    </div>
                    <div class="col-sm-11">
                      <strong>{{ trans('messages.inbox.need_answer_question') }}</strong>

                    </div>
                  </div>
                </label>

                <div class="row row-condensed">
                  <div class="col-sm-offset-1 col-sm-11">
                    <div class="drawer hide">

                      <textarea style="resize: none; height: 122px; overflow: hidden; word-wrap: break-word;" class="row-space-2 input-resize-vert required" placeholder="{{ trans('messages.inbox.only_guest_see_msg') }}" name="message"></textarea>

                      <div class="pull-right">
                        <input type="submit" value="{{ trans('messages.inbox.send') }}" class="btn btn-primary" ng-click="reply_message('discussion')">
                      </div>
                    </div>
                  </div>
                </div>
              </li>
            </ul>
</form>        </li>
          
    </ul>

  </div>
</div>

      </div>
    </div>
  </div>
</li>
<div id="thread-list">
@for($i=0; $i < count($messages); $i++)

  @if($messages[$i]->user_from == Auth::user()->user()->id)
  <li id="question2_post_{{ $messages[$i]->id }}" class="thread-list-item">

@if($messages[$i]->message_type == 7)
<div class="row row-condensed">
      <div class="col-md-10 col-md-offset-1">
        <div class="panel row-space-4">
          <div class="panel-body panel-dark">
                    <span class="label label-info">
          {{ trans('messages.inbox.special_offer') }}
        </span>
        <div class="h5">
          {{ $messages[$i]->reservation->users->first_name }} {{ trans('messages.inbox.pre_approved_stay_at') }} <a href="{{ url('rooms/'.$messages[$i]->special_offer->room_id) }}">{{ $messages[$i]->special_offer->rooms->name }}</a>
        </div>
        <p class="text-muted">
          {{ $messages[$i]->special_offer->dates }}
          ·
          {{ $messages[$i]->special_offer->number_of_guests }} {{ trans_choice('messages.inbox.guest',$messages[$i]->special_offer->number_of_guests) }}

            <br>
            <strong>{{ trans('messages.inbox.you_could_earn') }} {{ $messages[$i]->special_offer->currency->symbol.$messages[$i]->special_offer->price }} {{ $messages[$i]->special_offer->currency->session_code }}</strong> ({{ trans('messages.inbox.once_reservation_made') }})
        </p>

          </div>
            <div class="panel-body">
                        <a href="{{ url() }}/messaging/remove_special_offer/{{ $messages[$i]->special_offer_id }}" class="btn" data-confirm="Are you sure?" data-method="post" rel="nofollow">{{ trans('messages.inbox.remove_special_offer') }}</a>

            </div>
        </div>
      </div>
    </div>
@endif

@if($messages[$i]->message_type == 6)
<div class="row row-condensed">
      <div class="col-md-10 col-md-offset-1">
        <div class="panel row-space-4">
          <div class="panel-body panel-dark">
                    <div class="h5">
          {{ $messages[$i]->reservation->users->first_name }} {{ trans('messages.inbox.pre_approved_stay_at') }} <a href="{{ url('rooms/'.$messages[$i]->reservation->room_id) }}">{{ $messages[$i]->special_offer->rooms->name }}</a>
        </div>
        <p class="text-muted">
          {{ $messages[$i]->special_offer->dates }}
          ·
          {{ $messages[$i]->special_offer->number_of_guests }} {{ trans_choice('messages.home.guest',$messages[$i]->special_offer->number_of_guests) }}
          ·
            {{ $messages[$i]->special_offer->currency->symbol.$messages[$i]->special_offer->price }} {{ $messages[$i]->special_offer->currency->session_code }}
          
        </p>

          </div>
            <div class="panel-body">
                        <a href="{{ url() }}/messaging/remove_special_offer/{{ $messages[$i]->special_offer_id }}" class="btn" data-confirm="Are you sure?" data-method="post" rel="nofollow">{{ trans('messages.inbox.remove_pre_approval') }}</a>
            </div>
        </div>
      </div>
    </div>
@endif

      <div class="row row-condensed">
          <div class="col-sm-2 col-md-1 text-center">
            <a aria-label="{{ $messages[$i]->reservation->rooms->users->first_name }}" data-behavior="tooltip" class="media-photo media-round" href="{{ url() }}/users/show/{{ $messages[$i]->reservation->host_id }}"><span title="{{ $messages[$i]->reservation->rooms->users->first_name }}" style="background-image:url({{ $messages[$i]->reservation->rooms->users->profile_picture->src }}); width:36px; height:36px;"></span></a>
          </div>

        <div class="col-sm-10 ">
          <div class="row-space-4">
    <div class="panel panel-quote panel-quote-flush ">
      <div class="panel-body">

          <div class="message-text">
              <p class="trans">{{ $messages[$i]->message }}</p>
          </div>
      </div>

    </div>

    <div class="time-container text-muted ">
      <small title="{{ $messages[$i]->created_at }}" class="time">
      
          {{ $messages[$i]->created_time }}

      </small>
      <small class="exact-time hide">
        {{ $messages[$i]->created_at }}
      </small>
    </div>
</div>

        </div>

      </div>
  </li>
  @endif

@if($messages[$i]->user_from != Auth::user()->user()->id)

  <li id="question2_post_{{ $messages[$i]->id }}" class="thread-list-item">

  @if($messages[$i]->message_type == 1 || $messages[$i]->message_type == 9)
    <div class="row row-condensed">
      <div class="col-md-10 col-md-offset-1">
        <div class="panel row-space-4">
          <div class="panel-body panel-dark">
                    <div class="h5">
          {{ trans('messages.inbox.inquiry_about') }} <a locale="en" data-popup="true" href="{{ url() }}/rooms/{{ $messages[$i]->reservation->room_id }}">{{ $messages[$i]->reservation->rooms->name }}</a>
        </div>
          <p class="text-muted">
            {{ $messages[$i]->reservation->dates }}
            ·
            {{ $messages[$i]->reservation->number_of_guests }} {{ trans_choice('messages.home.guest',$messages[$i]->reservation->number_of_guests) }}{{ ($messages[$i]->reservation->number_of_guests > 1) ? 's' : '' }}
            <br>
              {{ trans('messages.inbox.you_will_earn') }} {{ $messages[$i]->reservation->currency->symbol.$messages[$i]->reservation->host_payout }} {{ $messages[$i]->reservation->currency->code }}
          </p>

          </div>
        </div>
      </div>
    </div>
  @endif

      <div class="row row-condensed">

        <div class="col-sm-10 col-md-push-1">
          <div class="row-space-4">
    <div class="panel panel-quote panel-quote-flush panel-quote-right">
      <div class="panel-body">

          <div class="message-text">
              <p class="trans">{{ $messages[$i]->message }}</p>
          </div>
      </div>

    </div>

    <div class="time-container text-muted text-right">
      <small title="{{ $messages[$i]->created_at }}" class="time">
          {{ $messages[$i]->created_time }}

      </small>
      <small class="exact-time hide">
        {{ $messages[$i]->created_at }}
      </small>
    </div>
</div>

        </div>

          <div class="col-sm-2 col-md-1 col-md-push-1 text-center">
            <a aria-label="{{ $messages[$i]->reservation->users->first_name }}" data-behavior="tooltip" class="media-photo media-round" href="{{ url() }}/users/show/{{ $messages[$i]->reservation->user_id }}"><span title="{{ $messages[$i]->reservation->users->first_name }}" style="background-image:url({{ $messages[$i]->reservation->users->profile_picture->src }}); width:36px; height:36px;"></span></a>
          </div>

      </div>
  </li>
@endif
@endfor
</div>
</ul>

      </div>
      <div class="col-lg-4">

<div class="panel row-space-4">

  <div class="mini-profile va-container media">
    <div class="va-top pull-left">
      <a class="media-photo" href="{{ url() }}/users/show/{{ $messages[0]->reservation->user_id }}">
        <span style="background-image:url({{ $messages[0]->reservation->users->profile_picture->src }}); height: 100px; width:100px;"></span>
      </a>
    </div>

    <div class="va-middle">
      <div class="h4">
        <a class="text-normal" href="{{ url() }}/users/show/{{ $messages[0]->reservation->user_id }}">{{ $messages[0]->reservation->users->first_name }}</a>
          &nbsp;<!-- <i data-tooltip-sticky="true" data-tooltip-position="bottom" data-tooltip-el="#verifications-tooltip" class="icon icon-verified-id icon-lima" id="verified-id-icon"></i> -->
          <br>
          <small>{{ $messages[0]->reservation->users->live }}</small>
        <br>
        <small>
          {{ trans('messages.profile.member_since') }} {{ date('Y',strtotime($messages[0]->reservation->users->created_at)) }}
        </small>
      </div>
    </div>
  </div>

  @if(Auth::user()->user()->users_verification->show())
  <div class="panel-header">
      {{ trans('messages.dashboard.verifications') }}
  </div>
  <div class="panel-body">
      <ul class="list-unstyled">
      @if(Auth::user()->user()->users_verification->email == 'yes')
      <li class="row row-condensed">
        <div class="media">
          <i class="icon icon-ok icon-lima h3 pull-left"></i>
          <div class="media-body">
            <div>
              {{ trans('messages.dashboard.email_address') }}
            </div>
            <div class="text-muted">
              {{ trans('messages.dashboard.verified') }}
            </div>
          </div>
        </div>
      </li>
      @endif
      @if(Auth::user()->user()->users_verification->facebook == 'yes')
      <li class="row row-condensed">
        <div class="media">
          <i class="icon icon-ok icon-lima h3 pull-left"></i>
          <div class="media-body">
            <div>
              Facebook
            </div>
            <div class="text-muted">
              {{ trans('messages.dashboard.validated') }}
            </div>
          </div>
        </div>
      </li>
      @endif
      @if(Auth::user()->user()->users_verification->google == 'yes')
      <li class="row row-condensed">
        <div class="media">
          <i class="icon icon-ok icon-lima h3 pull-left"></i>
          <div class="media-body">
            <div>
              Google
            </div>
            <div class="text-muted">
              {{ trans('messages.dashboard.validated') }}
            </div>
          </div>
        </div>
      </li>
      @endif
      @if(Auth::user()->user()->users_verification->linkedin == 'yes')
      <li class="row row-condensed">
        <div class="media">
          <i class="icon icon-ok icon-lima h3 pull-left"></i>
          <div class="media-body">
            <div>
              LinkedIn
            </div>
            <div class="text-muted">
              {{ trans('messages.dashboard.validated') }}
            </div>
          </div>
        </div>
      </li>
      @endif
  </ul>
  </div>
@endif
</div>


<div class="select select-block row-space-2">
  {!! Form::select('hosting', $rooms, $messages[0]->reservation->room_id, ['id'=>'hosting']); !!}
</div>

<div id="calendar-container" class="small-calendar row-space-2">
{!! $calendar !!}
</div>

  <a href="{{ url() }}/manage-listing/{{ $messages[0]->reservation->room_id }}/calendar" id="edit_calendar_url">
    {{ trans('messages.inbox.full_calc_edit') }}
  </a>

  <h3>{{ trans('messages.inbox.protect_your_payments') }}</h3>
<p>
  {{ trans('messages.inbox.never_pay_outside',['site_name'=>$site_name]) }}
</p>
<p>
  {{ trans('messages.inbox.protect_your_payments_desc',['site_name'=>$site_name]) }}
</p>

<h3>{{ trans('messages.inbox.contact_info') }}</h3>
<p>
  {{ trans('messages.inbox.contact_info_desc') }}
</p>

      </div>
    </div>

</div>

</main>
@stop