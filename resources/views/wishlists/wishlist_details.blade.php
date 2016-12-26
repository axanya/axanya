@extends('template')
@section('main')
<main id="site-content" role="main" ng-controller="wishlists">

<div class="app_view">
  @include('common.wishlists_subheader')
  
  <div class="wishlists-container page-container row-space-top-4">
  <div class="show_view">
  @if($owner)
  <div class="social-share-cta alert text-center row-space-top-2 row-space-6">
    <h5 class="text-center">
      
        {{ trans('messages.rooms.share') }} ‘{{ $result[0]->name }}’ {{ trans('messages.wishlist.with_friends') }}!
      
      <span class="social-share-widget-container icon-kazan text-kazan"><div class="social-share-widget"><span class="share-title">{{ trans('messages.rooms.share') }}:</span>
<span class="share-triggers"><a class="share-btn share-facebook-btn link-icon" href="http://www.facebook.com/sharer.php?u={{ url('wishlists/'.$result[0]->id) }}"><i class="icon icon-facebook h4"></i>
</a><a class="share-btn share-twitter-btn link-icon" href="https://twitter.com/intent/tweet?source=tweetbutton&amp;text=Wow.+I+love+this+Wish+List+on+%40{{ $site_name }}+%23lovemywishlist&amp;url={{ url('wishlists/'.$result[0]->id) }}&amp;original_referer={{ url('wishlists/'.$result[0]->id) }}"><i class="icon icon-twitter h4"></i>
</a><a href="javascript:void();" class="share-btn share-envelope-btn link-icon"><i class="icon icon-envelope h4"></i>
</a></span>
</div></span>
    </h5>
  </div>
@endif

<div class="top-bar row row-table row-space-4">
  <div class="col-6 col-middle">
    
      <div class="media">
  <a href="{{ url() }}/users/show/{{ $result[0]->user_id }}" class="pull-left media-photo media-round" title="{{ $result[0]->users->first_name }}">
     
       <img src="{{ $result[0]->profile_picture->src }}" alt="{{ $result[0]->users->first_name }}" width="50" height="50">
     
  </a>
  <div class="media-body">
    <div class="row row-table wishlist-header-text">
      <div class="col-12 col-middle">
        <a href="{{ url() }}/users/{{ $result[0]->user_id }}/wishlists" class="h4">
          {{ $result[0]->users->first_name }}’s {{ trans_choice('messages.header.wishlist', 2) }}
        </a>
        <div>
        
          <span>{{ $result[0]->name }}:</span>
          <strong id="wishlist_count">{{ $result[0]->saved_wishlists->count() }}</strong>
            @if($owner)
              <a href="javascript:void();" class="edit-button">{{ trans('messages.reviews.edit') }}</a>
            @endif
        </div>
      </div>
    </div>
  </div>
</div>

  </div>
  <div class="col-6 col-middle text-right">
    
    <div class="btn-group view-btn-group">
      <button class="btn" data-view="list" disabled="disabled" id="list">{{ trans('messages.wishlist.list_view') }}</button>
      <button class="btn" data-view="map" id="map">{{ trans('messages.wishlist.map_view') }}</button>
    </div>
  </div>
</div>

<ul class="results-list list-unstyled">
@foreach($result[0]->saved_wishlists as $row)
<li class="row listing row-space-2 row-space-top-2" id="li_{{ $row->room_id }}">
<label class="col-12" for="hosting_id_{{ $row->room_id }}">
  <div class="row">
    <div class="col-3">
      <div class="slideshow-container">
      <div class="listing_slideshow_thumb_view">
      <div class="listing-slideshow">
  <img src="{{ url('images/'.$row->rooms->photo_name) }}" alt="" width="216" height="144" style="position: absolute; top: 0px; left: 0px; z-index: 1; opacity: 1; display: inline;" id="wishlist_image_{{ $row->room_id }}" rooms_image="">
</div>

