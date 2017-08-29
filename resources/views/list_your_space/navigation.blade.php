<style type="text/css">
    .list-unstyled {
        margin: 0px !important;
    }
</style>
<div class="col-lg-2 col-md-3 listing-nav-sm nopad pos-fix col-sm-8 min-size" id="js-manage-listing-nav">
    <div class="nav-sections">

        <!--<div class="h6 section-header row-space-1 pre-listed">
      {{ trans('messages.lys.hosting') }}
                </div>-->

        <!--<div class="h6 section-header row-space-1 pre-listed">
      {{ trans('messages.lys.listing') }}
                </div>-->

        <ul class="list-unstyled row-space-5 list-nav-link">

            <li class="nav-item nav-description pre-listed" data-track="description"
                ng-class="(step == 'description') ? 'nav-active' : ''">
                <a href="{{ ($room_step != 'description') ? url('manage-listing/'.$room_id.'/description') : '' }}"
                   id="href_description">
                    <div class="row nav-item">
                        <div class="col-sm-12 va-container">
                            <span class="display-inline-middle">{{ trans('messages.lys.overview') }}</span>

                            <div class="instant-book-status pull-right">
                                <div class="instant-book-status__on hide">
                                    <i class="icon icon-bolt icon-beach h3"></i>
                                </div>
                                <div class="instant-book-status__off hide">
                                    <i class="icon icon-bolt icon-light-gray h3"></i>
                                </div>
                            </div>

                            <!-- <div class="js-new-section-icon not-post-listed pull-right transition {{ ($rooms_status->description == 1) ? 'hide' : 'visible' }}">
            <i class="nav-icon icon icon-add icon-grey"></i>
          </div> -->

                            <div class="pull-right">
                                <i class="nav-icon icon icon-ok-alt icon-babu not-post-listed {{ ($rooms_status->description == 1) ? '' : 'hide' }}"></i>
                                <i class="dot dot-success hide"></i>
                            </div>

                        </div>
                    </div>
                </a>
            </li>

            <li class="nav-item nav-basics pre-listed" data-track="basics"
                ng-class="(step == 'basics') ? 'nav-active' : ''">
                <a href="{{ ($room_step != 'basics') ? url('manage-listing/'.$room_id.'/basics') : '' }}"
                   id="href_basics">
                    <div class="row nav-item">
                        <div class="col-sm-12 va-container">
                            <span class="display-inline-middle">{{ trans('messages.lys.the_space') }}</span>

                            <div class="instant-book-status pull-right">
                                <div class="instant-book-status__on hide">
                                    <i class="icon icon-bolt icon-beach h3"></i>
                                </div>
                                <div class="instant-book-status__off hide">
                                    <i class="icon icon-bolt icon-light-gray h3"></i>
                                </div>
                            </div>


                            <div class="pull-right">
                                <i class="nav-icon icon icon-ok-alt icon-babu not-post-listed {{ ($rooms_status->basics == 1) ? '' : 'hide' }}"></i>
                                <i class="dot dot-success hide"></i>
                            </div>

                        </div>
                    </div>
                </a>
            </li>

            <li class="nav-item nav-photos pre-listed" data-track="photos"
                ng-class="(step == 'photos') ? 'nav-active' : ''">
                <a href="{{ ($room_step != 'photos') ? url('manage-listing/'.$room_id.'/photos') : '' }}"
                   id="href_photos">
                    <div class="row nav-item">
                        <div class="col-sm-12 va-container">
                            <span class="display-inline-middle">{{ trans('messages.lys.photos') }}</span>

                            <div class="instant-book-status pull-right">
                                <div class="instant-book-status__on hide">
                                    <i class="icon icon-bolt icon-beach h3"></i>
                                </div>
                                <div class="instant-book-status__off hide">
                                    <i class="icon icon-bolt icon-light-gray h3"></i>
                                </div>
                            </div>

                            <!-- <div class="js-new-section-icon not-post-listed pull-right transition {{ ($rooms_status->photos == 1) ? 'hide' : 'visible' }}">
            <i class="nav-icon icon icon-add icon-grey"></i>
          </div> -->

                            <div class="pull-right">
                                <i class="nav-icon icon icon-ok-alt icon-babu not-post-listed {{ ($rooms_status->photos == 1) ? '' : 'hide' }}"></i>
                                <i class="dot dot-success hide"></i>
                            </div>

                        </div>
                    </div>
                </a>
            </li>


            <li class="nav-item nav-location pre-listed" data-track="location"
                ng-class="(step == 'location') ? 'nav-active' : ''">
                <a href="{{ ($room_step != 'location') ? url('manage-listing/'.$room_id.'/location') : '' }}"
                   id="href_location">
                    <div class="row nav-item">
                        <div class="col-sm-12 va-container">
                            <span class="display-inline-middle">{{ trans('messages.your_trips.location') }}</span>

                            <div class="instant-book-status pull-right">
                                <div class="instant-book-status__on hide">
                                    <i class="icon icon-bolt icon-beach h3"></i>
                                </div>
                                <div class="instant-book-status__off hide">
                                    <i class="icon icon-bolt icon-light-gray h3"></i>
                                </div>
                            </div>

                            <!-- <div class="js-new-section-icon not-post-listed pull-right transition {{ ($rooms_status->location == 1) ? 'hide' : 'visible' }}">
            <i class="nav-icon icon icon-add icon-grey"></i>
          </div> -->

                            <div class="pull-right">
                                <i class="nav-icon icon icon-ok-alt icon-babu not-post-listed {{ ($rooms_status->location == 1) ? '' : 'hide' }}"></i>
                                <i class="dot dot-success hide"></i>
                            </div>

                        </div>
                    </div>
                </a>
            </li>

            <li class="nav-item nav-amenities pre-listed" data-track="amenities"
                ng-class="(step == 'amenities') ? 'nav-active' : ''">
                <a href="{{ ($room_step != 'amenities') ? url('manage-listing/'.$room_id.'/amenities') : '' }}"
                   id="href_amenities">
                    <div class="row nav-item">
                        <div class="col-sm-12 va-container">
                            <span class="display-inline-middle">{{ trans('messages.lys.amentities_accomodations') }}</span>

                            <div class="instant-book-status pull-right">
                                <div class="instant-book-status__on hide">
                                    <i class="icon icon-bolt icon-beach h3"></i>
                                </div>
                                <div class="instant-book-status__off hide">
                                    <i class="icon icon-bolt icon-light-gray h3"></i>
                                </div>
                            </div>


                            <!-- <div class="js-new-section-icon not-post-listed pull-right transition {{ ($rooms_status->amenities == 1) ? 'hide' : 'visible' }}">
            <i class="nav-icon icon icon-add icon-grey"></i>
          </div> -->

                            <div class="pull-right">
                                <i class="nav-icon icon icon-ok-alt icon-babu not-post-listed {{ ($rooms_status->amenities == 1) ? '' : 'hide' }}"></i>
                                <i class="dot dot-success hide"></i>
                            </div>

                        </div>
                    </div>
                </a>
            </li>



            <li class="nav-item nav-pricing pre-listed" data-track="pricing"
                ng-class="(step == 'pricing') ? 'nav-active' : ''">
                <a href="{{ ($room_step != 'pricing') ? url('manage-listing/'.$room_id.'/pricing') : '' }}"
                   id="href_pricing">
                    <div class="row nav-item">
                        <div class="col-sm-12 va-container">
                            <span class="display-inline-middle">{{ trans('messages.lys.fees_discount') }}</span>

                            <div class="instant-book-status pull-right">
                                <div class="instant-book-status__on hide">
                                    <i class="icon icon-bolt icon-beach h3"></i>
                                </div>
                                <div class="instant-book-status__off hide">
                                    <i class="icon icon-bolt icon-light-gray h3"></i>
                                </div>
                            </div>

                            <!-- <div class="js-new-section-icon not-post-listed pull-right transition {{ ($rooms_status->pricing == 1) ? 'hide' : 'visible' }}">
            <i class="nav-icon icon icon-add icon-grey"></i>
          </div> -->

                            <div class="pull-right">
                                <i class="nav-icon icon icon-ok-alt icon-babu not-post-listed {{ ($rooms_status->pricing == 1) ? '' : 'hide' }}"></i>
                                <i class="dot dot-success hide"></i>
                            </div>

                        </div>
                    </div>
                </a>
            </li>

            <li class="nav-item nav-calendar pre-listed" data-track="calendar"
                ng-class="(step == 'calendar') ? 'nav-active' : ''">
                <a href="{{ ($room_step != 'calendar') ? url('manage-listing/'.$room_id.'/calendar') : '' }}"
                   class="calendar-popup" id="href_calendar">
                    <div class="row nav-item">
                        <div class="col-sm-12 va-container">
                            <span class="display-inline-middle">{{ trans('messages.rooms.availability') }}</span>

                            <div class="instant-book-status pull-right">
                                <div class="instant-book-status__on hide">
                                    <i class="icon icon-bolt icon-beach h3"></i>
                                </div>
                                <div class="instant-book-status__off hide">
                                    <i class="icon icon-bolt icon-light-gray h3"></i>
                                </div>
                            </div>

                            <!-- <div class="js-new-section-icon not-post-listed pull-right hide">
                              <i class="nav-icon icon icon-add icon-grey"></i>
                            </div> -->

                            <div class="pull-right">
                                <i class="nav-icon icon icon-ok-alt icon-babu not-post-listed {{ ($rooms_status->calendar == 1) ? '' : 'hide' }}"></i>
                                <i class="dot dot-success hide"></i>
                            </div>

                        </div>
                    </div>
                </a>
            </li>

            <li class="nav-item nav-terms pre-listed" data-track="terms"
                ng-class="(step == 'terms') ? 'nav-active' : ''">
                <a href="{{ ($room_step != 'terms') ? url('manage-listing/'.$room_id.'/terms') : '' }}" id="href_terms">
                    <div class="row nav-item">
                        <div class="col-sm-12 va-container">
                            <span class="display-inline-middle">{{ trans('messages.lys.policies') }}</span>

                            <div class="instant-book-status pull-right">
                                <div class="instant-book-status__on hide">
                                    <i class="icon icon-bolt icon-beach h3"></i>
                                </div>
                                <div class="instant-book-status__off hide">
                                    <i class="icon icon-bolt icon-light-gray h3"></i>
                                </div>
                            </div>

                            <!-- <div class="js-new-section-icon not-post-listed pull-right">
                              <i class="nav-icon icon icon-add icon-grey"></i>
                            </div> -->
                            <div class="pull-right">
                                <i class="nav-icon icon icon-ok-alt icon-babu not-post-listed {{ ($rooms_status->terms == 1) ? '' : 'hide' }}"></i>
                                <i class="dot dot-success hide"></i>
                            </div>


                        </div>
                    </div>
                </a>
            </li>

            <li data-track="verification" class="nav-item nav-how-guests-book pre-listed"
                ng-class="(step == 'verification') ? 'nav-active' : ''">
                <a href="{{ ($room_step != 'verification') ? url('manage-listing/'.$room_id.'/verification') : '' }}"
                   id="href_verification">
                    <div class="row nav-item">
                        <div class="col-sm-12 va-container">
                            <div class="text-wrap display-inline-middle">
                                <span class="nav-item-name">{{ trans('messages.lys.verification') }}</span>
                            </div>
                            <div class="instant-book-status pull-right">
                                <div class="instant-book-status__on hide">
                                    <i class="icon icon-bolt icon-beach h3"></i>
                                </div>
                                <div class="instant-book-status__off hide">
                                    <i class="icon icon-bolt icon-light-gray h3"></i>
                                </div>
                            </div>

                            <!-- <div class="js-new-section-icon not-post-listed pull-right">
                              <i class="nav-icon icon icon-add icon-grey"></i>
                            </div> -->
                            <div class="pull-right">
                                <i class="nav-icon icon icon-ok-alt icon-babu not-post-listed {{ ($rooms_status->verification == 1) ? '' : 'hide' }}"></i>
                                <i class="dot dot-success hide"></i>
                            </div>

                        </div>
                    </div>
                </a>
            </li>


            <!--
            <li data-track="referral" class="nav-item nav-how-guests-book pre-listed"
                ng-class="(step == 'referral') ? 'nav-active' : ''">
                <a href="{{ ($room_step != 'referral') ? url('manage-listing/'.$room_id.'/referral') : '' }}"
                   id="href_referral">
                    <div class="row nav-item">
                        <div class="col-sm-12 va-container">
                            <div class="text-wrap display-inline-middle">
                                <span class="nav-item-name">{{ trans('messages.lys.referral_code') }}</span>
                            </div>
                            <div class="instant-book-status pull-right">
                                <div class="instant-book-status__on hide">
                                    <i class="icon icon-bolt icon-beach h3"></i>
                                </div>
                                <div class="instant-book-status__off hide">
                                    <i class="icon icon-bolt icon-light-gray h3"></i>
                                </div>
                            </div>

                            <div class="pull-right">
                                <i class="nav-icon icon icon-ok-alt icon-babu not-post-listed {{ ($rooms_status->referral == 1) ? '' : 'hide' }}"></i>
                                <i class="dot dot-success hide"></i>
                            </div>

                        </div>
                    </div>
                </a>
            </li>
          -->

            <!-- <li data-track="how-guests-book" class="nav-item nav-how-guests-book pre-listed"
                ng-class="(step == 'booking') ? 'nav-active' : ''">
                <a href="{{ ($room_step != 'booking') ? url('manage-listing/'.$room_id.'/booking') : '' }}"
                   id="href_booking">
                    <div class="row nav-item">
                        <div class="col-sm-12 va-container">
                            <div class="text-wrap display-inline-middle">
                                <span class="nav-item-name">{{ trans('messages.lys.booking') }}</span>
                            </div>
                            <div class="instant-book-status pull-right">
                                <div class="instant-book-status__on hide">
                                    <i class="icon icon-bolt icon-beach h3"></i>
                                </div>
                                <div class="instant-book-status__off hide">
                                    <i class="icon icon-bolt icon-light-gray h3"></i>
                                </div>
                            </div>
                            <div class="pull-right">
                                <i class="nav-icon icon icon-ok-alt icon-babu not-post-listed {{ ($rooms_status->booking == 1) ? '' : 'hide' }}"></i>
                                <i class="dot dot-success hide"></i>
                            </div>

                        </div>
                    </div>
                </a>
            </li> -->



            <!-- <li class="nav-item nav-details pre-listed" data-track="details"
                ng-class="(step == 'details') ? 'nav-active' : ''">
                <a href="{{ ($room_step != 'details') ? url('manage-listing/'.$room_id.'/details') : '' }}"
                   id="href_details">
                    <div class="row nav-item">
                        <div class="col-sm-12 va-container">
                            <span class="display-inline-middle">{{ trans('messages.lys.detailed_description') }}</span>

                            <div class="instant-book-status pull-right">
                                <div class="instant-book-status__on hide">
                                    <i class="icon icon-bolt icon-beach h3"></i>
                                </div>
                                <div class="instant-book-status__off hide">
                                    <i class="icon icon-bolt icon-light-gray h3"></i>
                                </div>
                            </div>

                            <div class="pull-right">
                                <i class="nav-icon icon icon-ok-alt icon-babu not-post-listed {{ ($rooms_status->details == 1) ? '' : 'hide' }}"></i>
                                <i class="dot dot-success hide"></i>
                            </div>

                        </div>
                    </div>
                </a>
            </li> -->

            @if($result->status != NULL)

                    <!-- <li class="nav-item nav-guidebook post-listed" data-track="guidebook" ng-class="(step == 'guidebook') ? 'nav-active' : ''">
  <a href="{{ ($room_step != 'guidebook') ? url('manage-listing/'.$room_id.'/guidebook') : '' }}" id="href_guidebook">
    <div class="row nav-item">
      <div class="col-sm-12 va-container">
        <span class="display-inline-middle">{{ trans('messages.lys.guidebook') }}</span>

          <div class="instant-book-status pull-right">
            <div class="instant-book-status__on hide">
              <i class="icon icon-bolt icon-beach h3"></i>
            </div>
            <div class="instant-book-status__off hide">
              <i class="icon icon-bolt icon-light-gray h3"></i>
            </div>
          </div>

        <div class="pull-right">
          <i class="nav-icon icon icon-ok-alt icon-babu nav-icon-checked not-post-listed"></i>
          <i class="dot dot-success hide"></i>
        </div>
      </div>
    </div>
  </a>
