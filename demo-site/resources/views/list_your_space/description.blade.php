<div class="manage-listing-content-container" id="js-manage-listing-content-container">
    <div class="manage-listing-content-wrapper">
        <div class="manage-listing-content" id="js-manage-listing-content">
            <div>



                <div class="panel row-space-4">
                    <div class="panel-header active-panel-header">
                        <div class="row">
                            <div class="col-6 active-panel-padding">Listing name & Summary</div>
                            <div id="ib-master-switch-container" class="col-6"></div>
                        </div>
                    </div>

                    <div class="panel-body active-panel-header">

                        <form name="overview">
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

                                    <input type="text" name="name" value="{{ $result->name }}"
                                           class="overview-title input-large name_required"
                                           placeholder="{{ trans('messages.lys.name_placeholder') }}" maxlength="50"
                                           ng-model="name">
                                    <p class="hide icon-rausch error-too-long row-space-top-1">{{ trans('messages.lys.shorten_to_save_changes') }}</p>

                                    <p class="hide icon-rausch error-value-required row-space-top-1 name_required_msg">{{ trans('messages.lys.value_is_required') }}</p>

                                </div>

                                <div id="help-panel-summary">
                                    <div class="row">
                                        <div class="col-4">
                                            <label class="label-large">{{ trans('messages.lys.summary') }}
                                                ({{ trans('messages.reviews.required') }})</label>
                                        </div>
                                        <div class="col-8">
                                            <div id="js-summary-count" class="row-space-top-1 h6 label-large text-right">
                                                <span ng-bind="500 - summary.length">500</span> {{ trans('messages.lys.characters_left') }}
                                            </div>
                                        </div>
                                    </div>

                            <textarea class="overview-summary input-large summary_required" name="summary" rows="6"
                                      placeholder="{{ trans('messages.lys.summary_placeholder') }}" maxlength="500"
                                      ng-model="summary">{{ $result->summary }}</textarea>
                                </div>
                                <p class="hide icon-rausch error-too-long row-space-top-1">{{ trans('messages.lys.shorten_to_save_changes') }}</p>

                                <p class="hide icon-rausch error-value-required row-space-top-1 summary_required_msg">{{ trans('messages.lys.value_is_required') }}</p>

                            </div>


                        </form>

                    </div>
                </div>


                <p class="row-space-top-4 not-post-listed">* text</p>

                <p class="row-space-top-6 not-post-listed hide">
                    {{ trans('messages.lys.you_can_add_more') }} <a
                            href="{{ url('manage-listing/'.$room_id.'/description') }}"
                            id="js-write-more">{{ trans('messages.lys.details') }}</a> {{ trans('messages.lys.tell_travelers_about_your_space') }}
                </p>


                <div class="not-post-listed row row-space-top-6 progress-buttons">
                    <div class="col-12">
                        <div class="separator"></div>
                    </div>


                <!-- <a class="back-section-button" href="{{ url('manage-listing/'.$room_id.'/basics') }}" data-prevent-default="">{{ trans('messages.lys.back') }}</a> -->

                    <div class="col-10">
                        <div id="js-publish-button" style="float:right;">

                            <div class="not-post-listed text-center">
                            <!-- <div  class="animated text-lead text-muted steps-remaining js-steps-remaining {{ ($result->steps_count != 0) ? 'show' : 'show' }}" style="opacity: 1;"><strong class="text-highlight"> <span id="steps_count">{{ 7-$result->steps_count }}</span> / 6</strong>{{ trans('messages.lys.steps') }} completed</div> -->

                                <div class="animated text-lead text-muted steps-remaining js-steps-remaining show"
                                     style="opacity: 1;">
                                    <div class="col-2 row-space-top-1 next_step">
                                        <a class="back-section-button"
                                           href="{{ url('manage-listing/'.$room_id.'/basics') }}"
                                           data-prevent-default="">{{ trans('messages.lys.back') }}</a>
                                    </div>
                                </div>

                            </div>


                        </div>
                    </div>
                    @if($result->status == NULL)
                        <button data-href="complete"
                                class="animated btn btn-large btn-host btn-primary {{ ($result->steps_count == 0) ? '' : 'hide'}}"
                                id="js-list-space-button" data-track="list_space_button_left_nav" style="">
                            {{ trans('messages.lys.list_space') }}
                        </button>
                        <div class="col-2 text-right next_step">
                            <a data-prevent-default="" href="{{ url('manage-listing/'.$room_id.'/details') }}"
                               class="btn btn-large btn-primary next-section-button {{ ($result->steps_count != 0) ? '' : 'hide'}}">
                                {{ trans('messages.lys.next') }}
                            </a>
                        </div>
                </div>
                @endif
                @if($result->status != NULL)
                    <div class="col-2 text-right next_step">
                        <a data-prevent-default="" href="{{ url('manage-listing/'.$room_id.'/details') }}"
                           class="btn btn-large btn-primary next-section-button">
                            {{ trans('messages.lys.next') }}
                        </a>
                    </div>
            </div>
            @endif

        </div>


    </div>
</div>
<div class="manage-listing-help" id="js-manage-listing-help">
    <div class="manage-listing-help-panel-wrapper">
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