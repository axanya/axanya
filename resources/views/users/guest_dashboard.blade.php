@extends('template')

@section('main')

<main id="site-content" role="main">

  @if ($errors->has('email'))
  <div class="alert alert-danger" role="alert">
    <a href="#" class="alert-close" data-dismiss="alert"></a>
  {{ $errors->first('email') }}
  </div>
  @endif

@include('common.subheader')

<div class="page-container-responsive space-top-4 space-4">

  <div class="row">
    <div class="col-md-3">
      <div class="panel space-4">
        <div class="media media-photo-block dashboard-profile-photo panel-image ">
          <a href="{{ url('users/show/'.Auth::user()->user()->id) }}" title="{{ trans('messages.dashboard.view_profile') }}">
          {!! Html::image(Auth::user()->user()->profile_picture->src, Auth::user()->user()->first_name, ['class'=>'img-responsive', 'width' => '190', 'height'=>'190', 'title' => Auth::user()->user()->first_name, 'id' => 'profile_picture']) !!}
          </a>

            <div class="upload-profile-photo-cta btn btn-contrast btn-block text-wrap">
              <label for="profile_pic_uploader" class="link-reset btn-label">
                <i class="icon icon-camera"></i>
                <span>{{ trans('messages.dashboard.add_profile_photo') }}</span>
              </label>
              <input type="file" name="profile_pic_uploader" id="profile_pic_uploader" data-user_id="{{ Auth::user()->user()->id }}">
              <!--
              <a href="{{ url('users/edit/media') }}" class="link-reset">
                <i class="icon icon-camera"></i>
                <span>{{ trans('messages.dashboard.add_profile_photo') }}</span>
              </a>
            -->
            </div>
            <div class="profile-overlay">
              <div class="progress">
                <div class="bar"></div>
              </div>
            </div>
        </div>
        <div class="panel-body">
          <h2 class="text-center">
            {{ Auth::user()->user()->first_name }}
          </h2>
          <ul class="list-unstyled text-center">
            <li>
            <a href="{{ url('users/show/'.Auth::user()->user()->id) }}">{{ trans('messages.dashboard.view_profile') }}</a>
            </li>
            {{-- @if(Auth::user()->user()->profile_picture->src == '' || Auth::user()->user()->about == '')
            <li>
                <a href="{{ url('users/edit') }}" class="btn btn-primary btn-block text-wrap space-top-1" id="edit-profile">{{ trans('messages.dashboard.complete_profile') }}</a>
            </li>
            @endif --}}
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
              {{ trans('messages.dashboard.welcome_desc') }}</p><p> @if(Auth::user()->user()->profile_picture->src == '' || Auth::user()->user()->about == '') {{ trans('messages.dashboard.welcome_ask_to_complete_profile') }} @endif
            </p>

            <ol class="list-orderd hdb-light-bg">
                @if(Auth::user()->user()->users_verification->email == 'no')
                  <li class="space-2">
                    <span class="dashboard_alert_text">
                      {{ trans('messages.dashboard.confirm_your_email') }} <a href="{{ url('users/request_new_confirm_email') }}">{{ trans('messages.dashboard.request_confirmation_email') }}</a> {{ trans('messages.login.or') }} <a href="{{ url('users/edit') }}" data-popup="email-verification">{{ trans('messages.dashboard.change_email_address') }}</a>.
                    </span>
                  </li>
                @endif

                @if(Auth::user()->user()->users_verification->phone == 'no')
                  <li class="space-2">
                    <a href="{{ url('verification') }}" data-popup="phone-verification">{{ trans('messages.dashboard.Verify_your_phone_number') }}</a>
                  </li>
                @endif

                @if(Auth::user()->user()->gender == '')
                <li class="space-2">
                  <a href="javascript:void(0)" data-popup="change-gender">Select your gender</a>
                </li>
                @endif

                <!--
                @if(Auth::user()->user()->about == '')
                  <li class="space-2">
                    <strong><a href="{{ url('users/edit') }}">{{ trans('messages.dashboard.complete_your_profile') }}</a></strong>
                    {{ trans('messages.dashboard.complete_your_profile_desc') }}
                  </li>
                @endif
                -->

                @if(Auth::user()->user()->profile_picture->src == '' || (Auth::user()->user()->profile_picture->src == url('/images/user_pic-225x225.png')))
                  <li class="space-2">
                    <a href="{{ url('users/edit/media') }}" id="trigger_profile_uploader">{{ trans('messages.dashboard.add_profile_photo') }}</a>
                  </li>
                @endif

            </ol>

            <p>Wishing you great experiences and safe travels!</p>

          </div>
        </div>

      @if(Auth::user()->user()->users_verification->email == 'no')
        <div class="panel space-4">
          <div class="panel-header">
            {{ trans_choice('messages.header.notification',2) }}
          </div>
          <div class="panel-body">

  {{-- <ul class="list-unstyled hdb-light-bg">
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
  </ul> --}}
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

<div class="page-overlay"></div>

