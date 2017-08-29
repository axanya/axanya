<div id="header" class="axanya-header new {{ (!isset($exception)) ? (Route::current()->uri() == '/' ? 'shift-with-hiw' : '') : '' }}">
    <header class="header--sm show-sm" aria-hidden="true" role="banner">

  <div class="title--sm text-center">
      @if(!isset($exception))
          <a href="javascript:void(0);" class="header-belo" style="{{ $logo_style }}">
            <span class="screen-reader-only">
              {{ $site_name }}
            </span>
          </a>
		  <a href="javascript:void(0);" aria-label="Homepage" data-prevent-default="" class="link-reset burger--sm">
			<i class="icon icon-chevron-small icon-chevron-down icon-black pull-left"></i>
			<span class="screen-reader-only">
			  {{ $site_name }}
			</span>
		  </a>
			<div class="title--sm text-center mob_search">
			  <button class="btn btn-block search-btn--sm search-modal-trigger " style="white-space: nowrap; overflow: hidden; text-overflow: ellipsis;">
				   <div id="icon-drawer-search-icon" data-reactid=".0.0.3"><svg viewBox="0 0 24 24" style="fill:currentColor;height:1em;width:1em;display:block;" data-reactid=".0.0.3.0"><path d="M23.53 22.47l-6.807-6.808A9.455 9.455 0 0 0 19 9.5 9.5 9.5 0 1 0 9.5 19c2.353 0 4.502-.86 6.162-2.277l6.808 6.807a.75.75 0 0 0 1.06-1.06zM9.5 17.5a8 8 0 1 1 0-16 8 8 0 0 1 0 16z" data-reactid=".0.0.3.0.0"></path></svg></div>
				  <span class="search-placeholder--sm">
					{{ trans('messages.header.where_are_you_going') }}
				  </span>
			  </button>
			  <a href="/" class="header-belo hide">
				<span class="screen-reader-only">
				  {{ $site_name }}
				</span>
			  </a>
		  </div>
      @endif
  </div>

  <div class="action--sm"></div>

