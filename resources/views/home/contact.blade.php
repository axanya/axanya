  @extends('template')

	@section('main')

    <main id="site-content" role="main">
@if(Session::has('message'))
	<!--
  <div class="alert {{ Session::get('alert-class') }}" role="alert">
    <a href="#" class="alert-close" data-dismiss="alert"></a>
  {{ Session::get('message') }}
  </div>
  -->
@endif


<div class="page-container-responsive page-container-auth row-space-top-4 row-space-8">
  <div class="row">
    <div class="col-md-6 col-lg-4 col-center">
      <div class="panel">
          <div class="panel-body">
			<h1>{{ trans('messages.footer.contact') }}</h1>

<ul>
    @foreach($errors->all() as $error)
        <li>{{ $error }}</li>
    @endforeach
</ul>

{!! Form::open(array('url' => 'contact_store', 'class' => 'form', 'method' => 'post')) !!}

<div class="form-group">
    {!! Form::label('Your Name') !!}
    {!! Form::text('name', null,
        array('required',
              'class'=>'form-control',
              'placeholder'=>'Your name')) !!}
</div>

<div class="form-group">
    {!! Form::label('Your E-mail Address') !!}
    {!! Form::email('email', null,
        array('required',
              'class'=>'form-control',
              'placeholder'=>'Your e-mail address',
              'oninvalid' => 'this.setCustomValidity(\'Invalid email\')')) !!}
</div>

<div class="form-group">
    {!! Form::label('Reason For Contacting Us') !!}
    {!! Form::textarea('message', null,
        array('required',
              'class'=>'form-control',
              'placeholder'=>'Your message')) !!}
</div>
<br>
<div class="form-group">
    {!! Form::submit('Contact Us!',
      array('class'=>'btn btn-primary')) !!}
</div>
{!! Form::close() !!}





      </div>
    </div>
  </div>
</div>

    </main>
@stop