<ul class="photo-dots-list list-layout">
@for($i=0; $i<count($row->rooms_photos); $i++)
<!-- <li class="photo-dots-list-item dot {{ ($i==0) ? 'dot-dark-gray' : '' }}"> </li> -->
@endfor
</ul>
<a href="javascript:void();" data-prevent-default="" class="photo-paginate prev marker_slider" data-room_id="{{ $row->room_id }}"><i></i></a>
<a href="javascript:void();" data-prevent-default="" class="photo-paginate next marker_slider" data-room_id="{{ $row->room_id }}"><i></i></a>
</div></div>
    </div>
    <div class="col-9">
      <div class="room-info row row-space-2">
        <div class="col-9">
          <h2 class="h3 row-space-1"><a href="{{ url() }}/rooms/{{ $row->room_id }}">{{ $row->rooms->name }}</a></h2>

          <p class="text-muted row-space-1">
            {{ $row->rooms->rooms_address->city }}
          </p>
          <ul class="list-unstyled inline spaced text-muted">
            <li>{{ $row->rooms->room_type_name }}</li>
            <li>{{ $row->rooms->bed_type_name }}</li>
            <li>Sleeps {{ $row->rooms->accommodates }}</li>
          </ul>
        </div>

        <div class="col-3">
          <div class="text-center pull-right">
            <sup class="h5">{{ $row->rooms_price->currency->symbol }}</sup>
            <span class="h2 price-amount">{{ $row->rooms_price->night }}</span>
            <sup class="h5"></sup>
            <br>
            <span class="text-muted">{{ trans('messages.wishlist.per_night') }}</span>
          </div>
        </div>
      </div>
      @if($owner)
      <div class="row">
        <div class="media col-8">
          
            <img src="{{ $row->profile_picture->src }}" alt="{{ $row->users->first_name }}" class="pull-left img-round" width="28" height="28">
          
          <form id="add-note-form" class="note-container media-body text-right" data-room_id="{{ $row->room_id }}">
            <textarea name="note" id="note_{{ $row->room_id }}" class="row-space-2" placeholder="{{ trans('messages.wishlist.add_note') }}">{{ $row->note }}</textarea>
            
            <button type="submit" class="btn add-note">
              {{ trans('messages.wishlist.save_note') }}
            </button>
          </form>
        </div>
        <div class="col-4">
          <div class="btn-group pull-right wish_list_button_container">
            @if($owner)
            <a class="btn gray remove_listing_button" data-room_id="{{ $row->room_id }}">
              <span class="icon icon-remove"></span>
              {{ trans('messages.account.remove') }}
            </a>
            @endif
          </div>
        </div>
      </div>
      @endif
    </div>
  </div>
</label>
</li>
@endforeach
</ul>
<div data-map-container="" class="row-space-8 results-map" id="results_map">
  
</div>
</div>
<!-- Edit -->
<div class="edit_view row-space-8" style="display:none;">
<div class="row row-space-4">
  <div class="col-12">
    <div class="media">
     
      <a href="{{ url() }}/users/{{ $result[0]->user_id }}/wishlists" class="media-photo media-round pull-left" title="{{ $result[0]->users->first_name }}">
         <img src="{{ url($result[0]->users->profile_picture->src) }}" alt="{{ $result[0]->users->first_name }}" width="50" height="50">
      </a>
     
      <div class="media-body">
        <div class="row row-table wishlist-header-text">
          <div class="col-12 col-middle">
            <span>{{ trans('messages.reviews.edit') }} {{ trans_choice('messages.header.wishlist', 1) }}:</span>
            <strong></strong>
          </div>
        </div>
      </div>
      </div>
    </div>
  </div>



<div class="wishlists-body row">
  <div class="col-4">
    <div class="panel">
  <a href="{{ url() }}/wishlists/{{ $result[0]->id }}" class="panel-image media-photo media-link media-photo-block wishlist-unit">
    <div class="media-cover media-cover-dark wishlist-bg-img" style="background-image:url('{{ url('images'.'/'.@$result[0]->saved_wishlists[0]->rooms->photo_name) }}');">
    </div>

    <div class="row row-table row-full-height">
      <div class="col-12 col-middle text-center text-contrast">
        <div class="panel-body">
        @if($result[0]->privacy)
          <i class="icon icon-lock h3"></i>
        @endif
          <div class="h2"><strong>{{ $result[0]->name }}</strong></div>
        </div>
        <div class="btn btn-guest">{{ $result[0]->rooms_count }} {{ trans_choice('messages.wishlist.listing', $result[0]->rooms_count) }}</div>
      </div>
    </div>
  </a>
