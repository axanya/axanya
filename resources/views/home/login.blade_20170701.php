  @extends('template')
   
	@section('main')
	
    <main id="site-content" role="main">
      

<div class="page-container-responsive page-container-auth row-space-top-4 row-space-8">
  <div class="row">
    <div class="col-md-6 col-lg-4 col-center">
      <div class="panel">
          <div class="panel-body">
<a href="{{ $fb_url }}" class="fb-button fb-blue btn icon-btn btn-block btn-large row-space-1 btn-facebook" data-populate_uri="" data-redirect_uri="{{URL::to('/')}}/authenticate">
	<span class="icon-container">
    <i class="icon icon-facebook"></i>
	</span>
	<span class="text-container">
    {{ trans('messages.login.login_with') }} Facebook
  </span>
</a>

  
<a href="{{URL::to('googleLogin')}}" class="btn icon-btn btn-block btn-large row-space-1 btn-google">
  <span class="icon-container">
    <i class="icon icon-google-plus"></i>
  </span>
  <span class="text-container">
    {{ trans('messages.login.login_with') }} Google
  </span>
</a>


  <div class="signup-or-separator">
    <span class="h6 signup-or-separator--text">{{ trans('messages.login.or') }}</span>
    <hr>
  </div>

  {!! Form::open(['action' => 'UserController@authenticate', 'class' => 'signup-form login-form', 'data-action' => 'Signin', 'accept-charset' => 'UTF-8' , 'novalidate' => 'true']) !!}

  {!! Form::hidden('from', 'email_login', ['id' => 'from']) !!}
  
  <div class="control-group row-space-1">

    @if ($errors->has('email')) <p class="help-block">{{ $errors->first('email') }}</p> @endif

  {!! Form::email('email', '', ['class' => $errors->has('email') ? 'decorative-input inspectletIgnore invalid' : 'decorative-input inspectletIgnore', 'placeholder' => trans('messages.login.email_address'), 'id' => 'signin_email']) !!}

  </div>
  <div class="control-group row-space-2">

    @if ($errors->has('password')) <p class="help-block">{{ $errors->first('password') }}</p> @endif

  {!! Form::password('password', ['class' => $errors->has('password') ? 'decorative-input inspectletIgnore invalid' : 'decorative-input inspectletIgnore', 'placeholder' => trans('messages.login.password'), 'id' => 'signin_password', 'data-hook' => 'signin_password']) !!}

  </div>

  <div class="clearfix row-space-2">
    <label for="remember_me2" class="checkbox remember-me">
      {!! Form::checkbox('remember_me', '1', false, ['id' => 'remember_me2', 'class' => 'remember_me']) !!}
      {{ trans('messages.login.remember_me') }}
    </label>
    <a href="{{URL::to('/')}}/forgot_password" class="forgot-password pull-right">{{ trans('messages.login.forgot_pwd') }}</a>
  </div>

  {!! Form::submit(trans('messages.header.login'), ['class' => 'btn btn-primary btn-block btn-large', 'id' => 'user-login-btn']) !!}

</form>
          </div>
          <div class="panel-body">
            {{ trans('messages.login.dont_have_account') }}
            <a href="{{URL::to('/')}}/signup_login?" class="link-to-signup-in-login">
              {{ trans('messages.header.signup') }}
            </a>
          </div>
      </div>
    </div>
  </div>
</div>

    </main>
@stop