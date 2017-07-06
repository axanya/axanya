<div class="manage-listing-content-container" id="js-manage-listing-content-container">
  <div class="manage-listing-content-wrapper">
    <div class="manage-listing-content" id="js-manage-listing-content">


      <div class="js-section" id="js-section-details" ng-init="direction_helpful_tips='{{ $result->rooms_description->direction_helpful_tips }}'; overview='{{ $result->rooms_description->overview }}'; getting_arround='{{ $result->rooms_description->getting_arround }}'; local_jewish_life='{{ $result->rooms_description->local_jewish_life }}'; location='{{ $rooms_status->location }}';">
      <!-- <textarea>{{$result->rooms_description->direction_helpful_tips}}</textarea> -->
        <div class="list_frame">
          <div class="list_frame_label">
              {{ trans('messages.your_trips.location') }}
          </div>
          <div class="list_inner_frame">
            <!-- HTML for rooms and beds subsection -->
            <div class="row row-space-2">
              <p class="text-muted">{{ trans('messages.lys.reservation_confirmed_rext') }}</p>
            </div>
            <div class="js-saving-progress saving-progress location-panel-saving" style="display: none;">
              <h5>{{ trans('messages.lys.saving') }}...</h5>
            </div>
            <div class="row">
              @if($rooms_status->location == 0)
                <div class="row-space-2" id="edit_address_field">
                  <label class="label-large">{{ trans('messages.lys.where_your_space') }}</label>
                  <input type="text" placeholder="{{ trans('messages.lys.enter_the_address') }}" value="{{ $result->rooms_address->full_address }}" class="focus" id="pac-input" name="space_location" autocomplete="off">
                  <div class="error add_error" style="display: none;"></div>
                </div>

                <div class="row row-space-2" id="edited_address_field" style="display: none;">
                  <div class="col-8">
                    <div class="panel-body">
                      <address id="saved_address">
                        <span class="address-line" ng-init="full_address = '{{ $result->rooms_address->full_address }}';">{{ $result->rooms_address->full_address }}</span>

                        {{-- <span class="address-line" ng-init="address_line_1 = '{{ $result->rooms_address->address_line_1 }}'; address_line_2 = '{{ $result->rooms_address->address_line_2 }}'">{{ $result->rooms_address->address_line_1 }} {{ ($result->rooms_address->address_line_2) ? '/ '.$result->rooms_address->address_line_2 : '' }}</span>
                        <span class="" ng-init="city = '{{ $result->rooms_address->city }}'; state = '{{ $result->rooms_address->state }}'">{{ $result->rooms_address->city }} {{ $result->rooms_address->state }}</span>
                        <span class="" ng-init="postal_code = '{{ $result->rooms_address->postal_code }}'">{{ $result->rooms_address->postal_code }}</span>
                        <span class="" ng-init="country='{{ $result->rooms_address->country }}';latitude='{{ $result->rooms_address->latitude }}';longitude='{{ $result->rooms_address->longitude }}'">{{ $result->rooms_address->country_name }}</span> --}}
                      </address>
                    </div>
                  </div>
                  <div class="col-4">
                    <div style="padding: 8px 0">
                      <button id="js-edit-address" class="btn btn-large btn-primary" style="width: 100%">
                        {{ trans('messages.lys.edit_address') }}
                      </button>
                    </div>
                  </div>
                </div>
              @else
                <div class="row-space-2" id="edit_address_field" style="display: none;">
                  <label class="label-large">{{ trans('messages.lys.where_your_space') }}</label>
                  <input type="text" placeholder="{{ trans('messages.lys.enter_the_address') }}" value="{{ $result->rooms_address->full_address }}" class="focus" id="pac-input" name="space_location" autocomplete="off">
                  <div class="error add_error" style="display: none;"></div>
                </div>

                <div class="row row-space-2" id="edited_address_field">
                  <div class="col-8">
                    <div class="panel-body">
                      <address class="{{ ($rooms_status->location == 0) ? 'hide' : '' }}" id="saved_address">
                        <span class="address-line" ng-init="full_address = '{{ $result->rooms_address->full_address }}';">{{ $result->rooms_address->full_address }}</span>

                        {{-- <span class="address-line" ng-init="address_line_1 = '{{ $result->rooms_address->address_line_1 }}'; address_line_2 = '{{ $result->rooms_address->address_line_2 }}'">{{ $result->rooms_address->address_line_1 }} {{ ($result->rooms_address->address_line_2) ? '/ '.$result->rooms_address->address_line_2 : '' }}</span>
                        <span class="" ng-init="city = '{{ $result->rooms_address->city }}'; state = '{{ $result->rooms_address->state }}'">{{ $result->rooms_address->city }} {{ $result->rooms_address->state }}</span>
                        <span class="" ng-init="postal_code = '{{ $result->rooms_address->postal_code }}'">{{ $result->rooms_address->postal_code }}</span>
                        <span class="" ng-init="country='{{ $result->rooms_address->country }}';latitude='{{ $result->rooms_address->latitude }}';longitude='{{ $result->rooms_address->longitude }}'">{{ $result->rooms_address->country_name }}</span> --}}
                      </address>
                    </div>
                  </div>
                  <div class="col-4">
                    <div style="padding: 8px 0">
                      <button id="js-edit-address" class="btn btn-large btn-primary" style="width: 100%">
                        {{ trans('messages.lys.edit_address') }}
                      </button>
                    </div>
                  </div>
                </div>
              @endif



              <div class="row-space-top-6">
                <div id="map" style="height: 70%; width: 100%"></div>
                <div id="infowindow-content" style="display: none;">
                <img src="" width="16" height="16" id="place-icon">
                <span id="place-name"  class="title"></span><br>
                <span id="place-address"></span>
              </div>
              <br />
                <p><?php echo html_entity_decode(trans('messages.lys.Your_local_laws_notes')); ?>....<a href="{{ url('/legal') }}" target="_blank">Show More</a></p>

              </div>

              <div class="row-space-2" id="help-panel-direction-helpful-tips">
                <label class="label-large">{{ trans('messages.lys.direction_helpful_tips') }}</label>
                <textarea class="input-large textarea-resize-vertical" name="direction_helpful_tips" rows="4" placeholder="{{ trans('messages.lys.direction_helpful_tips_placeholder') }}" ng-model="direction_helpful_tips">{{$result->rooms_description->direction_helpful_tips}}</textarea>
              </div>

            </div>

          </div>
        </div>

        <div class="row-space-top-6 row-space-5"></div>

        <div class="list_frame">
          <div class="list_frame_label">
              {{ trans('messages.lys.the_neighborhood_optional') }}
          </div>
          <div class="list_inner_frame">
          <div class="js-saving-progress saving-progress help-panel-saving" style="display: none;">
            <h5>{{ trans('messages.lys.saving') }}...</h5>
          </div>

            <!-- HTML for rooms and beds subsection -->
            <div class="row row-space-2">
              <div class="row-space-2" id="help-panel-overview">
                <label class="label-large">{{ trans('messages.lys.overview') }}</label>
                <textarea class="input-large textarea-resize-vertical" name="overview" rows="4" placeholder="{{ trans('messages.lys.overview_placeholder') }}" ng-model="overview">{{ $result->rooms_description->overview }}</textarea>
              </div>

              <div class="row-space-2" id="help-panel-getting-arround">
                <label class="label-large">{{ trans('messages.lys.getting_around') }}</label>
                <textarea class="input-large textarea-resize-vertical" name="getting_arround" rows="4" placeholder="{{ trans('messages.lys.getting_around_placeholder') }}" ng-model="getting_arround">{{ $result->rooms_description->getting_arround }}</textarea>
              </div>

              <div class="row-space-2" id="help-panel-local-jewish-life">
                <label class="label-large">{{ trans('messages.lys.local_jewish_life') }}</label>
                <textarea class="input-large textarea-resize-vertical" name="local_jewish_life" rows="4" placeholder="{{ trans('messages.lys.local_jewish_life_placeholder') }}" ng-model="local_jewish_life">{{ $result->rooms_description->local_jewish_life }}</textarea>
              </div>





            </div>

          </div>
        </div>


        <div id="address-flow-view"></div>

          <div class="not-post-listed row row-space-top-6 progress-buttons">
            <div class="col-12">
              <div class="separator"></div>
            </div>


                <!-- <a data-prevent-default="" href="{{ url('manage-listing/'.$room_id.'/description') }}" class="back-section-button">{{ trans('messages.lys.back') }}</a> -->


                <div class="row">
                  <div id="js-publish-button" style="float:right;">

                    <div class="not-post-listed text-center">
                    <!-- <div  class="animated text-lead text-muted steps-remaining js-steps-remaining {{ ($result->steps_count != 0) ? 'show' : 'show' }}" style="opacity: 1;"><strong class="text-highlight"> <span id="steps_count">{{ 7-$result->steps_count }}</span> / 6 </strong>{{ trans('messages.lys.steps') }} completed</div> -->

                      <div  class="animated text-lead text-muted steps-remaining js-steps-remaining show" style="opacity: 1;">
                        <div class="col-3 row-space-top-1 next_step">
                          <a class="back-section-button" href="{{ url('manage-listing/'.$room_id.'/photos') }}" data-prevent-default="">{{ trans('messages.lys.back') }}</a>
                        </div>

                        @if($result->status == NULL)
                          <button data-href="complete" class="animated btn btn-large btn-host btn-primary {{ ($result->steps_count == 0) ? '' : 'hide'}}" id="js-list-space-button" data-track="list_space_button_left_nav" style="">
                                    {{ trans('messages.lys.list_your_space') }}
                          </button>
                           <div class="col-3 text-right next_step">
                              <a data-prevent-default="" href="{{ url('manage-listing/'.$room_id.'/amenities') }}" class="btn btn-large btn-primary next-section-button {{ ($result->steps_count != 0) ? '' : 'hide'}}">
                                  {{ trans('messages.lys.next') }}
                                </a>
                            </div>
                          @endif
                          @if($result->status != NULL)
                          <div class="col-3 text-right next_step">
                              <a data-prevent-default="" href="{{ url('manage-listing/'.$room_id.'/amenities') }}" class="btn btn-large btn-primary next-section-button">
                                  {{ trans('messages.lys.next') }}
                                </a>
                            </div>
                          </div>
                          @endif
                      </div>

                    </div>


                  </div>
                </div>




      </div>
    </div>
  </div>
</div>


@push('scripts')
{{-- <script src="https://maps.googleapis.com/maps/api/js?key=AIzaSyAvg-I_zH4nmbNTIauZBLsW2yDREQ4BRWQ&libraries=places&callback=initMap"
        async defer></script> --}}

<script type="text/javascript">
var lat = <?php if ($result->rooms_address->latitude != '') {echo $result->rooms_address->latitude;} else {echo "-33.8688";}?>;
var lng = <?php if ($result->rooms_address->longitude != '') {echo $result->rooms_address->longitude;} else {echo "-151.2195";}?>;

</script>
@endpush
<script type="text/javascript">
var lat = <?php if ($result->rooms_address->latitude != '') {echo $result->rooms_address->latitude;} else {echo "-33.8688";}?>;
var lng = <?php if ($result->rooms_address->longitude != '') {echo $result->rooms_address->longitude;} else {echo "-151.2195";}?>;

</script>
