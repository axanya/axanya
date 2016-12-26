@extends('template')

@section('main')
 
        
<main id="site-content" role="main" ng-controller="rooms_detail">

<div class="subnav-container">

  <div data-sticky="true" data-transition-at="#summary" aria-hidden="true" class="subnav section-titles hide-md">
    <div class="page-container-responsive">
      <ul class="subnav-list">
        <li>
          <a href="#photos" aria-selected="true" class="subnav-item">
            {{ trans_choice('messages.header.photo',2) }}
          </a>
        </li>
        <li>
          <a href="#summary" class="subnav-item">
            {{ trans('messages.rooms.about_this_listing') }}
          </a>
        </li>
        <li>
          <a href="#reviews" class="subnav-item">
            {{ trans_choice('messages.header.review',2) }}
          </a>
        </li>
        <li>
          <a href="#host-profile" class="subnav-item">
            {{ trans('messages.rooms.the_host') }}
          </a>
        </li>
        <li>
          <a href="#neighborhood" class="subnav-item">
            {{ trans('messages.your_trips.location') }}
          </a>
        </li>
      </ul>
    </div>
  </div>
</div>

<div id="og_pro_photo_prompt" class="container"></div>

<div id="room" itemscope="" itemtype="http://schema.org/Product">

  <div id="photos" class="with-photos with-modal">
    
    <span class="cover-img-container img-box1" data-hook="cover-img-container">
     <a href="{{ url('rooms/'.$result->id.'/slider') }}" oncontextmenu="return false" class="gallery" data-lightbox-type="iframe">
      <div  id ="frontimage_slider" class="cover-img" data-hook="img-lg" style="background-image:
                  url({{ url('images/'.$result->photo_name) }})">
      </div></a>
      </div>
    </span>

  <div id="summary" class="panel room-section">
    
    <div class="page-container-responsive">
      <div class="row">
        <div class="col-lg-8">
       
<div class="row-space-4 row-space-top-4 summary-component">
  <div class="row">

    <div class="col-md-3 space-sm-4 text-center space-sm-2">
      
<div class="media-photo-badge">

  <a href="{{ url('users/show/'.$result->user_id) }}" class="media-photo media-round prof_pic">

  <span class="host-profile-image" data-pin-nopin="true" style="width:115px; height:115px; background-image:url({{ $result->users->profile_picture->src }})" title="{{ $result->users->first_name }}"></span>
  </a>
</div>

    </div>

    <div class="col-md-9">

  
      <h1 itemprop="name" class="overflow h3 row-space-1 text-center-sm" id="listing_name">
        {{ $result->name }}
      </h1>


      <div id="display-address" class="row-space-2 text-muted text-center-sm" itemprop="aggregateRating" itemscope="">

        <a href="" class="link-reset">{{$result->rooms_address->city}} @if($result->rooms_address->city !=''),@endif {{$result->rooms_address->state}} @if($result->rooms_address->state !=''),@endif {{$result->rooms_address->country_name}}</a>
        &nbsp;
        @if($result->overall_star_rating)
        <a href="#reviews" class="link-reset hide-sm">
        <div class="star-rating-wrapper">
        {!! $result->overall_star_rating !!}
        <span> </span>
        <span>
        <small>
        <span>(</span>
        <span>{{ $result->reviews->count() }}</span>
        <span>)</span>
        </small>
        </span>
        </div>
        </a>
        @endif
      </div>

      <div class="row row-condensed text-muted text-center">
          <div class="col-sm-3">
          @if( $result->room_type_name == "Private room" )
            <i class="icon icon-private-room icon-size-2"></i>
          @elseif($result->room_type_name == "Entire home/apt")
          <i class="icon icon-entire-place icon-size-2"></i>
          @else
          <i class="icon icon-shared-room icon-size-2"></i>
          @endif
          </div>
          <div class="col-sm-3">
            <i class="icon icon-group icon-size-2"></i>
          </div>
          <div class="col-sm-3">
            <i class="icon icon-double-bed icon-size-2"></i>
          </div>
      </div>

    </div>

  </div>

  <div class="row">

    <div class="col-md-3 text-muted text-center hide-sm">
      <a href="" class="link-reset text-wrap">{{ $result->users->first_name }}</a>
    </div>

    <div class="col-md-9">
      <div class="row row-condensed text-muted text-center">
          <div class="col-sm-3">
            {{ $result->room_type_name }}
          </div>
          <div class="col-sm-3">
            {{ $result->accommodates }} {{ trans_choice('messages.home.guest',2) }}
          </div>
          <div class="col-sm-3">
            {{ $result->beds}} {{ trans('messages.lys.beds') }}
          </div>
      </div>
    </div>

  </div>
</div>

        </div>
        <div class="col-lg-4">
          
<div id="tax-descriptions-tip" class="tooltip tooltip-top-middle" role="tooltip" data-sticky="true" data-trigger="#tax-descriptions-tooltip">
</div>
  <form accept-charset="UTF-8" action="{{ url('payments/book/'.$room_id) }}" id="book_it_form" method="post">
        {!! Form::token() !!}
  <h4 class="screen-reader-only">
    {{ trans('messages.rooms.request_to_book') }}
  </h4>
  <div id="pricing" itemprop="offers" itemscope="" class="">
    <div id="price_amount" class="book-it-price-amount pull-left h3 text-special">{{ $result->rooms_price->currency->symbol }} <span  id="rooms_price_amount" value="">{{ $result->rooms_price->night }}</span>
    @if($result->booking_type == 'instant_book')
    <span aria-label="Book Instantly" data-behavior="tooltip" class="h3 icon-beach">
      <i class="icon icon-instant-book icon-flush-sides"></i>
    </span>
    @endif
