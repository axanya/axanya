<style type="text/css">
  body{background: white !important;}
</style>

@extends('template')

@section('main')

    <main id="site-content" role="main" ng-controller="manage_listing">

<div class="manage-listing  never-listed" id="js-manage-listing">
  <div class="manage-listing-alerts">
    <div id="js-disaster-alert"></div>
    @if(Session::has('message'))
      <div class="alert {{ Session::get('alert-class', 'info') }}" role="alert">
        <a href="#" class="alert-close" data-dismiss="alert"></a>
        {!! Session::get('message') !!}
      </div>
    @endif
  </div>

  <div id="ajax_header">
  <!-- @include('list_your_space.header') -->
  </div>
<input type="hidden" ng-model="rooms_status_value" ng-init="rooms_status_value='{{$result->status}}'" />
<!-- Center Part Starting  -->
  <div class="manage-listing-row-container page-container {{ ($result->status != NULL && $room_step == 'calendar') ? 'has-collapsed-nav' : '' }}" >
    @include('list_your_space.navigation')
   <div id="ajax_container" class="col-lg-10 col-md-9 mar-left-cont">
   @if($result->status != NULL && $room_step == 'calendar')
    @include('list_your_space.'.$room_step)
   @else
    @include('list_your_space.'.$room_step)
   @endif
   </div>



      <div class="manage-listing-background va-container-h" id="js-manage-listing-background">
        <div class="illustration-container va-container-v va-container-h">
          <!-- {!! Html::image('images/amenities.png', '', ['class' => 'bg-illustration illst-amenities hide']) !!}
          {!! Html::image('images/basics.png', '', ['class' => 'bg-illustration illst-basics hide']) !!}
          {!! Html::image('images/calendar.png', '', ['class' => 'bg-illustration illst-calendar hide']) !!}
          {!! Html::image('images/description.png', '', ['class' => 'bg-illustration illst-description hide']) !!}
          {!! Html::image('images/location.png', '', ['class' => 'bg-illustration illst-location hide']) !!}
          {!! Html::image('images/photos.png', '', ['class' => 'bg-illustration illst-photos hide']) !!}
          {!! Html::image('images/pricing.png', '', ['class' => 'bg-illustration illst-pricing hide']) !!}
          {!! Html::image('images/rules_prep.png', '', ['class' => 'bg-illustration illst-rules-and-prep illst-instant-book hide']) !!} -->
        </div>
      </div>
  </div>
<!-- Center Part Ending -->

  <!-- @include('list_your_space.footer') -->
  <script type="text/javascript">
     var map_key = "{!! $map_key !!}";
  </script>

</div>

    <div id="gmap-preload" class="hide"></div>

    <div class="ipad-interstitial-wrapper"><span data-reactid=".2"></span></div>

<div class="modal welcome-new-host-modal" aria-hidden="{{ ($result->started == 'Yes') ? 'true' : 'false' }}">
  <div class="modal-table">
    <div class="modal-cell">
      <div class="modal-content">
        <div class="panel-body">
          <div class="row row-table row-full-height">
            <div class="col-12 col-middle text-center">
              <div class="h2 row-space-6 row-space-top-4">
                  {{ trans('messages.lys.you_created_listing') }} </div>
              <div class="steps-remaining-circle anim-bounce-in visible">
                <div class="h1 steps-remaining-text">
                  <strong>
                    6
                  </strong>
                </div>
              </div>
              <div class="h4 steps-remaining-more-text text-center row-space-top-2 row-space-4 fade-in">
                {{ trans('messages.lys.more_steps_to_lys') }}
              </div>
            </div>
          </div>
        </div>
        <div class="panel-body text-center">
          <button class="btn btn-primary js-finish" data-track="welcome_modal_finish_listing">
            {{ trans('messages.lys.finish_my_listing') }}
          </button>
        </div>
      </div>
    </div>
  </div>
</div>

<div id="address-flow-view">
<div aria-hidden="true" style="" class="modal" role="dialog" data-sticky="true">
  <div class="modal-table">
    <div class="modal-cell">
      <div class="modal-content">
	  <a class="modal1-close" href=""></a>
      <div id="js-address-container">

      </div>

      </div>
    </div>
  </div>
