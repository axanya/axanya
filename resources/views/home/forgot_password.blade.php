@extends('template')
@section('main')
<main role="main" id="site-content">
      <div class="page-container-responsive page-container-auth row-space-top-4 row-space-8">
  <div class="row">
    <div class="col-md-6 col-lg-4 col-center">
      <div class="panel">
        {!! Form::open(['url' => url('forgot_password')]) !!}
  <div id="forgot_password_container">
    <h3 class="panel-header panel-header-gray">
      {{ trans('messages.login.reset_pwd') }}
    </h3>
    <div class="panel-padding panel-body">
      <p>{{ trans('messages.login.reset_pwd_desc') }}</p>
      @if ($errors->has('email')) <p class="help-block">{{ $errors->first('email') }}</p> @endif
      <div id="inputEmail" class="textInput text-input-container">
        {!! Form::email('email', '', ['placeholder' => trans('messages.login.email'), 'id' => 'forgot_email', 'class' => $errors->has('email') ? 'decorative-input inspectletIgnore invalid' : 'decorative-input inspectletIgnore']) !!}
      </div>
      <hr>
      <button class="btn btn-primary" type="submit">
        {{ trans('messages.login.send_reset_link') }}
      </button>
    </div>
  </div>
{!! Form::close() !!}
      </div>
    </div>
  </div>
</div>

    </main>

@stop