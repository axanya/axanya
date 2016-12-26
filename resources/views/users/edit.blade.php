@extends('template')

@section('main')

<main id="site-content" role="main">
      
 @include('common.subheader')  
      
<div id="notification-area"></div>
<div class="page-container-responsive space-top-4 space-4">
  <div class="row">
    <div class="col-md-3 space-sm-4">
      <div class="sidenav">
      @include('common.sidenav')
      </div>
      <a href="{{ url('users/show/'.Auth::user()->user()->id) }}" class="btn btn-block row-space-top-4">{{ trans('messages.dashboard.view_profile') }}</a>
    </div>
    <div class="col-md-9">
      
      <div id="dashboard-content">
        
{!! Form::open(['url' => url('users/update/'.Auth::user()->user()->id), 'id' => 'update_form']) !!}

<div class="panel row-space-4">
  <div class="panel-header">
    {{ trans('messages.profile.required') }}
  </div>
  <div class="panel-body">
          <div class="row row-condensed space-4">
        <label class="text-right col-sm-3" for="user_first_name">
          {{ trans('messages.profile.first_name') }} 
        </label>
        <div class="col-sm-9">

      {!! Form::text('first_name', Auth::user()->user()->first_name, ['id' => 'user_first_name', 'size' => '30', 'class' => 'focus']) !!}

        </div>
      </div>

          <div class="row row-condensed space-4">
        <label class="text-right col-sm-3" for="user_last_name">
          {{ trans('messages.profile.last_name') }}
        </label>
        <div class="col-sm-9">
          
      {!! Form::text('last_name', Auth::user()->user()->last_name, ['id' => 'user_last_name', 'size' => '30', 'class' => 'focus']) !!}

          <div class="text-muted row-space-top-1">{{ trans('messages.profile.last_name_never_share', ['site_name'=>$site_name]) }}</div>
        </div>
      </div>

          <div class="row row-condensed space-4">
        <label class="text-right col-sm-3" for="user_gender">
          {{ trans('messages.profile.i_am') }} <i class="icon icon-lock icon-ebisu" data-behavior="tooltip" aria-label="Private"></i>
        </label>
        <div class="col-sm-9">
          
      <div class="select">
        {!! Form::select('gender', ['Male' => trans('messages.profile.male'), 'Female' => trans('messages.profile.female'), 'Other' => trans('messages.profile.other')], Auth::user()->user()->gender, ['id' => 'user_gender', 'placeholder' => trans('messages.profile.gender'), 'class' => 'focus']) !!}
      </div>

          <div class="text-muted row-space-top-1">{{ trans('messages.profile.gender_never_share') }}</div>
        </div>
      </div>

          <div class="row row-condensed space-4">
        <label class="text-right col-sm-3" for="user_birthdate">
          {{ trans('messages.profile.birth_date') }} <i class="icon icon-lock icon-ebisu" data-behavior="tooltip" aria-label="Private"></i>
        </label>
        <div class="col-sm-9">

      <div class="select">
      {!! Form::selectMonthWithDefault('birthday_month', $dob[1], trans('messages.header.month'), ['id' => 'user_birthday_month', 'class' => 'focus']) !!}
      </div>

      <div class="select">
      {!! Form::selectRangeWithDefault('birthday_day', 1, 31, $dob[2], trans('messages.header.day'), ['id' => 'user_birthday_day', 'class' => 'focus']) !!}
      </div>

      <div class="select">
      {!! Form::selectRangeWithDefault('birthday_year', date('Y'), date('Y')-120, $dob[0], trans('messages.header.year'), ['id' => 'user_birthday_year', 'class' => 'focus']) !!}
      </div>

          <div class="text-muted row-space-top-1">{{ trans('messages.profile.birth_date_never_share') }}</div>
        </div>
      </div>

          <div class="row row-condensed space-4">
        <label class="text-right col-sm-3" for="user_email">
          {{ trans('messages.dashboard.email_address') }} <i class="icon icon-lock icon-ebisu" data-behavior="tooltip" aria-label="Private"></i>
        </label>
        <div class="col-sm-9">
          
          {!! Form::text('email', Auth::user()->user()->email, ['id' => 'user_email', 'size' => '30', 'class' => 'focus']) !!}

          <div class="text-muted row-space-top-1">{{ trans('messages.profile.email_never_share', ['site_name'=>$site_name]) }}</div>
        </div>
      </div>

          <div class="row row-condensed space-4">
        <label class="text-right col-sm-3" for="user_live">
          {{ trans('messages.profile.where_you_live') }}
        </label>
        <div class="col-sm-9">
          
          {!! Form::text('live', Auth::user()->user()->live, ['id' => 'user_live', 'placeholder' => 'e.g. Paris, FR / Brooklyn, NY / Chicago, IL', 'size' => '30', 'class' => 'focus']) !!}
          
        </div>
      </div>

          <div class="row row-condensed space-4">
        <label class="text-right col-sm-3" for="user_about">
          {{ trans('messages.profile.describe_yourself') }}
        </label>
        <div class="col-sm-9">
          
      {!! Form::textarea('about', Auth::user()->user()->about, ['id' => 'user_about', 'cols' => '40', 'rows' => '5', 'class' => 'focus']) !!}

          <div class="text-muted row-space-top-1">{{ trans('messages.profile.about_desc1', ['site_name'=>$site_name]) }}<br><br>{{ trans('messages.profile.about_desc2') }}<br><br>{{ trans('messages.profile.about_desc3', ['site_name'=>$site_name]) }}<br><br>{{ trans('messages.profile.about_desc4') }}</div>
        </div>
      </div>
  </div>
</div>


<div class="panel row-space-4">
  <div class="panel-header">
    {{ trans('messages.profile.optional') }}
  </div>
  <div class="panel-body">
          <div class="row row-condensed space-4">
        <label class="text-right col-sm-3" for="user_profile_info_university">
          {{ trans('messages.profile.school') }} 
        </label>
        <div class="col-sm-9">

      {!! Form::text('school', Auth::user()->user()->school, ['id' => 'user_profile_info_university', 'size' => '30', 'class' => 'focus']) !!}
          
        </div>
      </div>

          <div class="row row-condensed space-4">
        <label class="text-right col-sm-3" for="user_profile_info_employer">
          {{ trans('messages.profile.work') }} 
        </label>
        <div class="col-sm-9">
          
      {!! Form::text('work', Auth::user()->user()->work, ['id' => 'user_profile_info_employer', 'size' => '30', 'placeholder' => 'e.g. Airbnb / Apple / Taco Stand', 'class' => 'focus']) !!}
          
        </div>
      </div>

          <div class="row row-condensed space-4">
        <label class="text-right col-sm-3" for="user_time_zone">
          {{ trans('messages.profile.timezone') }} 
        </label>
        <div class="col-sm-9">
          
      <div class="select">
      {!! Form::select('timezone', $timezone, Auth::user()->user()->timezone, ['id' => 'user_time_zone', 'class' => 'focus']) !!}
      </div>

          <div class="text-muted row-space-top-1">{{ trans('messages.profile.timezone_desc') }}</div>
        </div>
      </div>

  </div>
</div>

<button type="submit" class="btn btn-primary btn-large">
  {{ trans('messages.profile.save') }}
</button>

{!! Form::close() !!}
      </div>
    </div>
  </div>
</div>

    </main>

@stop