</div>
    <i class="icon icon-bolt icon-beach pull-left h3 pricing__bolt"></i>

    <div id="payment-period-container" class="pull-right">
      <div id="per_night" class="per-night">
        {{ trans('messages.rooms.per_night') }}
      </div>
      <div id="per_month" class="per-month hide">
        {{ trans('messages.rooms.per_month') }}
        <i id="price-info-tooltip" class="icon icon-question hide" data-behavior="tooltip"></i>
      </div>
    </div>
  </div>

  <div id="book_it" class="display-subtotal" style="top: 0px;">
    <div class="panel book-it-panel">
      <div class="panel-body panel-light">
        <div class="form-fields">
          <div class="row row-condensed space-3">
            <div class="col-md-9">
              <div class="row row-condensed">
                <div class="col-sm-6 space-1-sm">
                  <label for="checkin">
                    {{ trans('messages.home.checkin') }}
                  </label>
                  <input class="checkin ui-datepicker-target" id="list_checkin" name="checkin" placeholder="{{ trans('messages.rooms.dd-mm-yyyy') }}" type="text">
                </div>

                <input type="hidden" ng-model="room_id" ng-init="room_id = {{ $room_id }}">
                <input type="hidden" id="room_blocked_dates" value="" >
                <input type="hidden" id="calendar_available_price" value="" >
                <input type="hidden" id="room_available_price" value="" >
                <input type="hidden" id="price_tooltip" value="" >
                <input type="hidden" id="url_checkin" value="{{ $checkin }}" >
                <input type="hidden" id="url_checkout" value="{{ $checkout }}" >
                <input type="hidden" id="url_guests" value="{{ $guests }}" >
                <input type="hidden" name="booking_type" id="booking_type" value="{{ $result->booking_type }}" >

                <div class="col-sm-6 space-1-sm">
                  <label for="checkout">
                    {{ trans('messages.home.checkout') }}
                  </label>
                  <input class="checkout ui-datepicker-target" id="list_checkout" name="checkout" placeholder="{{ trans('messages.rooms.dd-mm-yyyy') }}" type="text">
                </div>
              </div>
            </div>
            <div class="col-md-3">
              <label for="number_of_guests">
                {{ trans_choice('messages.home.guest',2) }}
              </label>
              <div class="select select-block">
                <select id="number_of_guests" name="number_of_guests">
                @for($i=1;$i<= $result->accommodates;$i++)
                <option value="{{ $i }}">{{ $i }}</option>
                 @endfor
                </select>
              </div>
            </div>
          </div>

          <div class="simple-dates-message-container hide">
            <div class="media text-kazan space-top-2 space-2">
              <div class="pull-left message-icon">
                <i class="icon icon-currency-inr"></i>
              </div>
              <div class="media-body">
                <strong>
                  {{ trans('messages.search.enter_dates') }}
                </strong>
              </div>
            </div>
          </div>
        </div>
        <div class="js-book-it-status">
          <div class="js-book-it-enabled clearfix">
            <div class="js-subtotal-container book-it__subtotal panel-padding-fit" style="display:none;">
            <table class="table table-bordered price_table" >
    <tbody>
    <tr>
      <td>
        {{ $result->rooms_price->currency->symbol }}  <span  id="rooms_price_amount_1" value="">{{ $result->rooms_price->night }}</span> x <span  id="total_night_count" value="">0</span> {{ trans_choice('messages.rooms.night',1) }}
        
      </td>
      <td>{{ $result->rooms_price->currency->symbol }}<span  id="total_night_price" value="">0</span></td>
    </tr>
  
    <tr>
      <td>
        {{ trans('messages.rooms.service_fee') }}
        
          <i id="service-fee-tooltip" class="icon icon-question"></i>
        
      </td>
      <td>{{ $result->rooms_price->currency->symbol }}<span  id="service_fee" value="">0</span></td>
    </tr>
    
    <tr class = "additional_price"> 
      <td>
        {{ trans('messages.rooms.addtional_guest_fee') }}
      </td>
    <td>{{ $result->rooms_price->currency->symbol }}<span  id="additional_guest" value="">0</span></td>
    </tr>

    <tr class = "security_price"> 
      <td>
        {{ trans('messages.rooms.security_fee') }}
      </td>
    <td>{{ $result->rooms_price->currency->symbol }}<span  id="security_fee" value="">0</span></td>
    </tr>

    <tr class = "cleaning_price"> 
      <td>
        {{ trans('messages.rooms.cleaning_fee') }}
      </td>
    <td>{{ $result->rooms_price->currency->symbol }}<span  id="cleaning_fee" value="">0</span></td>
    </tr>
  
    <tr>
      <td>{{ trans('messages.rooms.total') }}</td>
      <td>{{ $result->rooms_price->currency->symbol }}<span  id="total" value="">0</span></td>
    </tr>
  
</tbody></table>
</div>

      <div id="book_it_disabled" class="text-center" style="display:none;">
            <p id="book_it_disabled_message" class="icon-rausch">
              {{ trans('messages.rooms.dates_not_available') }}
            </p>
            <a href="{{URL::to('/')}}/s?location={{$result->rooms_address->city }}" class="btn btn-large btn-block" id="view_other_listings_button">
              {{ trans('messages.rooms.view_other_listings') }}
            </a>
          </div>
          
            <div class="js-book-it-btn-container {{ ($result->user_id == @Auth::user()->user()->id) ? 'hide' : '' }}">
              <button type="submit" class="js-book-it-btn btn btn-large btn-block btn-primary">
                <span class="book-it__btn-text {{ ($result->booking_type != 'instant_book') ? '' : 'hide' }}">
                  {{ trans('messages.rooms.request_to_book') }}
                </span>
                <span class="{{ ($result->booking_type == 'instant_book') ? '' : 'book-it__btn-text--instant' }}">
                  <i class="icon icon-bolt text-beach h4 book-it__instant-book-icon"></i>
                  {{ trans('messages.lys.instant_book') }}
                </span>
              </button>
            </div>
            <p class="text-muted book-it__btn-text--instant-alt space-1 space-top-3 text-center {{ ($result->user_id == @Auth::user()->user()->id) ? 'hide' : '' }}">
              <small>
                {{ trans('messages.rooms.review_before_paying') }}
              </small>
            </p>
          </div>
        </div>
      </div>
    </div>

    <div class="panel wishlist-panel space-top-6">
      <div class="panel-body panel-light">
      @if(Auth::user()->check())
        <div class="wishlist-wrapper hide-sm">
          <div class="rich-toggle wish_list_button not_saved" data-hosting_id="{{ $result->id }}">
  <input type="checkbox" name="wishlist-button" id="wishlist-button">
  <label for="wishlist-button" class="btn btn-block btn-large">
    <span class="rich-toggle-checked">
      <i class="icon icon-heart icon-rausch"></i>
      Saved to Wish List
    </span>
    <span class="rich-toggle-unchecked">
      <i class="icon icon-heart-alt icon-light-gray"></i>
      {{ trans('messages.wishlist.save_to_wishlist') }}
    </span>
  </label>