</div>
</div>

<div id="bedroom-flow-view">
<div aria-hidden="true" style="" class="modal" role="dialog" data-sticky="true">
  <div class="modal-table">
    <div class="modal-cell">
      <div class="modal-content">
	  <a class="modal1-close" data-behavior="modal-close" href=""></a>
            <div id="js-bedroom-container">

            </div>

      </div>
    </div>
  </div>
</div>
</div>

<div id="js-error" class="modal show" aria-hidden="true" style="" tabindex="-1">
  <div class="modal-table">
    <div class="modal-cell">
      <div class="modal-content">
        <div class="panel-header">

        </div>
        <div class="panel-body">
          <p> </p>
        </div>
        <div class="panel-footer">
          <button data-behavior="modal-close" class="btn">
            {{ trans('messages.home.close') }}
          </button>
          <button class="btn btn-primary js-delete-photo-confirm hide" data-id="">
            {{ trans('messages.lys.delete') }}
          </button>
        </div>
      </div>
    </div>
  </div>
</div>

@if($result->status == NULL)
<div id="js-list-space-tooltip" class="animated tooltip tooltip-bottom-left list-space-tooltip anim-fade-in-up show" aria-hidden="true" style="left: 336px !important; position: fixed; top: 500px !important;">
  <div class="panel-body">
    <div class="media">
      <a class="pull-right panel-close" href=""></a>
      <div class="media-body">
          <h4>{{ trans('messages.lys.listing_congratulation') }}</h4>
            <p>{{ trans('messages.lys.listing_congratulation_desc') }}</p>
      </div>
    </div>
  </div>
</div>
@endif

<div class="modal finish-modal hide" aria-hidden="false" style="" tabindex="-1">
  <div class="modal-table">
    <div class="modal-cell">
      <div class="modal-content content-container">
        <div class="panel">
          <a class="modal-close" href="{{url('rooms')}}"></a>

            <div class="finish-modal-header"></div>
            <div class="listing-card-container">

<div class="listing">

  <div class="panel-image listing-img">

    <a class="media-photo media-cover" target="" href="{{ url('rooms/'.$result->id) }}">
      <div class="listing-img-container media-cover text-center">

        <img alt="@{{ room_name }}" class="img-responsive-height" src="{{ url() }}/images/@{{ popup_photo_name }}" data-current="0" itemprop="image">

      </div>
    </a>

    <a class="link-reset panel-overlay-bottom-left panel-overlay-label panel-overlay-listing-label" target="" href="{{ url('rooms/'.$result->id) }}">
      <div>
        <sup class="h6 text-contrast"><span id="symbol_finish"></span></sup>
        <span class="h3 text-contrast price-amount">@{{ popup_night }}</span>
        <sup class="h6 text-contrast"></sup>

      </div>

    </a>

    <div class="panel-overlay-top-right wl-social-connection-panel">

    </div>

  </div>

  <div class="panel-body panel-card-section">
    <div class="media">

        <a class="media-photo-badge pull-right card-profile-picture card-profile-picture-offset" href="{{ url('users/show/'.$result->user_id) }}">
          <div class="media-photo media-round">
            <img alt="" src="{{ $result->users->profile_picture->src }}">
          </div>

        </a>

      <h3 class="h5 listing-name text-truncate row-space-top-1" itemprop="name" title="d">

        <a class="text-normal" target="" href="{{ url('rooms/'.$result->id) }}">
          @{{ popup_room_name }}
        </a>
      </h3>
      <div class="text-muted listing-location text-truncate" itemprop="description">@{{ popup_room_type_name }} · @{{ popup_state }}, @{{ popup_country }}</div>
    </div>

  </div>