</li> -->
            @endif


            <a href="{{ ($room_step != 'calendar') ? url('manage-listing/'.$room_id.'/calendar') : '' }}"
               class="inline-calender hide" id="href_calendar"></a>
            <!--      <li class="nav-item nav-calendar pre-listed calendar-on" data-track="calendar" ng-class="(step == 'calendar') ? 'nav-active' : ''">
  <a href="{{ ($room_step != 'calendar') ? url('manage-listing/'.$room_id.'/calendar') : '' }}" class="inline-calender" id="href_calendar">
    <div class="row nav-item">
      <div class="col-sm-12 va-container">
        <span class="display-inline-middle">{{ trans('messages.lys.calendar') }}</span>

          <div class="instant-book-status pull-right">
            <div class="instant-book-status__on hide">
              <i class="icon icon-bolt icon-beach h3"></i>
            </div>
            <div class="instant-book-status__off hide">
              <i class="icon icon-bolt icon-light-gray h3"></i>
            </div>
          </div>

          <div class="js-new-section-icon not-post-listed pull-right transition {{ ($rooms_status->calendar == 1) ? 'hide' : 'visible' }}">
            <i class="nav-icon icon icon-add icon-grey"></i>
          </div>

        <div class="pull-right">
          <i class="nav-icon icon icon-ok-alt icon-babu not-post-listed {{ ($rooms_status->calendar == 1) ? '' : 'hide' }}"></i>
          <i class="dot dot-success hide"></i>
        </div>
      </div>
    </div>
  </a>
