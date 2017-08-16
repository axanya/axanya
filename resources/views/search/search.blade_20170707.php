
<style type="text/css">
   .labels {
     color: red;
     background-color: white;
     font-family: "Lucida Grande", "Arial", sans-serif;
     font-size: 10px;
     font-weight: bold;
     text-align: center;
     width: 40px;
     border: 2px solid black;
     white-space: nowrap;
   }

</style>

@extends('template')

  @section('main')

  <div class="flash-container"></div>

 <main id="site-content" role="main" ng-controller="search-page">
<div class="map-search">
  <div class="sidebar">
<input type="hidden" id="location" value="{{ $location }}">
<input type="hidden" id="lat" value="{{ $lat }}">
<input type="hidden" id="long" value="{{ $long }}">
    <div class="filters collapse" style="bottom: 606px;">

      <div class="panel-header filters-section show-sm">
        {{ trans('messages.search.filters') }}
      </div>
        <div class="filters-section intro-filter panel-body panel-light">
    <div class="row">
      <div class="col-lg-2 col-md-12 text-center-sm text-center-md row-space-sm-1 filter-label">
        <label>{{ trans('messages.your_trips.dates') }}</label>
      </div>

      <form class="col-lg-9 trip-form">
        <div class="row row-condensed">
          <div class="col-md-4 col-sm-6 row-space-1-sm">
            <label for="map-search-checkin" class="screen-reader-only">
              {{ trans('messages.home.checkin') }}
            </label>
            <input  ng-model="checkin" id="checkin" type="text" ng-change="search_result();" class="checkin tour-target ui-datepicker-target" placeholder="{{ trans('messages.home.checkin') }}" ng-init="checkin = '{{ $checkin }}'">
          </div>

          <div class="col-md-4 col-sm-6 row-space-1-sm">
            <label for="map-search-checkout" class="screen-reader-only">
              {{ trans('messages.home.checkout') }}
            </label>
            <input  ng-model="checkout" id="checkout" type="text" ng-change="search_result();" class="checkout tour-target ui-datepicker-target" placeholder="{{ trans('messages.home.checkout') }}" ng-init="checkout = '{{ $checkout }}'">
          </div>

          <div class="col-md-4 col-sm-12">
            <div class="select select-block">
              <label for="guest-select" class="screen-reader-only">
                {{ trans('messages.home.no_of_guests') }}
              </label>
              <select  ng-model="guests" class="guest-select" id="guest-select" data-prefill="" ng-change="search_result();" ng-init="guests = '{{ $guest }}'">
              <option value="">{{ trans_choice('messages.home.guest',1) }}</option>
              @for($i=1;$i<=16;$i++)
                <option value="{{ $i }}"> {{ ($i == '16') ? $i.'+ '.trans_choice('messages.home.guest',$i) : $i.' '.trans_choice('messages.home.guest',$i) }} </option>
              @endfor
              </select>
            </div>
          </div>
        </div>

        <div class="simple-dates-message-container hide">
          <div class="text-kazan space-top-2">
            <strong>
              <i class="icon icon-currency-inr"></i>
              {{ trans('messages.search.enter_dates') }}
            </strong>
          </div>
        </div>
      </form>
    </div>
  </div>

  <div class="filters-section room-type-group intro-filter panel-body panel-light">
    <div class="row">
      <div class="col-lg-2 col-md-12 text-center-sm text-center-md filter-label">
        <label>
          {{ trans('messages.lys.room_type') }}
          <i class="icon icon-question hide-sm hide-md" id="room-type-tooltip"></i>
        </label>


      </div>

      <div class="col-lg-9">
        <div class="row row-condensed" id="room-options">
          @foreach($room_type as $row=>$value)
          <div class="col-middle-alt col-sm-4">
            <label class="checkbox panel panel-dark">
              <input class="pull-right room-type" type="checkbox" ng-model="room_type_{{ $row }}" value="{{ $row }}" ng-change="search_result();" ng-checked="{{ (in_array($row, $room_type_selected)) }}" ng-init="room_type_{{ $row }} = '{{ $row }}'">
              @if($row == 1)
              <i class="icon icon-entire-place h5 needsclick"></i>
              @endif
              @if($row == 2)
              <i class="icon icon-private-room h5 needsclick"></i>
              @endif
              @if($row == 3)
              <i class="icon icon-shared-room h5 needsclick"></i>
              @endif
              {{ $value }}
            </label>
          </div>
          @endforeach
        </div>
      </div>
    </div>
  </div>

  <div class="filters-section intro-filter panel-body panel-light">
    <div class="row">
      <div class="col-lg-2 col-md-12 text-center-sm text-center-md filter-label">
        <label>{{ trans('messages.search.price_range') }}</label>
      </div>
      <div class="col-lg-9 col-md-12">
                 <div id="slider-3" class="price-range-slider p2-slider-new"></div>
    <div class="row" style="margin-top: 20px;">
    <div class="col-6">
    <span> <span>{{ $currency_symbol }}</span> <span class="price" id="min_text">{{ $min_price }}</span> <span></span></span><input type="hidden" id="min_value" value="{{ $min_price }}"></div>
    <div class="col-6 text-right"><span>
    <span>{{ $currency_symbol }}</span><span class="price" id="max_text">{{ $max_price }}+</span><span></span></span><input type="hidden" id="max_value" value="{{ $max_price }}">
    </div>
    </div>
      </div>
    </div>
  </div>

