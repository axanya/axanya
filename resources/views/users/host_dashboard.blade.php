@extends('template')

  @section('main')  

    <main id="site-content" role="main">
      
@include('common.subheader')

<div id="host-dashboard-container"><div class="host-dashboard" data-reactid=".3"><div class="page-container-full" data-reactid=".3.0"><div class="header-color" data-reactid=".3.0.0"><div class="page-container-responsive" data-reactid=".3.0.0.0"><div class="row header-background" data-reactid=".3.0.0.0.0"><div class="col-md-8 text-contrast hide-sm" data-reactid=".3.0.0.0.0.1"><div class="row" data-reactid=".3.0.0.0.0.1.0"><div class="col-md-2" data-reactid=".3.0.0.0.0.1.0.0"><div class="va-container va-container-h collapsed-header" data-reactid=".3.0.0.0.0.1.0.0.0"><div class="va-middle" data-reactid=".3.0.0.0.0.1.0.0.0.0"><a href="{{ url('users/show') }}" class="media-photo media-round pull-right" data-tracking="{&quot;section&quot;:&quot;header_profile_photo&quot;}" data-reactid=".3.0.0.0.0.1.0.0.0.0.0"><img src="./images/original(1).jpg" data-reactid=".3.0.0.0.0.1.0.0.0.0.0.0"></a></div></div></div><div class="col-md-10" data-reactid=".3.0.0.0.0.1.0.1"><div class="va-container va-container-h collapsed-header" data-reactid=".3.0.0.0.0.1.0.1.0"><div class="va-middle text-lead" data-reactid=".3.0.0.0.0.1.0.1.0.0"><div data-reactid=".3.0.0.0.0.1.0.1.0.0.0"><strong>Hi {{ Auth::user()->user()->first_name }}!</strong> By sharing your space, you're creating a world where people can belong anywhere.</div></div></div></div></div></div><div class="text-contrast col-md-4 panel-right collapsed-header" data-reactid=".3.0.0.0.0.2"><div class="text-center" data-reactid=".3.0.0.0.0.2.0"><div class="va-container collapsed-header va-container-h" data-reactid=".3.0.0.0.0.2.0.0"><div class="va-middle text-contrast text-lead" data-reactid=".3.0.0.0.0.2.0.0.0">No Guests This Month</div></div></div></div></div></div></div></div><div class="page-container-full alt-bg-module-panel-container relative" data-reactid=".3.1"><div data-reactid=".3.1.0"><div class="page-container-responsive relative" data-reactid=".3.1.0.0"><div class="hide-sm" data-reactid=".3.1.0.0.0"><a data-prevent-default="true" href="{{ url('home/dashboard') }}" data-position="bottom" data-sticky="true" data-behavior="tooltip" class="row row-condensed circular-progress" data-reactid=".3.1.0.0.0.0" aria-label="You&#39;ve responded within 24 hours to 0 of the 0 inquiries and reservation requests you received in the past year.&lt;br /&gt;&lt;br /&gt;&lt;a href=&quot;/help/article/430&quot;&gt;Learn More&lt;/a&gt;"><div class="response-text" data-reactid=".3.1.0.0.0.0.0"><strong class="percent-number text-lead" data-reactid=".3.1.0.0.0.0.0.0"><span data-reactid=".3.1.0.0.0.0.0.0.0">0</span><span data-reactid=".3.1.0.0.0.0.0.0.1">%&nbsp;</span></strong><strong data-reactid=".3.1.0.0.0.0.0.1">Response Rate</strong></div><div class="progressbar-container" data-reactid=".3.1.0.0.0.0.1"><div style="display:block;position:relative;" data-reactid=".3.1.0.0.0.0.1.0"><svg width="36" height="36" version="1.1" data-reactid=".3.1.0.0.0.0.1.0.0"><circle r="12" cx="18" cy="18" fill="transparent" style="stroke-dashoffset:0;stroke-dasharray:75.39822368615503px;stroke-width:5;" data-reactid=".3.1.0.0.0.0.1.0.0.0"></circle><circle class="bar" r="12" cx="18" cy="18" fill="transparent" style="stroke-dashoffset:75.39822368615503;stroke-dasharray:75.39822368615503px;stroke-width:5;" data-reactid=".3.1.0.0.0.0.1.0.0.1"></circle></svg></div></div></a></div><ul role="tablist" class="tabs" data-reactid=".3.1.0.0.1"><li data-reactid=".3.1.0.0.1.0"><a href="{{ url('home/dashboard') }}" class="tab-item text-lead h4" role="tab" aria-controls="hdb-tab-standalone-first" aria-selected="true" data-tracking="{&quot;section&quot;:&quot;inbox_pending_requests_tab&quot;}" data-reactid=".3.1.0.0.1.0.0">0 Pending Requests and Inquiries</a></li><li class="relative" data-reactid=".3.1.0.0.1.1"><a href="{{ url('home/dashboard') }}" class="tab-item text-lead h4" role="tab" aria-controls="hdb-tab-standalone-second" aria-selected="false" data-tracking="{&quot;section&quot;:&quot;inbox_alerts_tab&quot;}" data-reactid=".3.1.0.0.1.1.0">Notifications</a><i class="alert-count text-center" data-reactid=".3.1.0.0.1.1.1">2</i></li></ul></div><ul class="list-unstyled page-container-responsive" data-reactid=".3.1.0.1"><li id="hdb-tab-standalone-first" class="tab-panel hdb-light-bg" role="tabpanel" aria-hidden="false" data-reactid=".3.1.0.1.0"><div class="text-lead text-muted no-req-res-row text-center" data-reactid=".3.1.0.1.0.0">You have no reservation requests or booking inquiries to respond to right now.</div></li><li id="hdb-tab-standalone-second" class="tab-panel" role="tabpanel" aria-hidden="true" data-reactid=".3.1.0.1.1"><div class="row" data-reactid=".3.1.0.1.1.0"><div class="col-12" data-reactid=".3.1.0.1.1.0.0"><div data-reactid=".3.1.0.1.1.0.0.0">
  <ul class="list-unstyled hdb-light-bg">
      <li class="default airplane removable  alert0 panel-body" data-alert-id="246540390">
        <div class="row row-table large-alert">
          <div class="col-11 col-middle">
              <a class="dashboard_alert_link" href="{{ url('invite?r=14') }}">
                Invite a friend and you both get $25 to travel.
                <i class="icon icon-caret-right"></i>
              </a>

          </div>
          <div class="col-1 col-middle">
              <a href="{{ url('home/remove_dashboard_alert/246540390') }}" class="pull-right icon-light-gray remove-alert" title="Remove Alert">
                <i class="icon icon-remove"></i>
              </a>
          </div>
        </div>
      </li>
      <li class="default   alert1 panel-body" data-alert-id="284797117">
        <div class="row row-table large-alert">
          <div class="col-11 col-middle">
              <a class="dashboard_alert_link" href="{{ url('users/payout_preferences') }}">
                Please tell us how to pay you.
                <i class="icon icon-caret-right"></i>
              </a>

          </div>
          <div class="col-1 col-middle">
          </div>
        </div>
      </li>
      <li class="default removable  alert2 panel-body" data-alert-id="284797122">
        <div class="row row-table large-alert">
          <div class="col-11 col-middle">
              <a class="dashboard_alert_link" href="{{ url('users/edit') }}">
                Add your mailing address. Sometimes we send gifts.
                <i class="icon icon-caret-right"></i>
              </a>

          </div>
          <div class="col-1 col-middle">
              <a href="{{ url('home/remove_dashboard_alert/284797122') }}" class="pull-right icon-light-gray remove-alert" title="Remove Alert">
                <i class="icon icon-remove"></i>
              </a>
          </div>
        </div>
      </li>
      <li class="default   alert3 panel-body" data-alert-id="284797159">
        <div class="row row-table large-alert">
          <div class="col-11 col-middle">
              <span class="dashboard_alert_text">
                Hello... You can always visit our <a href="{{ url('help') }}">Help Center</a> with any questions about the site.
              </span>

          </div>
          <div class="col-1 col-middle">
          </div>
        </div>
      </li>
      <li class="default   alert4 panel-body" data-alert-id="284818680">
        <div class="row row-table large-alert">
          <div class="col-11 col-middle">
              <span class="dashboard_alert_text">
                Hello... You can always visit our <a href="{{ url('help') }}">Help Center</a> with any questions about the site.
              </span>

          </div>
          <div class="col-1 col-middle">
          </div>
        </div>
      </li>
  </ul>
