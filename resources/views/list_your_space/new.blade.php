@extends('template')

@section('main')

<main id="site-content" role="main" ng-controller="rooms_new">
      
<div class="page-container-full">


  <div class="panel lys-panel-header">
    <div class="panel-body panel-light">

      <div></div>
        <div class="row space-top-6 space-6">
          <div class="col-sm-10 col-center text-center">
            <h1 class="h2">{{ trans('messages.header.list_your_space') }}</h1>
            <p class="text-lead">
              {{ $site_name }} {{ trans('messages.lys.make_money_renting') }}
            </p>
          </div>
        </div>
      <div></div>
    </div>

    <div class="panel-body panel-medium">
      <div class="page-container-responsive">
          <div class="row">
  <div class="col-lg-9 col-md-11 col-md-push-1 col-lg-push-0" style="left:20%;">
    
<div id="alert-row" class="row">
  <div id="alert-status" class="col-lg-10 col-md-11 lys-alert"></div>
</div>

{!! Form::open(['url' => 'rooms/create', 'class' => 'host-onboarding-form', 'accept-charset' => 'UTF-8' , 'name' => 'lys_new']) !!}

  {!! Form::hidden('hosting[property_type_id]', '', ['id' => 'property_type_id', 'ng-model' => 'property_type_id', 'required' => 'required', 'ng-value' => 'property_type_id']) !!}

  {!! Form::hidden('hosting[person_capacity]', '', ['id' => 'person_capacity', 'ng-model' => 'selected_accommodates', 'required' => 'required', 'ng-value' => 'selected_accommodates']) !!}

  {!! Form::hidden('hosting[room_type]', '', ['id' => 'room_type', 'ng-model' => 'room_type_id', 'required' => 'required', 'ng-value' => 'room_type_id']) !!}

  {!! Form::hidden('hosting[address]', '', ['id' => 'address', 'ng-model' => 'address', 'ng-value' => 'address']) !!}

  {!! Form::hidden('hosting[street_number]', '', ['id' => 'street_number', 'ng-model' => 'street_number', 'ng-value' => 'street_number']) !!}
  {!! Form::hidden('hosting[route]', '', ['id' => 'route', 'ng-model' => 'route', 'ng-value' => 'route']) !!}
  {!! Form::hidden('hosting[postal_code]', '', ['id' => 'postal_code', 'ng-model' => 'postal_code', 'ng-value' => 'postal_code']) !!}
  
  {!! Form::hidden('hosting[city]', '', ['id' => 'city', 'ng-model' => 'city', 'ng-value' => 'city']) !!}
  {!! Form::hidden('hosting[state]', '', ['id' => 'state', 'ng-model' => 'state', 'ng-value' => 'state']) !!}
  {!! Form::hidden('hosting[country]', '', ['id' => 'country', 'ng-model' => 'country', 'ng-value' => 'country']) !!}
  {!! Form::hidden('hosting[latitude]', '', ['id' => 'latitude', 'ng-model' => 'latitude', 'ng-value' => 'latitude']) !!}
  {!! Form::hidden('hosting[longitude]', '', ['id' => 'longitude', 'ng-model' => 'longitude', 'ng-value' => 'longitude']) !!}
    

  <div class="row space-top-4 space-1">
    <div id="property-type-id-header" class="h5 text-light">
      <strong>
        {{ trans('messages.lys.home_type') }}
      </strong>
    </div>
  </div>
  <div class="row fieldset fieldset_property_type_id">
    <div class="btn-group" ng-hide="property_type_id">
    <button class="btn btn-large type alert-highlighted-element hover-select-highlight" data-name="{{ $property_type[0]->name }}" data-type-id="{{ $property_type[0]->id }}" data-icon-class="icon-apartment" data-behavior="tooltip" type="button" data-position="bottom" aria-label="Your space is an apartment, flat, or unit in a multi-unit building." ng-click="property_type('{{ $property_type[0]->id }}', '{{ $property_type[0]->name }}', 'icon-apartment')">
      <i class="icon icon-apartment h4 icon-kazan"></i>
      {{ $property_type[0]->name }}
    </button>
    <button class="btn btn-large type alert-highlighted-element hover-select-highlight" data-name="{{ $property_type[1]->name }}" data-type-id="{{ $property_type[1]->id }}" data-icon-class="icon-home" data-behavior="tooltip" type="button" data-position="bottom" aria-label="Your space is a single-family house or townhouse." ng-click="property_type('{{ $property_type[1]->id }}', '{{ $property_type[1]->name }}', 'icon-home')">
      <i class="icon icon-home h4 icon-kazan"></i>
      {{ $property_type[1]->name }}
    </button>
    <button class="btn btn-large type alert-highlighted-element hover-select-highlight" data-name="{{ $property_type[2]->name }}" data-type-id="{{ $property_type[2]->id }}" data-icon-class="icon-cup" data-behavior="tooltip" type="button" data-position="bottom" aria-label="You rent out several rooms within an establishment. Your service includes breakfast." ng-click="property_type('{{ $property_type[2]->id }}', '{{ $property_type[2]->name }}', 'icon-cup')">
      <i class="icon icon-cup h4 icon-kazan"></i>
      {{ $property_type[2]->name }}
    </button>
  <div class="select select-large other-select" id="property-select">
    <select class="alert-highlighted-element hover-select-highlight" id="property_type_dropdown" ng-model="change_property_value" ng-change="property_change(change_property_value)">
      <option disabled="" selected="" value="">{{ trans('messages.profile.other') }}</option>
      {{--*/ $i = 0 /*--}}
        @foreach($property_type as $row)
          @if($i > 2)
          <option data-icon-class="icon-star-alt" data-name="{{ $row->name }}" data-type-id="{{ $row->id }}" value="{{ $row->id }}">
          {{ $row->name }}
          </option>
          @endif
          {{ $i++ }}
        @endforeach
    </select>
  </div>
</div>

<div class="active-selection" ng-show="property_type_id" ng-click="property_type_rm()" style="display:none;">
  <div data-type="property_type_id" class="selected-item property_type_id">

    <div class="active-panel">
      <div class="active-title active-col">
        <div class="h4 title-value">
          <i class="icon icon-kazan h4 @{{ property_type_icon }}"></i>
          @{{ selected_property_type }}
        </div>
      </div>
      <div class="active-caret active-col">
        <i class="icon icon-caret-right"></i>
      </div>
      <div class="active-message active-col">
        {{ $site_name }} {{ trans('messages.lys.home_type_desc') }}
      </div>
    </div>

  </div>
</div>

  </div>
  <div class="row space-top-3 space-1">
    <div id="room-type-header" class="h5 text-light">
      <strong>
        {{ trans('messages.lys.room_type') }}
      </strong>
    </div>
  </div>
  <div class="row fieldset fieldset_room_type">
    <div class="btn-group" ng-hide="room_type_id">

    <button class="btn btn-large type alert-highlighted-element hover-select-highlight" data-name="{{ $room_type[0]->name }}" data-slug="entire_home_apt" data-type="{{ $room_type[0]->name }}" data-type-id="{{ $room_type[0]->id }}" data-icon-class="icon-entire-place" data-behavior="tooltip" type="button" data-position="bottom" aria-label="You&#39;re renting out an entire home." ng-click="room_type('{{ $room_type[0]->id }}', '{{ $room_type[0]->name }}', 'icon-entire-place')">
      <i class="icon icon-entire-place h4 icon-kazan"></i>
      {{ $room_type[0]->name }}
    </button>


    <button class="btn btn-large type alert-highlighted-element hover-select-highlight" data-name="{{ $room_type[1]->name }}" data-slug="private_room" data-type="{{ $room_type[1]->name }}" data-type-id="{{ $room_type[1]->id }}" data-icon-class="icon-private-room" data-behavior="tooltip" type="button" data-position="bottom" aria-label="You&#39;re renting out a private room within a home." ng-click="room_type('{{ $room_type[1]->id }}', '{{ $room_type[1]->name }}', 'icon-private-room')">
      <i class="icon icon-private-room h4 icon-kazan"></i>
      {{ $room_type[1]->name }}
    </button>


    <button class="btn btn-large type alert-highlighted-element hover-select-highlight" data-name="{{ $room_type[2]->name }}" data-slug="shared_room" data-type="{{ $room_type[2]->name }}" data-type-id="{{ $room_type[2]->id }}" data-icon-class="icon-shared-room" data-behavior="tooltip" type="button" data-position="bottom" aria-label="You&#39;re renting out a common area, such as an airbed in a living room." ng-click="room_type('{{ $room_type[2]->id }}', '{{ $room_type[2]->name }}', 'icon-shared-room')">
      <i class="icon icon-shared-room h4 icon-kazan"></i>
      {{ $room_type[2]->name }}
    </button>

</div>

<div class="active-selection" ng-show="room_type_id" ng-click="room_type_rm()" style="display:none;">
  <div data-type="room_type" class="selected-item room_type">

    <div class="active-panel">
      <div class="active-title active-col">
        <div class="h4 title-value">
          <i class="icon icon-kazan h4 @{{ room_type_icon }}"></i>
          @{{ selected_room_type }}
        </div>
      </div>
      <div class="active-caret active-col">
        <i class="icon icon-caret-right"></i>
      </div>
      <div class="active-message active-col">
        {{ trans('messages.lys.room_type_desc',['site_name'=>$site_name]) }}
      </div>
    </div>

  </div>
</div>

  </div>
  <div class="row space-top-3 space-1">
    <div id="person-capacity-header" class="h5 text-light">
      <strong>
        {{ trans('messages.lys.accommodates') }}
      </strong>
    </div>
  </div>
  <div class="row fieldset fieldset_person_capacity wmpw-spacing">
<div class="unselected row row-condensed" ng-hide="selected_accommodates">
  <div class="col-sm-3">
    <div class="panel accommodates-panel">
      <div class="panel-body panel-light alert-highlighted-element hover-select-highlight">
        <div class="row">
          <div class="col-sm-offset-2 col-sm-2 va-container">
            <i class="icon icon-group h4 icon-kazan va-middle icons-accommodates"></i>
          </div>
          <div class="col-sm-5">
            <div class="select select-large">
              <select id="accomodates-select" class="hover-select-highlight" ng-model="accommodates_value" ng-change="change_accommodates(accommodates_value)">
                @for($i=1;$i<=16;$i++)
                  <option class="accommodates" data-accommodates="{{ ($i == '16') ? $i.'+' : $i }}" value="{{ ($i == '16') ? $i.'+' : $i }}">
                    {{ $i }}
                  </option>
                @endfor
              </select>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>

<div class="active-selection" ng-show="selected_accommodates" ng-click="accommodates_rm()" style="display:none;">
  <div data-type="person_capacity" class="selected-item person_capacity">
    <div class="active-panel">
      <div class="active-title active-col">
        <div class="h4 title-value">
          <i class="icon icon-kazan h4 icon-group"></i>
          @{{ selected_accommodates }}
        </div>
      </div>
      <div class="active-caret active-col">
        <i class="icon icon-caret-right"></i>
      </div>
      <div class="active-message active-col">
        {{ trans('messages.lys.accommodates_desc') }}
      </div>
    </div>

  </div>
</div>

  </div>
  <div class="row space-top-3 space-1">
    <div id="city-header" class="h5 text-light">
      <strong>
              {{ trans('messages.account.city') }}
      </strong>
    </div>
  </div>

  <div class="row fieldset fieldset_city">
    
<div class="row" ng-hide="city_show">
  <div class="col-sm-12">
    <div class="panel location lys-location alert-highlighted-element">
      <div class="panel-body panel-light panel-border location-panel-body clearfix">
        <div class="col location-input-frame">
          <i class="icon icon icon-map-marker icon-kazan h4 pull-left"></i>
          <input class="pull-left alert-highlighted-element geocomplete" name="location_input" type="text" value="" placeholder="Enter a location" autocomplete="off" ng-click="city_click()" ng-model="address" id="location_input">
        </div>
      </div>
    </div>
  </div>
</div>

<div class="active-selection" ng-show="city_show" ng-click="city_rm()" style="display:none;">
  <div class="selected-item city" data-type="city">
    <div class="active-panel">
      <div class="active-title active-col">
        <div class="h4 title-value">
          <i class="icon icon-kazan h4 icon-map-marker"></i>
          @{{ address }}
        </div>
      </div>
      <div class="active-caret active-col">
        <i class="icon icon-caret-right"></i>
      </div>
      <div class="active-message active-col">
        {{ trans('messages.lys.city_desc') }}
      </div>
    </div>

  </div>
</div>

  </div>

  <div class="row text-left space-top-3">
    <div class="" id="js-submit-button"><div class="lys-continue-button-wrapper col-3">
  <button type="submit" class="btn btn-primary btn-large btn-block submit" ng-disabled="((city_show == false) || lys_new.$invalid)">
    {{ trans('messages.lys.continue') }}
  </button>
</div>
</div>
  </div>

  <div id="cohosting-signup-widget-banner" class="hide-sm hide-md"></div>
{!! Form::close() !!}
  </div>
</div>
          <div class="space-5 row"> </div>
      </div>
    </div>

      <div class="panel-medium">
  <div class="page-container-responsive col-center">
    <div class="row space-5">
      <div class="col-md-4 text-center">
        <i class="icon icon-handshake icon-kazan icon-size-3"></i>
        <h4>{{ trans('messages.lys.trust_safety') }}</h4>
        <div class="text-lead text-color-light">
          {{ trans('messages.lys.trust_safety_desc') }}
        </div>
      </div>
      <div class="col-md-4 text-center">
        <i class="icon icon-host-guarantee icon-kazan icon-size-3"></i>
        <h4>{{ trans('messages.lys.host_guarantee') }}</h4>
        <div class="text-lead text-color-light">
          {{ trans('messages.lys.host_guarantee_desc1') }} <a href="{{ url('host_guarantee') }}" target="_blank">{{ trans('messages.lys.eligible') }}</a> {{ trans('messages.lys.host_guarantee_desc2',['site_name'=>$site_name]) }}
        </div>
      </div>
      <div class="col-md-4 text-center">
        <i class="icon icon-lock icon-kazan icon-size-3"></i>
        <h4>{{ trans('messages.lys.secure_payments') }}</h4>
        <div class="text-lead text-color-light">
          {{ trans('messages.lys.secure_payments_desc') }}
        </div>
      </div>
    </div>
  </div>
</div>
  </div>
</div>
    </main>
 @stop