<nav class="nav--sm" role="navigation"><div class="nav-mask--sm"></div>
<div class="nav-content--sm panel text-white logged-in">
  <div class="nav-menu-wrapper space-top-8">
    <div class="nav-menu panel-body">
      <ul class="menu-group list-unstyled">
        <li>
		 <a rel="nofollow" class="link-reset menu-item" href="{{ url() }}">
              {{ trans('messages.header.home') }}
         </a>
        </li>
        <hr>
        <li class="{{ (Auth::user()->user()) ? '' : 'items-logged-in' }}">
          <a href="{{ url() }}/users/show/{{ (Auth::user()->user()) ? Auth::user()->user()->id : '0' }}" class="link-reset menu-item" rel="nofollow">
            {{ trans('messages.header.profile') }}
          </a>
          <i class="nav-icon nav-icon-user"></i>
        </li>
        <li class="{{ (Auth::user()->user()) ? '' : 'items-logged-in' }}">
          <a href="{{ url() }}/trips/current" rel="nofollow" class="link-reset menu-item">
            {{ trans('messages.header.your_trips') }}
          </a>

          <i class="nav-icon nav-icon-suitcase"></i>
        </li>
        <li class="items-logged-in">
          <a href="{{ url() }}/dashboard" class="link-reset menu-item dashboard hide" rel="nofollow">
            {{ trans('messages.header.dashboard') }}
          </a>
        </li>
        <li class="{{ (Auth::user()->user()) ? '' : 'items-logged-in' }}">
          <a href="{{ url() }}/inbox" rel="nofollow" class="link-reset menu-item">
            {{ trans('messages.header.messages') }}
            <i class="alert-count unread-count--sm fade text-center">
              0
            </i>
          </a>
          <i class="nav-icon nav-icon-message"></i>
        </li>
        <!--host menu-->
        <li class="{{ (Auth::user()->user()) ? '' : 'items-logged-in' }}">
          <hr>
        </li>

        <li class="dropdown-trigger">
          <a href="{{ url('rooms') }}" rel="nofollow" class="no-crawl link-reset menu-item item-trips">
            {{ trans('messages.header.host') }} <i class="fa fa-angle-down pull-right"></i>
          </a>
          <ul class="tooltip tooltip-top-right dropdown-menu list-unstyled header-dropdown">
              <li>
                <a href="{{ url('rooms') }}" rel="nofollow" class="no-crawl link-reset menu-item item-listing">
                  {{ trans('messages.header.manage_listing') }}
                </a>
              </li>
              <li>
                <a href="{{ url('my_reservations') }}" rel="nofollow" class="no-crawl link-reset menu-item header-reservations">
                  {{ trans('messages.header.reservations') }}
                </a>
              </li>
            <li>
                <a href="{{ url('users/transaction_history') }}" rel="nofollow" class="no-crawl link-reset menu-item header-transaction">
                  {{ trans('messages.header.transaction_history') }}
                </a>
              </li>
            <li>
                <a href="{{ url('users/reviews') }}" rel="nofollow" class="no-crawl link-reset menu-item header-reviews">
                  {{ trans_choice('messages.header.review',2) }}
                </a>
            </li>
          </ul>
        </li>

        <li class="{{ (Auth::user()->user()) ? '' : 'items-logged-in' }}">
          <hr>
        </li>

        <li>
          <a href="{{ url() }}/rooms/new" class="link-reset menu-item become-a-host" rel="nofollow">
            {{ trans('messages.header.list_your_space') }}
          </a>
        </li>
        <li class="{{ (Auth::user()->user()) ? 'items-logged-out' : '' }}">
          <a data-signup-modal="" href="{{ url() }}/signup_login" class="link-reset menu-item" rel="nofollow">
            {{ trans('messages.header.signup') }}
          </a>
        </li>
        <li class="{{ (Auth::user()->user()) ? 'items-logged-out' : '' }}">
          <a data-login-modal="" href="/login" class="link-reset menu-item" rel="nofollow">
            {{ trans('messages.header.login') }}
          </a>
        </li>

        <hr>
        <!--
        <li>
          <a href="{{ url() }}/help" rel="nofollow" class="link-reset menu-item">
            {{ trans('messages.header.help') }}
          </a>
        </li>
      -->
        <li class="{{ (Auth::user()->user()) ? '' : 'items-logged-in' }}">
          <a href="{{ url() }}/invite" rel="nofollow" class="link-reset menu-item">
            {{ trans('messages.header.invite_friends') }}
          </a>
        </li>
		    <li>
          <a href="{{ url('contact') }}" class="link-reset menu-item">{{ trans('messages.footer.contact') }}</a>
        </li>
        <li class="{{ (Auth::user()->user()) ? '' : 'items-logged-in' }}">
          <a href="{{ url() }}/logout" rel="nofollow" class="link-reset menu-item logout">
            {{ trans('messages.header.logout') }}
          </a>
        </li>
      </ul>
    </div>
  </div>
  </div>
