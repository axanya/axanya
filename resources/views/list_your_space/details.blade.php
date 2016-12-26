<div class="manage-listing-content-container" id="js-manage-listing-content-container">
      <div class="manage-listing-content-wrapper">
        <div class="manage-listing-content no-top-padding" id="js-manage-listing-content"><div>
  
    
  
<div class="row-space-4">
  <div class="row">
    
      <h3 class="col-12">{{ trans('messages.lys.details_title') }}</h3>
    
  </div>
  <p class="text-muted">{{ trans('messages.lys.details_desc') }}</p>
</div>

  <hr>

<div class="js-section" id="js-section-details" ng-init="space='{{ $result->rooms_description->space }}'; access='{{ $result->rooms_description->access }}'; interaction='{{ $result->rooms_description->interaction }}'; notes='{{ $result->rooms_description->notes }}'; house_rules='{{ $result->rooms_description->house_rules }}'; neighborhood_overview='{{ $result->rooms_description->neighborhood_overview }}'; transit='{{ $result->rooms_description->transit }}';">
  <div class="js-saving-progress saving-progress help-panel-saving" style="display: none;">
  <h5>{{ trans('messages.lys.saving') }}...</h5>
</div>
<div class="row-space-2">
<label for="details_later" id="help-panel-details_later">
  <input type="checkbox" name="details_later" value="1" id="details_later" {{ ($result->rooms_description->details_later == 1) ? 'checked' : '' }} >
       {{ trans('messages.lys.details_later') }}
</label>
</div>

       <hr>

  <h4>{{ trans('messages.lys.the_trip') }}</h4>

    <div class="row-space-2" id="help-panel-space">
      <label class="label-large">{{ trans('messages.lys.the_space') }}</label>
      <textarea class="input-large textarea-resize-vertical" name="space" rows="4" placeholder="{{ trans('messages.lys.space_placeholder') }}" ng-model="space">{{ $result->rooms_description->space }}</textarea>
    </div>
    <div class="row-space-2" id="help-panel-access">
      <label class="label-large">{{ trans('messages.lys.guest_access') }}</label>
      <textarea class="input-large textarea-resize-vertical" name="access" rows="4" placeholder="{{ trans('messages.lys.guest_access_placeholder') }}" ng-model="access">{{ $result->rooms_description->access }}</textarea>
    </div>
    <div class="row-space-2" id="help-panel-interaction">
      <label class="label-large">{{ trans('messages.lys.interaction_with_guests') }}</label>
      <textarea class="input-large textarea-resize-vertical" name="interaction" rows="4" placeholder="{{ trans('messages.lys.interaction_with_guests_placeholder') }}" ng-model="interaction">{{ $result->rooms_description->interaction }}</textarea>
    </div>
    <div class="row-space-2" id="help-panel-notes">
      <label class="label-large">{{ trans('messages.lys.other_things_note') }}</label>
      <textarea class="input-large textarea-resize-vertical" name="notes" rows="4" placeholder="{{ trans('messages.lys.other_things_note_placeholder') }}" ng-model="notes">{{ $result->rooms_description->notes }}</textarea>
    </div>
    <div class="row-space-2" id="help-panel-house-rules">
      <label class="label-large">{{ trans('messages.lys.house_rules') }}</label>
      <textarea class="input-large textarea-resize-vertical" name="house_rules" rows="4" placeholder="{{ trans('messages.lys.house_rules_placeholder') }}" ng-model="house_rules">{{ $result->rooms_description->house_rules }}</textarea>
    </div>

</div>

  <hr class="row-space-top-6 row-space-5">

<div class="js-section">
  <div class="js-saving-progress saving-progress help-panel-neigh-saving" style="display: none;">
  <h5>{{ trans('messages.lys.saving') }}...</h5>
</div>

  <h4>{{ trans('messages.lys.the_neighborhood') }}</h4>

    <div class="row-space-2" id="help-panel-neighborhood">
      <label class="label-large">{{ trans('messages.lys.overview') }}</label>
      <textarea class="input-large textarea-resize-vertical" name="neighborhood_overview" rows="4" placeholder="{{ trans('messages.lys.overview_placeholder') }}" ng-model="neighborhood_overview">{{ $result->rooms_description->neighborhood_overview }}</textarea>
    </div>
    <div id="help-panel-transit">
      <label class="label-large">{{ trans('messages.lys.getting_around') }}</label>
      <textarea class="input-large textarea-resize-vertical" name="transit" rows="4" placeholder="{{ trans('messages.lys.getting_around_placeholder') }}" ng-model="transit">{{ $result->rooms_description->transit }}</textarea>
    </div>
  
</div>

  <div class="not-post-listed row row-space-top-6 progress-buttons">
  <div class="col-12">
    <div class="separator"></div>
  </div>
  <div class="col-2 row-space-top-1 next_step">
  <!-- <a data-prevent-default="" href="{{ url('manage-listing/'.$room_id.'/photos') }}" class="back-section-button">{{ trans('messages.lys.back') }}</a> -->
  </div>
    <div class="col-8">
      <div id="js-publish-button" style="float:right;">

  <div class="not-post-listed text-center">

  <div  class="animated text-lead text-muted steps-remaining js-steps-remaining show" style="opacity: 1;">
    <div class="col-2 row-space-top-1 next_step">
      <a class="back-section-button" href="{{ url('manage-listing/'.$room_id.'/description') }}" data-prevent-default="">{{ trans('messages.lys.back') }}</a>
    </div>
  </div>


</div>


</div>
</div>
@if($result->status == NULL)
<button data-href="complete" class="animated btn btn-large btn-host btn-primary {{ ($result->steps_count == 0) ? '' : 'hide'}}" id="js-list-space-button" data-track="list_space_button_left_nav" style="">
          {{ trans('messages.lys.list_space') }}
</button>
 <div class="col-2 text-right next_step">
    <a data-prevent-default="" href="{{ url('manage-listing/'.$room_id.'/location') }}" class="btn btn-large btn-primary next-section-button {{ ($result->steps_count != 0) ? '' : 'hide'}}">
        {{ trans('messages.lys.next') }}
      </a>
  </div>
</div>
@endif
@if($result->status != NULL)
<div class="col-2 text-right next_step">
    <a data-prevent-default="" href="{{ url('manage-listing/'.$room_id.'/location') }}" class="btn btn-large btn-primary next-section-button">
        {{ trans('messages.lys.next') }}
      </a>
  </div>
</div>
@endif

</div></div>
        <div class="manage-listing-help" id="js-manage-listing-help"><div class="manage-listing-help-panel-wrapper">
  <div class="panel manage-listing-help-panel" style="top: 437.5px;">
    <div class="help-header-icon-container text-center va-container-h">
      {!! Html::image('images/lightbulb2x.png', '', ['class' => 'col-center', 'width' => '50', 'height' => '50']) !!}
    </div>
    <div class="panel-body">
      <h4 class="text-center">{{ trans('messages.lys.guest_access') }}</h4>
      
  <p>{{ trans('messages.lys.guest_access_desc') }}</p>

    </div>
  </div>
</div>

</div>
      </div>
      <div class="manage-listing-content-background"></div>
    </div>