</li>-->

        </ul>


    </div>

    <a href="{{ url('rooms/'.$room_id.'?preview') }}" data-track="preview" class="subnav-item pull-right"
       id="preview-btn" title="Preview your listing as it will appear when active." target="_blank">

        <i class="icon icon-eye"></i>

        {{ ($result->status == NULL) ? trans('messages.lys.preview') : trans('messages.lys.view') }}

    </a>
    <!--<div class="publish-actions text-center" style="bottom: 20%;">
  <div id="user-suspended"></div>
  <div id="availability-dropdown">
  @if($result->status != NULL)
            <i class="dot row-space-top-2 col-top dot-{{ ($result->status == 'Listed') ? 'success' : 'danger' }}"></i>&nbsp;
<div class="select">
  <select>
    <option value="Listed" {{ ($result->status == 'Listed') ? 'selected' : '' }}>{{ trans('messages.your_listing.listed') }}</option>

    <option value="Unlisted" {{ ($result->status == 'Unlisted') ? 'selected' : '' }}>{{ trans('messages.your_listing.unlisted') }}</option>
  </select>
</div>
@endif

            </div>
              <div id="js-publish-button">
            @if($result->status == NULL)
            <div class="not-post-listed text-center">
            <div class="animated text-lead text-muted steps-remaining js-steps-remaining {{ ($result->steps_count != 0) ? 'show' : 'hide' }}" style="opacity: 1;">{{ trans('messages.lys.complete') }} <strong class="text-highlight"> <span id="steps_count">{{ $result->steps_count }}</span> {{ trans('messages.lys.steps') }} </strong> {{ trans('messages.lys.to_list_your_space') }}</div>
  <button data-href="complete" class="animated btn btn-large btn-host btn-primary" id="js-list-space-button" data-track="list_space_button_left_nav" style="{{ ($result->steps_count != '0') ? 'display: none;' : '' }}">
    {{ trans('messages.lys.list_space') }}
            </button>
          </div>
          @endif

            </div>
            </div>
            </div>-->
</div>