<div class="dashboard-popup" id="email-verification">
  <a class="close" href="#" data-action="close">&times;</a>

  {!! Form::open(['url' => url('users/update_email/'.Auth::user()->user()->id), 'id' => 'update_form']) !!}

  <div class="row row-condensed space-4">
    <label class="text-right col-sm-3" for="user_email">
      {{ trans('messages.dashboard.email_address') }} <i class="icon icon-lock icon-ebisu" data-behavior="tooltip" aria-label="Private"></i>
    </label>
    <div class="col-sm-6">
      {!! Form::email('email', Auth::user()->user()->email, ['id' => 'user_email', 'size' => '30', 'class' => 'focus', 'required' => 'required']) !!}
    </div>
    <div class="col-sm-3">
      <button type="submit" class="btn btn-primary">Change</button>
    </div>
  </div>

  {!! Form::close() !!}

  <div class="text-center">
    <a href="#" data-action="close">Remind me later</a>
  </div>
</div>

<div class="dashboard-popup" id="change-gender">
  <a class="close" href="#" data-action="close">&times;</a>

  {!! Form::open(['url' => url('users/update_gender/'.Auth::user()->user()->id), 'id' => 'update_form']) !!}

  <div class="row row-condensed space-4">
    <div class="col-sm-6 col-sm-offset-3 text-center">Select your gender. We use this data for analysis and never share it with other users.</div>
  </div>

  <div class="row row-condensed space-4">
    <label class="text-right col-sm-5" for="user_gender">
      {{ trans('messages.profile.i_am') }} <i class="icon icon-lock icon-ebisu" data-behavior="tooltip" aria-label="Private"></i>
    </label>
    <div class="col-sm-6">
      <div class="select">
        {!! Form::select('gender', ['Male' => trans('messages.profile.male'), 'Female' => trans('messages.profile.female')], Auth::user()->user()->gender, ['id' => 'user_gender', 'placeholder' => trans('messages.profile.gender'), 'class' => 'focus', 'required' => 'required']) !!}
      </div>
      <button type="submit" class="btn btn-primary" style="margin-left: 15px;">Done</button>
    </div>
  </div>

  {!! Form::close() !!}

  <div class="text-center">
    <a href="#" data-action="close">Remind me later</a>
  </div>
</div>

<div class="dashboard-popup" id="phone-verification">
  <a class="close" href="#" data-action="close">&times;</a>

  <input type="hidden" id="hidden_phone_number" value="{{ $phone_info->phone_number }}">
  <input type="hidden" id="hidden_phone_status" value="{{ $phone_info->status }}">

  <div id="dashboard-phone-ajax-status" class="text-center"></div>

    <form method="post" id="dashboard-change-number" action="" style="display: none;">
      <div class="row" id="change-number-section">
        <div class="col-md-4 col-sm-12 text-left">
          <label>{{ trans('messages.lys.country_code') }}</label>
          <select class="form-control" name="phone_code" id="phone_code">
            <option value="0">{{ trans('messages.lys.select') }}...</option>
            @foreach($country as $val)
            <option value="{{$val->phone_code}}">{{$val->long_name}}</option>
            @endforeach
          </select>
        </div>
        <div class="col-md-5 col-sm-12 text-left">
          <label>{{ trans('messages.lys.phone_number') }}</label>
          <input type="text" class="form-control" name="phone_number" id="phone_number" maxlength="15" placeholder="{{ trans('messages.lys.Enter_mobile_number') }}" required="">
        </div>
        <div class="col-md-3 col-sm-12 text-left">
          <label>&nbsp;</label>
           <button type="submit" class="btn btn-primary" data-value="change">Submit</button>
        </div>
      </div>
    </form>

  <form method="post" id="dashboard-verify-number" action="" style="display: none;" data-phone-id="{{$phone_info->id}}">
    <div class="row">
      <p class="text-center">Enter the code you've received via SMS.</p>
    </div>
      <div class="row">
        <div class="col-sm-9">
          <div class="form-group">
             <input type="text" class="form-control" name="otp" id="otp" ng-model="otp" maxlength="4" placeholder="{{ trans('messages.lys.Enter_your_code') }}" required="">
          </div>
        </div>
        <div class="col-sm-3">
          <div class="form-group">
            <button type="submit" class="btn btn-primary" data-value="verify">{{ trans('messages.lys.verify') }}</button>
          </div>
        </div>
      </div>
      <p style="margin-top: 10px; max-width: 80%; margin-left: auto; margin-right: auto;" class="text-left">
        <a href="javascript:void(0)" id="mobile-verification-resend-code" data-value="resend" data-phone-id="{{$phone_info->id}}" class="pull-left">{{ trans('messages.lys.resend_otp') }}</a>
        <a href="javascript:void(0)" id="mobile-verification-change-number" class="pull-right">{{ trans('messages.lys.change_phone_number') }}</a>
      </p>
  </form>

  <br><br>

  <div class="text-center">
    <a href="#" data-action="close">Remind me later</a>
  </div>
</div>

@stop
