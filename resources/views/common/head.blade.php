<!DOCTYPE html>
<html class="" lang="en-IN" xmlns:fb="https://ogp.me/ns/fb#">
<head>
  <meta charset="utf-8">
  <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
  <link rel="dns-prefetch" href="https://maps.googleapis.com/">
  <link rel="dns-prefetch" href="https://maps.gstatic.com/">
  <link rel="dns-prefetch" href="https://mts0.googleapis.com/">
  <link rel="dns-prefetch" href="https://mts1.googleapis.com/">
  <link rel="shortcut icon" href="{{ $favicon }}">

  <!--[if IE 8]>
    {!! Html::style('css/common_ie8.css') !!}
  <![endif]-->
  <!--[if !(IE 8)]><!-->
    {!! Html::style('css/common.css') !!}
    {!! Html::style('css/map-icons.min.css') !!}
    {!! Html::style('pcss?css=css/dynamic.css') !!}
  <!--<![endif]-->
	{!! Html::style('css/animate.css') !!}

  <!--[if lt IE 9]>
    {!! Html::style('css/airglyphs-ie8.css') !!}
  <![endif]-->

  @if (isset($exception))
    @if ($exception->getStatusCode()  == '404')
      {!! Html::style('css/error_pages_pretzel.css') !!}
    @endif
  @endif

  @if (!isset($exception))

    @if (Route::current()->uri() == 'signup_action')
    	{!! Html::style('css/signinup.css') !!}
    @endif

    @if (Route::current()->uri() == '/')
    	{!! Html::style('css/main.css') !!}
      {!! Html::style('css/home_blake.css') !!}
    @endif

    @if (Route::current()->uri() == 'dashboard')
      {!! Html::style('css/host_dashboard.css') !!}
      {!! Html::style('css/dashboard.css') !!}
    @endif

    @if (Route::current()->uri() == 'rooms/new')
      {!! Html::style('css/new.css') !!}
    @endif

    @if (Route::current()->uri() == 'inbox')
      {!! Html::style('css/threads.css') !!}
    @endif

    @if (Route::current()->uri() == 'reservation/change')
      {!! Html::style('css/alterations.css') !!}
      {!! Html::style('css/policy_timeline_v2.css') !!}
    @endif

    @if (Route::current()->uri() == 'alterations/{code}')
      {!! Html::style('css/alterations.css') !!}
    @endif

    @if (Route::current()->uri() == 'z/q/{id}')
      {!! Html::style('css/messaging.css') !!}
      {!! Html::style('css/tooltip.css') !!}
    @endif

    @if (Route::current()->uri() == 'messaging/qt_with/{id}')
      {!! Html::style('css/messaging.css') !!}
      {!! Html::style('css/tooltip.css') !!}
      {!! Html::style('css/responsive_calendar.css') !!}
    @endif

    @if (Route::current()->uri() == 'manage-listing/{id}/{page}')
      {!! Html::style('css/manage_listing.css') !!}
    @endif

    @if (Route::current()->uri() == 'wishlists/picks' || Route::current()->uri() == 'wishlists/my' || Route::current()->uri() == 'wishlists/popular' || Route::current()->uri() == 'wishlists/{id}' || Route::current()->uri() == 'users/{id}/wishlists')
      {!! Html::style('css/wishlists.css') !!}
    @endif

    @if (Route::current()->uri() == 'rooms/{id}')
      {!! Html::style('css/rooms_detail.css') !!}
      {!! Html::style('css/slider/nivo-lightbox.css') !!}
      {!! Html::style('css/slider/default.css') !!}
      {!! Html::style('css/jquery.bxslider.css') !!}
      {!! Html::style('css/p3.css') !!}
      {!! Html::style('css/rooms_detail_blake.css') !!}
      {!! Html::style('css/sweetalert.css') !!}
    @endif

    @if (Route::current()->uri() == 'rooms')
      {!! Html::style('css/index.css') !!}
      {!! Html::style('css/unlist_modal.css') !!}
      {!! Html::style('css/dashboard.css') !!}
    @endif

    @if (Route::current()->uri() == 'reservation/itinerary')
    @endif

    @if (Route::current()->uri() == 'reservation/receipt')
      {!! Html::style('css/receipt.css') !!}
      {!! Html::style('css/receipt-print.css',['media'=>'print']) !!}
    @endif

    @if (Route::current()->uri() == 's' || Route::current()->uri() == 'wishlists/popular')
      {!! Html::style('css/map_search.css') !!}
    @endif

    @if (Route::current()->uri() == 'payments/book/{id?}')
      {!! Html::style('css/payments.css') !!}
      {!! Html::style('css/p4.css') !!}
      {!! Html::style('css/StyleSheet.css') !!}
    @endif

    @if (Route::current()->uri() == 'reservation/requested')
      {!! Html::style('css/page5.css') !!}
    @endif

    @if (Route::current()->uri() == 's?{id}')
      {!! Html::style('css/search.css') !!}
    @endif
    @if (Route::current()->uri() == 'users/edit')
      {!! Html::style('css/address_widget.css') !!}
      {!! Html::style('css/phonenumbers.css') !!}
      {!! Html::style('css/edit_profile.css') !!}
    @endif

    @if (Route::current()->uri() == 'users/show/{id}')
      {!! Html::style('css/profile.css') !!}
    @endif

    @if (Route::current()->uri() == 'users/payout_preferences/{id}')
      {!! Html::style('css/payout_preferences.css') !!}
    @endif

    @if (Route::current()->uri() == 'home/cancellation_policies')
      {!! Html::style('css/policy_timeline_v2.css') !!}
    @endif

    @if (Route::current()->uri() == 'reviews/edit/{id}')
      {!! Html::style('css/reviews.css') !!}
    @endif

    @if (Route::current()->uri() == 'invite' || Route::current()->uri() == 'c/{username}')
      {!! Html::style('css/referrals.css') !!}
    @endif

    @if (Route::current()->uri() == 'help' || Route::current()->uri() == 'help/topic/{id}/{category}' || Route::current()->uri() == 'help/article/{id}/{question}')
      {!! Html::style('css/help.css') !!}
      {!! Html::style('css/jquery-ui.css') !!}
    @endif

  @endif

  <style type="text/css">
    .ui-selecting { background: #FECA40; }
    .ui-selected { background: #F39814; color: white; }
  </style>
	@if (trans('messages.header.currentlanguage') == 'lang-iw')
    {!! Html::style('css/rtl.css') !!}
  @endif
	@if (Route::current()->uri() == 'contact')
    <title>Contact Us</title>
  @endif
  @if (Route::current()->uri() == 'invites')
    <title>Invite Friends</title>
  @endif
	@if (Route::current()->uri() != 'contact')
    <title>{{ $title or Helpers::meta((!isset($exception)) ? Route::current()->uri() : '', 'title') }} {{ $additional_title or '' }}</title>
	@endif
  <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
  <meta name="description" content="{{ Helpers::meta((!isset($exception)) ? Route::current()->uri() : '', 'description') }}">
  <meta name="keywords" content="{{ Helpers::meta((!isset($exception)) ? Route::current()->uri() : '', 'keywords') }}">
  <meta property="og:title" content="{{ $title or Helpers::meta((!isset($exception)) ? Route::current()->uri() : '', 'title') }} {{ $additional_title or '' }}" />
  <meta property="og:url" content="{{ Request::url() }}">
  @if ( Request::is('rooms/*') )
  <meta property="og:description" content="{{ $result->summary }}">
  <meta property="og:image" content="{{ url('images/'.$result->photo_name) }}">
  @else
  <meta property="og:description" content="{{ Helpers::meta((!isset($exception)) ? Route::current()->uri() : '', 'description') }}">
  <meta property="og:image" content="{{ url() }}/images/axanya_why_choose_us.jpg">
  @endif

  <meta name="twitter:widgets:csp" content="on">
  <meta name="viewport" content="width=device-width, initial-scale=1">

  <link rel="image_src" href="#">
  <link rel="search" type="application/opensearchdescription+xml" href="#" title="">

  <meta name="mobile-web-app-capable" content="yes">

  <meta name="theme-color" content="#f5f5f5">
  <script src="https://use.fontawesome.com/63071359a9.js"></script>

  <style type="text/css" media="screen">
    .is_mobile{
      max-width:100% !important;
      max-height:100% !important;
      overflow:hidden !important;
    }
  </style>

</head>
  <body class="{{ (!isset($exception)) ? (Route::current()->uri() == '/' ? 'home_view v2 simple-header p1' : '') : '' }} {{ !Auth::user()->check() ? '' : 'loggedin' }}" ng-app="App">
  <div class="hide">
  {{trans('messages.header.currentlanguage')}}

    </div>