</div>
        </div>
        @endif
        <div class="other-actions hide-sm text-center">
          <div class="social-share-widget space-top-3 p3-share-widget">
  <span class="share-title">
    {{ trans('messages.rooms.share') }}:
  </span>
  <span class="share-triggers">

      <a class="share-btn link-icon" data-email-share-link="" data-network="email" rel="nofollow" title="{{ trans('messages.login.email') }}" href="mailto:?subject=I love this room&amp;body=Check out this {{ Request::url() }}">
        <span class="screen-reader-only">{{ trans('messages.login.email') }}</span>
        <i class="icon icon-envelope social-icon-size"></i>
      </a>
      <a class="share-btn link-icon" data-network="facebook" rel="nofollow" title="Facebook" href="http://www.facebook.com/sharer.php?u={{ Request::url() }}" target="_blank">
        <span class="screen-reader-only">Facebook</span>
        <i class="icon icon-facebook social-icon-size"></i>
      </a>

      <a class="share-btn link-icon" data-network="twitter" rel="nofollow" title="Twitter" href="http://twitter.com/home?status=Love this! {{ $result->name }} - {{ $result->property_type_name }} for Rent - {{ "@".$site_name}} Travel  {{ Request::url() }}" target="_blank">
        <span class="screen-reader-only">Twitter</span>
        <i class="icon icon-twitter social-icon-size"></i>
      </a>  
       
    <a class="share-btn link-icon" data-network="pinterest" rel="nofollow" title="Pinterest" href="http://pinterest.com/pin/create/button/?url={{ Request::url() }}&media={{ url('images/'.$result->photo_name) }}&description={{ $result->summary }}" target="_blank">
        <span class="screen-reader-only">Pinterest</span>
        <i class="icon icon-pinterest social-icon-size"></i>
      </a> 
      
     
      <a class="share-btn link-icon" href="https://plus.google.com/share?url={{ Request::url() }}"  itemprop="nofollow" rel="publisher" target="_blank">
            <span class="screen-reader-only">Google+</span>
            <i class="icon social-icon-size icon-google-plus"></i>
      </a>  

  </span>
  

</div>


          </div>
        </div>
      </div>
    </div>
  </div>

  <input id="hosting_id" name="hosting_id" type="hidden" value="{{ $result->id }}">
</form></div>
      </div>
    </div>
  </div>

  <div id="details" class="details-section webkit-render-fix">
    <div class="page-container-responsive">
      <div class="row">
        <div class="col-lg-8" id="details-column">
          
<div class="row-space-8 row-space-top-8">

      <h4 class="row-space-4 text-center-sm">
      {{ trans('messages.rooms.about_this_listing') }}
    </h4>
	 @if($result->koshire =='1')
		<p class="koshire_details">
		<span class="h3 icon-k1">K</span> This space must be kept Kosher
		</p>
	@endif

    <p>{{ $result->summary }}</p>

@if(Auth::user()->check())
@if(Auth::user()->user()->id != $result->user_id)
  <p class="text-center-sm">
    <a id="contact-host-link" href="javascript:void(0);">
      <strong>{{ trans('messages.rooms.contact_host') }}</strong>
    </a>
  </p>
@endif
@endif

    <div class="space-4 space-top-4 show-sm">
    @foreach($rooms_photos as $row_photos)
    <div class="inline-photo panel-image">
      <a href="{{ url('images/rooms/'.$room_id.'/'.$row_photos->name) }}" class="photo-trigger" data-index="1">
        <img src="{{ url('images/rooms/'.$room_id.'/'.$row_photos->name) }}" alt="{{ $row_photos->highlights }}" class="media-photo media-photo-block space-1 space-top-1 img-responsive">
        <div class="panel-overlay-top-right panel-overlay-label panel-overlay-button-icon">
          <i class="icon icon-full-screen icon-white icon-size-2"></i>
        </div>