<div id="search_more_filter">
  <div class="filters-section size-group toggle-group panel-body panel-light">
    <div class="row">
      <div class="col-lg-2 col-md-12 text-center-sm text-center-md filter-label">
        <label>{{ trans('messages.search.size') }}</label>
      </div>

      <form class="col-lg-9">
        <div class="row row-condensed">
          <div class="col-md-4 col-sm-12 row-space-1">
            <div class="select select-block">
              <label for="map-search-min-bedrooms" class="screen-reader-only">
                {{ trans('messages.search.minimum_bedrooms') }}
              </label>
              <select name="min_bedrooms" ng-model="bedrooms" class="min-bedrooms" id="map-search-min-bedrooms" ng-init="bedrooms = '{{ $bedrooms }}'">
                <option value="">{{ trans('messages.lys.bedrooms') }}</option>
                    @for($i=1;$i<=10;$i++)
              <option value="{{ $i }}" >
              {{ $i}} {{ trans('messages.lys.bedrooms') }}
              </option>
          @endfor
                  </select>
            </div>
          </div>

          <div class="col-md-4 col-sm-12 row-space-1">
            <div class="select select-block">
              <label for="map-search-min-bathrooms" class="screen-reader-only">
                {{ trans('messages.search.minimum_bathroms') }}
              </label>
              <select name="min_bathrooms" ng-model="bathrooms" class="min-bathrooms input-medium" id="map-search-min-bathrooms" ng-init="bathrooms = '{{ $bathrooms }}'">
                <option value="">{{ trans('messages.lys.bathrooms') }}</option>

          @for($i=0.5;$i<=8;$i+=0.5)
            <option class="bathrooms" value="{{ $i }}" >
            {{ ($i == '8') ? $i.'+' : $i }} {{ trans('messages.lys.bathrooms') }}
            </option>
           @endfor

              </select>
            </div>
          </div>

          <div class="col-md-4 col-sm-12">
            <div class="select select-block">
              <label for="map-search-min-beds" class="screen-reader-only">
                {{ trans('messages.search.minimum_beds') }}
              </label>
              <select name="min_beds" ng-model="beds" class="min-beds input-medium" id="map-search-min-beds" ng-init="beds = '{{ $beds }}'">
                 <option value="">{{ trans('messages.lys.beds') }}</option>
                @for($i=1;$i<=16;$i++)
            <option value="{{ $i }}" >
            {{ ($i == '16') ? $i.'+' : $i }} {{ trans('messages.lys.beds') }}
            </option>
        @endfor
                 </select>
            </div>
          </div>
        </div>
      </form>
    </div>
  </div>

  <div class="checkbox-group hosting_amenities filters-section panel-body panel-light toggle-group" data-name="hosting_amenities">
    <div class="row">
      <div class="col-lg-2 col-md-12 filter-label">
        <label>{{ trans('messages.lys.amenities') }}</label>
      </div>

      <div class="col-lg-9 col-md-11">
        <div class="row row-condensed filters-columns">
        {{--*/ $row_inc = 1 /*--}}
            @foreach($amenities as $row_amenities)
               @if($row_inc <= 3)

          <div class="col-md-4">
            <label class="media checkbox text-truncate" title="{{ $row_amenities->name }}">
              <input type="checkbox" name="more_filter[]" value="{{ $row_amenities->id }}" class="pull-left amenities" id="map-search-amenities-{{ $row_amenities->id }}" ng-checked="{{ (in_array($row_amenities->id, $amenities_selected)) ? 'true' : 'false' }}">
              {{ $row_amenities->name }}
            </label>
          </div>
           @endif
           {{--*/ $row_inc++ /*--}}
          @endforeach

        </div>

        <div class="filters-more collapse">
          <hr>
          <div class="row row-condensed filters-columns">
             {{--*/ $amen_inc = 1 /*--}}

             @foreach($amenities as $row_amenities)
               @if($amen_inc > 3)

            <div class="col-md-4">
            <label class="media checkbox text-truncate" title="{{ $row_amenities->name }}">
              <input type="checkbox" name="more_filter[]" value="{{ $row_amenities->id }}" class="pull-left amenities" id="map-search-amenities-{{ $row_amenities->id }}" ng-checked="{{ (in_array($row_amenities->id, $amenities_selected)) ? 'true' : 'false' }}">
              {{ $row_amenities->name }}
            </label>
          </div>
          @endif
           {{--*/ $amen_inc++ /*--}}
            @endforeach

          </div>
        </div>
      </div>

      <div class="col-md-1">
        <label class="show-more">
          <span>
            <i class="icon icon-caret-down hide-sm"></i>
            <strong class="text-muted show-sm">+ {{ trans('messages.profile.more') }}</strong>
          </span>
          <span class="hide"><i class="icon icon-caret-up"></i></span>
        </label>
      </div>
    </div>
  </div>

  <div class="checkbox-group property_type_id filters-section panel-body panel-light toggle-group" data-name="property_type_id">
    <div class="row">
      <div class="col-lg-2 col-md-12 filter-label">
        <label>{{ trans('messages.lys.property_type') }}</label>
      </div>

      <div class="col-lg-9 col-md-11">
        <div class="row row-condensed filters-columns">
          {{--*/ $pro_inc = 1 /*--}}
          @foreach($property_type as $row_property_type =>$value_property_type)
             @if($pro_inc <= 3)

          <div class="col-md-4">
            <label class="media checkbox text-truncate" title="{{ $value_property_type }}">
              <input type="checkbox" ng-model="property_type_{{ $row_property_type }}" value="{{ $row_property_type }}" class="pull-left property_type" id="map-search-property_type-{{ $row_property_type }}" ng-checked="{{ (in_array($row_property_type, $property_type_selected)) ? 'true' : 'false' }}">
              {{ $value_property_type}}
            </label>
          </div>
           @endif
           {{--*/ $pro_inc++ /*--}}
            @endforeach

        </div>

        <div class="filters-more collapse">
          <hr>
          <div class="row row-condensed filters-columns">
             {{--*/ $property_inc = 1 /*--}}
       @foreach($property_type as $row_property_type =>$value_property_type)
        @if($property_inc > 3)

          <div class="col-md-4">
            <label class="media checkbox text-truncate" title="{{ $value_property_type }}">
              <input type="checkbox" ng-model="property_type_{{ $row_property_type }}" value="{{ $row_property_type }}" class="pull-left property_type" id="map-search-property_type-{{ $row_property_type }}" ng-checked="{{ (in_array($row_property_type, $property_type_selected)) ? 'true' : 'false' }}">
              {{ $value_property_type}}
            </label>
          </div>
           @endif
           {{--*/ $property_inc++ /*--}}

             @endforeach
          </div>
        </div>
      </div>

      <div class="col-md-1">
        <label class="show-more">
          <span>
            <i class="icon icon-caret-down hide-sm"></i>
            <strong class="text-muted show-sm">+ {{ trans('messages.profile.more') }}</strong>
          </span>
          <span class="hide"><i class="icon icon-caret-up"></i></span>
        </label>
      </div>
    </div>

  </div>

  <div class="panel-body panel-light show-sm toggle-group">
    <div class="sticky-apply-filters-placeholder"></div>
  </div>

      <div class="panel-light panel-btn-sm filters-footer filters-footer-sm toggle-group">
        <div class="row row-condensed">
          <div class="col-md-8 col-lg-7 col-md-offset-4 col-lg-offset-5">
            <div class="row row-condensed space-sm-7">
              <div class="col-sm-6">
                <button class="btn btn-block cancel-btn" id="cancel-filter">{{ trans('messages.your_reservations.cancel') }}</button>
              </div>
              <div class="col-sm-6">
                <button class="btn btn-block btn-primary apply-filters-btn" id="more_filter_submit" {{ (count($amenities_selected) || count($property_type_selected)) ? '' : 'disabled' }} ng-click="apply_filter();">
                  {{ trans('messages.search.apply_filters') }}
                </button>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
    </div>
    <div class="filters-placeholder hide hide-sm"></div>
    <a id="docked-filters"></a>

    <div class="sidebar-header toggle-hide panel-body hide-sm clearfix panel-bg-medium">
      <button class="btn show-filters pull-left" id="more_filters">
        <span class="text-more-filters">{{ trans('messages.search.more_filters') }}</span>
        <span class="text-filters">{{ trans('messages.search.filters') }}</span>
      </button>

    </div>
  <div class="sidebar-header-placeholder"></div>
    <div class="search-results toggle-hide">
      <div class="outer-listings-container row-space-2">

<div class="listings-container">
    <div class="row">

        <div class="col-sm-12 row-space-2 col-md-6" ng-repeat="rooms in room_result.data" ng-cloak>
          <div class="listing list_view" ng-mouseover="on_mouse($index);" ng-mouseleave="out_mouse($index);" data-room_id="@{{rooms.id}}"  >

            <div class="panel-image listing-img">
                <div class="listing-description wl-data-@{{ rooms.id }}">
                  <div class="summary">
                    <p>
                     @{{ rooms.summary }}
           <a href="{{ url('s') }}/@{{ location }}?checkin=@{{ checkin }}&checkout=@{{ checkout }}&guests=@{{ guests }}" id="tooltip-sticky-@{{ rooms.id }}" class="learn-more">{{ trans('messages.search.learn_more') }}</a>
                    </p>

                  </div>
                  <p class="address">@{{ rooms.city }}</p>

                </div>
              <a href="{{ url('rooms') }}/@{{ rooms.id }}?checkin=@{{checkin}}&checkout=@{{checkout}}&guests=@{{ guests }}" target="listing_@{{ rooms.id }}" class="media-photo media-cover">
                <div class="listing-img-container media-cover text-center">
                  <img id="rooms_image_@{{ rooms.id}}"  src="{{ url() }}/images/@{{ rooms.photo_name }}" class="img-responsive-height" alt="@{{ rooms.name }}">
                </div>
              </a>

                <div class="target-prev target-control rooms-slider block-link"  data-room_id="@{{rooms.id}}">
                  <i class="icon icon-chevron-left icon-size-2 icon-white"></i>
                </div>

              <a href="{{ url('rooms') }}/@{{ rooms.id }}?checkin=@{{checkin}}&checkout=@{{checkout}}&guests=@{{ guests }}" target="listing_@{{ rooms.id }}" class="link-reset panel-overlay-bottom-left panel-overlay-label panel-overlay-listing-label">
                <div>
                  <sup class="h6 text-contrast"><span ng-bind-html="rooms.rooms_price.currency.symbol"></span></sup>
                  <span class="h3 text-contrast price-amount"> @{{ rooms.rooms_price.night }}</span>

                  <sup class="h6 text-contrast"></sup>
                  <span aria-label="Book Instantly" data-behavior="tooltip" class="h3 icon-beach" ng-if="rooms.booking_type == 'instant_book'">
                      <i class="icon icon-instant-book icon-flush-sides"></i>
                  </span>
                </div>

              </a>
			  <span title="This house is Kosher" class="h3 icon-k hastooltip" ng-if="rooms.koshire == '1'">K</span>
          <div class="target-next target-control rooms-slider block-link" data-room_id="@{{rooms.id}}">
                    <i class="icon icon-chevron-right icon-size-2 icon-white"></i>
                  </div>

            <div class="panel-overlay-top-right wl-social-connection-panel">
                  <span class="rich-toggle wish_list_button wishlist-button not_saved">
                    <input type="checkbox" id="wishlist-widget-@{{ rooms.id }}" name="wishlist-widget-@{{ rooms.id }}" data-for-hosting="@{{ rooms.id }}" ng-checked="rooms.saved_wishlists">
                    <label for="wishlist-widget-@{{ rooms.id }}" class="hide-sm">
                      <span class="screen-reader-only">Wishlist</span>
                      <i class="icon icon-heart icon-rausch icon-size-2 rich-toggle-checked"></i>
                      <i class="icon icon-heart wishlist-heart-unchecked icon-size-2 rich-toggle-unchecked"></i>
                      <i class="icon icon-heart-alt icon-white icon-size-2" id="wishlist-widget-icon-@{{ rooms.id }}" data-room_id="@{{ rooms.id }}" data-img="{{ url('images') }}/@{{ rooms.photo_name }}" data-name="@{{ rooms.name }}" data-address="@{{ rooms.rooms_address.city }}" data-price="@{{ rooms.rooms_price.currency.symbol }}@{{ rooms.rooms_price.night }}" data-review_count="" data-host_img="@{{ rooms.users.profile_picture.src }}" data-star_rating="" data-summary="@{{ rooms.summary }}" data-room_type="@{{ rooms.room_type_name }}" data-property_type_name="@{{ rooms.property_type_name }}" data-person_capacity_string="" data-bedrooms_string="" data-space_tab_content="" data-neighborhood_tab_content=""></i>
                    </label>
                  </span>

                </div>

            </div>

            <div class="panel-body panel-card-section">
              <div class="media">
                  <a href="{{ url('users/show/') }}/@{{ rooms.user_id }}" class="media-photo-badge pull-right card-profile-picture card-profile-picture-offset">
                    <div class="media-photo media-round">
                      <span style="background-image:url(@{{ rooms.users.profile_picture.src }}); height:56px; width:56px;"></span>
                    </div>
                  </a>
                <a href="{{ url('rooms') }}/@{{ rooms.id }}?checkin=@{{ checkin }}&checkout=@{{ checkout }}&guests=@{{ guests }}" target="listing_@{{ rooms.id }}" class="text-normal">
                  <h3 title="@{{ rooms.name }}" itemprop="name" class="h5 listing-name text-truncate row-space-top-1">
                    @{{ rooms.name }}
                  </h3>
                </a>
                <div itemprop="description" class="text-muted listing-location text-truncate"><a href="{{ url('rooms') }}/@{{ rooms.id }}?checkin=@{{ checkin }}&checkout=@{{ checkout }}&guests=@{{ guests }}" class="text-normal link-reset">
  @{{ rooms.room_type_name }}
  <span ng-show="rooms.overall_star_rating">
  ·
  <span ng-bind-html="rooms.overall_star_rating"></span>
  </span>
  <span ng-show="rooms.reviews_count">
  ·
  @{{ rooms.reviews_count }} {{ trans_choice('messages.header.review',1) }}@{{ (rooms.reviews_count > 1) ? 's' : '' }}
  </span>
</a>
</div>
              </div>

            </div>
          </div>
        </div>

        <h2 ng-hide="room_result.data.length" class="text-center" ng-cloak>
        {{ trans('messages.search.no_results_found') }}
        </h2>

    </div>

</div>

      </div>

      <div class="results-footer">

  <div class="pagination-buttons-container row-space-8" ng-cloak>
    <div class="results_count">
      <p>
        @{{ room_result.from }} – @{{ room_result.to }} {{ trans('messages.search.of') }} @{{ room_result.total }} {{ trans('messages.search.rentals') }}
      </p>
    </div>
    <div>
      <posts-pagination></posts-pagination>
    </div>
  </div>

  <div class="nearby-links">
    <div class="breadcrumbs row-space-top-1" itemprop="breadcrumb" ng-cloak>
        <span itemscope="" itemtype="https://data-vocabulary.org/Breadcrumb">
          <a href="{{ url('s/India') }}" itemprop="url">
            <span itemprop="title">@{{ room_result[0].rooms_address.country_name }}</span>
          </a>
          <i class="icon icon-chevron-right breadcrumb-spacer"></i>
        </span>
        <span itemscope="" itemtype="https://data-vocabulary.org/Breadcrumb">
          <a href="{{ url('s/Tamil-Nadu--India') }}" itemprop="url">
            <span itemprop="title">@{{ room_result[0].rooms_address.state }}</span>
          </a>
          <i class="icon icon-chevron-right breadcrumb-spacer"></i>
        </span>
          <span>@{{ room_result[0].rooms_address.city }}</span>
    </div>
  </div>

</div>


    </div>

    <div class="panel-btn-sm hide filters-btn fixed">
      <button class="js-small-filter-toggle btn btn-large btn-block btn-primary">{{ trans('messages.search.filters') }}</button>
    </div>
  </div>

  <div class="map hide-sm">

    <div id="map_canvas" role="presentation" class="map-canvas" style="position: relative; overflow: hidden; transform: translateZ(0px); background-color: rgb(164, 221, 245);">
  </div>

  </div>

  <button class="hide-sm btn footer-toggle">
    <span class="open-content">
      <i class="icon icon-globe"></i> {{ trans('messages.search.language_currency') }}
    </span>
    <span class="close-content"><i class="icon icon-remove"></i> {{ trans('messages.home.close') }}</span>
  </button>
  <div class="col-lg-12 col-md-12 map-content">
    <div class="col-lg-3 col-md-3" ng-repeat="place_info in places_info track by $index" ng-bind-html="place_info" >

    </div>
  </div>
</div>


<div id="js-ib-icon-tooltip" class="tooltip tooltip-left-middle ib-icon-tooltip" role="tooltip" aria-hidden="true">
  <div class="panel-body">
  <h5>{{ trans('messages.lys.instant_book') }}</h5>
  <p class="text-muted">
    {{ trans('messages.search.instant_book_desc') }}
  </p>
  </div>
</div>

<div class="modal-container modal-transitions wl-modal__modal hide">
<div class="modal-table">
<div class="modal-cell">
<div class="modal-content">
<div class="wl-modal">
<div class="row row-margin-zero">
<div class="hide-sm hide-md col-lg-7 wl-modal__col">
<div class="media-cover media-cover-dark background-cover background-listing-img" style="background-image:url();">
</div>
<div class="panel-overlay-top-left text-contrast wl-modal-listing-tabbed">
<div class="va-container media">
<img class="pull-left host-profile-img media-photo media-round space-2" height="67" width="67" src="">
<div class="media-body va-middle">
<div class="h4 space-1 wl-modal-listing__name"></div>
<div class="wl-modal-listing__rating-container">
<span class="hide">
<div class="star-rating-wrapper">
<div class="star-rating" content="0">
<div class="foreground">
<span> </span>
</div>
<div class="background">
<span>
<span>
<i class="icon-star icon icon-light-gray icon-star-big">
</i>
<span> </span>
</span>
<span>
<i class="icon-star icon icon-light-gray icon-star-big">
</i>
<span> </span>
</span>
<span>
<i class="icon-star icon icon-light-gray icon-star-big">
</i>
<span> </span>
</span>
<span>
<i class="icon-star icon icon-light-gray icon-star-big">
</i>
<span> </span>
</span>
<span>
<i class="icon-star icon icon-light-gray icon-star-big">
</i>
<span> </span>
</span>
</span>
</div>
</div>
<span> </span>
<span class="h6 hide">
<small>
<span>(</span>
<span>
</span>
<span>)</span>
</small>
</span>
</div>
<span> · </span>
<span class="wl-modal-listing__text">
</span>
<span> · </span>
</span>
<span class="wl-modal-listing__address wl-modal-listing__text"></span>
</div>
</div>
</div>
</div>
</div>
<div class="col-lg-5 wl-modal__col">
<div class="panel-header panel-light wl-modal__header">
<div class="va-container va-container-h va-container-v">
<div class="va-middle">
<div class="pull-left h3">{{ trans('messages.wishlist.save_to_wishlist') }}</div>
<a class="modal-close wl-modal__modal-close">
</a>
</div>
</div>
</div>
<div class="wl-modal-wishlists">
<div class="panel-body panel-body-scroll wl-modal-wishlists__body wl-modal-wishlists__body--scroll">
<div class="text-lead text-gray space-4 hide">{{ trans('messages.wishlist.save_fav_list') }}</div>
<div class="wl-modal-wishlist-row clickable" ng-repeat="item in wishlist_list" ng-class="(item.saved_id) ? 'text-dark-gray' : 'text-gray'" ng-click="wishlist_row_select($index)" id="wishlist_row_@{{ $index }}">
<div class="va-container va-container-v va-container-h">
<div class="va-middle text-left text-lead wl-modal-wishlist-row__name">
<span> </span>
<span>@{{ item.name }}</span>
<span> </span>
</div>
<div class="va-middle text-right">
<div class="h3 wl-modal-wishlist-row__icons">
<i class="icon icon-heart-alt icon-light-gray wl-modal-wishlist-row__icon-heart-alt" ng-hide="item.saved_id"></i>
<i class="icon icon-heart icon-rausch wl-modal-wishlist-row__icon-heart" ng-show="item.saved_id"></i>
</div>
</div>
</div>
</div>
</div>
<div class="text-beach panel-body wl-modal-wishlists__body hide">
<small>
</small>
</div>
<div class="panel-footer wl-modal-footer clickable">
<form class="wl-modal-footer__form hide">
<strong>
<div class="pull-left text-lead va-container va-container-v">
<input type="text" class="wl-modal-footer__text wl-modal-footer__input" value="" placeholder="Name Your Wish List">
</div>
<div class="pull-right">
<button class="btn btn-flat wl-modal-wishlists__footer__save-button btn-contrast">{{ trans('messages.wishlist.create') }}</button>
</div>
</strong>
</form>
<div class="text-rausch va-container va-container-v va-container-h">
<div class="va-middle text-lead wl-modal-footer__text">{{ trans('messages.wishlist.create_new_wishlist') }}</div>
</div>
</div>
</div>
</div>
</div>
</div>
</div>
</div>
</div>
</div>

    </main>
    @stop

@push('scripts')
<script type="text/javascript">
  var min_slider_price = {!! $default_min_price !!};
  var max_slider_price = {!! $default_max_price !!};
  var min_slider_price_value = {!! $min_price !!};
  var max_slider_price_value = {!! $max_price !!};
</script>
@stop
