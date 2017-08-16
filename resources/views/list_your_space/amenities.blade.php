<div id="js-manage-listing-content-container" class="manage-listing-content-container">
  <div class="manage-listing-content-wrapper">
    <div id="js-manage-listing-content" class="manage-listing-content">
      <div>
<!--        <div>
        <div class="space-4">
        <h3>{{ trans('messages.lys.amenities_title') }}</h3>
        <p class="text-muted">{{ trans('messages.lys.amenities_desc',['site_name'=>$site_name]) }}</p>
        </div>
        <hr>
        </div>-->

<!--        <div>
        <div class="js-section">
        <div style="display:none;" class="js-saving-progress saving-progress save_no_amenities">
        <h5>{{ trans('messages.lys.saving') }}...</h5>
        </div>
        <input type="hidden" ng-model="amenities_status" value="{{ count($prev_amenities) }}" ng-init=" amenities_status = '{{ count($prev_amenities) }}' " />
        <span>&nbsp;&nbsp;</span>
        <label class="label-large label-inline amenity-label">
        <input type="checkbox" value="0" name="amenities" data-saving="save_no_amenities" {{ in_array("0", $prev_amenities) ? 'checked' : '' }} >
        <span>{{ trans('messages.new.no_amenities') }}</span>
        </label>
        <span>&nbsp;</span>

        </div>
        </div>-->

        <div class="list_frame">
                <div class="list_frame_label">
                    <!--{{ trans('messages.lys.property_room_type') }}-->
                    {{ trans('messages.lys.amentities_optional') }}
                </div>
<!--                <form name="overview" class="list_inner_frame">-->
                    <div class = "list_inner_frame clearfix" style = "margin-bottom:10px;">
        <?php $i = 0;?>
        @foreach($amenities_type as $row_type)
        <?php $i++;?>
        <?php
if ($i == 1 || $i == 3) {
	echo "<div class = 'col-sm-12 col-md-6 rtl-right'>";
}
?>
        <div>
        <div class="js-section">
        <div style="display:none;" class="js-saving-progress saving-progress {{ $row_type->id }}">
        <h5>{{ trans('messages.lys.saving') }}...</h5>
        </div>

        <h4 style = "margin-top:20px; margin-bottom: 10px;">{{ \App::getLocale() == 'iw' ? $row_type->name_iw : $row_type->name }}</h4>

        @if($row_type->description != '')
        <p class="text-muted" style = "display:none;">{{ $row_type->description }}</p>
        @endif

        <ul class="list-unstyled">
        @foreach($amenities as $row_amenities)

        @if($row_amenities->type_id == $row_type->id)
        <li>
        <span>&nbsp;&nbsp;</span>
        <label class="label-large label-inline amenity-label">
        <input type="checkbox" value="{{ $row_amenities->id }}" name="amenities" data-saving="{{ $row_type->id }}" {{ in_array($row_amenities->id, $prev_amenities) ? 'checked' : '' }}>
        <span>{{ \App::getLocale() == 'iw' ? $row_amenities->name_iw : $row_amenities->name }}</span>
        </label>
        <span>&nbsp;</span>

        @if($row_amenities->description != '')
        <span data-toggle="tooltip" class="icon icon-question" title="{{ $row_amenities->description }}"></span>
        @endif

        </li>
        @endif

        @endforeach

        </ul>

<!--        <hr class="space-top-4 space-4">-->
        </div>
        </div>
        <?php