</nav>

  <div class="search-modal-container">
  <div class="modal hide" role="dialog" id="search-modal--sm" aria-hidden="false" tabindex="-1">
  <div class="modal-table">
    <div class="modal-cell">
      <div class="modal-content">
        <div class="panel-header modal-header">
          <a href="#" class="modal-close" data-behavior="modal-close">
            <span class="screen-reader-only">{{ trans('messages.home.close') }}</span>
            <span class="aria-hidden"></span>
          </a>
          {{ trans('messages.home.search') }}
        </div>
        <div class="panel-body">
          <div class="modal-search-wrapper--sm">
              <input type="hidden" name="source" value="mob">
              <div class="row">
                  <div class="searchbar__location-error hide" style="left:22px; top:2%;">{{ trans('messages.home.search_validation') }}</div>
                <div class="col-sm-12">
                  <label for="header-location--sm">
                    <span class="screen-reader-only">{{ trans('messages.header.where_are_you_going') }}</span>
                    <input type="text" placeholder="{{ trans('messages.header.where_are_you_going') }}" autocomplete="off" name="location" id="header-location--sm" class="input-large" value="{{ @$location }}">
                  </label>
                </div>
              </div>
              <div class="row row-condensed">
                <div class="col-sm-6">
                  <label class="checkin">
                    <span class="screen-reader-only">{{ trans('messages.home.checkin') }}</span>
                    <input type="text" name="checkin" id="modal_checkin" class="checkin input-large ui-datepicker-target" placeholder="{{ trans('messages.home.checkin') }}" value="{{ @$checkin }}" readonly="readonly">
                  </label>
                </div>
                <div class="col-sm-6">
                  <label class="checkout">
                    <span class="screen-reader-only">{{ trans('messages.home.checkout') }}</span>
                    <input type="text" name="checkout" id="modal_checkout" class="checkout input-large ui-datepicker-target" placeholder="{{ trans('messages.home.checkout') }}" value="{{ @$checkout }}" readonly="readonly">
                  </label>
                </div>
              </div>
              <div class="row space-2 space-top-1">
                <div class="col-sm-12">
                  <label for="header-search-guests" class="screen-reader-only">
                    {{ trans('messages.home.no_of_guests') }}
                  </label>
                  <div class="select select-block select-large">
                    <select id="modal_guests" name="guests--sm">
                    @for($i=1;$i<=16;$i++)
                      <option value="{{ $i }}" {{ ($i == @$guest) ? 'selected' : '' }}>{{ $i }} guest{{ ($i>1) ? 's' : '' }}</option>
                    @endfor
                    </select>
                  </div>
                </div>
              </div>
              <div class="panel room-type-filter--sm row-space-top-1">
                <div class="panel-body">
                  <div class="row text-center">
                   @foreach($header_room_type as $row)

                    <label class="col-sm-4 modal-filter needsclick" for="room-type-{{ $row->id }}--sm">
					<input type="checkbox" id="room-type-{{ $row->id }}--sm" name="room_types[]" value="{{ $row->id }}" {{ (@in_array($row->id,@$room_type_selected)) ? 'checked' : '' }}>
                    @if($row->id == 1)
                    <i class="icon icon-entire-place icon-size-2 needsclick"></i>
                    @endif
                    @if($row->id == 2)
                    <i class="icon icon-private-room icon-size-2 needsclick"></i>
                    @endif
                    @if($row->id == 3)
                    <i class="icon icon-shared-room icon-size-2 needsclick"></i>
                    @endif
                    <br>{{ $row->name }}
                    </label>
                    @endforeach
                  </div>
                </div>
              </div>
              <div class="row row-space-top-2">
                <div class="col-sm-12">
                  <button type="submit" id="search-form--sm-btn" class="btn btn-primary btn-large btn-block">
                    <i class="icon icon-search"></i>
                    {{ trans('messages.header.find_place') }}
                  </button>
                </div>
              </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>
</div>
</header>

