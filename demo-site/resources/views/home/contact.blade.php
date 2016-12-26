@extends('template')

@section('main')

    <main id="site-content" role="main">


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

                            {!! Form::open(array('route' => 'contact_store', 'class' => 'form')) !!}

                            <div class="form-group">
                                {!! Form::label('Your Name') !!}
                                {!! Form::text('name', null,
                                    array('required',
                                          'class'=>'form-control',
                                          'placeholder'=>'Your name')) !!}
                            </div>

                            <div class="form-group">
                                {!! Form::label('Your E-mail Address') !!}
                                {!! Form::text('email', null,
                                    array('required',
                                          'class'=>'form-control',
                                          'placeholder'=>'Your e-mail address')) !!}
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