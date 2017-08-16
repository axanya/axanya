@extends('template')

@section('main')

  <main id="site-content" role="main" ng-controller="inbox">

@include('common.subheader')

<div class="page-container-responsive page-container-inbox space-4 space-top-4">
  <div id="inbox" class="threads">
    <div class="panel">
      <div class="panel-header">
        <div class="row">
          <form accept-charset="UTF-8" action="" class="col-md-4" id="inbox_filter_form" method="get"><div style="margin:0;padding:0;display:inline"><input name="utf8" type="hidden" value="✓"></div>            <div class="select select-large select-block">
              <select id="inbox_filter_select" name="filter" ng-cloak>
            <option value="all" selected="selected" >{{ trans('messages.dashboard.all_messages') }} (@{{message_count.all_message_count}})</option>
<option value="starred" >{{ trans('messages.inbox.starred') }} (@{{message_count.stared_count}})</option>
<option value="unread">{{ trans('messages.inbox.unread') }} (@{{message_count.unread_count}})</option>
<option value="reservations">{{ trans('messages.inbox.reservations') }} (@{{message_count.reservation_count}})</option>
<!-- <option value="late_or_no_response">{{ trans('messages.inbox.late_or_noresponse') }} (0)</option> -->
<option value="pending_requests">{{ trans('messages.inbox.pending_requests') }} (@{{message_count.pending_count}})</option>
<option value="hidden">{{ trans('messages.inbox.archived') }} (@{{message_count.archived_count}})</option></select>
            </div>
</form>          <div class="hide-sm col-md-8">
            <div class="pull-right">

            </div>
          </div>
        </div>
      </div>
<input type="hidden" ng-model="user_id" ng-init="user_id = {{ $user_id }}">
      <ul id="threads" class="list-layout panel-body">


          <li id="thread_153062093" class="panel-body js-thread is-starred thread"  ng-repeat="all in message_result.data" ng-class="(all.read == '1') ? ' thread--read' : ''" ng-cloak>

  <div class="row">
    <div class="col-sm-9 col-md-3 thread-author">
      <div class="row row-table">
        <div class="thread-avatar col-md-5">
          <a href="{{ url('users/show/')}}/@{{ all.user_details.id  }}">
		  <span style="background:url(@{{ all.user_details.profile_picture.src }}); height:50px; width:50px;" title="@{{ all.user_details.first_name }}" class="media-round media-photo"></span></a>
        </div>
        <div class="col-sm-7 thread-name">
          @{{ all.user_details.first_name }}

          <br>
          <span class="thread-date">@{{ all.created_time }}</span>
        </div>
      </div>
    </div>

    <a  class="thread-link link-reset text-muted" ng-show="all.host_check ==1 && all.reservation.status == 'Pending'"  href="{{ url('reservation')}}/@{{ all.reservation_id }}">

     <div class="col-sm-7 col-md-5 col-lg-6 thread-body" >

      <span class="thread-subject" ng-class="(all.read == '1') ? '' : 'unread_message'">
           @{{ all.message }}
        </span>

        <div class="text-muted show-lg">
            <span class="street-address">@{{ all.rooms_address.address_line_1 }} @{{ all.rooms_address.address_line_2 }}</span>, <span class="locality">@{{ all.rooms_address.city }}</span>, <span class="region">@{{ all.rooms_address.state }}</span>, <span class="country">@{{ all.rooms_address.country }}</span>
              (@{{ all.reservation.checkin | date : 'MMM dd'  }} , @{{ all.reservation.checkout | date : 'MMM dd, yyyy'  }})
        </div>
      </div>
</a>

    <a  class="thread-link link-reset text-muted" ng-show="all.host_check ==1 && all.reservation.status != 'Pending'"  href="{{ url('messaging/qt_with')}}/@{{ all.reservation_id }}">

     <div class="col-sm-7 col-md-5 col-lg-6 thread-body" >

      <span class="thread-subject" ng-class="(all.read == '1') ? '' : 'unread_message'">
           @{{ all.message }}
        </span>

        <div class="text-muted show-lg">
            <span class="street-address">@{{ all.rooms_address.address_line_1 }} @{{ all.rooms_address.address_line_2 }}</span>, <span class="locality">@{{ all.rooms_address.city }}</span>, <span class="region">@{{ all.rooms_address.state }}</span>, <span class="country">@{{ all.rooms_address.country }}</span>
              (@{{ all.reservation.checkin | date : 'MMM dd'  }} , @{{ all.reservation.checkout | date : 'MMM dd, yyyy'  }})
        </div>
      </div>