</a>    </div>
    <div class="row">
      <div class="col-lg-9">
          <p class="text-muted pull-left">{{ $row_photos->highlights }}</p>
      </div>
      <div class="col-lg-3">
      </div>
    </div>
    @endforeach
  </div>


  <hr>



      <div class="row">
        <div class="col-md-3">
              <div class="text-muted">
      {{ trans('messages.lys.the_space') }}
    </div>

        </div>
        <div class="col-md-9">
          <div class="row">
              <div class="col-md-6">
                  @if($result->bed_type_name != NULL)
                    <div>{{ trans('messages.rooms.bed_type') }}: <strong>{{ $result->bed_type_name }}</strong></div>
                    @endif
                    <div>{{ trans('messages.rooms.property_type') }}: <strong><a href="#" class="link-reset">{{ $result->property_type_name }}</a></strong></div>

                    <div>{{ trans('messages.lys.accommodates') }}: <strong>{{ $result->accommodates }}</strong></div>
              </div>
              <div class="col-md-6">

                    <div>{{ trans('messages.lys.bedrooms') }}: <strong>{{ $result->bedrooms }}</strong></div>

                    <div>{{ trans('messages.lys.bathrooms') }}: <strong>{{ $result->bathrooms }}</strong></div>

                    <div>{{ trans('messages.lys.beds') }}: <strong>{{ $result->beds }}</strong></div>
              </div>
          </div>
        </div>
      </div>


    <hr>


  <div class="row amenities">
  <div class="col-md-3 text-muted">
    {{ trans('messages.lys.amenities') }}
  </div>



    <div class="col-md-9 expandable expandable-trigger-more">
      <div class="expandable-content-summary">
        <div class="row rooms_amenities_before" >

                 
            <div class="col-sm-6">
               
               {{--*/ $i = 1 /*--}}

               {{--*/ $count = round(count($amenities)/2) /*--}}

                @foreach($amenities as $all_amenities)

                
               @if($i < 6)

                @if($all_amenities->status != null)
                <div class="row-space-1">
                @else
                <div class="row-space-1 text-muted">
                @endif

                <i class="icon h3 icon-{{ $all_amenities->icon }}"></i>
                    &nbsp;
                  <span class="js-present-safety-feature"><strong>
                        @if($all_amenities->status == null)
                        <del> 
                        @endif
                        {{ $all_amenities->name }}
                        @if($all_amenities->status == null)
                        </del> 
                        @endif
                      </strong></span>
                
                </div>

             
                </div>
                <div class="col-sm-6">
                @endif
                {{--*/ $i++ /*--}}
                @endforeach
                <a class="expandable-trigger-more amenities_trigger" href="">
      <strong>+ {{ trans('messages.profile.more') }}</strong>
    </a>
            
        </div>

            </div>

                  <div class="row rooms_amenities_after" style="display:none;">

                 
            <div class="col-sm-6">
               
               {{--*/ $i = 1 /*--}}

               {{--*/ $count = round(count($amenities)/2) /*--}}

                @foreach($amenities as $all_amenities)

                
               
                @if($all_amenities->status != null)
                <div class="row-space-1">
                @else
                <div class="row-space-1 text-muted">
                @endif
                <i class="icon h3 icon-{{ $all_amenities->icon }}"></i>
                    &nbsp;
                  <span class="js-present-safety-feature"><strong>
                         @if($all_amenities->status == null)
                        <del> 
                        @endif
                        {{ $all_amenities->name }}
                        @if($all_amenities->status == null)
                        </del> 
                        @endif
                      </strong></span>
                
                </div>

             
                </div>
                <div class="col-sm-6">
                
                {{--*/ $i++ /*--}}
                @endforeach
            
        </div>

            </div>

        </div>
      </div>

    </div>

<hr>



    <div class="row">
      <div class="col-md-3">
            <div class="text-muted">
      {{ trans('messages.rooms.prices') }}
    </div>

      </div>
      <div class="col-md-9">
        <div class="row">
            <div class="col-md-6">
                <div>{{ trans('messages.rooms.extra_people') }}: <strong> 
                @if($result->rooms_price->guests !=0)
                
                 <span> {{ $result->rooms_price->currency->symbol }} {{ $result->rooms_price->additional_guest }}   / {{ trans('messages.rooms.night_after_guest',['count'=>$result->rooms_price->guests]) }}</span>

                @else
                <span >{{ trans('messages.rooms.no_charge') }}</span>
                @endif
                </strong></div>
                <div>{{ trans('messages.lys.weekly_price') }}: 
                @if($result->rooms_price->week != 0)
                <strong> <span id="weekly_price_string">{{ $result->rooms_price->currency->symbol }} {{ $result->rooms_price->week }}</span> /{{ trans('messages.rooms.week') }}</strong>
                @else
                <strong><span id="weekly_price_string">{{ $result->rooms_price->currency->symbol }} {{ number_format($result->rooms_price->night * 7) }}</span> /{{ trans('messages.rooms.week') }}</strong>
                
                @endif
                </div>
                
            </div>
            <div class="col-md-6">
                <div>{{ trans('messages.lys.monthly_price') }}:
                  @if($result->rooms_price->month != 0)
                <strong> <span id="weekly_price_string">{{ $result->rooms_price->currency->symbol }} {{ $result->rooms_price->month }}</span> /{{ trans('messages.rooms.month') }}</strong>
                @else
                <strong><span id="weekly_price_string">{{ $result->rooms_price->currency->symbol }} {{ number_format($result->rooms_price->night * 30) }}</span> /{{ trans('messages.rooms.month') }}</strong>
                
                @endif
                </div>

            </div>
        </div>
      </div>
    </div>

    <hr>


   @if($result->rooms_description->space !='' || $result->rooms_description->access !='' || $result->rooms_description->interaction !='' || $result->rooms_description->neighborhood_overview !='' || $result->rooms_description->transit || $result->rooms_description->notes) 
  <div class="row description">

    <div class="col-md-3 text-muted">
      {{ trans('messages.lys.description') }}
    </div>

    <div class="col-md-9 expandable expandable-trigger-more all_description">
    

      <div class="expandable-content expandable-content-long">

            @if($result->rooms_description->space)
            <p><strong>{{ trans('messages.lys.the_space') }}</strong></p>
            <p>{{ $result->rooms_description->space}}</p>
            @endif
            @if($result->rooms_description->access)
            <p><strong>{{ trans('messages.lys.guest_access') }}</strong></p>
            <p>{{ $result->rooms_description->access}} </p>
            @endif
            @if($result->rooms_description->interaction)
            <p><strong>{{ trans('messages.lys.interaction_with_guests') }}</strong></p>
            <p> {{ $result->rooms_description->interaction}}</p>
            @endif
            @if($result->rooms_description->neighborhood_overview)
            <p><strong>{{ trans('messages.lys.the_neighborhood') }}</strong></p>
            <p> {{ $result->rooms_description->neighborhood_overview}}</p>
            @endif
            @if($result->rooms_description->transit)
            <p><strong>{{ trans('messages.lys.getting_around') }}</strong></p>
            <p>{{ $result->rooms_description->transit}}</p>
            @endif
            @if($result->rooms_description->notes)
            <p><strong>{{ trans('messages.lys.other_things_note') }}</strong></p>
            <p>{{ $result->rooms_description->notes}}</p>
            @endif
     

      <div class="expandable-indicator"></div>
      </div>

          <a class="expandable-trigger-more" href="">
      <strong>+ {{ trans('messages.profile.more') }}</strong>
    </a>


    </div>
  </div>

  <hr>
@endif


@if($result->rooms_description->house_rules !='')
    <div class="row">
      <div class="col-md-3">
            <div class="text-muted">
      {{ trans('messages.lys.house_rules') }}
    </div>

      </div>
      <div class="col-md-9 expandable expandable-trigger-more expanded">
        <div class="expandable-content">
          <p>{{ $result->rooms_description->house_rules}}</p>
          <div class="expandable-indicator"></div>
        </div>
            <a class="expandable-trigger-more" href="#">
      <strong>+ {{ trans('messages.profile.more') }}</strong>
    </a>

      </div>
    </div>

    <hr>
@endif


  <div class="js-p3-safety-features-section">
  @if(count($safety_amenities) !=0)
    <div class="row">
      <div class="col-md-3">
            <div class="text-muted">
      {{ trans('messages.rooms.safety_features') }}
    </div>

      </div>
      <div class="col-md-9">
        <div class="js-no-safety-features-text hide">
          {{ trans('messages.account.none') }}
        </div>
        <div class="row">
            <div class="col-sm-6">
               
               {{--*/ $i = 1 /*--}}

               {{--*/ $count = round(count($safety_amenities)/2) /*--}}

                @foreach($safety_amenities as $row_safety)
              
                @if($row_safety->status != null)
                 <div class="row-space-1">
                @else
                <div class="row-space-1 text-muted">
                @endif
                <i class="icon h3 icon-{{ $row_safety->icon }}"></i>
                    &nbsp;
                  <span class="js-present-safety-feature"><strong>
                         @if($row_safety->status == null)
                        <del> 
                        @endif
                        {{ $row_safety->name }}
                        @if($row_safety->status == null)
                        </del> 
                        @endif
                      </strong></span>
                
                </div>

             
                </div>
                <div class="col-sm-6">
               
                {{--*/ $i++ /*--}}
                @endforeach
            
        </div>
      </div>
    </div>
   
  </div>
 <hr>
 @endif
 @if(count($religious_amenities) !=0)
  <div class="row">
      <div class="col-md-3">
            <div class="text-muted">
      {{ trans('messages.new.religious_accommadations') }}
    </div>

      </div>
      <div class="col-md-9">
        <div class="js-no-safety-features-text hide">
          {{ trans('messages.account.none') }}
        </div>
        <div class="row">
          @foreach(array_chunk($religious_amenities,ceil(count($religious_amenities)/2), true) as $religious_amenities_type_array)
            <div class="col-md-6">
              @foreach($religious_amenities_type_array as $k => $religious_amenity_type)
                <div class="space-2">
                  <h4 class="text-muted">{{$k}}</h4>
                  @foreach($religious_amenity_type as $religious_amenity)
                    <div class="row-space-1 @if($religious_amenity->status == null)text-muted @endif " style="margin-left:10px;">
                      <span class="js-present-safety-feature"><strong>
                        @if($religious_amenity->status == null)
                            <del> 
                        @endif
                            {{ $religious_amenity->name }} @if($religious_amenity->description != '')( {{ $religious_amenity->description }} )@endif @if(@$religious_amenities_extra_data[$religious_amenity->id]) - {{$religious_amenities_extra_data[$religious_amenity->id]}} @endif
                        @if($religious_amenity->status == null)
                            </del> 
                        @endif
                      </strong></span>  
                    </div> 
                  @endforeach
                </div>
              @endforeach 
            </div>
          @endforeach
        </div>
      </div> 
    </div>
   
  </div>
 <hr>
 @endif
  <div class="row">
    <div class="col-md-3">
          <div class="text-muted">
      {{ trans('messages.rooms.availability') }}
    </div>

    </div>
    <div class="col-md-9">
      <div class="row">
          <div class="col-md-6">
            <strong>1 {{ trans_choice('messages.rooms.night',1) }}</strong> {{ trans('messages.rooms.minimum_stay') }}
          </div>
        <div class="col-md-6">
          <a id="view-calendar" href="#"><strong>{{ trans('messages.rooms.view_calendar') }}</strong></a>
        </div>
      </div>
    </div>
  </div>



  
  <div id="photo-gallery" class="photo-grid row-space-4 row-space-top-4 hide-sm ">

        {{--*/ $i = 1 /*--}}

               <!-- {{--*/ $count = round(count($amenities)/2) /*--}} -->
               <!-- {{ count($rooms_photos)}} -->

                @foreach($rooms_photos as $row_photos)

                 @if(count($rooms_photos) == 1)
                              <div class="row featured-height">
                 <div class="col-12 row-full-height img-box1">
          <a class="photo-grid-photo photo-trigger gallery" style="background-image: url({{ url('images/rooms/'.$room_id.'/'.$row_photos->name) }})" href="{{ url('rooms/'.$result->id.'/slider') }}" data-index="1" data-lightbox-type="iframe">
  <img src="{{ 'images/rooms/'.$room_id.'/'.$row_photos->name }}" class="hide" alt="">
</a>
        </div></div>
               @endif


                @if($i == 2)
                <div class="row featured-height">
                 <div class="col-12 row-full-height img-box1">
          <a class="photo-grid-photo photo-trigger gallery" style="background-image: url({{ url('images/rooms/'.$room_id.'/'.$row_photos->name) }})" href="{{ url('rooms/'.$result->id.'/slider') }}" data-index="1" data-lightbox-type="iframe">
  <img src="{{ 'images/rooms/'.$room_id.'/'.$row_photos->name }}" class="hide" alt="">
</a>
        </div></div>
                @endif
                @if($i==3 && $i >2)               
        <div class="col-6 supporting-height img-box1">
          <a class="photo-grid-photo photo-trigger gallery" style="background-image: url({{ url('images/rooms/'.$room_id.'/'.$row_photos->name) }})" href="{{ url('rooms/'.$result->id.'/slider') }}" data-index="2" data-lightbox-type="iframe">
  <img src="{{ 'images/rooms/'.$room_id.'/'.$row_photos->name }}" class="hide" alt="">
</a>
        </div>
        @endif

        @if($i==4 && $i >3)

        <div class="col-6 supporting-height img-box1">
              <div class="media-photo media-photo-block row-full-height">
                <div class="media-cover media-cover-dark img-box1">
                  <a class="photo-grid-photo photo-trigger gallery"
   style="background-image: url({{ url('images/rooms/'.$room_id.'/'.$row_photos->name) }})"
   href="{{ url('rooms/'.$result->id.'/slider') }}"
   data-index="5" data-lightbox-type="iframe">
  <img src="url({{ url('images/rooms/'.$room_id.'/'.$row_photos->name) }})"
       class="hide"
       alt="Private shower/Longterm/Decent B&amp;B">
</a>
                </div>
                <a class="photo-trigger gallery"
                   href="{{ url('rooms/'.$result->id.'/slider') }}"
                   data-index="5" data-lightbox-type="iframe">
                  <div class="row row-table supporting-height">
                    <div class="col-6 col-middle text-center text-contrast">
                      <div class="h5">
                        {{ trans('messages.rooms.see_all') }} {{ round(count($rooms_photos))}} {{ trans_choice('messages.header.photo',2) }}
                      </div>
                    </div>
                  </div>
                </a></div></div>
                @endif
                {{--*/ $i++ /*--}}
                @endforeach
  </div>
</div>
        </div>
      </div>
    </div>
  </div>

  <div id="reviews" class="room-section webkit-render-fix">
    <div class="panel">
      <div class="page-container-responsive row-space-2">
        <div class="row">
          <div class="col-lg-8">   
            @if(!$result->reviews->count())
            <div class="review-content">
                <div class="panel-body">
                    <h4 class="row-space-4 text-center-sm">
                  {{ trans('messages.rooms.no_reviews_yet') }}
                </h4>
                @if($result->users->reviews->count())
                  <p>
                  {{ trans_choice('messages.rooms.review_other_properties', $result->users->reviews->count(), ['count'=>$result->users->reviews->count()]) }}
                  </p>
                  <a href="{{ url('users/show/'.$result->user_id) }}" class="btn">{{ trans('messages.rooms.view_other_reviews') }}</a>
                @endif
              </div>
            </div>
            @else
            <div class="review-wrapper">
            <div>
            <div class="row space-2 space-top-8 row-table">
            <div class="review-header col-md-8">
            <div class="va-container va-container-v va-container-h">
            <div class="va-bottom review-header-text">
            <h4 class="text-center-sm col-middle">
            <span>{{ $result->reviews->count() }} {{ trans_choice('messages.header.review',$result->reviews->count()) }}</span>
            <div style="display:inline-block;">
            <div class="star-rating-wrapper">
            {!! $result->overall_star_rating !!}
            </div>
            </div>
            </h4>
            </div>
            </div>
            </div>
            </div>
            <div>
            <hr>
            </div>
            </div>
            <div class="review-main">
            <div class="review-inner space-top-2 space-2">
            <div class="row">
            <div class="col-lg-3 show-lg">
            <div class="text-muted">
            <span>{{ trans('messages.lys.summary') }}</span>
            </div>
            </div>
            <div class="col-lg-9">
            <div class="row">
            <div class="col-lg-6">
            <div>
            <div class="pull-right">
            <div class="star-rating-wrapper">
            {!! $result->accuracy_star_rating !!}
            <span> </span>
            </div>
            </div>
            <strong>{{ trans('messages.reviews.accuracy') }}</strong>
            </div>
            <div>
            <div class="pull-right">
            <div class="star-rating-wrapper">
            {!! $result->communication_star_rating !!}
            <span> </span>
            </div>
            </div>
            <strong>{{ trans('messages.reviews.communication') }}</strong>
            </div>
            <div>
            <div class="pull-right">
            <div class="star-rating-wrapper">
            {!! $result->cleanliness_star_rating !!}
            <span> </span>
            </div>
            </div>
            <strong>{{ trans('messages.reviews.cleanliness') }}</strong>
            </div>
            </div>
            <div class="col-lg-6">
            <div>
            <div class="pull-right">
            <div class="star-rating-wrapper">
            {!! $result->location_star_rating !!}
            <span> </span>
            </div>
            </div>
            <strong>{{ trans('messages.reviews.location') }}</strong>
            </div>
            <div>
            <div class="pull-right">
            <div class="star-rating-wrapper">
            {!! $result->checkin_star_rating !!}
            <span> </span>
            </div>
            </div>
            <strong>{{ trans('messages.home.checkin') }}</strong>
            </div>
            <div>
            <div class="pull-right">
            <div class="star-rating-wrapper">
            {!! $result->value_star_rating !!}
            <span> </span>
            </div>
            </div>
            <strong>{{ trans('messages.reviews.value') }}</strong>
            </div>
            </div>
            </div>
            </div>
            </div>
            </div>
            <div class="review-content">
            <div class="panel-body">
            @foreach($result->reviews as $row_review)
            <div>
            <div class="row review">
            <div class="col-md-3 text-center space-2">
            <div class="media-photo-badge">
            <a class="media-photo media-round" href="{{ url('users/show/'.$row_review->user_from) }}">
            <span  title="{{ $row_review->users_from->first_name }}" style="width:67px; height:67px; background-image:url({{ $row_review->users_from->profile_picture->src }})" data-pin-nopin="true"></span>
            </a>
            </div>
            <div class="name">
            <a target="_blank" class="text-muted link-reset" href="{{ url('users/show/'.$row_review->user_from) }}">{{ $row_review->users_from->first_name }}</a>
            </div>
            </div>
            <div class="col-md-9">
            <div class="space-2">
            <div class="review-text" data-review-id="{{ $row_review->id }}">
            <div class="react-expandable expanded">
            <div class="expandable-content" tabindex="-1" style="">
            <p>{{ $row_review->comments }}</p>
            </div>
            </div>
            </div>
            <div class="text-muted review-subtext">
            <div class="review-translation-language">
            </div>
            <div class="va-container va-container-h va-container-v">
            <div class="va-middle">
            <span class="date" style="display:inline-block;">{{ $row_review->date_fy }}</span>
            </div>
            </div>
            </div>
            </div>
            <span>
            </span>
            </div>
            <div class="row space-2">
            <div class="col-md-9 col-md-push-3">
            <hr>
            </div>
            </div>
            </div>
            </div>
            @endforeach
            @if($result->users->reviews->count() - $result->reviews->count())
            <div class="row row-space-top-2">
            <div class="col-lg-9 col-offset-3">
            <p>
            <span>{{ trans_choice('messages.rooms.review_other_properties', $result->users->reviews->count() - $result->reviews->count(), ['count'=>$result->users->reviews->count() - $result->reviews->count()]) }}</span>
            </p>
            <a target="blank" class="btn" href="{{ url('users/show/'.$result->user_id) }}">
            <span>{{ trans('messages.rooms.view_other_reviews') }}</span>
            </a>
            </div>
            </div>
            @endif
            </div>
            </div>
            </div>
            </div>
            @endif
          </div>
        </div>
      </div>
    </div>
  </div>

  <div id="host-profile" class="room-section webkit-render-fix">
    <div class="page-container-responsive space-top-8 space-8">
      <div class="row">
        <div class="col-lg-8">
    <h4 class="row-space-4 text-center-sm">
      {{ trans('messages.rooms.about_host') }}, {{ $result->users->first_name }}
    </h4>
<div class="row">
  <div class="col-md-3 text-center">
    <a href="{{ url('users/show/'.$result->user_id) }}" class="media-photo media-round prof_pic"><span  data-pin-nopin="true" style="width:90px; height:90px; background-image:url({{ $result->users->profile_picture->src }});" title="{{ $result->users->first_name }}" ></span></a>
  </div>
  <div class="col-md-9">
    <div class="row row-condensed space-2">
      <div class="col-md-6 text-center-sm">
        @if($result->users->live)
        <div>
          {{ $result->users->live }}
        </div>
        @endif
          <div>
            {{ trans('messages.profile.member_since') }} {{ $result->users->since }}
          </div>
      </div>
    </div>
    @if(Auth::user()->check())
    @if(Auth::user()->user()->id != $result->user_id)
    <div id="contact_wrapper">
      <button id="host-profile-contact-btn" class="btn btn-small btn-primary">
        {{ trans('messages.rooms.contact_host') }}
      </button>
    </div>
    @endif
    @endif
  </div>
</div>

  <hr class="space-4 space-top-4">
  <div class="row">
    <div class="col-md-3">
          <div class="text-muted">
      {{ trans('messages.rooms.trust') }}
    </div>
    </div>
    <div class="col-md-9">
      <div class="row row-condensed">
          <div class="col-sm-4 col-md-3">
            <a class="link-reset" rel="nofollow" href="{{ url('users/show/'.$result->user_id) }}#reviews">
  <div class="text-center text-wrap">
      <div class="badge-pill h3">
        <span class="badge-pill-count">{{ $result->users->reviews->count() }}</span>
      </div>
    <div class="row-space-top-1">{{ trans_choice('messages.header.review',2) }}</div>
  </div>
</a>
          </div>
      </div>
    </div>
  </div>
        </div>
      </div>
    </div>
  </div>
  
<div id="neighborhood" class="room-section">
<div class="page-container-responsive" data-reactid=".2" style="position:relative;">
  <div class="panel location-panel">
    <div id="map" data-lat="{{ $result->rooms_address->latitude }}" data-lng="{{ $result->rooms_address->longitude }}" data-address="{{ $result->rooms_address->state }}+{{ $result->rooms_address->country }}"> </div>
<ul id="guidebook-recommendations" class="hide">
  <li class="user-image">
    <a href=""><img alt="Jeya" data-pin-nopin="true" height="90" src="" title="Jeya" width="90"></a>
  </li>
</ul>

    <div id="hover-card" class="panel">
  <div class="panel-body">
    <div class="text-center">
      {{ trans('messages.rooms.listing_location') }}
    </div>
    <div class="text-center">
        <span>
          <a href="" class="text-muted"><span>{{$result->rooms_address->state}},</span></a>
        </span>
        <span>
          <a href="" class="text-muted"><span>{{$result->rooms_address->country_name}}</span></a>
        </span>
    </div>
  </div>
</div>

  </div>
  </div>
</div>


      <div id="similar-listings" class="row-space-top-4">

<div  id="slider-next" class="" data-reactid=".2.0.1.2">
<i class="" data-reactid=".2.0.1.2.0"></i>
</div>
<div  id="slider-prev" class="" data-reactid=".2.0.1.2">
<i class="" data-reactid=".2.0.1.2.0"></i>
</div>
@if(count($similar)!= 0)

  <div class="page-container-responsive">
        <h4 class="row-space-4 text-center-sm">
      {{ trans('messages.rooms.similar_listings') }}
    </h4>
<div class="slider1">
@foreach($similar as $row_similar)
<div class="col-md-4">
<div class="listing"> <div class="panel-image listing-img">
    <a href="{{ url('rooms/'.$row_similar->id) }}" target="_self" class="media-photo media-cover" target="_blank">
      <div class="listing-img-container media-cover text-center slide">
        {!! Html::image('images/'.$row_similar->photo_name, $row_similar->name, '') !!}
      </div>
    </a>
	
    <a href="{{ url('rooms/'.$row_similar->id) }}" target="_self" class="link-reset panel-overlay-bottom-left panel-overlay-label panel-overlay-listing-label" target="_blank">
      <div> 
        <sup class="h6 text-contrast">{{ $row_similar->rooms_price->currency->symbol }}</sup>
        <span class="h3 text-contrast price-amount">{{ $row_similar->rooms_price->night }}</span>
        <sup class="h6 text-contrast"></sup>
		 @if($row_similar->koshire =='1')
<span data-original-title="This house is Kosher" data-toggle="tooltip" class="h3 icon-k">K</span>
@endif
    @if($row_similar->booking_type == 'instant_book')
    <span aria-label="Book Instantly" data-behavior="tooltip" class="h3 icon-beach">
      <i class="icon icon-instant-book icon-flush-sides"></i>
    </span>
    @endif
      </div>
    </a>
  </div>

  <div class="panel-body panel-card-section">
    <div class="media">
        <a href="{{ url('users/show/'.$result->user_id) }}" class="media-photo-badge pull-right card-profile-picture card-profile-picture-offset">
          <div class="media-photo media-round">
            <span style="background-image:url({{ $row_similar->users->profile_picture->src }}); height:56px; width:56px;"></span>
          </div>
        </a>
      <a href="{{ url('rooms/'.$row_similar->id) }}" target="_self" class="text-normal">
        <h3 title="{{ $row_similar->name }} " itemprop="name" class="h5 listing-name text-truncate row-space-top-1">
          {{ $row_similar->name }} 
        </h3>
      </a>
      <div itemprop="description" class="text-muted listing-location text-truncate">{{ $row_similar->room_type_name }}  — {{ number_format($row_similar->distance,2) }} {{ trans('messages.rooms.km_away') }}
</div>
    </div>
  </div>
</div>
          </div>
@endforeach
</div>
</div>
  @endif
</div>
</div>
</div>
</div>
</div>



<div><div>
<span>
<div class="modal-container modal-transitions contact-modal hide">
<div class="modal-table">
<div class="modal-cell">
<div class="modal-content">
<a data-behavior="modal-close" class="modal-close" href="#" style="font-size:3em;"></a>
<div id="contact-host-panel" class="">
<div id="compose-message" class="contact-host-panel panel-dark">
<div class="row">
<div class="host-questions-panel panel panel-dark col-md-4">
<div class="panel-body">
<div class="text-center">
<div class="media-photo media-round"  style="width:129px; height:128px;">
<div class="media-photo-badge" style="width:126px; height:126px;">
<a href="{{ url() }}/users/show/{{ $result->user_id }}" class="media-photo media-round">
<span data-pin-nopin="true" style="width:120px; height:120px; background-image:url({{ $result->users->profile_picture->src }})" title="{{ $result->users->first_name }}"></span>
</a>
</div>
</div>
</div>
<div>
<h5>
<span>{{ trans('messages.rooms.send_a_message',['first_name'=>$result->users->first_name]) }}</span>
</h5>
<p>
<span>{{ trans('messages.rooms.share_following') }}:</span>
</p>
<ul>
<li>
<span>{{ trans('messages.rooms.tell_about_yourself',['first_name'=>$result->users->first_name]) }}</span>
</li>
<li>
<span>{{ trans('messages.rooms.what_brings_you',['city'=>$result->rooms_address->city]) }}?</span>
</li>
<li>
<span>{{ trans('messages.rooms.love_about_listing') }}!</span>
</li>
</ul>
</div>
</div>
</div>
<div class="guest-message-panel panel col-md-8">
<div class="alert alert-with-icon alert-info error-block row-space-4 alert-header panel-header contacted-before hide">
<i class="icon alert-icon icon-comment">
</i>
<div class="not-available">
<span>{{ trans('messages.rooms.dates_arenot_available') }}</span>
</div>
<div class="other">
<strong>
</strong>
</div>
</div>
<div class="panel-body">
<form id="message_form" class="contact-host-panel" action="{{ url() }}/users/ask_question/{{ $result->id }}" method="POST">
{!! Form::token() !!}
<h5>
<span>{{ trans('messages.rooms.when_you_traveling') }}?</span>
</h5>
<div class="row-space-4 clearfix">
<div>
<div class="col-4 input-col">
<label class="screen-reader-only">{{ trans('messages.home.checkin') }}</label>
<input value="" name="message_checkin" id="message_checkin" class="checkin text-center ui-datepicker-target" placeholder="{{ trans('messages.home.checkin') }}" type="text" required>
</div>
<div class="col-4 input-col">
<label class="screen-reader-only">{{ trans('messages.home.checkout') }}</label>
<input value="" name="message_checkout" id="message_checkout" class="checkout text-center ui-datepicker-target" placeholder="{{ trans('messages.home.checkout') }}" type="text" required>
</div>
</div>
<div class="col-4 input-col">
<div class="select select-block">
<select class="text-center" name="message_guests" id="message_guests">
@for($i=1;$i<= $result->accommodates;$i++)
  <option value="{{ $i }}">{{ $i }} {{ trans_choice('messages.home.guest',$i) }}</option>
@endfor
</select>
</div>
</div>
</div>
<div class="row">
<div class="col-12">
<div class="message-panel tooltip tooltip-fixed tooltip-bottom-left row-space-4">
<div class="panel-body">
<textarea class="focus-on-active" name="question" placeholder="{{ trans('messages.rooms.start_your_msg') }}..."></textarea>
</div>
</div>
</div>
</div>
<noscript>
</noscript>
<div class="row">
<div class="col-4">
<div class="media-photo media-round">
<div class="media-photo-badge">
<a href="{{ url() }}/users/show/{{ (Auth::user()->check()) ? Auth::user()->user()->id : '' }}" class="media-photo media-round">
<span data-pin-nopin="true" style="width:68px; height:68px; background-image:url({{ (Auth::user()->check()) ? Auth::user()->user()->profile_picture->src : '' }})" title="{{ (Auth::user()->check()) ? Auth::user()->user()->first_name : '' }}"></span>
</a>
</div>
</div>
</div>
<div class="col-7 col-offset-1">
<button type="submit" class="btn btn-block btn-large btn-primary row-space-top-2">
<span>{{ trans('messages.your_reservations.send_message') }}</span>
</button>
</div>
</div>
<input name="message_save" value="1" type="hidden">
</form>
</div>
</div>
</div>
</div>
<div class="contact-host-panel hide">
<div class="panel">
<div class="panel-header panel-header-message-sent text-center">
<strong>{{ trans('messages.rooms.message_sent') }}!</strong>
</div>
<div class="panel-body text-center">
<div class="row">
<p class="col-10 col-center row-space-top-4 text-lead">
<span>{{ trans('messages.rooms.keep_contacting_other') }}</span>
</p>
</div>
<div class="row">
<div class="col-6 col-center row-space-top-4 row-space-2">
<a href="#" class="btn btn-block btn-primary confirmation btn-large text-wrap">{{ trans('messages.rooms.ok') }}</a>
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
 </span>
 </div>
 </div>

<div class="modal-container modal-transitions wl-modal__modal hide">
<div class="modal-table">
<div class="modal-cell">
<div class="modal-content">
<div class="wl-modal">
<div class="row row-margin-zero">
<div class="hide-sm hide-md col-lg-7 wl-modal__col">
<div class="media-cover media-cover-dark background-cover background-listing-img" style="background-image:url({{ url('images/'.$result->photo_name) }});">
</div>
<div class="panel-overlay-top-left text-contrast wl-modal-listing-tabbed">
<div class="va-container media">
<span class="pull-left host-profile-img media-photo media-round space-2" style="width:67px; height:67px; background-image:url({{ $result->users->profile_picture->src }})"></span>
<div class="media-body va-middle">
<div class="h4 space-1 wl-modal-listing__name">{{ $result->name }}</div>
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
<span class="wl-modal-listing__address wl-modal-listing__text">{{ $result->rooms_address->city }}</span>
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
<input type="text" class="wl-modal-footer__text wl-modal-footer__input" value="{{ $result->rooms_address->city }}" placeholder="Name Your Wish List">
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
{!! Html::script('js/infobubble.js') !!}
@stop