<header class="regular-header clearfix hide-sm" id="old-header" role="banner">
  <a aria-label="Homepage" href="{{ url() }}" class="header-belo pull-left rtl-right {{ (!isset($exception)) ? (Route::current()->uri() == '/' ? 'home-logo' : '') : '' }}" style="{{ (!isset($exception)) ? (Route::current()->uri() == '/' ? $home_logo_style : $logo_style) : $logo_style }}">
    <span class="screen-reader-only">
      {{ $site_name }}
    </span>
  </a>

  <!-- hide-sm -->
  @if(Request::segment(1) != 'help')
    <ul class="nav pull-left rtl-right list-unstyled search-form-container" id="search-form-header">
      <li id="header-search" class="search-bar-wrapper pull-left medium-right-margin">
        <form action="{{ url() }}/s" class="search-form">
          <div class="search-bar">
            <div id="icon-drawer-search-icon" data-reactid=".0.0.3"><svg viewBox="0 0 24 24" style="fill:currentColor;height:1em;width:1em;display:block;" data-reactid=".0.0.3.0"><path d="M23.53 22.47l-6.807-6.808A9.455 9.455 0 0 0 19 9.5 9.5 9.5 0 1 0 9.5 19c2.353 0 4.502-.86 6.162-2.277l6.808 6.807a.75.75 0 0 0 1.06-1.06zM9.5 17.5a8 8 0 1 1 0-16 8 8 0 0 1 0 16z" data-reactid=".0.0.3.0.0"></path></svg></div>
            <label class="screen-reader-only" for="header-search-form">{{ trans('messages.header.where_are_you_going') }}</label>
            <input type="text" placeholder="{{ trans('messages.header.where_are_you_going') }}" autocomplete="off" name="location" id="header-search-form" class="location" value="">
          </div>
          <div id="header-search-settings" class="panel search-settings header-menu">
            <div class="panel-body clearfix basic-settings">
              <div class="setting checkin">
                <label for="header-search-checkin" class="field-label">
                  <strong>{{ trans('messages.home.checkin') }}</strong>
                </label>
                <input type="text" id="header-search-checkin" data-field-name="check_in_dates" name="checkin" class="checkin ui-datepicker-target" placeholder="{{ trans('messages.rooms.dd-mm-yyyy') }}" readonly>
              </div>

              <div class="setting checkout">
                <label for="header-search-checkout" class="field-label">
                  <strong>{{ trans('messages.home.checkout') }}</strong>
                </label>
                <input type="text" id="header-search-checkout" data-field-name="check_out_dates" class="checkout ui-datepicker-target" name="checkout" placeholder="{{ trans('messages.rooms.dd-mm-yyyy') }}" readonly>
              </div>

  <div class="setting guests">
  <label for="header-search-guests" class="field-label">
  <strong>{{ trans_choice('messages.home.guest', 2) }}</strong>
  </label>
  <div class="select select-block">
  <select id="header-search-guests" data-field-name="number_of_guests" name="guests">
  @for($i=1;$i<=16;$i++)
    <option value="{{ $i }}"> {{ ($i == '16') ? $i.'+ ' : $i }} </option>
  @endfor
  </select>
  </div>
  </div>
  </div>

  <div class="panel-header menu-header normal-line-height">
  <small>
  <strong>{{ trans('messages.header.room_type') }}</strong>
  </small>
  </div>

  <div class="panel-body">
  <div class="row-space-4">
  @foreach($header_room_type as $row)
  <label class="checkbox menu-item" for="header_room_type_{{ $row->id }}">
  <input type="checkbox" id="header_room_type_{{ $row->id }}" name="room_types[]" value="{{ $row->id }}">
  @if($row->id == 1)
  <i class="icon icon-entire-place horizontal-margin-medium"></i>
  @endif
  @if($row->id == 2)
  <i class="icon icon-private-room horizontal-margin-medium"></i>
  @endif
  @if($row->id == 3)
  <i class="icon icon-shared-room horizontal-margin-medium"></i>
  @endif
  <span>{{ $row->name }}</span>
  </label>
  @endforeach
  </div>
  <button type="submit" class="btn btn-primary btn-block">
  <i class="icon icon-search"></i>
  <span>{{ trans('messages.header.find_place') }}</span>
  </button>
  </div>
  </div>
  </form>
