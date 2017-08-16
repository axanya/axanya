    <div class="manage-listing-content-container" id="js-manage-listing-content-container">
      <div class="manage-listing-content-wrapper">
        <div class="manage-listing-content" id="js-manage-listing-content"><div>

{{--<div class="row-space-4">
  <div class="row">

      <h3 class="col-12">{{ trans('messages.lys.amenities_title') }}</h3>

  </div>
  <p class="text-muted">{{ trans('messages.lys.amenities_desc',['site_name'=>$site_name]) }}</p>
</div>--}}

  {{--<hr>--}}
   <div class = "list_frame">
       <div class = "list_frame_label">
           {{ trans('messages.lys.list_name_summary') }}
       </div>
        <form name="overview" class = "list_inner_frame">
    <div class="js-section" ng-init="name='{{ $result->name }}'; summary='{{ $result->summary }}'; ">
      <div class="js-saving-progress saving-progress" style="display: none;">
  <h5>{{ trans('messages.lys.saving') }}...</h5>
</div>

      <div class="row-space-2 clearfix" id="help-panel-name">
        <div class="row row-space-top-2">
          <div class="col-4">
            <label class="label-large">{{ trans('messages.lys.listing_name') }}</label>
          </div>
          <div class="col-8">
            <div id="js-name-count" class="row-space-top-1 h6 label-large text-right">
              <span ng-bind="50 - name.length">50</span> {{ trans('messages.lys.characters_left') }}
            </div>
          </div>
        </div>

        <input type="text" name="name" value="{{ $result->name }}" class="overview-title input-large name_required" placeholder="{{ trans('messages.lys.name_placeholder') }}" maxlength="50" ng-model="name">
        <p class="hide icon-rausch error-too-long row-space-top-1">{{ trans('messages.lys.shorten_to_save_changes') }}</p>

        <p class="hide icon-rausch error-value-required row-space-top-1 name_required_msg">{{ trans('messages.lys.value_is_required') }}</p>

      </div>

      <div id="">
        <div class="row">
          <div class="col-4">
            <label class="label-large">{{ trans('messages.lys.summary') }} ({{ trans('messages.reviews.required') }})</label>
          </div>
          <div class="col-8">
            <div id="js-summary-count" class="row-space-top-1 h6 label-large text-right">
              <span ng-bind="500 - summary.length">500</span> {{ trans('messages.lys.characters_left') }}
            </div>
          </div>
        </div>

        <textarea class="overview-summary input-large summary_required" name="summary" rows="6" placeholder="{{ trans('messages.lys.summary_placeholder') }}" maxlength="500" ng-model="summary">{{ $result->summary }}</textarea>
      </div>
      <p class="hide icon-rausch error-too-long row-space-top-1">{{ trans('messages.lys.shorten_to_save_changes') }}</p>

      <p class="hide icon-rausch error-value-required row-space-top-1 summary_required_msg">{{ trans('messages.lys.value_is_required') }}</p>

