<div class="manage-listing-header" id="js-manage-listing-header">
  <div class="subnav ml-header-subnav">
  <ul class="subnav-list text-center hide-sm {{ ($result->status != NULL && $room_step == 'calendar') ? 'has-collapsed-nav' : '' }}">
    <li class="show-if-collapsed-nav" id="collapsed-nav" style="width:20%;">
      <a href="" data-prevent-default="" class="subnav-item show-collapsed-nav-link">
        <i class="icon icon-reorder show-collapsed-nav-link--icon"></i>
        <span class="show-collapsed-nav-link--text">
          {{ trans('messages.lys.pricing_listing_details') }}…
        </span>
      </a>
    </li>
    <li class="subnav-text" style="width:70%;">
      <span id="listing-name">
      <span ng-hide="name">{{ ($result->name == '') ? $result->sub_name : $result->name }}</span>
      <span ng-show="name"><span ng-bind="name"></span></span>
      </span>
      
    </li>
  </ul>
    <ul class="subnav-list has-collapsed-nav hide-md">
    <li class="show-if-collapsed-nav nav-min" id="collapsed-nav">
      <a href="" data-prevent-default="" class="subnav-item show-collapsed-nav-link">
        <i class="icon icon-reorder show-collapsed-nav-link--icon"></i>
        <span class="show-collapsed-nav-link--text">
          {{ trans('messages.lys.pricing_listing_details') }}…
        </span>
      </a>
    </li>
    </ul>
</div>
</div>