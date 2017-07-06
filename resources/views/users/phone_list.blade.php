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

          <div class="panel verified-container">
            <div class="panel-header">
                Phone Numbers <a href="{{ url('/verification?type=new') }}" class="pull-right"><i class="fa fa-plus-circle" aria-hidden="true"></i> Add New</a>
            </div>
            <div class="panel-body">
                <!-- <ul class="list-layout edit-verifications-list"> -->

                  <!-- <li class="edit-verifications-list-item clearfix email verified">
                    <h4>{{ trans('messages.dashboard.email_address') }}</h4>
                    <p class="description">{{ trans('messages.profile.you_have_confirmed_email') }} <b>{{ Auth::user()->user()->email }}</b>.  {{ trans('messages.profile.email_verified') }}
                    </p>
                  </li> -->

                  <div class="table-container">
                    <table class="table table-filter">
                      <tbody>
                        <tr data-status="pagado">
                          <td><b>Phone Number</b></td>
                          <td><b>Status</b></td>
                          <td><b>Action</b></td>
                        </tr>
                        @foreach($phone_info as $val)
                        <tr data-status="pagado">
                          <!-- <td>
                            <div class="ckbox">
                              <input type="checkbox" id="checkbox1">
                              <label for="checkbox1"></label>
                            </div>
                          </td> -->
                          <td>
                            +{{$val->phone_code}} {{$val->phone_number}}
                          </td>
                          <td>
                            @if($val->status == 'Pending')
                              <label class="clabel clabel-warning"><i class="fa fa-ban" aria-hidden="true"></i> {{ trans('messages.referrals.pending') }}</label>
                            @elseif($val->status == 'Confirmed')
                              <label class="clabel clabel-success"><i class="fa fa-check-circle-o" aria-hidden="true"></i> {{ trans('messages.dashboard.verified') }}</label>
                            @endif
                          </td>

                          <td>
                            @if($val->status == 'Pending')
                              <a  href="{{url('/verification?phone_id='.$val->id)}}">{{ trans('messages.lys.verify') }}</a> &nbsp;
                            @endif
                             <a  href="{{url('/users/delete_phone?phone_id='.$val->id)}}" onclick="return confirm('Are you sure?')"> Delete</a>
                          </td>

                        </tr>
                        @endforeach
                        @if(count($phone_info) == 0)
                          <tr data-status="pagado"><td colspan="3"><h4 class="text-center">No record found</h4></td></tr>
                        @endif
                      </tbody>
                    </table>
                  </div>


                <!-- </ul> -->
            </div>
          </div>
        </div>
      </div>
    </div>
</div>

</main>

@stop