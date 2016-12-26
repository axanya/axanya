@extends('template')

@section('main')  

<main id="site-content" role="main">

@include('common.subheader')

<div class="page-container-responsive space-top-4 space-4">
  <div class="row">
    <div class="col-md-3">
      <div class="panel space-4">
        <div class="media media-photo-block
             dashboard-profile-photo panel-image ">
          <a href="{{ url('users/show/'.Auth::user()->user()->id) }}" title="{{ trans('messages.dashboard.view_profile') }}">
          {!! Html::image(Auth::user()->user()->profile_picture->src, Auth::user()->user()->first_name, ['class'=>'img-responsive', 'width' => '190', 'height'=>'190', 'title' => Auth::user()->user()->first_name]) !!}
          </a>
          
            <div class="upload-profile-photo-cta btn btn-contrast btn-block text-wrap">
              <a href="{{ url('users/edit/media') }}" class="link-reset">
                <i class="icon icon-camera"></i>
                {{ trans('messages.dashboard.add_profile_photo') }}
</a>            </div>
        </div>
        <div class="panel-body">
          <h2 class="text-center">
            {{ Auth::user()->user()->first_name }}
          </h2>
          <ul class="list-unstyled text-center">
            <li>
            <a href="{{ url('users/show/'.Auth::user()->user()->id) }}">{{ trans('messages.dashboard.view_profile') }}</a>
            </li>
            @if(Auth::user()->user()->profile_picture->src == '' || Auth::user()->user()->about == '')
            <li>
                <a href="{{ url('users/edit') }}" class="btn btn-primary btn-block text-wrap space-top-1" id="edit-profile">{{ trans('messages.dashboard.complete_profile') }}</a>
            </li>
            @endif
          </ul>
        </div>
      </div>
@if(Auth::user()->user()->users_verification->show())
  <div class="panel row-space-4 verifications-panel-vertical ">
  <div class="panel-header row">
    <div class="pull-left">
      {{ trans('messages.dashboard.verifications') }}
    </div>
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
    <!-- <a href="{{ url() }}/users/edit_verification">
      {{ trans('messages.dashboard.add_verifications') }}
    </a> -->
  </div>
</div>
@endif

    </div>

    <div class="col-md-9">

        <div class="panel space-4">
          <div class="panel-header">
            {{ trans('messages.dashboard.welcome') }} {{ $site_name }}, {{Auth::user()->user()->first_name }}!
          </div>
          <div class="panel-body">
            <p>
              {{ trans('messages.dashboard.welcome_desc') }} @if(Auth::user()->user()->profile_picture->src == '' || Auth::user()->user()->about == '') {{ trans('messages.dashboard.welcome_ask_to_complete_profile') }} @endif
            </p>

            @if(Auth::user()->user()->profile_picture->src == '' || Auth::user()->user()->about == '')
            <ul class="list-unstyled">
              @if(Auth::user()->user()->profile_picture->src == '' || Auth::user()->user()->about == '')
                <li class="space-2">
                  <strong><a href="{{ url('users/edit') }}">{{ trans('messages.dashboard.complete_your_profile') }}</a></strong>
                  <div>{{ trans('messages.dashboard.complete_your_profile_desc') }}</div>
                </li>
              @endif
            </ul>
            @endif
          </div>
        </div>

      @if(Auth::user()->user()->users_verification->email == 'no')
        <div class="panel space-4">
          <div class="panel-header">
            {{ trans_choice('messages.header.notification',2) }}
          </div>
          <div class="panel-body">
            
  <ul class="list-unstyled hdb-light-bg">
    @if(Auth::user()->user()->users_verification->email == 'no')
      <li class="default alert3">
        <div class="row row-table ">
          <div class="col-11 col-middle">
              <span class="dashboard_alert_text">
                {{ trans('messages.dashboard.confirm_your_email') }} <a href="{{ url('users/request_new_confirm_email') }}">{{ trans('messages.dashboard.request_confirmation_email') }}</a> {{ trans('messages.login.or') }} <a href="{{ url('users/edit') }}">{{ trans('messages.dashboard.change_email_address') }}</a>.
              </span>
          </div>
          <div class="col-1 col-middle">
          </div>
        </div>
      </li>
    @endif
  </ul>
          </div>
        </div>
    @endif

      <div class="panel space-4">
        <div class="panel-header">
          {{ trans_choice('messages.dashboard.message',2) }} ({{$all_message->count()}} {{ trans('messages.dashboard.new') }})
        </div>
        <ul class="list-layout">
            @foreach($all_message as $all)
            <li id="thread_{{ $all->id }}" class="panel-body thread-read thread">
  <div class="row">
    <div class="col-3">
      <div class="row row-table">
        <div class="col-5">          
          <a data-popup="true" href="#"><span title="{{ $all->user_details->first_name }}" style="background-image:url({{ $all->user_details->profile_picture->src }}); height:50px; width:50px;" class="media-round media-photo"></span></a>
        </div>
        <div class="col-7">
          {{ $all->user_details->first_name }}
          <br>
          {{ $all->created_time }}
        </div>
      </div>
    </div>
    @if($all->host_check ==1 && $all->reservation->status == 'Pending')
    <a class="link-reset text-muted" href="{{ url('reservation')}}/{{ $all->reservation_id }}">
    @elseif($all->host_check ==1 && $all->reservation->status != 'Pending')
    <a class="link-reset text-muted" href="{{ url('messaging/qt_with')}}/{{ $all->reservation_id }}">
    @endif
    @if($all->guest_check !=0)
    <a class="link-reset text-muted" href="{{ url('z/q')}}/{{ $all->reservation_id }}">
    @endif
      <div class="col-7">
         <span class="thread-subject ng-binding unread_message">{{ $all->message }}</span>
        <br>
        <span class="text-muted">
            <span class="street-address">{{ $all->rooms_address->address_line_1 }} {{ $all->rooms_address->address_line_2 }}</span>, <span class="locality">{{ $all->rooms_address->city }}</span>, <span class="region">{{ $all->rooms_address->state }}</span>
          ({{  (date('M d', strtotime( $all->reservation->checkin))) }}, {{  (date('M d, Y', strtotime( $all->reservation->checkout))) }})
        </span>       
      </div>
</a>
   <div class="col-2">
        <span class="label label-{{ $all->reservation->status_color }}">{{ $all->reservation->status }}</span>
        <br>
        {{ $all->reservation->currency->original_symbol }}{{ $all->reservation->total }}
            </span>
    </div>
  </div>
</li>
@endforeach

          </ul>
          <div class="panel-body">
            <a href="{{ url('inbox') }}">{{ trans('messages.dashboard.all_messages') }}</a>
          </div>
      </div>


    </div>
  </div>
</div>

    </main>

@stop    