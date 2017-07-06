 <!-- Left side column. contains the logo and sidebar -->
  <aside class="main-sidebar">
    <!-- sidebar: style can be found in sidebar.less -->
    <section class="sidebar">
      <!-- Sidebar user panel -->
      <div class="user-panel">
        <div class="pull-left image">
          <img src="{{ url('admin_assets/dist/img/avatar04.png') }}" class="img-circle" alt="User Image">
        </div>
        <div class="pull-left info">
          <p>{{ Auth::admin()->get()->username }}</p>
          <a href="#"><i class="fa fa-circle text-success"></i> Online</a>
        </div>
      </div>
      <!-- sidebar menu: : style can be found in sidebar.less -->
      <ul class="sidebar-menu">

        <li class="header">MAIN NAVIGATION</li>

        <li class="{{ (Route::current()->uri() == 'admin/dashboard') ? 'active' : ''  }}"><a href="{{ url('admin/dashboard') }}"><i class="fa fa-dashboard"></i><span>Dashboard</span></a></li>

        @if(Auth::admin()->user()->can('manage_admin'))
          <li class="treeview {{ (Route::current()->uri() == 'admin/admin_users' || Route::current()->uri() == 'admin/roles' || Route::current()->uri() == 'admin/permissions') ? 'active' : ''  }}">
          <a href="#">
            <i class="fa fa-user-plus"></i> <span>Manage Admin</span> <i class="fa fa-angle-left pull-right"></i>
          </a>
          <ul class="treeview-menu">
            <li class="{{ (Route::current()->uri() == 'admin/admin_users') ? 'active' : ''  }}"><a href="{{ url('admin/admin_users') }}"><i class="fa fa-circle-o"></i><span>Admin Users</span></a></li>
            <li class="{{ (Route::current()->uri() == 'admin/roles') ? 'active' : ''  }}"><a href="{{ url('admin/roles') }}"><i class="fa fa-circle-o"></i><span>Roles & Permissions</span></a></li>
          </ul>
          </li>
        @endif

        @if(Auth::admin()->user()->can('users'))
          <li class="{{ (Route::current()->uri() == 'admin/users') ? 'active' : ''  }}"><a href="{{ url('admin/users') }}"><i class="fa fa-users"></i><span>Manage Users</span></a></li>
        @endif

        @if(Auth::admin()->user()->can('rooms'))
          <li class="{{ (Route::current()->uri() == 'admin/rooms') ? 'active' : ''  }}"><a href="{{ url('admin/rooms') }}"><i class="fa fa-home"></i><span>Manage Rooms</span></a></li>
        @endif

        @if(Auth::admin()->user()->can('reservations'))
          <li class="treeview {{ (Route::current()->uri() == 'admin/reservations' || Route::current()->uri() == 'admin/host_penalty') ? 'active' : ''  }}">
          <a href="#">
            <i class="fa fa-plane"></i> <span>Reservations & Penalty</span><i class="fa fa-angle-left pull-right"></i>
          </a>
          <ul class="treeview-menu">
            <li class="{{ (Route::current()->uri() == 'admin/reservations') ? 'active' : ''  }}"><a href="{{ url('admin/reservations') }}"><i class="fa fa-circle-o"></i><span>Reservations</span></a></li>
              <li class="{{ (Route::current()->uri() == 'admin/host_penalty') ? 'active' : ''  }}"><a href="{{ url('admin/host_penalty') }}"><i class="fa fa-circle-o"></i><span>Host Penalty</span></a></li>
          </ul>
          </li>
        @endif

        @if(Auth::admin()->user()->can('email_settings') || Auth::admin()->user()->can('send_email'))
          <li class="treeview {{ (Route::current()->uri() == 'admin/email_settings' || Route::current()->uri() == 'admin/send_email') ? 'active' : ''  }}">
          <a href="#">
            <i class="fa fa-envelope-o"></i> <span>Manage Emails</span><i class="fa fa-angle-left pull-right"></i>
          </a>
          <ul class="treeview-menu">
            @if(Auth::admin()->user()->can('send_email'))
            <li class="{{ (Route::current()->uri() == 'admin/send_email') ? 'active' : ''  }}"><a href="{{ url('admin/send_email') }}"><i class="fa fa-circle-o"></i><span>Send Email</span></a></li>
            @endif
            @if(Auth::admin()->user()->can('email_settings'))
              <li class="{{ (Route::current()->uri() == 'admin/email_settings') ? 'active' : ''  }}"><a href="{{ url('admin/email_settings') }}"><i class="fa fa-circle-o"></i><span>Email Settings</span></a></li>
            @endif
          </ul>
          </li>
        @endif

        @if(Auth::admin()->user()->can('manage_reviews'))
          <li class="{{ (Route::current()->uri() == 'admin/reviews') ? 'active' : ''  }}"><a href="{{ url('admin/reviews') }}"><i class="fa fa-eye"></i><span>Reviews</span></a></li>
        @endif

        @if(Auth::admin()->user()->can('manage_wishlists'))
          <li class="{{ (Route::current()->uri() == 'admin/wishlists') ? 'active' : ''  }}"><a href="{{ url('admin/wishlists') }}"><i class="fa fa-heart"></i><span>Wish Lists</span></a></li>
        @endif

        @if(Auth::admin()->user()->can('manage_coupon_code'))
          <li class="{{ (Route::current()->uri() == 'admin/coupon_code') ? 'active' : ''  }}"><a href="{{ url('admin/coupon_code') }}"><i class="fa fa-ticket"></i><span>Manage Coupon Code</span></a></li>
        @endif

        @if(Auth::admin()->user()->can('reports'))
          <li class="{{ (Route::current()->uri() == 'admin/reports') ? 'active' : ''  }}"><a href="{{ url('admin/reports') }}"><i class="fa fa-file-text-o"></i><span>Reports</span></a></li>
        @endif

        @if(Auth::admin()->user()->can('manage_home_cities'))
          <li class="{{ (Route::current()->uri() == 'admin/home_cities') ? 'active' : ''  }}"><a href="{{ url('admin/home_cities') }}"><i class="fa fa-globe"></i><span>Manage Home Cities</span></a></li>
        @endif

        @if(Auth::admin()->user()->can('manage_help'))
          <li class="treeview {{ (Route::current()->uri() == 'admin/help' || Route::current()->uri() == 'admin/help_category' || Route::current()->uri() == 'admin/help_subcategory') ? 'active' : ''  }}">
          <a href="#">
            <i class="fa fa-support"></i> <span>Manage Help</span> <i class="fa fa-angle-left pull-right"></i>
          </a>
          <ul class="treeview-menu">
            <li class="{{ (Route::current()->uri() == 'admin/help') ? 'active' : ''  }}"><a href="{{ url('admin/help') }}"><i class="fa fa-circle-o"></i><span>Help</span></a></li>
            <li class="{{ (Route::current()->uri() == 'admin/help_category') ? 'active' : ''  }}"><a href="{{ url('admin/help_category') }}"><i class="fa fa-circle-o"></i><span>Category</span></a></li>
            <li class="{{ (Route::current()->uri() == 'admin/help_subcategory') ? 'active' : ''  }}"><a href="{{ url('admin/help_subcategory') }}"><i class="fa fa-circle-o"></i><span>Subcategory</span></a></li>
          </ul>
          </li>
        @endif

        @if(Auth::admin()->user()->can('manage_amenities'))
          <li class="treeview {{ (Route::current()->uri() == 'admin/amenities' || Route::current()->uri() == 'admin/amenities_type') ? 'active' : ''  }}">
          <a href="#">
            <i class="fa fa-bullseye"></i> <span>Manage Amenities</span> <i class="fa fa-angle-left pull-right"></i>
          </a>
          <ul class="treeview-menu">
            <li class="{{ (Route::current()->uri() == 'admin/amenities') ? 'active' : ''  }}"><a href="{{ url('admin/amenities') }}"><i class="fa fa-circle-o"></i><span>Amenities</span></a></li>
            <li class="{{ (Route::current()->uri() == 'admin/amenities_type') ? 'active' : ''  }}"><a href="{{ url('admin/amenities_type') }}"><i class="fa fa-circle-o"></i><span>Amenities Type</span></a></li>
          </ul>
          </li>

          <li class="treeview {{ (Route::current()->uri() == 'admin/religious_amenities' || Route::current()->uri() == 'admin/religious_amenities_type') ? 'active' : ''  }}">
          <a href="#">
            <i class="fa fa-bullseye"></i> <span>Manage Religious  Amenities</span> <i class="fa fa-angle-left pull-right"></i>
          </a>
          <ul class="treeview-menu">
            <li class="{{ (Route::current()->uri() == 'admin/religious_amenities') ? 'active' : ''  }}"><a href="{{ url('admin/religious_amenities') }}"><i class="fa fa-circle-o"></i><span>Religious Amenities</span></a></li>
            <li class="{{ (Route::current()->uri() == 'admin/religious_amenities_type') ? 'active' : ''  }}"><a href="{{ url('admin/religious_amenities_type') }}"><i class="fa fa-circle-o"></i><span>Religious Amenities Type</span></a></li>
          </ul>
          </li>
        @endif

        @if(Auth::admin()->user()->can('manage_property_type'))
          <li class="{{ (Route::current()->uri() == 'admin/property_type') ? 'active' : ''  }}"><a href="{{ url('admin/property_type') }}"><i class="fa fa-building"></i><span>Manage Property Type</span></a></li>
        @endif

        @if(Auth::admin()->user()->can('manage_room_type'))
          <li class="{{ (Route::current()->uri() == 'admin/room_type') ? 'active' : ''  }}"><a href="{{ url('admin/room_type') }}"><i class="fa fa-home"></i><span>Manage Room Type</span></a></li>
        @endif

        @if(Auth::admin()->user()->can('manage_bed_type'))
          <li class="{{ (Route::current()->uri() == 'admin/bed_type') ? 'active' : ''  }}"><a href="{{ url('admin/bed_type') }}"><i class="fa fa-hotel"></i><span>Manage Bed Type</span></a></li>
        @endif

        @if(Auth::admin()->user()->can('manage_pages'))
          <li class="{{ (Route::current()->uri() == 'admin/pages') ? 'active' : ''  }}"><a href="{{ url('admin/pages') }}"><i class="fa fa-newspaper-o"></i><span>Manage Static Pages</span></a></li>
        @endif

        @if(Auth::admin()->user()->can('manage_currency'))
          <li class="{{ (Route::current()->uri() == 'admin/currency') ? 'active' : ''  }}"><a href="{{ url('admin/currency') }}"><i class="fa fa-dollar"></i><span>Manage Currency</span></a></li>
        @endif

        @if(Auth::admin()->user()->can('manage_language'))
          <li class="{{ (Route::current()->uri() == 'admin/language') ? 'active' : ''  }}"><a href="{{ url('admin/language') }}"><i class="fa fa-language"></i><span>Manage Language</span></a></li>
        @endif

        @if(Auth::admin()->user()->can('manage_country'))
          <li class="{{ (Route::current()->uri() == 'admin/country') ? 'active' : ''  }}"><a href="{{ url('admin/country') }}"><i class="fa fa-globe"></i><span>Manage Country</span></a></li>
        @endif

        @if(Auth::admin()->user()->can('manage_referral_settings'))
          <li class="{{ (Route::current()->uri() == 'admin/referral_settings') ? 'active' : ''  }}"><a href="{{ url('admin/referral_settings') }}"><i class="fa fa-users"></i><span>Manage Referral Settings</span></a></li>
        @endif

        @if(Auth::admin()->user()->can('manage_fees'))
          <li class="{{ (Route::current()->uri() == 'admin/fees') ? 'active' : ''  }}"><a href="{{ url('admin/fees') }}"><i class="fa fa-dollar"></i><span>Manage Fees</span></a></li>
        @endif

        @if(Auth::admin()->user()->can('manage_metas'))
          <li class="{{ (Route::current()->uri() == 'admin/metas') ? 'active' : ''  }}"><a href="{{ url('admin/metas') }}"><i class="fa fa-bar-chart"></i><span>Manage Metas</span></a></li>
        @endif

        @if(Auth::admin()->user()->can('api_credentials'))
          <li class="{{ (Route::current()->uri() == 'admin/api_credentials') ? 'active' : ''  }}"><a href="{{ url('admin/api_credentials') }}"><i class="fa fa-facebook"></i><span>Api Credentials</span></a></li>
        @endif

        @if(Auth::admin()->user()->can('payment_gateway'))
          <li class="{{ (Route::current()->uri() == 'admin/payment_gateway') ? 'active' : ''  }}"><a href="{{ url('admin/payment_gateway') }}"><i class="fa fa-paypal"></i><span>Payment Gateway</span></a></li>
        @endif

        @if(Auth::admin()->user()->can('join_us'))
          <li class="{{ (Route::current()->uri() == 'admin/join_us') ? 'active' : ''  }}"><a href="{{ url('admin/join_us') }}"><i class="fa fa-share-alt"></i><span>Join Us Links</span></a></li>
        @endif

        @if(Auth::admin()->user()->can('site_settings'))
          <li class="{{ (Route::current()->uri() == 'admin/theme_settings') ? 'active' : ''  }}"><a href="{{ url('admin/theme_settings') }}"><i class="fa fa-eye"></i><span>Theme Settings</span></a></li>
          <li class="{{ (Route::current()->uri() == 'admin/site_settings') ? 'active' : ''  }}"><a href="{{ url('admin/site_settings') }}"><i class="fa fa-gear"></i><span>Site Settings</span></a></li>
        @endif

      </ul>
    </section>
    <!-- /.sidebar -->
  </aside>