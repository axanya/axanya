@extends('template')
@section('main')
<main id="site-content" role="main" ng-controller="wishlists">

<div class="app_view">

  @include('common.wishlists_subheader')

  <div class="wishlists-container page-container row-space-top-4">
  <div class="index_view">

  <div class="row wishlists-body">
@if($result->count() == 0)
<h2 class="text-center" style="margin-top:10%">
{{ trans('messages.search.no_results_found') }}!
</h2>
@endif
    @foreach($result as $row)
      <div class="col-4 row-space-4">
      <div class="panel">
  <a href="{{ url('wishlists/'.$row->id) }}" class="panel-image media-photo media-link media-photo-block wishlist-unit">
    <div class="media-cover media-cover-dark wishlist-bg-img" style="background-image:url('{{ url('images/'.@$row->saved_wishlists[0]->rooms->photo_name) }}');">
    </div>
    <div class="row row-table row-full-height">
      <div class="col-12 col-middle text-center text-contrast">
        <div class="panel-body">
          <div class="h2"><strong>{{ $row->name }}</strong></div>
        </div>
        <div class="btn btn-guest">{{ $row->all_rooms_count }} {{ trans_choice('messages.wishlist.listing', $row->rooms_count) }}</div>
      </div>
    </div>
  </a>
</div>
      </div>
    @endforeach
  </div>
</div>
</div>
</div>
</div>
</div>
</main>
@stop