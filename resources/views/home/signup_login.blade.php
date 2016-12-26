@extends('template')

@section('main')

    <main id="site-content" role="main">
      

<div class="page-container-responsive page-container-auth row-space-top-4 row-space-8">
  <div class="row">
    <div class="col-md-6 col-lg-4 col-center">
      <div class="panel">
          
<div class="alert alert-with-icon alert-error alert-header panel-header hidden-element notice" id="notice">
  <i class="icon alert-icon icon-alert-alt"></i>
  
</div>
<div class="panel-padding panel-body">

  <div class="social-buttons">

      
<a href="{{ $fb_url }}" class="fb-button fb-blue btn icon-btn btn-block row-space-1 btn-large btn-facebook" data-populate_uri="" data-redirect_uri="{{URL::to('/')}}/authenticate">
	<span class="icon-container">
    <i class="icon icon-facebook"></i>
	</span>
	<span class="text-container">
    {{ trans('messages.login.signup_with') }} Facebook
  </span>
</a>

      
<a href="{{URL::to('googleLogin')}}" class="btn icon-btn btn-block row-space-1 btn-large btn-google">
  <span class="icon-container">
    <i class="icon icon-google-plus"></i>
  </span>
  <span class="text-container">
    {{ trans('messages.login.signup_with') }} Google
  </span>
</a>
  </div>

  <div class="text-center social-links hide">
    {{ trans('messages.login.signup_with') }} <a href="{{ $fb_url }}" class="facebook-link-in-signup">Facebook</a> {{ trans('messages.login.or') }} <a href="{{URL::to('googleLogin')}}">Google</a>
  </div>

    <div class="signup-or-separator">
      <span class="h6 signup-or-separator--text">{{ trans('messages.login.or') }}</span>
      <hr>
    </div>

    <div class="text-center">
      <a href="{{URL::to('/')}}/signup_login?sm=2" class="create-using-email btn-block  row-space-2 btn btn-primary btn-block btn-large large icon-btn" id="create_using_email_button">
          <i class="icon icon-envelope"></i>
          {{ trans('messages.login.signup_with') }} {{ trans('messages.login.email') }}
</a>    </div>

    <div id="tos_outside" class="row-space-top-3">
       <small>
  {{ trans('messages.login.signup_agree') }} {{ $site_name }}'s <a href="{{URL::to('/')}}/terms_of_service" data-popup="true">{{ trans('messages.login.terms_service') }}</a>, <a href="{{URL::to('/')}}/privacy_policy" data-popup="true">{{ trans('messages.login.privacy_policy') }}</a>, <a href="{{URL::to('/')}}/guest_refund" data-popup="true">{{ trans('messages.login.guest_policy') }}</a>, {{ trans('messages.header.and') }} <a href="{{URL::to('/')}}/host_guarantee" data-popup="true">{{ trans('messages.login.host_guarantee') }}</a>.
</small>

    </div>
</div>

<div class="panel-body">
  {{ trans('messages.login.already_an') }} {{ $site_name }} {{ trans('messages.login.member') }}
    <a href="{{URL::to('/')}}/login?" class="modal-link link-to-login-in-signup" data-modal-href="/login_modal?" data-modal-type="login">
      {{ trans('messages.header.login') }}
</a></div>

      </div>
    </div>
  </div>
</div>

    </main>
    
 @stop