</a>


    <a  class="thread-link link-reset text-muted" ng-show="all.guest_check"  href="{{ url('z/q')}}/@{{ all.reservation_id }}">
      <div class="col-sm-7 col-md-5 col-lg-6 thread-body" >
      <span class="thread-subject" ng-class="(all.read == '1') ? '' : 'unread_message'">
           @{{ all.message }}
        </span>
        <div class="text-muted show-lg">
            <span class="street-address">@{{ all.rooms_address.address_line_1 }} @{{ all.rooms_address.address_line_2 }}</span>, <span class="locality">@{{ all.rooms_address.city }}</span>, <span class="region">@{{ all.rooms_address.state }}</span>, <span class="country">@{{ all.rooms_address.country }}</span>
              (@{{ all.reservation.checkin | date : 'MMM dd'  }} , @{{ all.reservation.checkout | date : 'MMM dd, yyyy'  }})
        </div>
      </div>
</a>

<div class="col-sm-7 col-md-4 col-lg-3 thread-label">
      <div class="row">
        <div  class="col-sm-12 col-md-6" >
          <span class="label label-@{{ all.reservation.status_color }}">@{{ all.reservation.status }}</span>
            <br>
            <span id="price-breakdown-153062093" class="hide-sm price-breakdown-trigger">
             <span ng-bind-html="all.reservation.currency.original_symbol"></span>@{{ all.reservation.check_total }}
            </span>
        </div>
        <div id="options_153062093" class="col-sm-6 options thread-actions hide-sm">
          <ul class="thread-actions list-unstyled">
            <li class="row-space-1">
            <a data-thread-id="153062093" href="javascript:void(0);" class="link-icon thread-action js-star-thread ">
                <i ng-show="all.star == 1" class="icon istar_@{{ all.user_from }} icon-star icon-beach"></i>
                <i ng-show="all.star == 0" class="icon iunstar_@{{ all.user_from }} icon-star-alt icon-gray"></i>
                <span ng-show="all.star == 1" class="thread-star link-icon__text star_@{{all.user_from}}" ng-click="star($index, all.user_from,all.id,'Unstar')">{{ trans('messages.inbox.unstar') }}</span>
                <span ng-show="all.star == 0" class="thread-star link-icon__text un_star_@{{all.user_from}}" ng-click="star($index, all.user_from,all.id,'Star')">{{ trans('messages.inbox.star') }}</span>
              </a>
            </li>
            <li id="thread_153062093_hidden">
               <a data-thread-id="153062093" href="javascript:void(0);" class="link-icon thread-action js-archive-thread ">
                <i ng-show="all.archive == 1" class="icon icon-archive icon-kazan"></i>
                <i ng-show="all.archive == 0" class="icon icon-archive icon-gray"></i>
                <span  ng-show="all.archive == 0" class="link-icon__text user_from_@{{all.user_from}}"  ng-click="archive($index, all.user_from,all.id,'Archive')">{{ trans('messages.inbox.archive') }}</span>
                <span ng-show="all.archive == 1" class="link-icon__text un_user_from_@{{all.user_from}}"  ng-click="archive($index,all.user_from ,all.id,'Unarchive')">{{ trans('messages.inbox.unarchive') }}</span>
              </a>
            </li>
          </ul>
        </div>
      </div>
    </div>
  </div>
</li>

        </ul>

        <div class="results-footer">

  <div class="pagination-buttons-container row-space-8" ng-cloak>
    <div class="results_count" style="float: right;margin-top: 20px;">
      <p>
        @{{ message_result.from }} – @{{ message_result.to }} of @{{ message_result.total }} {{ trans_choice('messages.dashboard.message',2) }}
      </p>
      <posts-pagination></posts-pagination>
    </div>
    <div>

    </div>
  </div>


</div>

    </div>
  </div>
</div>



    </main>

  @stop