{{--      <div class="row-space-2 clearfix" id="help-panel-name">
          <div class="row row-space-top-2">
            <div class="col-4" style="white-space: nowrap">
               <label for="koshire"><input type="checkbox" name="koshire" value="1" class="overview-title" id="koshire" ng-model="koshire" {{ ($result->koshire == 1) ? 'checked' : '' }} >
			 {{ trans('messages.lys.kosher') }}<a style="float:right;" target="_blank" href="{{ url('kosher') }}">Kosher</a></label>

            </div>
          </div>
      </div>--}}

    </div>


    </form>



    <form name="house_rules" class="">

        <div class="js-section list_inner_frame">
            <div class="js-saving-progress saving-progress save_guests_req2" style="display: none;">
                <h5>{{ trans('messages.lys.select') }}...</h5>
            </div>

            <div class="row row-space-top-4">
                <div class="col-12" ng-init="is_kosher = {{ (@$rooms_policies->is_kosher == 'Yes') ? 'true' : 'false' }}">
                    <label class="label-large label-inline terms-panel">
                        <input id="is_kosher_termscheckbox" type="checkbox" value="0" name="is_kosher" ng-model="is_kosher" data-saving="save_guests_req2" {{ (@$rooms_policies->is_kosher == 'Yes') ? 'checked' : '' }}>
                        <span>{{ trans('messages.lys.this_space_must_be_kept_kosher') }}</span>
                        <a href="{{ url('/kosher') }}" target="_blank">{{ trans('messages.search.learn_more') }}</a>
                    </label>
                    <div style="font-size: 0.9rem;" ng-show="is_kosher">{{ trans('messages.lys.kosher_notes') }}</div>
                </div>
            </div>

        </div>

        <div class="js-section list_inner_frame">
            <div class="js-saving-progress saving-progress save_guests_req3" style="display: none;">
                <h5>{{ trans('messages.lys.select') }}...</h5>
            </div>

            <div class="row">
                <div class="col-12" ng-init="is_special_accommodation = {{ (@$rooms_policies->is_special_accommodation == 'Yes') ? 'true' : 'false' }}">
                    <label class="label-large label-inline terms-panel">
                        <input id="is_special_accommodation_termscheckbox" type="checkbox" value="0" name="is_special_accommodation" ng-model="is_special_accommodation" data-saving="save_guests_req3" {{ (@$rooms_policies->is_special_accommodation == 'Yes') ? 'checked' : '' }}>
                        <span>{{ trans('messages.lys.special_accommodation_checkbox') }}</span>
                    </label>
                </div>
            </div>

        </div>

    </form>




   </div>

    <div style = "margin: 30px">
        <!--{{ trans('messages.lys.kosher_notes') }}-->
    </div>
  <p class="row-space-top-6 not-post-listed hide">
    {{ trans('messages.lys.you_can_add_more') }} <a href="{{ url('manage-listing/'.$room_id.'/description') }}" id="js-write-more">{{ trans('messages.lys.details') }}</a> {{ trans('messages.lys.tell_travelers_about_your_space') }}
  </p>


<div class="not-post-listed row row-space-top-6 progress-buttons">
    <div class="col-12">
        <div class="separator"></div>
    </div>

    <div class="row">
      <div id="js-publish-button" style="float:right;">

        <div class="not-post-listed text-center">


          <div  class="animated text-lead text-muted steps-remaining js-steps-remaining show" style="opacity: 1;">
            <div class="col-3 row-space-top-1 next_step">
            </div>

            @if($result->status == NULL)
              <button data-href="complete" class="animated btn btn-large btn-host btn-primary {{ ($result->steps_count == 0) ? '' : 'hide'}}" id="js-list-space-button" data-track="list_space_button_left_nav" style="">
                        {{ trans('messages.lys.list_your_space') }}
              </button>
               <div class="col-3 text-right next_step">
                  <a data-prevent-default="" href="{{ url('manage-listing/'.$room_id.'/basics') }}" class="btn btn-large btn-primary next-section-button {{ ($result->steps_count != 0) ? '' : 'hide'}}">
                      {{ trans('messages.lys.next') }}
                    </a>
                </div>
              @endif
              @if($result->status != NULL)
              <div class="col-3 text-right next_step">
                  <a data-prevent-default="" href="{{ url('manage-listing/'.$room_id.'/basics') }}" class="btn btn-large btn-primary next-section-button">
                      {{ trans('messages.lys.next') }}
                    </a>
                </div>
              </div>
              @endif
          </div>

        </div>
    </div>
</div>






</div></div>
        <div class="manage-listing-help" id="js-manage-listing-help"><div class="manage-listing-help-panel-wrapper">
  <div class="panel manage-listing-help-panel" style="top: 166px;">
    <div class="help-header-icon-container text-center va-container-h">
      {!! Html::image('images/lightbulb2x.png', '', ['class' => 'col-center', 'width' => '50', 'height' => '50']) !!}
    </div>
    <div class="panel-body">
      <h4 class="text-center">{{ trans('messages.lys.listing_name') }}</h4>

  <p>{{ trans('messages.lys.listing_name_desc') }}</p>
  <p>{{ trans('messages.lys.example_name') }}</p>

    </div>
  </div>
</div>

</div>
      </div>
      <div class="manage-listing-content-background"></div>
    </div>