</div><span data-reactid=".3.1.0.1.1.0.0.1"></span></div></div></li></ul></div></div><div class="page-container-full alt-bg-module-panel-container relative" data-reactid=".3.2"><div class="page-container-responsive" data-reactid=".3.2.0"><div class="tasks-module-row row space-top-4 space-4" data-reactid=".3.2.0.0"><div class="col-md-8 tasks-list" data-reactid=".3.2.0.0.0"><div class="h4" data-reactid=".3.2.0.0.0.0">Tips to Get Guests' Attention</div><div class="space-top-5" data-reactid=".3.2.0.0.0.1"><div class="task-title text-lead" data-reactid=".3.2.0.0.0.1.0">Show Off Your Space</div><div class="task-subtitle" data-reactid=".3.2.0.0.0.1.1">Add extra photos and offer more details in your description.</div></div><div class="space-top-5" data-reactid=".3.2.0.0.0.2"><div class="task-title text-lead" data-reactid=".3.2.0.0.0.2.0">Offer a Great Deal</div><div class="task-subtitle" data-reactid=".3.2.0.0.0.2.1">Lower your price to get your first few reservations—you can always raise it later if you'd like.</div></div><div class="space-top-6" data-reactid=".3.2.0.0.0.3"><div class="task-title text-lead" data-reactid=".3.2.0.0.0.3.0">Be Flexible</div><div class="task-subtitle" data-reactid=".3.2.0.0.0.3.1">Consider shortening the minimum stay for your listing to accommodate more guests.</div></div></div><div class="col-md-4" data-reactid=".3.2.0.0.1"></div></div></div></div><div class="page-container-full alt-bg-module-panel-container relative" data-reactid=".3.6"><div class="page-container-responsive superhost-panel" data-reactid=".3.6.0"><div class="row" data-reactid=".3.6.0.0"><div class="col-md-3 col-md-push-9 space-sm-3" data-reactid=".3.6.0.0.0"><div class="sh-sprite sh-badge" data-reactid=".3.6.0.0.0.0"></div></div><div class="col-md-9 col-md-pull-3 text-center-sm" data-reactid=".3.6.0.0.1"><h4 data-reactid=".3.6.0.0.1.0">Become a Superhost</h4><p class="text-lead" data-reactid=".3.6.0.0.1.1"><span data-reactid=".3.6.0.0.1.1.0">The Superhost program celebrates and rewards hosts who reach key hospitality benchmarks.</span><span data-reactid=".3.6.0.0.1.1.1">&nbsp;</span><span data-reactid=".3.6.0.0.1.1.2">Here, you can track your progress towards receiving Superhost status.</span></p><p data-reactid=".3.6.0.0.1.2"><a href="{{ url('home/dashboard') }}" class="btn btn-large btn-beach" data-reactid=".3.6.0.0.1.2.0">Track Your Progress</a></p><p class="space-top-3" data-reactid=".3.6.0.0.1.3"><small data-reactid=".3.6.0.0.1.3.0"><a href="{{ url('superhost/terms') }}" data-reactid=".3.6.0.0.1.3.0.0">Terms and Conditions</a></small></p></div></div></div></div><div class="page-container-full alt-bg-module-panel-container relative" data-reactid=".3.7"><div class="page-container-responsive referral-panel" data-reactid=".3.7.0"><div class="panel" data-reactid=".3.7.0.0"><div class="panel-body text-center" data-reactid=".3.7.0.0.0"><div class="space-top-4 space-4" data-reactid=".3.7.0.0.0.0"><h3 data-reactid=".3.7.0.0.0.0.0"><strong data-reactid=".3.7.0.0.0.0.0.0">Invite Friends, Earn Travel Credit!</strong></h3><p data-reactid=".3.7.0.0.0.0.1">Earn up to ₹6,327 for everyone you invite.</p><a data-tracking="{&quot;section&quot;:&quot;promo_invite_friends&quot;}" href="{{ url('invite?r=2') }}" class="btn btn-large btn-primary" data-reactid=".3.7.0.0.0.0.2">Invite Friends</a></div></div></div><div class="row space-top-5" data-reactid=".3.7.0.1"><div class="col-sm-6 col-center text-center" data-reactid=".3.7.0.1.0"><a class="help-link" data-tracking="{&quot;section&quot;:&quot;help_center_link&quot;}" href="{{ url('help') }}" data-reactid=".3.7.0.1.0.0"><i class="icon icon-question icon-rausch" data-reactid=".3.7.0.1.0.0.0"></i><span data-reactid=".3.7.0.1.0.0.1">&nbsp;&nbsp;</span><span data-reactid=".3.7.0.1.0.0.2">Visit the Help Center</span></a></div></div></div></div></div></div>

    </main>
@stop      