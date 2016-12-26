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

  <div class="social-buttons hide">

      
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

  <div class="text-center social-links">
    {{ trans('messages.login.signup_with') }} <a href="{{ $fb_url }}" class="facebook-link-in-signup">Facebook</a> {{ trans('messages.login.or') }} <a href="{{URL::to('googleLogin')}}">Google</a>
  </div>

    <div class="signup-or-separator">
      <span class="h6 signup-or-separator--text">{{ trans('messages.login.or') }}</span>
      <hr>
    </div>

    <div class="text-center">
      <a href="{{URL::to('/')}}/signup_login?sm=2" class="create-using-email btn-block  row-space-2  icon-btn hide" id="create_using_email_button">
          <i class="icon icon-envelope"></i>
          {{ trans('messages.login.signup_with') }} {{ trans('messages.login.email') }}
</a>    </div>

    {!! Form::open(['action' => 'UserController@create', 'class' => 'signup-form', 'data-action' => 'Signup', 'id' => 'user_new', 'accept-charset' => 'UTF-8' , 'novalidate' => 'true']) !!}

    <div class="signup-form-fields">

      {!! Form::hidden('from', 'email_signup', ['id' => 'from']) !!}
      
<div class="control-group row-space-1" id="inputFirst">

  @if ($errors->has('first_name')) <p class="help-block">{{ $errors->first('first_name') }}</p> @endif
  
  {!! Form::text('first_name', '', ['class' =>  $errors->has('first_name') ? 'decorative-input invalid' : 'decorative-input', 'placeholder' => trans('messages.login.first_name')]) !!}

</div>

<div class="control-group row-space-1" id="inputLast">
  
  @if ($errors->has('last_name')) <p class="help-block">{{ $errors->first('last_name') }}</p> @endif

  {!! Form::text('last_name', '', ['class' => $errors->has('last_name') ? 'decorative-input inspectletIgnore invalid' : 'decorative-input inspectletIgnore', 'placeholder' => trans('messages.login.last_name')]) !!}

</div>

<div class="control-group row-space-1" id="inputEmail">

  @if ($errors->has('email')) <p class="help-block">{{ $errors->first('email') }}</p> @endif

  {!! Form::email('email', '', ['class' => $errors->has('email') ? 'decorative-input inspectletIgnore invalid' : 'decorative-input inspectletIgnore', 'placeholder' => trans('messages.login.email_address')]) !!}

</div>

<div class="control-group row-space-1" id="inputPassword">

  @if ($errors->has('password')) <p class="help-block">{{ $errors->first('password') }}</p> @endif

  {!! Form::password('password', ['class' => $errors->has('password') ? 'decorative-input inspectletIgnore invalid' : 'decorative-input inspectletIgnore', 'placeholder' => trans('messages.login.password'), 'id' => 'user_password', 'data-hook' => 'user_password']) !!}
  
  <div data-hook="password-strength" class="password-strength hide"></div>
</div>


<div class="control-group row-space-top-3 row-space-1">
  <strong>{{ trans('messages.login.birthday') }}</strong>
  <strong data-behavior="tooltip" aria-label="To sign up, you must be 18 or older. Other people won’t see your birthday.">
    <i class="icon icon-question"></i>
  </strong>
</div>

<div class="control-group row-space-1" id="inputBirthday"></div>

@if ($errors->has('birthday_month') || $errors->has('birthday_day') || $errors->has('birthday_year')) <p class="help-block">{{ $errors->has('birthday_month') ? $errors->first('birthday_month') : ($errors->has('birthday_day')) ? $errors->first('birthday_day') : ($errors->has('birthday_year')) ? $errors->first('birthday_year') : '' }}</p> @endif

<div class="control-group row-space-2">
	
  <div class="select">
{!! Form::selectMonthWithDefault('birthday_month', null, trans('messages.header.month'), [ 'class' => $errors->has('birthday_month') ? 'invalid' : '', 'id' => 'user_birthday_month']) !!}
  </div>
  
  <div class="select">
{!! Form::selectRangeWithDefault('birthday_day', 1, 31, null, trans('messages.header.day'), [ 'class' => $errors->has('birthday_day') ? 'invalid' : '', 'id' => 'user_birthday_day']) !!}
  </div>
  
  <div class="select">
{!! Form::selectRangeWithDefault('birthday_year', date('Y'), date('Y')-120, null, trans('messages.header.year'), [ 'class' => $errors->has('birthday_year') ? 'invalid' : '', 'id' => 'user_birthday_year']) !!}
  </div>
  
</div>

<label class="pull-left checkbox">

{!! Form::hidden('user_profile_info[receive_promotional_email]', '0') !!}

{!! Form::checkbox('user_profile_info[receive_promotional_email]', '1', 'true', ['id' => 'user_profile_info_receive_promotional_email']) !!}

</label>
<!-- <label for="user_profile_info_receive_promotional_email" class="checkbox">
 I’d like to receive coupons and inspiration
</label> -->

      <div id="tos_outside" class="row-space-top-3">
       <small>
  {{ trans('messages.login.signup_agree') }} {{ $site_name }}'s <a href="{{URL::to('/')}}/terms_of_service" data-popup="true">{{ trans('messages.login.terms_service') }}</a>, <a href="{{URL::to('/')}}/privacy_policy" data-popup="true">{{ trans('messages.login.privacy_policy') }}</a>, <a href="{{URL::to('/')}}/guest_refund" data-popup="true">{{ trans('messages.login.guest_policy') }}</a>, {{ trans('messages.header.and') }} <a href="{{URL::to('/')}}/host_guarantee" data-popup="true">{{ trans('messages.login.host_guarantee') }}</a>.
</small>

    </div>
    {!! Form::submit('Sign up', ['class' => 'btn btn-primary btn-block btn-large']) !!}

    </div>
    {!! Form::close() !!}
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