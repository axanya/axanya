<div class="manage-listing-content-container" id="js-manage-listing-content-container">
    <div class="manage-listing-content-wrapper">
        <div class="manage-listing-content" id="js-manage-listing-content"><div>
                <div class="js-section">
                    <div class="js-saving-progress saving-progress" style="display: none;">
                        <h5>{{ trans('messages.lys.saving') }}...</h5>
                    </div>
                    <!-- Check-in and Check-out html-->
                    <div class="row-space-top-4">
                        <div class="list_frame">
                            <div class="list_frame_label">
                                {{ trans('messages.lys.check_in_check_out') }}
                            </div>
                            <form name="check_in_check_out" class="">

                                <div class="js-section list_inner_frame">
                                    <div class="js-saving-progress saving-progress basics2" style="display: none;">
                                        <h5>{{ trans('messages.lys.select') }}...</h5>
                                    </div>

                                    <!-- HTML for listing info subsection -->
                                    <div class="row row-space-2">
                                        <div class="col-4">
                                            <label class="label-large">{{ trans('messages.lys.check_in_window') }}</label>
                                            <div id="terms-type-select">
                                                <div class="select select-large select-block">
                                                    {!! Form::select('from_time',$time_array, @$rooms_policies->from_time, ['id' => 'terms-select-from-time', 'data-saving' => 'basics2']) !!}
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-1"> <label class="label-large">&nbsp;&nbsp;</label>
                                            <label class="label-large text-center">to</label> </div>
                                        <div class="col-4">
                                            <label class="label-large">&nbsp;&nbsp;</label>
                                            <div id="terms-type-select">
                                                <div class="select select-large select-block">
                                                    {!! Form::select('to_time',$time_array, @$rooms_policies->to_time, ['id' => 'terms-select-to-time', 'data-saving' => 'basics2']) !!}
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row row-space-2">
                                        <div class="col-4">
                                            <label class="label-large">{{ trans('messages.lys.checkout_by') }}</label>
                                            <div id="terms-type-select">
                                                <div class="select select-large select-block">
                                                    {!! Form::select('checkout_time',$time_array, @$rooms_policies->checkout_time, ['id' => 'terms-select-checkout-time', 'data-saving' => 'basics2']) !!}
                                                </div>
                                            </div>
                                        </div>
                                    </div>


                                </div>
                            </form>
                        </div>
                    </div>

                    <!--cancellation policy html-->
                    <div class="row-space-top-4">
                        <div class="list_frame">
                            <div class="list_frame_label">
                                {{ trans('messages.payments.cancellation_policy') }}
                            </div>
                            <form name="cancellation_policy" class="">

                                <div class="js-section list_inner_frame">
                                    <div class="js-saving-progress saving-progress basics4" style="display: none;">
                                        <h5>{{ trans('messages.lys.select') }}...</h5>
                                    </div>

                                    <!-- HTML for listing info subsection -->
                                    <div class="row row-space-top-2">
                                        <div class="col-12">
                                            <div class="radio">
                                                <label>
                                                    <input type="radio" name="cancel_policy" ng-model="cancel_policy" ng-init="cancel_policy='{{ @$rooms_policies->cancel_policy }}'" id="cancel_policy1" value="Flexible" {{ (@$rooms_policies->cancel_policy == 'Flexible') ? 'checked' : '' }} data-saving="basics4">
                                                    {{ trans('messages.lys.flexible_desc') }}
                                                </label>
                                            </div>
                                        </div>

                                        <div class="col-12">
                                            <div class="radio">
                                                <label>
                                                    <input type="radio" name="cancel_policy" ng-model="cancel_policy" ng-init="cancel_policy='{{ @$rooms_policies->cancel_policy }}'" id="cancel_policy2" value="Moderate" {{ (@$rooms_policies->cancel_policy == 'Moderate') ? 'checked' : '' }} data-saving="basics4">
                                                    {{ trans('messages.lys.moderate_desc') }}
                                                </label>
                                            </div>
                                        </div>

                                        <div class="col-12">
                                            <div class="radio">
                                                <label>
                                                    <input type="radio" name="cancel_policy" ng-model="cancel_policy" ng-init="cancel_policy='{{ @$rooms_policies->cancel_policy }}'" id="cancel_policy3" value="Strict" {{ (@$rooms_policies->cancel_policy == 'Strict') ? 'checked' : '' }} data-saving="basics4">
                                                    {{ trans('messages.lys.strict_desc') }}
                                                </label>
                                            </div>
                                        </div>

                                        <div class="col-12">
                                            <div class="" style="margin-left: 30px">
                                                <label>
                                                    <a href="{{ url('/terms_of_service') }}" target="_blank">{{ trans('messages.lys.click_here_for_policy_details') }}</a>
                                                </label>
                                            </div>
                                        </div>

                                    </div>
                            </form>
                        </div>
                    </div>

                    <!--  Guest Requirements (Optional) html -->
                    <div class="row-space-top-4">
                        <div class="list_frame">
                            <div class="list_frame_label">
                                {{ trans('messages.lys.guest_requirements') }}
                            </div>

                            <form name="guest_requirements" class="">

                                <div class="js-section list_inner_frame">
                                    <div class="row row-space-top-4">
                                        <div class="col-12">
                                            <p class="text-muted">{{ trans('messages.lys.guest_requirements_desc') }}</p>
                                        </div>
                                    </div>

                                    <div class="js-saving-progress saving-progress save_guests_req" style="display: none;">
                                        <h5>{{ trans('messages.lys.select') }}...</h5>
                                    </div>

                                    <!-- HTML for listing info subsection -->
                                    <div class="row row-space-2">
                                        <div class="col-md-6 col-sm-12">
                                            <label class="label-large label-inline">
                                                <i class="fa fa-check-square-o green" aria-hidden="true"></i>
                                                <span>{{ trans('messages.lys.confirmed_phone_number') }}</span>
                                            </label>
                                        </div>

                                        <div class="col-md-6 col-sm-12">
                                            <label class="label-large label-inline">
                                                <i class="fa fa-check-square-o green" aria-hidden="true"></i>
                                                <span>{{ trans('messages.lys.profile_photo') }}</span>
                                            </label>
                                        </div>

                                        <div class="col-md-6 col-sm-12">
                                            <label class="label-large label-inline">
                                                <i class="fa fa-check-square-o green" aria-hidden="true"></i>
                                                <span>{{ trans('messages.login.email_address') }}</span>
                                            </label>
                                        </div>

                                        <div class="col-md-6 col-sm-12">
                                            <label class="label-large label-inline">
                                                <i class="fa fa-check-square-o green" aria-hidden="true"></i>
                                                <span>{{ trans('messages.lys.payment_information') }}</span>
                                            </label>
                                        </div>

                                    </div>

                                    <div class="row row-space-top-4">
                                        <div class="col-12">
                                            <label class="label-large label-inline terms-panel">{{ trans('messages.lys.additional_requirements') }}</label>
                                            <p class="text-muted">{{ trans('messages.lys.additional_requirements_notes') }}</p>
                                        </div>

                                        <div class="col-md-6 col-sm-12">
                                            <label class="label-large label-inline terms-panel">
                                                <input id="is_personal_ref_required_termscheckbox" type="checkbox" value="0" name="is_personal_ref_required" data-saving="save_guests_req" {{ (@$rooms_policies->is_personal_ref_required == 'Yes') ? 'checked' : '' }}>
                                                <span>{{ trans('messages.lys.personal_references') }}</span>
                                            </label>
                                        </div>

                                        <div class="col-md-6 col-sm-12">
                                            <label class="label-large label-inline terms-panel">
                                                <input id="is_government_id_required_termscheckbox" type="checkbox" value="0" name="is_government_id_required" data-saving="save_guests_req" {{ (@$rooms_policies->is_government_id_required == 'Yes') ? 'checked' : '' }}>
                                                <span>{{ trans('messages.lys.government_issued_id') }} <i class="fa fa-address-card-o" aria-hidden="true"></i></span>
                                            </label>
                                        </div>

                                    </div>

                                </div>
                            </form>
                        </div>
                    </div>

                    <!-- House Rules html-->
                    <div class="row-space-top-4"><?php $yes_no_array = ['No' => 'No', 'Yes' => 'Yes'];?>
                        <div class="list_frame">
                            <div class="list_frame_label">
                                {{ trans('messages.lys.house_rules') }}
                            </div>
                            <form name="house_rules" class="">

                                <div class="js-section list_inner_frame">
                                    <div class="js-saving-progress saving-progress basics3" style="display: none;">
                                        <h5>{{ trans('messages.lys.select') }}...</h5>
                                    </div>

                                    <!-- HTML for listing info subsection -->
                                    <div class="row row-space-top-4">
                                        <div class="col-8">
                                            <label class="label-large">{{ trans('messages.lys.suitable_for_children') }}</label>
                                        </div>
                                        <div class="col-4">
                                            <div id="terms-type-select">
                                                <div class="select select-large select-block">
                                                    {!! Form::select('suitable_for_children',$yes_no_array, @$rooms_policies->suitable_for_children, ['id' => 'terms-select-suitable-for-children', 'data-saving' => 'basics3']) !!}
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="row row-space-top-4">
                                        <div class="col-8">
                                            <label class="label-large">{{ trans('messages.lys.suitable_for_infants') }}</label>
                                        </div>
                                        <div class="col-4">
                                            <div id="terms-type-select">
                                                <div class="select select-large select-block">
                                                    {!! Form::select('suitable_for_infants',$yes_no_array, @$rooms_policies->suitable_for_infants, ['id' => 'terms-select-suitable-for-infants', 'data-saving' => 'basics3']) !!}
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="row row-space-top-4">
                                        <div class="col-8">
                                            <label class="label-large">{{ trans('messages.lys.suitable_for_pets') }}</label>
                                        </div>
                                        <div class="col-4">
                                            <div id="terms-type-select">
                                                <div class="select select-large select-block">
                                                    {!! Form::select('suitable_for_pets',$yes_no_array, @$rooms_policies->suitable_for_pets, ['id' => 'terms-select-suitable-for-pets', 'data-saving' => 'basics3']) !!}
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="row row-space-top-4">
                                        <div class="col-8">
                                            <label class="label-large">{{ trans('messages.lys.smoking_allowed') }}</label>
                                        </div>
                                        <div class="col-4">
                                            <div id="terms-type-select">
                                                <div class="select select-large select-block">
                                                    {!! Form::select('smoking_allowed',$yes_no_array, @$rooms_policies->smoking_allowed, ['id' => 'terms-select-smoking-allowed', 'data-saving' => 'basics3']) !!}
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="row row-space-top-4">
                                        <div class="col-8">
                                            <label class="label-large">{{ trans('messages.lys.events_parties_allowed') }}</label>
                                        </div>
                                        <div class="col-4">
                                            <div id="terms-type-select">
                                                <div class="select select-large select-block">
                                                    {!! Form::select('parties_allowed',$yes_no_array, @$rooms_policies->parties_allowed, ['id' => 'terms-select-parties-allowed', 'data-saving' => 'basics3']) !!}
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="row row-space-top-4">
                                        <div class="col-12">
                                            <label class="label-large label-inline terms-panel">{{ trans('messages.lys.additional_rules') }}</label>
                                            <p class="text-muted">{{ trans('messages.lys.additional_rules_note') }}</p>
                                        </div>

                                        <div class="col-12" id="terms-panel-additional-rules">
                                            <textarea class="input-large textarea-resize-vertical" name="additional_rules" rows="4" placeholder="{{ trans('messages.lys.additional_rules_placeholder') }}" data-saving="basics3">{{@$rooms_policies->additional_rules}}</textarea>
                                        </div>

                                    </div>


                                </div>
                            </form>
                        </div>
                    </div>


                    <!-- Kosher html-->
                    <div class="row-space-top-4">
                        <div class="list_frame">
                            <div class="list_frame_label">
                                {{ trans('messages.lys.kosher_optional') }}
                            </div>
                            <form name="house_rules" class="">

                                <div class="js-section list_inner_frame">
                                    <div class="js-saving-progress saving-progress save_guests_req2" style="display: none;">
                                        <h5>{{ trans('messages.lys.select') }}...</h5>
                                    </div>

                                    <div class="row row-space-top-4">
                                        <div class="col-12">
                                            <label class="label-large label-inline terms-panel">
                                                <input id="is_kosher_termscheckbox" type="checkbox" value="0" name="is_kosher" data-saving="save_guests_req2" {{ (@$rooms_policies->is_kosher == 'Yes') ? 'checked' : '' }}>
                                                <span>{{ trans('messages.lys.this_space_must_be_kept_kosher') }} </span><a href="{{ url('/kosher') }}" target="_blank">{{ trans('messages.search.learn_more') }}</a>
                                            </label>
                                        </div>
                                    </div>

                                    <div class="row row-space-top-4">

                                        <div class="col-12" id="terms-panel-kosher-expectations">
                                            <p class="text-muted">{{ trans('messages.lys.define_your_kosher') }}</p>
                                            <textarea class="input-large textarea-resize-vertical" name="kosher_expectations" rows="4" placeholder="" data-saving="save_guests_req2">{{@$rooms_policies->kosher_expectations}}</textarea>
                                        </div>

                                    </div>


                                </div>
                            </form>
                        </div>
                    </div>


                    <!-- Host/Guest Interaction (Optional) html-->
                    <div class="row-space-top-4">
                        <div class="list_frame">
                            <div class="list_frame_label">
                                {{ trans('messages.lys.host_guest_interaction') }}
                            </div>
                            <form name="house_rules" class="">

                                <div class="js-section list_inner_frame">
                                    <div class="js-saving-progress saving-progress save_guests_req3" style="display: none;">
                                        <h5>{{ trans('messages.lys.select') }}...</h5>
                                    </div>

                                    <div class="row row-space-top-4">

                                        <div class="col-12" id="terms-panel-host-interaction">
                                            <p class="text-muted">{{ trans('messages.lys.host_guest_interaction_note') }}</p>
                                            <textarea class="input-large textarea-resize-vertical" name="host_interaction" rows="4" placeholder="" data-saving="save_guests_req3">{{@$rooms_policies->host_interaction}}</textarea>
                                        </div>

                                    </div>


                                </div>
                            </form>
                        </div>
                    </div>

                </div>

                <div class="not-post-listed row row-space-top-6 progress-buttons">
                    <div class="col-12">
                        <div class="separator"></div>
                    </div>


                    <!-- <a data-prevent-default="" href="{{ url('manage-listing/'.$room_id.'/details') }}" class="back-section-button">{{ trans('messages.lys.back') }}</a> -->

                    <div class="col-12">
                      <div id="js-publish-button" style="float:right;">
                        <div class="col-3 row-space-top-1 next_step mt15">
                            <a class="back-section-button" href="{{ url('manage-listing/'.$room_id.'/calendar') }}" data-prevent-default="">{{ trans('messages.lys.back') }}</a>
                        </div>
                        <div class="col-7 row-space-top-1 next_step">
                        @if($result->status == NULL)
                          <button data-href="complete" class="animated btn btn-large btn-host btn-primary {{ ($result->steps_count == 0) ? '' : 'hide'}}" id="js-list-space-button" data-track="list_space_button_left_nav" style="">
                                    {{ trans('messages.lys.list_your_space') }}
                          </button>
                          @endif

                          @if($result->steps_count != 0)
                            <a data-prevent-default="" href="{{ url('manage-listing/'.$room_id.'/verification') }}" class="animated btn btn-large btn-host btn-primary {{ ($result->steps_count != 0) ? '' : 'hide'}}">
                                  {{ trans('messages.lys.next') }}
                            </a>
                          @endif
                          </div>
                      </div>
                    </div>

                    {{-- <div class="col-12">
                        <div id="js-publish-button" style="float:right;">
                            <div class="not-post-listed text-center">

                                @if($result->status == NULL)
                                <button data-href="complete" class="animated btn btn-large btn-host btn-primary {{ ($result->steps_count == 0) ? '' : 'hide'}}" id="js-list-space-button" data-track="list_space_button_left_nav" style="">
                                    {{ trans('messages.lys.list_your_space') }}
                                </button>
                                <div class="col-3 text-right next_step">
                                    <a data-prevent-default="" href="{{ url('manage-listing/'.$room_id.'/verification') }}" class="btn btn-large btn-primary next-section-button {{ ($result->steps_count != 0) ? '' : 'hide'}}">
                                        {{ trans('messages.lys.next') }}
                                    </a>
                                </div>
                                @endif
                                @if($result->status != NULL)
                                <div class="col-3 text-right next_step">
                                    <a data-prevent-default="" href="{{ url('manage-listing/'.$room_id.'/verification') }}" class="btn btn-large btn-primary next-section-button">
                                        {{ trans('messages.lys.next') }}
                                    </a>
                                </div>

                                @endif
                            </div>
                        </div>

                        <div id="js-publish-button" style="float:right;">
                            <div class="not-post-listed text-center">
                                <div  class="animated text-lead text-muted steps-remaining js-steps-remaining show" style="opacity: 1;">
                                    <div class="col-3 row-space-top-1 next_step">
                                        <a class="back-section-button" href="{{ url('manage-listing/'.$room_id.'/calendar') }}" data-prevent-default="">{{ trans('messages.lys.back') }}</a>
                                    </div>
                                </div>
                            </div>
                        </div>


                    </div> --}}
                </div>
            </div>

        </div>
        <div class="manage-listing-content-background"></div>
    </div>

</div>
<div class="manage-listing-content-background"></div>
</div>

<style type="text/css">
    .amenity-label {
        margin-right: 0px;
    }
    .label-large {
        padding-bottom: 5px;
    }
    .label-inline {
        display: inline-block;
    }
    .label-large {
        padding-top: 11px;
    }
    input[type="radio"], input[type="checkbox"] {
        position: relative;
        -webkit-appearance: none;
        height: 1.25em;
        width: 1.25em;
        margin-bottom: -0.25em;
        margin-right: 5px;
        vertical-align: top;
    }
    [type="text"], [type="password"], [type="search"], [type="email"], [type="url"], [type="number"], [type="tel"], textarea, select, input[type="radio"], input[type="checkbox"], .input-prefix, .input-suffix {
        border: 1px solid #c4c4c4;
        border-radius: 2px;
        background-color: #fff;
        color: #565a5c;
    }
    label, input, textarea, select, input[type="radio"], input[type="checkbox"], .input-prefix, .input-suffix {
        line-height: normal;
    }

</style>