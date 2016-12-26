@extends('template')
@section('main')
<main id="site-content" role="main" ng-controller="wishlists">

<div class="app_view">

  @include('common.wishlists_subheader')

  <div class="wishlists-container page-container row-space-top-4">
  <div class="index_view">
  <div class="top-bar row row-table row-space-4">
    <div class="col-6 col-middle">
        <div class="media">
  <a href="{{ url() }}/users/show/{{ $result[0]->user_id }}" class="pull-left media-photo media-round" title="{{ $result[0]->users->first_name }}">
    <img src="{{ $result[0]->profile_picture->src }}" alt="{{ $result[0]->users->first_name }}" width="50" height="50">
  </a>
  <div class="media-body">
    <div class="row row-table wishlist-header-text">
      <div class="col-12 col-middle">
        <a href="" class="h4">
          {{ $result[0]->users->first_name }}’s {{ trans_choice('messages.header.wishlist', 2) }}
        </a>
        <div>
          <span class="text-muted">{{ trans_choice('messages.wishlist.wishlist', $result->count()) }}:</span>
          <strong>{{ $result->count() }}</strong>
        </div>
      </div>
    </div>
  </div>
</div>
    </div>
    <div class="col-6 col-middle text-right">
    @if($owner)
      <div class="btn-group social-share-widget-container hide"></div>
      <div class="btn-group">
        <button class="btn pull-right create">{{ trans('messages.wishlist.create_new_wishlist') }}</button>
      </div>
      @endif
    </div>
  </div>

  <div class="row wishlists-body">
    @foreach($result as $row)
      <div class="col-4 row-space-4">
      <div class="panel">
  <a href="{{ url('wishlists/'.$row->id) }}" class="panel-image media-photo media-link media-photo-block wishlist-unit">
    <div class="media-cover media-cover-dark wishlist-bg-img" style="background-image:url('{{ url('images/'.@$row->saved_wishlists[0]->rooms->photo_name) }}');">
    </div>
    <div class="row row-table row-full-height">
      <div class="col-12 col-middle text-center text-contrast">
        <div class="panel-body">
        @if($row->privacy)
          <i class="icon icon-lock h3"></i>
        @endif
          <div class="h2"><strong>{{ $row->name }}</strong></div>
        </div>
        <div class="btn btn-guest">{{ $row->rooms_count }} {{ trans_choice('messages.wishlist.listing', $row->rooms_count) }}</div>
      </div>
    </div>
  </a>
</div>
      </div>
    @endforeach
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
<div class="modal-container modal-transitions hide">
<div class="modal-table">
<div class="modal-cell">
<div class="modal-content" tabindex="-1">
<form action="{{ url('create_new_wishlist') }}" method="post">
{!! Form::token() !!}
<header class="panel-header">
<span>{{ trans('messages.wishlist.create_new_wishlist') }}</span>
</header>
<div class="loading1">
<div class="panel-body">
<label for="wishlistName">
<span>{{ trans('messages.wishlist.wish_list_name') }}</span>
</label>
<input type="text" value="" id="wishlistName" name="name" required>
<label class="row-space-top-2">
<span>{{ trans('messages.wishlist.privacy_settings') }}</span>
</label>
<div class="row row-table">
<div class="col-4 col-middle">
<div class="select select-block" id="wishlist-edit-privacy-setting">
<select name="privacy">
<option selected="" value="0">{{ trans('messages.wishlist.everyone') }}</option>
<option value="1">{{ trans('messages.wishlist.only_me') }}</option>
</select>
</div>
</div>
<div class="col-8 col-middle">
</div>
</div>
</div>
<div class="panel-footer">
<button class="btn save btn-primary" type="submit">
<span>{{ trans('messages.profile.save') }}</span>
</button>
<button class="btn cancel">
<span>{{ trans('messages.your_reservations.cancel') }}</span>
</button>
</form>
</div>
</div>
</div>
</div>
</div>
</div>
</main>
@stop