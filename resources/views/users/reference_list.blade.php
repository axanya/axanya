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
                Reference Users <a class="pull-right" href="javascript:void(0)" id="add-reference-user"><i class="fa fa-plus-circle" aria-hidden="true"></i> Add New</a>
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
                          <td><b>Name</b></td>
                          <td width="170"><b>Contact Information 1</b></td>
                          <td width="170"><b>Contact Information 2</b></td>
                          <td><b>Relationship</b></td>
                          <td><b>Best Hours To Call</b></td>
                          <td><b>Action</b></td>
                        </tr>
                        @foreach($users_info as $val)
                        <tr data-status="pagado">
                          <td>
                            {{$val->name}}
                          </td>

                          <td>
                            <label>{{$val->email1}}</label>
                            <label>{{$val->phone1}}</label>
                          </td>
                          <td>
                            <label>{{$val->email2}}</label>
                            <label>{{$val->phone2}}</label>
                          </td>

                          <td>
                            {{$val->relationship}}
                          </td>

                          <td>
                            {{$val->time_to_call}}
                          </td>

                          <td><a href="{{url('/users/delete_reference?ref_id='.$val->id)}}" onclick="return confirm('Are you sure?')">Delete</a> &nbsp; {{-- <a href="{{url('/users/edit_reference?ref_id='.$val->id)}}">Edit</a> --}}</td>
                        </tr>
                        @endforeach

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
<div id="reference-flow-view">
<div aria-hidden="true" style="" class="modal" role="dialog" data-sticky="true">
  <div class="modal-table">
    <div class="modal-cell">
      <div class="modal-content">
        <a class="modal1-close" data-behavior="modal-close" href=""></a>
        <div id="js-reference-container">
          <div id="js-bedroom-container" class="enter_bedroom">
            <div style="padding: 14px 0px; text-align: center;" class="ng-scope"><h3>Add Reference</h3></div>
            <div class="panel ng-scope" style="background:#fafafa; pading-top:10px;">
              <div class="flash-container" id="js-flash-error-clicked-frozen-field"></div>
              <form action="{{ url('users/add_reference') }}" name="add_reference" class="list_inner_frame1" method="post">
              <div class="panel-body clearfix">
                  <div class="row-space-2 clearfix">
                    <div class="col-12">
                        <label class="label-large">Name</label>

                        <input type="text" name="name" value="" class="overview-title input-large name_required" placeholder="Name of reference" maxlength="50" ng-model="name" required="">
                    </div>
                  </div>

                  <div class="row-space-2 clearfix">
                    <div class="col-12">
                        <label class="label-large">Contact Information 1</label>
                    </div>

                    <div class="col-md-6 col-xs-12 row-space-2">
                      <input type="email" name="email1" value="" class="overview-title input-large name_required" placeholder="{{ trans('messages.login.email') }}" maxlength="100" ng-model="email1" required="">
                    </div>
                    <div class="col-md-6 col-xs-12 row-space-2">
                      <input type="tel" name="phone1" value="" class="overview-title input-large name_required" placeholder="{{ trans('messages.login.phone') }} (with country code)" maxlength="15" ng-model="phone1" required="">
                    </div>
                  </div>

                  <div class="row-space-2 clearfix">
                    <div class="col-12">
                        <label class="label-large">Contact Information 2</label>
                    </div>

                    <div class="col-md-6 col-xs-12 row-space-2">
                      <input type="email" name="email2" value="" class="overview-title input-large name_required" placeholder="{{ trans('messages.login.email') }}" maxlength="100" ng-model="email2">
                    </div>
                    <div class="col-md-6 col-xs-12 row-space-2">
                      <input type="tel" name="phone2" value="" class="overview-title input-large name_required" placeholder="{{ trans('messages.login.phone') }} (with country code)" maxlength="15" ng-model="phone2">
                    </div>
                  </div>

                  <div class="row-space-2 clearfix">
                    <div class="col-12">
                        <label class="label-large">Describe Relationship</label>
                        <input type="text" name="relationship" value="" class="overview-title input-large name_required" placeholder="Relationship" maxlength="50" ng-model="relationship" required="">
                    </div>
                  </div>

                  <div class="row-space-2 clearfix">
                    <div class="col-12">
                      <label class="label-large">Best Hours To Call</label>
                      <input type="text" name="time_to_call" value="" class="overview-title input-large name_required" placeholder="Ex. (10:00 AM - 06:00 PM)" maxlength="50" ng-model="time_to_call" required="">
                    </div>
                  </div>


              </div>

              <div class="panel-footer">
                <div class="force-oneline">
                    <button data-behavior="modal-close" class="btn js-secondary-btn">
                      Cancel
                    </button>
                  <button type="submit" class="btn btn-primary">
                    Save
                  </button>
                </div>
              </div>
              </form>
            </div>
          </div>


        </div>

      </div>
    </div>
  </div>
</div>
</div>
@stop