</div>

  </div>
  <div class="col-8">
    <form action="{{ url('edit_wishlist/'.$result[0]->id) }}" method="post">
    {!! Form::token() !!}
      <div class="row row-space-2">
        <div class="col-12">
          <div class="panel">
            <div class="panel-body">
              <div class="row">
                <div class="col-8">
                  <label for="wish-list-name">{{ trans('messages.wishlist.title') }}</label>
                  <input id="wish-list-name" name="name" value="{{ $result[0]->name }}" type="text">
                </div>
                <div class="col-4">
                  <label for="wishlist-edit-privacy-setting">{{ trans('messages.wishlist.privacy_settings') }}</label>
                  <div class="media">
                    <div class="media-body">
                      <div class="select select-block" id="wishlist-edit-privacy-setting">
                        <select name="privacy">
                          <option value="0" {{ ($result[0]->privacy == 0) ? 'selected' : '' }}>
                            {{ trans('messages.wishlist.everyone') }}
                          </option>
                          <option value="1" {{ ($result[0]->privacy == 1) ? 'selected' : '' }}>
                            {{ trans('messages.wishlist.only_me') }}
                          </option>
                        </select>
                      </div>
                    </div>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
      <div class="row">
        <div class="col-12">
          <button type="submit" class="btn btn-primary">{{ trans('messages.wishlist.save_changes') }}</button>
          <button class="btn cancel">{{ trans('messages.your_reservations.cancel') }}</button>
          <a href="javascript:void();" class="delete pull-right">{{ trans('messages.lys.delete') }} {{ trans_choice('messages.header.wishlist', 1) }}</a>
        </div>
      </div>
    </form>
  </div>
</div>

</div>

</div>
  <div class="wl-preload" style="display: none;">
    <div class="page-container">
      <div class="row">
        <div class="col-12">
          <p class="wl-loading">{{ trans('messages.wishlist.loading') }}…</p>
        </div>
      </div>
    </div>
  </div>
  <div class="loading-indicator wishlist-loading-indicator loading"></div>
</div>

<div class="modal-container modal-transitions invite-collaborators-modal" style="display:none;">
<div class="modal-table">
<div class="modal-cell">
<div class="modal-content">
<header class="panel-header">
<a href="javascript:void();" class="modal-close">
</a>
<span>{{ trans('messages.rooms.share') }} {{ trans_choice('messages.header.wishlist', 1) }}</span>
</header>
<form class="email-collaborators" action="{{ url() }}/share_email/{{ $result[0]->id }}" method="post">
{!! Form::token() !!}
<section class="panel-body">
<label class="space-top-2" for="message">{{ trans('messages.wishlist.send_to') }}:</label>
<div class="email-input-typeahead-container" data-typeahead-type="recent" data-email-tagging="true">
<ul class="input-tag-list list-unstyled list-inline clearfix">
<li class="input-list-item pull-left">
<span class="twitter-typeahead" style="position: relative; display: inline-block;">
<input type="text" class="typeahead tt-input" autocomplete="none" name="email" placeholder="{{ trans('messages.wishlist.email_address_placeholder') }}" spellcheck="false" dir="auto" style="position: relative; vertical-align: top;">
<div class="tt-menu" style="position: absolute; top: 100%; z-index: 100; display: none; left: -13px; width: 480px;">
<div class="tt-dataset tt-dataset-email-typeahead">
</div>
</div>
</span>
</li>
</ul>
</div>
<label class="space-top-2" for="message">{{ trans('messages.wishlist.write_message') }}:</label>
<textarea name="message" rows="3">{{ trans('messages.wishlist.checkout_places') }} {{ $site_name }}!</textarea>
</section>
<footer class="panel-footer">
<button type="submit" class="btn btn-primary">{{ trans('messages.wishlist.send_email') }}</button>
</footer>
</form>
</div>
</div>
</div>
</div>
    </main>
@stop
@push('scripts')
<script type="text/javascript">
var locations = {!! $result[0]->saved_wishlists !!};
var wishlist_id = {!! $result[0]->id !!}
</script>
@stop