</li>

  <li class="dropdown-trigger pull-left large-right-margin hide-sm hide" data-behavior="recently-viewed__container">
    <a class="no-crawl link-reset" href="{{ url() }}/login?_ga=1.237472128.1006317855.1436675116#" data-href="/history#docked-filters" data-behavior="recently-viewed__trigger">
      <span class="show-lg recently-viewed__label">
        {{ trans('messages.header.recently_viewed') }}
      </span>
      <span class="hide-lg recently-viewed__label">
        <i class="icon icon-recently-viewed icon-gray h4"></i>
      </span>
      <i class="icon icon-caret-down icon-light-gray h5"></i>
    </a>
    <div class="tooltip tooltip-top-left dropdown-menu recently-viewed__dropdown" data-behavior="recently-viewed__dropdown">
    </div>
  </li>
  <!-- show-md -->
   <!-- <li class="browse-container dropdown-trigger pull-left">
      <a class="link-reset header-browse-trigger" href="#" data-prevent-default="" id="header-browse-trigger">
  {{ trans('messages.header.browse') }}
   <i class="icon icon-caret-down icon-light-gray h5"></i>
</a>
<ul class="tooltip tooltip-top-left dropdown-menu list-unstyled">
  <li>
    <a href="{{ url('wishlists/popular') }}" class="link-reset menu-item header-browse-popular">
      {{ trans('messages.header.popular') }}
    </a>
  </li>
  <li>
    <a href="{{ url('locations') }}" class="hide link-reset menu-item header-browse-neighborhoods">
      {{ trans('messages.header.neighborhoods') }}
    </a>
  </li>
</ul>-->

 </ul>
  @endif
@if(!Auth::user()->check())
  <ul class="nav pull-right logged-out list-unstyled medium-right-margin">
    <li id="sign_up" class="pull-left medium-right-margin header_item">
      <a data-signup-modal="" data-header-view="true" href="{{ url('signup_login') }}" data-redirect="" class="link-reset">
        {{ trans('messages.header.signup') }}
      </a>
    </li>
    <li id="login" class="pull-left header_item">
      <a data-login-modal="" href="{{ url('login') }}" data-redirect="" class="link-reset">
        {{ trans('messages.header.login') }}
      </a>
    </li>
  </ul>
@endif

@if(Auth::user()->check())

 <ul class="nav pull-right list-unstyled" role="navigation">
  <li class="user-item pull-left medium-right-margin dropdown-trigger header_item">
    <a class="link-reset header-avatar-trigger" id="header-avatar-trigger" href="{{ url('login') }}">
      <span class="value_name">
        {{ Auth::user()->user()->first_name }}
      </span>
	    <div class="media-photo media-round user-profile-image" style="background-image: url('{{ Auth::user()->user()->profile_picture->header_src }}')" ></div>
    </a>
    <ul class="tooltip tooltip-top-right dropdown-menu list-unstyled header-dropdown">
      <li>
        <a href="{{ url('dashboard') }}" rel="nofollow" class="no-crawl link-reset menu-item item-user-edit">
         {{ trans('messages.header.dashboard') }}
        </a>
      </li>
		  <li>
        <a href="{{ url('users/edit') }}" rel="nofollow" class="no-crawl link-reset menu-item item-user-edit">
         {{ trans('messages.header.edit_profile') }}
        </a>
      </li>
	    <li>
        <a href="{{ url('invite') }}" class="no-crawl link-reset menu-item item-invite-friends">
          {{ trans('messages.referrals.invite') }}
          <span class="label label-pink label-new">
          </span>
        </a>
      </li>
      <li>
        <a href="{{ url('account') }}" rel="nofollow" class="no-crawl link-reset menu-item item-account">
          {{ trans('messages.header.payment_accounts') }}
        </a>
      </li>
	    <li class="edit-password">
        <a href="{{ url('users/security') }}" rel="nofollow" class="no-crawl link-reset menu-item item-edit-password">
          {{ trans('messages.header.edit_password') }}
        </a>
      </li>
      <li>
        <a href="{{ url('help') }}" rel="nofollow" class="no-crawl link-reset menu-item header-logout">
          {{ trans('messages.header.help') }}
        </a>
      </li>
      <li>
        <a href="{{ url('logout') }}" rel="nofollow" class="no-crawl link-reset menu-item header-logout">
          {{ trans('messages.header.logout') }}
        </a>
      </li>
    </ul>
  </li>