if ($i == 2 || $i == 7) {
	echo "</div>";
}
?>
        @endforeach

            <!--</form>-->
            </div>
            <div class="calendar_section border-top" style="display:{{ ($rooms_policies && ($rooms_policies->is_special_accommodation == 'Yes' || $rooms_policies->is_kosher == 'Yes')) ? 'none' : 'block' }};">
              <p>{{ trans('messages.lys.do_not_have_special_accommodations') }}</p>
              <a href="javascript:void(0)" id="special_accommodation_toggle">{{ trans('messages.lys.toggle_special_accommodations') }}</a>
            </div>
        </div>


        <div id="special_accommodation_wrapper" style="display:{{ ($rooms_policies && ($rooms_policies->is_special_accommodation == 'Yes' || $rooms_policies->is_kosher == 'Yes')) ? 'block' : 'none' }};">
            <div class="list_frame" style = "margin-top: 30px;">
              <div class="list_frame_label">
                  {{ trans('messages.lys.religious_accomodations_optional') }}
              </div>
              <div class = "list_inner_frame clearfix" style = "margin-bottom:10px;">

            <div class="js-section">
                <div style="display:none;" class="js-saving-progress saving-progress save_religious_amenities">
                    <h5>{{ trans('messages.lys.saving') }}...</h5>
                </div>
                <div class="row" >
                @foreach($religious_amenities_types->chunk(ceil($religious_amenities_types->count()/2)) as $religious_amenities_type_array)
                    <div class="col-md-6 rtl-right">
                        @foreach($religious_amenities_type_array as $religious_amenities_type)
                            <div class="space-3">
                                <h4 style = "margin-top:20px; margin-bottom: 10px;">{{ \App::getLocale() == 'iw' ? $religious_amenities_type->name_iw : $religious_amenities_type->name }} @if($religious_amenities_type->description != '')<span class="" style="font-weight:normal;">({{$religious_amenities_type->description}})</span>  @endif</h4>
                                <ul class="list-unstyled">
                                    @foreach($religious_amenities as $religous_amenity)
                                        @if($religous_amenity->type_id == $religious_amenities_type->id)
                                        <li style="margin-left:10px;">
                                            <!-- <span>&nbsp;&nbsp;</span> -->
                                            <label class="label-large label-inline amenity-label">
                                                <input type="checkbox" data-extra="@if($religous_amenity->extra_input == 'text')Yes @endif" value="{{ $religous_amenity->id }}" name="religious_amenities" data-saving="save_religious_amenities" {{ in_array($religous_amenity->id, $prev_religious_amenities) ? 'checked' : '' }}>
                                                <span>{{ \App::getLocale() == 'iw' ? $religous_amenity->name_iw : $religous_amenity->name }} @if($religous_amenity->description != '')({{$religous_amenity->description}})@endif</span>
                                            </label>
                                            @if($religous_amenity->extra_input == 'text')
                                            <div class="row row-condensed religious_amenity_extra_block {{ in_array($religous_amenity->id, $prev_religious_amenities) ? '' : 'hide' }}" id="religious_amenity_extra_{{$religous_amenity->id}}">
                                                <div class="col-md-1">
                                                </div>
                                                <div class="col-md-6">
                                                    <label class="label-inline">{{trans('messages.new.distance')}}</label>
                                                </div>
                                                <div class="col-md-5">
                                                    <input type="text" id="religious_amenities_extra_data_{{$religous_amenity->id}}" name="{{$religous_amenity->id}}"  data-saving="save_religious_amenities" value="{{@$religious_amenities_extra_data[$religous_amenity->id]}}">
                                                </div>
                                            </div>
                                            @endif
                                            <span>&nbsp;</span>
                                        </li>
                                        @endif
                                    @endforeach
                                </ul>
                            </div>
                        @endforeach
                    </div>
                @endforeach
                </div>
<!--                <hr class="space-top-4 space-4">-->
            </div>
                </div>
                </div>
        </div>


<div class="not-post-listed row space-top-6 next_step">

<div class="col-12">
        <div class="separator"></div>
    </div>

    <div class="row">
      <div id="js-publish-button" style="float:right;">

        <div class="not-post-listed text-center">
        <!-- <div  class="animated text-lead text-muted steps-remaining js-steps-remaining {{ ($result->steps_count != 0) ? 'show' : 'show' }}" style="opacity: 1;"><strong class="text-highlight"> <span id="steps_count">{{ 7-$result->steps_count }}</span> / 6 </strong>{{ trans('messages.lys.steps') }} completed</div> -->

          <div  class="animated text-lead text-muted steps-remaining js-steps-remaining show" style="opacity: 1;">
            <div class="col-3 row-space-top-1 next_step">
              <a class="back-section-button" href="{{ url('manage-listing/'.$room_id.'/location') }}" data-prevent-default="">{{ trans('messages.lys.back') }}</a>
            </div>

            @if($result->status == NULL)
              <button data-href="complete" class="animated btn btn-large btn-host btn-primary {{ ($result->steps_count == 0) ? '' : 'hide'}}" id="js-list-space-button" data-track="list_space_button_left_nav" style="">
                        {{ trans('messages.lys.list_your_space') }}
              </button>
               <div class="col-3 text-right next_step">
                  <a data-prevent-default="" href="{{ url('manage-listing/'.$room_id.'/pricing') }}" class="btn btn-large btn-primary next-section-button {{ ($result->steps_count != 0) ? '' : 'hide'}}">
                      {{ trans('messages.lys.next') }}
                    </a>
                </div>
              @endif
              @if($result->status != NULL)
              <div class="col-3 text-right next_step">
                  <a data-prevent-default="" href="{{ url('manage-listing/'.$room_id.'/pricing') }}" class="btn btn-large btn-primary next-section-button">
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

        <div id="js-manage-listing-help" class="manage-listing-help hide"></div>
      </div>
      <div class="manage-listing-content-background"></div>
    </div>