</div>
            </div>

          <div>
            <div class="panel-body finish-modal-body">
              <h3 class="text-center">
                {{ trans('messages.lys.listing_published') }}!
              </h3>
              <p class="col-11 col-center text-center">
                {{ trans('messages.lys.listing_published_desc1') }}
              </p>


			  <div class="col-10 col-offset-1">
        <p style="text-align:center;" class="hide">{{ trans('messages.lys.listing_published_desc1') }}</p>
        </div>
		  <div class="row row-space-top-5">
                <div class="col-6">
                  <a target="_blank" href="{{ url('rooms/'.$result->id) }}" id="view-listing-button" class="btn btn-block">{{ trans('messages.lys.view_listing') }}</a>
                </div>
				<div class="col-6">
					<a target="_blank" href="{{ url('rooms/') }}" id="view-listing-button" class="btn btn-block">{{ trans('messages.lys.manage_listing') }}</a>
				</div>
        <div class="face_book_icon">
        <a class="fb-button fb-blue btn icon-btn btn-block btn-large row-space-1 btn-facebook" data-network="facebook" rel="nofollow" title="Facebook" href="http://www.facebook.com/sharer.php?u={{ url('rooms/'.$result->id) }}" target="_blank">
         Share your space on Facebook
          <i class="icon icon-facebook social-icon-size"></i>
        </a>
        </div>

                <!-- <div class="col-5">
                  <a href="{{ url('manage-listing/'.$result->id.'/calendar') }}" data-prevent-default="true" class="btn btn-block btn-primary calender_pop">{{ trans('messages.lys.go_to_calendar') }}</a>
                </div> -->
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>

<div tabindex="-1" aria-hidden="true" role="dialog" class="modal show" id="export_popup">
<div class="modal-table">
<div class="modal-cell">
<div class="modal-content">
<div class="panel">
<div class="panel-header">
<span>{{ trans('messages.lys.export_calc') }}</span>
<a data-behavior="modal-close" class="modal-close" href="">
</a>
</div>
<div class="panel-body">
<p>
<span>{{ trans('messages.lys.copy_past_ical_link') }}</span>
</p>
<input type="text" value="{{ url('calendar/ical/'.$result->id.'.ics') }}" readonly="">
</div>
</div>
</div>
</div>
</div>
</div>

<div tabindex="-1" aria-hidden="{{ ($errors->has('name')) ? 'false' : 'true' }}" role="dialog" class="modal show" id="import_popup">
<div class="modal-table">
<div class="modal-cell">
<div style="max-width:552px;" class="modal-content">
<div class="wizard">
<div class="wizard-page-wrapper">
<div class="panel">
<div class="panel-header">
<span>{{ trans('messages.lys.import_new_calc') }}</span>
<a data-behavior="modal-close" class="modal-close" href="">
</a>
</div>
<div class="panel-body">
<p style="margin-bottom:20px;">
<span>{{ trans('messages.lys.import_calendar_desc') }}</span>
</p>
{!! Form::open(['url' => url('calendar/import/'.$result->id), 'name' => 'export']) !!}
<label style="margin-bottom:20px;padding:0;">
<p style="margin-bottom:10px;" class="label">
<span>{{ trans('messages.lys.calendar_address') }}</span>
</p>
<input type="text" value="{{ Input::old('url') }}" name="url" placeholder="{{ trans('messages.lys.ical_url_placeholder') }}" class="space-1 {{ ($errors->has('url')) ? 'invalid' : '' }}">
<span class="text-danger">{{ $errors->first('url') }}</span>
</label>
<label style="padding:0;margin-bottom:0;">
<p style="margin-bottom:10px;" class="label">
<span>{{ trans('messages.lys.name_your_calendar') }}</span>
</p>
<input type="text" value="{{ Input::old('name') }}" name="name" placeholder="{{ trans('messages.lys.ical_name_placeholder') }}" class="space-1 {{ ($errors->has('name')) ? 'invalid' : '' }}">
<span class="text-danger">{{ $errors->first('name') }}</span>
</label>
<div style="margin-top:20px;">
<button name="" data-prevent-default="true" class="btn btn-primary" ng-disabled="export.$invalid">
<span>{{ trans('messages.lys.import_calc') }}</span>
</button>
</div>
{!! Form::close() !!}
</div>
</div>
</div>
</div>
</div>
</div>
</div>
</div>

<input type="hidden" id="room_id" value="{{ $result->id }}">
<input type="hidden" id="room_status" value="{{ $result->status }}">

</main>

@stop
<div class="ui-datepicker-backdrop hide"></div>
</body></html>