</ul>
@endif

<!--
 <ul class="nav pull-right help-menu list-unstyled">
  <li id="header-help-menu" class="help-menu-container pull-right large-right-margin hide-md dropdown-trigger header_item">
    <a class="help-toggle link-reset" href="{{ url('help') }}">

      {{ trans('messages.header.help') }}
	  @if(Auth::user()->check())
		<i class="header-icon icon-lifesaver-alt-gray" data-reactid=".3.0.2"></i>
	  @endif
    </a>
  </li>
</ul>
-->

@if(Auth::user()->check())
  <ul class="nav pull-right list-unstyled medium-right-margin">
  <li id="inbox-item" class="inbox-item pull-left dropdown-trigger js-inbox-comp header_item">
    <a href="{{ url('inbox') }}" rel="nofollow" class="no-crawl link-reset">{{ trans('messages.header.messages') }}

        <span class="text-hide hide">
          {{ trans('messages.header.inbox') }}
        </span>
        <i class="alert-count notification_highlight text-center {{ (Auth::user()->user()->inbox_count()) ? '' : 'fade' }}"></i>
		<i class="js-message-icon header-icon message-icon icon-message-gray">
			<i class="alert-count text-center {{ (Auth::user()->user()->inbox_count()) ? '' : 'fade' }}">{{ Auth::user()->user()->inbox_count() }}</i>
		</i>

    </a>
    <div class="tooltip tooltip-top-right dropdown-menu list-unstyled header-dropdown notifications-dropdown">
		<div class="notifications-tooltip panel" data-reactid=".1">
			<div class="panel-header no-border" data-reactid=".1.0"><strong data-reactid=".1.0.0"><span data-reactid=".1.0.0.0">
      <a href="{{ url('inbox') }}" rel="nofollow" class="no-crawl link-reset">{{ trans('messages.header.messages') }}</a>
      </span><span data-reactid=".1.0.0.1"> (</span><span data-reactid=".1.0.0.2">{{ Auth::user()->user()->inbox_count() }}</span><span data-reactid=".1.0.0.3">)</span><a href="{{ url('inbox') }}" class="link-reset pull-right see-all" data-reactid=".1.0.0.4">{{ trans('messages.header.view_inbox') }}</a></strong>
			</div>
			<div class="panel-body" data-reactid=".1.1">
				<ul class="notifications-list list-unstyled" data-reactid=".1.1.0">
        <?php
$messages = Auth::user()->user()->inbox();
foreach ($messages as $message) {
	?>
            <li>
            <?php
if ($message['host_check'] == 1 && $message['reservation']['status'] == 'Pending') {?>
                <a href="{{ url('reservation')}}/<?php echo $message['reservation_id']; ?>">
              <?php } elseif ($message['guest_check']) {?>
                <a href="{{ url('z/q')}}/<?php echo $message['reservation_id']; ?>">
              <?php } elseif ($message['host_check'] == 1 && $message['reservation']['status'] != 'Pending') {?>
                <a href="{{ url('messaging/qt_with')}}/<?php echo $message['reservation_id']; ?>">
              <?php }
	?>
            <img height="50" width="50" title="<?php echo $message['user_details']['first_name']; ?>" src="<?php echo $message['user_details']['profile_picture']['src']; ?>" class="media-round media-photo" alt="<?php echo $message['user_details']['first_name']; ?>">
              <?php echo $message['user_details']['first_name']; ?>
              <?php echo $message['created_time']; ?>
              <?php echo $message['message']; ?>
              </a>
            </li>
          <?php }?>
				</ul>
			</div>
			<!-- <div data-reactid=".1.2">
				<div class="panel-header no-border" data-reactid=".1.2.0"><strong data-reactid=".1.2.0.0"><span data-reactid=".1.2.0.0.0">Notifications</span><a href="/dashboard?n=2&amp;v=0&amp;m=0" class="link-reset pull-right see-all" data-reactid=".1.2.0.0.1">View Dashboard</a></strong>
				</div>
				<div class="panel-body" data-reactid=".1.2.1">
					<ul class="notifications-list list-unstyled" data-reactid=".1.2.1.0">
						<li class="read-all" data-reactid=".1.2.1.0.0"><span data-reactid=".1.2.1.0.0.0"><span data-reactid=".1.2.1.0.0.0.0">There are 2 notifications waiting for you in your </span><span data-reactid=".1.2.1.0.0.0.$0"><a href="/dashboard?n=2&amp;v=0&amp;m=0" class="link-reset underline" data-reactid=".1.2.1.0.0.0.$0.0"><span data-reactid=".1.2.1.0.0.0.$0.0.0">dashboard</span>
							</a>
							</span><span data-reactid=".1.2.1.0.0.0.2">.</span></span>
						</li>
					</ul>
				</div>
			</div> -->
		</div>
    </div>
  </li>
