<div class="manage-listing-content-container" id="js-manage-listing-content-container">
  <div class="manage-listing-content-wrapper">
    <div class="manage-listing-content" id="js-manage-listing-content"><div>

    <div class = "list_frame">
      <div class = "list_frame_label">
        Referral Code
      </div>

      <div class="js-section" >

        <div class="container be-detail-container">
          <form name="referral" class = "list_inner_frame">
            <div class="row">
              <div class="js-saving-progress saving-progress referral-saving" style="display: none;">
                <h5>Saving...</h5>
              </div>
              <div class="col-md-6 col-md-offset-3 col-sm-11 col-sm-offset-1  mt10" ng-init="referral_code='{{ $result->referral_code }}'">
                <p>Please enter your referral code</p>

                <div class="col-sm-8 text-left">
                  <input type="text" class="referral" name="referral_code" ng-model="referral_code" id="referral_code" maxlength="15"  value="{{ $result->referral_code }}" placeholder="Referral Code" data-saving="referral-saving" ng-disabled="is_referral">
                </div>

                <div class="col-sm-8 text-center">
                  <br>
                  <p>or Select:</p>
                  <label class="label-large label-inline">
                      <input id="is_referral" type="checkbox" value="0" name="is_referral" data-saving="referral-saving" ng-model="is_referral" ng-init="is_referral = {{ ($result->is_referral == 'No') ? 'true' : 'false' }}" ng-checked="{{ ($result->is_referral == 'No') ? 'true' : 'false' }}" ng-disabled="referral_code">
                      <span>I don't have code</span>
                  </label>
                </div>
              </div>
            </div>
          </form>
        </div>
      </div>
    </div>


  <p class="row-space-top-6 not-post-listed hide">
    {{ trans('messages.lys.you_can_add_more') }} <a href="{{ url('manage-listing/'.$room_id.'/description') }}" id="js-write-more">{{ trans('messages.lys.details') }}</a> {{ trans('messages.lys.tell_travelers_about_your_space') }}
  </p>


  <div class="not-post-listed row row-space-top-6 progress-buttons">
  <div class="col-12">
    <div class="separator"></div>
  </div>



    <div class="col-12">
      <div id="js-publish-button" style="float:right;">
        <div class="col-4 row-space-top-1 next_step mt15">
            <a class="back-section-button" href="{{ url('manage-listing/'.$room_id.'/verification') }}" data-prevent-default="">{{ trans('messages.lys.back') }}</a>
        </div>
        <div class="col-7 row-space-top-1 next_step">

          @if($result->status == NULL)
          <button data-href="complete" class="animated btn btn-large btn-host btn-primary {{ ($result->steps_count == 0) ? '' : 'hide'}}" id="js-list-space-button" data-track="list_space_button_left_nav" style="">
                    {{ trans('messages.lys.list_your_space') }}
          </button>
          @endif

          @if($result->steps_count != 0)
          <button  class="next-section-button animated btn btn-large btn-host btn-primary {{ ($result->steps_count != 0) ? '' : 'hide'}}" onclick="alert('You have one or more incomplete fields.')" style="">
                    {{ trans('messages.lys.list_your_space') }}
          </button>
          @endif
        </div>
      </div>
    </div>

<!--  <div class="col-2 text-right next_step">
    <a data-prevent-default="" href="{{ url('manage-listing/'.$room_id.'/basics') }}" class="btn btn-large btn-primary next-section-button {{ ($result->steps_count != 0) ? '' : 'hide'}}">
        {{ trans('messages.lys.next') }}
      </a>
  </div> -->
</div>

@if($result->status != NULL)
<!-- <div class="col-2 text-right next_step">
    <a data-prevent-default="" href="{{ url('manage-listing/'.$room_id.'/basics') }}" class="btn btn-large btn-primary next-section-button">
        {{ trans('messages.lys.next') }}
      </a>
  </div> -->
</div>
@endif

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