</ul>
<ul class="nav pull-right list-unstyled medium-right-margin">
<li class="header_item">
	<a href="{{ url('trips/current') }}" rel="nofollow" class="no-crawl link-reset menu-item item-trips">

	  {{ trans('messages.header.trips') }}
	  <i class="header-icon js-trips-icon icon-suitcase-gray">
			<i class="alert-count js-trips-unread-count text-center fade">0</i>
		</i>
	</a>
</li>
</ul>
<ul class="nav pull-right list-unstyled medium-right-margin">
<li class="header_item dropdown-trigger">
	<a href="{{ url('rooms') }}" rel="nofollow" class="no-crawl link-reset menu-item item-trips">

	  {{ trans('messages.header.host') }}
	  <i class="header-icon host-icon js-host-icon icon-home-alt-gray">
		  <i class="alert-count js-host-item-count listing-count text-center fade in">!</i>
		</i>
	</a>
	<ul class="tooltip tooltip-top-right dropdown-menu list-unstyled header-dropdown">
      <li>
        <a href="{{ url('rooms') }}" rel="nofollow" class="no-crawl link-reset menu-item item-listing">
          {{ trans('messages.header.manage_listing') }}
        </a>
      </li>
      <li>
        <a href="{{ url('my_reservations') }}" rel="nofollow" class="no-crawl link-reset menu-item header-reservations">
          {{ trans('messages.header.reservations') }}
        </a>
      </li>
	  <li>
        <a href="{{ url('users/transaction_history') }}" rel="nofollow" class="no-crawl link-reset menu-item header-transaction">
          {{ trans('messages.header.transaction_history') }}
        </a>
      </li>
	  <li>
        <a href="{{ url('users/reviews') }}" rel="nofollow" class="no-crawl link-reset menu-item header-reviews">
          {{ trans_choice('messages.header.review',2) }}
        </a>
      </li>
    </ul>
	</li>
</ul>
@endif
<ul class="nav pull-right last_menu list-unstyled">
  <li class="list-your-space pull-right header_item">
      <a id="list-your-space" class="list-your-space-btn" href="{{ url('rooms/new') }}">
        {{ trans('messages.header.list_your_space') }}
      </a>
  </li>
</ul>


</header>
</div>
<div class="load_show hide">
<div class="loading_load1 loadicon"></div>
<div class="modal-backdrop fade in"></div>
</div>
<div class="flash-container">
@if(Session::has('message') && (!Auth::user()->check() || Route::current()->uri() == 'rooms/{id}' || Route::current()->uri() == 'payments/book/{id?}'))
  <div class="alert {{ Session::get('alert-class') }}" role="alert">
    <a href="#" class="alert-close" data-dismiss="alert"></a>
  {{ Session::get('message') }}
  </div>
@endif
</div>
