<div id="js-manage-listing-content-container" class="manage-listing-content-container">
    <div class="manage-listing-content-wrapper">
        <div id="js-manage-listing-content" class="manage-listing-content">
            <div>
                <div class="row-space-4">
                    <div class="row">

                        <h3 class="col-12">{{ trans('messages.lys.pricing_title') }}</h3>

                    </div>
                    <p class="text-muted">{{ trans('messages.lys.pricing_desc') }}</p>
                </div>

                <hr>

                <div id="help-panel-nightly-price" class="js-section">
                    <div style="display: none;" class="js-saving-progress saving-progress base_price">
                        <h5>{{ trans('messages.lys.saving') }}...</h5>
                    </div>

                    <h4>{{ trans('messages.lys.base_price') }}</h4>

                    <label for="listing_price_native"
                           class="label-large">{{ trans('messages.lys.nightly_price') }}</label>
                    <div class="row row-table row-space-1">
                        <div class="col-4 col-middle">
                            <div class="input-addon">
                                <span class="input-prefix">{{ $result->rooms_price->currency->original_symbol }}</span>
                                <input type="text" data-suggested="" id="price-night"
                                       value="{{ ($result->rooms_price->original_night == 0) ? '' : $result->rooms_price->original_night }}"
                                       name="night" class="input-stem input-large autosubmit-text"
                                       data-saving="base_price">
                                <input type="hidden" id="price-night-old"
                                       value="{{ ($result->rooms_price->original_night == 0) ? '' : $result->rooms_price->original_night }}"
                                       name="night_old" class="input-stem input-large autosubmit-text">
                            </div>
                        </div>
                        <div class="col-8 col-middle">

                            <!-- <span class="text-highlight">
                             <strong>Price tip: ₹805</strong>
                           </span>

                           <span id="suggestion_disclaimer_daily" class="icon icon-question grey" <i=""></span> -->

                        </div>
                    </div>

                    <p data-error="price" class="ml-error"></p>

                    <div class="row row-space-top-3">
                        <div class="col-4">
                            <label class="label-large">{{ trans('messages.account.currency') }}</label>
                            <div id="currency-picker">
                                <div class="select
            select-large
            select-block">
                                    {!! Form::select('currency_code',$currency, $result->rooms_price->currency_code, ['id' => 'price-select-currency_code', 'data-saving' => 'base_price']) !!}
                                </div>
                            </div>
                        </div>
                    </div>

                </div>

                <p class="row-space-top-6 text-center text-muted set-long-term-prices">@lang('messages.lys.calendar_note')</p>

                @if($result->rooms_price->original_week == 0 || $result->rooms_price->original_month == 0)
                    <p id="js-set-long-term-prices" class="row-space-top-6 text-center text-muted set-long-term-prices">
                        {{ trans('messages.lys.offer_discounts') }} <a data-prevent-default="" href="#"
                                                                       id="show_long_term">{{ trans('messages.lys.weekly_monthly') }}</a> {{ trans('messages.lys.prices') }}
                        .
                    </p>

                    <hr class="row-space-top-6 row-space-5 set-long-term-prices">
                @endif

                <div id="js-advanced-pricing-content">
                    <!-- Modal for advanced pricing goes here -->
                </div>

                <div class="row-space-top-8 row-space-8 {{ ($result->rooms_price->original_week == 0 || $result->rooms_price->original_month == 0) ? 'hide' : '' }}"
                     id="js-long-term-prices">

                    <div id="js-long-term-prices" class="js-section">
                        <div style="display: none;" class="js-saving-progress saving-progress long_price">
                            <h5>{{ trans('messages.lys.saving') }}...</h5>
                        </div>

                        <h4>{{ trans('messages.lys.long_term_prices') }}</h4>

                        <div class="row-space-3">
                            <div>
                                <label for="listing_weekly_price_native" class="label-large">
                                    {{ trans('messages.lys.weekly_price') }}
                                </label>
                                <div class="row row-table row-space-1">
                                    <div class="col-4 col-middle">
                                        <div class="input-addon">
                                            <span class="input-prefix">{{ $result->rooms_price->currency->original_symbol }}</span>
                                            <input type="text" data-suggested="" id="price-week"
                                                   class="input-stem input-large autosubmit-text"
                                                   value="{{ ($result->rooms_price->original_week == 0) ? '' : $result->rooms_price->original_week }}"
                                                   name="week" data-saving="long_price">
                                        </div>
                                    </div>
                                    <div class="col-8 col-middle text-highlight">

                                        <!--  <span clas="text-highlight">
                                           <strong>Price tip: ₹4790</strong>
                                         </span>
                                         <span id="suggestion_disclaimer_weekly" class="icon icon-question grey" <i=""></span> -->


                                    </div>
                                </div>

                                <p data-error="weekly_price" class="ml-error hide"></p>
                                <div class="js-advanced-weekly-pricing"></div>
                            </div>
                        </div>

                        <div class="row-space-3">
                            <div>
                                <label for="listing_monthly_price_native" class="label-large">
                                    {{ trans('messages.lys.monthly_price') }}
                                </label>
                                <div class="row row-table row-space-1">
                                    <div class="col-4 col-middle">
                                        <div class="input-addon">
                                            <span class="input-prefix">{{ $result->rooms_price->currency->original_symbol }}</span>
                                            <input type="text" data-suggested="₹16905" id="price-month"
                                                   class="autosubmit-text input-stem input-large"
                                                   value="{{ ($result->rooms_price->original_month == 0) ? '' : $result->rooms_price->original_month }}"
                                                   name="month" data-saving="long_price">
                                        </div>
                                    </div>
                                    <div class="col-8 col-middle text-highlight">

                                        <!--  <span clas="text-highlight">
                                           <strong>Price tip: ₹16905</strong>
                                         </span>
                                         <span id="suggestion_disclaimer_monthly" class="icon icon-question grey" <i=""></span> -->


                                    </div>
                                </div>

                                <p data-error="monthly_price" class="ml-error hide"></p>
                                <span class="js-advanced-monthly-pricing"></span>
                            </div>
                        </div>

                    </div>

                </div>

                <hr class="row-space-top-6 row-space-5 post-listed">


                <div class="js-section {{ ($result->status != NULL) ? 'pre-listed' : 'post-listed' }}">
                    <div style="display: none;" class="js-saving-progress saving-progress additional-saving">
                        <h5>{{ trans('messages.lys.saving') }}...</h5>
                    </div>

                    <h4>{{ trans('messages.lys.additional_pricing') }}</h4>

                    <p class="text-muted">

                    </p>

                    <div id="js-cleaning-fee" class="row-space-3 js-tooltip-trigger">
                        <label for="listing_cleaning_fee_native_checkbox" class="label-large label-inline">
                            <input type="checkbox" data-extras="true" ng-model="cleaning_checkbox"
                                   id="listing_cleaning_fee_native_checkbox"
                                   ng-init="cleaning_checkbox = {{ ($result->rooms_price->original_cleaning == 0) ? 'false' : 'true' }}"
                                   ng-checked="{{ ($result->rooms_price->original_cleaning == 0) ? 'false' : 'true' }}">
                            {{ trans('messages.lys.cleaning') }}
                        </label>

                        <div data-checkbox-id="listing_cleaning_fee_native_checkbox" ng-show="cleaning_checkbox"
                             ng-cloak>
                            <div class="row row-table row-space-1">
                                <div class="col-4 col-middle">
                                    <div class="input-addon">
                                        <span class="input-prefix">{{ $result->rooms_price->currency->original_symbol }}</span>
                                        <input type="text" data-extras="true" id="price-cleaning"
                                               value="{{ ($result->rooms_price->original_cleaning == 0) ? '' : $result->rooms_price->original_cleaning }}"
                                               name="cleaning" class="autosubmit-text input-stem input-large"
                                               data-saving="additional-saving">
                                    </div>
                                </div>
                                <div class="col-8 col-middle">

                                </div>
                            </div>
                            <p class="text-muted">
                                {{ trans('messages.lys.cleaning_desc') }}
                            </p>
                            <p data-error="extras_price" class="ml-error"></p>
                        </div>
                    </div>


                    <div id="js-additional-guests" class="row-space-3 js-tooltip-trigger">
                        <label for="price_for_extra_person_checkbox" class="label-large label-inline">
                            <input type="checkbox" data-extras="true" ng-model="extra_person_checkbox"
                                   id="price_for_extra_person_checkbox"
                                   ng-init="extra_person_checkbox = {{ ($result->rooms_price->original_additional_guest == 0) ? 'false' : 'true' }}"
                                   ng-checked="{{ ($result->rooms_price->original_additional_guest == 0) ? 'false' : 'true' }}">
                            {{ trans('messages.lys.additional_guests') }}
                        </label>

                        <div data-checkbox-id="price_for_extra_person_checkbox" ng-show="extra_person_checkbox"
                             ng-cloak>
                            <div class="row row-space-1 row-condensed">
                                <div class="col-4">
                                    <div class="input-addon">
                                        <span class="input-prefix">{{ $result->rooms_price->currency->original_symbol }}</span>
                                        <input type="text" data-extras="true"
                                               value="{{ ($result->rooms_price->original_additional_guest == 0) ? '' : $result->rooms_price->original_additional_guest }}"
                                               id="price-extra_person" name="additional_guest"
                                               class="autosubmit-text input-stem input-large"
                                               data-saving="additional-saving">
                                    </div>
                                </div>
                                <div class="col-5 text-right">
                                    <label class="label-large">{{ trans('messages.lys.for_each_guest_after') }}</label>
                                </div>
                                <div class="col-3">
                                    <div id="guests-included-select">
                                        <div class="select
            select-large
            select-block">
                                            <select id="price-select-guests_included" name="guests"
                                                    data-saving="additional-saving">

                                                @for($i=1;$i<=16;$i++)
                                                    <option value="{{ $i }}" {{ ($result->rooms_price->guests == $i) ? 'selected' : '' }}>
                                                        {{ ($i == '16') ? $i.'+' : $i }}
                                                    </option>
                                                @endfor

                                            </select>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <p data-error="price_for_extra_person" class="ml-error">

                            </p>
                            <p class="text-muted">
                                {{ trans('messages.lys.additional_guests_desc') }}
                            </p>
                        </div>
                    </div>


                    <div id="js-security-deposit" class="row-space-3 js-tooltip-trigger">
                        <label for="listing_security_deposit_native_checkbox" class="label-large label-inline">
                            <input type="checkbox" data-extras="true" ng-model="security_checkbox"
                                   id="listing_security_deposit_native_checkbox"
                                   ng-init="security_checkbox = {{ ($result->rooms_price->original_security == 0) ? 'false' : 'true' }}"
                                   ng-checked="{{ ($result->rooms_price->original_security == 0) ? 'false' : 'true' }}">
                            {{ trans('messages.lys.security_deposit') }}
                        </label>

                        <div data-checkbox-id="listing_security_deposit_native_checkbox" ng-show="security_checkbox"
                             ng-cloak>
                            <div class="row row-space-1">
                                <div class="col-4">
                                    <div class="input-addon">
                                        <span class="input-prefix">{{ $result->rooms_price->currency->original_symbol }}</span>
                                        <input type="text" data-extras="true"
                                               value="{{ ($result->rooms_price->original_security == 0) ? '' : $result->rooms_price->original_security }}"
                                               id="price-security" name="security"
                                               class="autosubmit-text input-stem input-large"
                                               data-saving="additional-saving">
                                    </div>
                                </div>
                            </div>
                            <p data-error="security_deposit" class="ml-error"></p>
                            <p class="text-muted">
                                {{ trans('messages.lys.security_deposit_desc') }}
                            </p>
                        </div>
                    </div>


                    <div id="js-weekend-pricing" class="row-space-3 js-tooltip-trigger">
                        <label for="listing_weekend_price_native_checkbox" class="label-large label-inline">
                            <input type="checkbox" data-extras="true" ng-model="weekend_checkbox"
                                   id="listing_weekend_price_native_checkbox"
                                   ng-init="weekend_checkbox = {{ ($result->rooms_price->original_weekend == 0) ? 'false' : 'true' }}"
                                   ng-checked="{{ ($result->rooms_price->original_weekend == 0) ? 'false' : 'true' }}">
                            {{ trans('messages.lys.weekend_pricing') }}
                        </label>

                        <div data-checkbox-id="listing_weekend_price_native_checkbox" ng-show="weekend_checkbox"
                             ng-cloak>
                            <div class="row row-table row-space-1">
                                <div class="col-4">
                                    <div class="input-addon">
                                        <span class="input-prefix">{{ $result->rooms_price->currency->original_symbol }}</span>
                                        <input type="text" data-extras="true"
                                               value="{{ ($result->rooms_price->original_weekend == 0) ? '' : $result->rooms_price->original_weekend }}"
                                               id="price-weekend" name="weekend"
                                               class="autosubmit-text input-stem input-large"
                                               data-saving="additional-saving">
                                    </div>
                                </div>
                            </div>

                            <p class="text-muted">
                                {{ trans('messages.lys.weekend_pricing_desc') }}
                            </p>
                        </div>
                    </div>


                </div>


                <div id="js-donation-tool-placeholder"></div>

                <div class="not-post-listed row row-space-top-6 progress-buttons">
                    <div class="col-12">
                        <div class="separator"></div>
                    </div>


                <!-- @if($result->status == NULL)
                    <a data-prevent-default="" href="{{ url('manage-listing/'.$room_id.'/photos') }}" class="back-section-button">{{ trans('messages.lys.back') }}</a>
    @endif

                @if($result->status != NULL)
                    <a data-prevent-default="" href="{{ url('manage-listing/'.$room_id.'/calendar') }}" class="back-section-button">{{ trans('messages.lys.back') }}</a>
    @endif -->

                    <div class="col-10">
                        <div id="js-publish-button" style="float:right;">

                            <div class="not-post-listed text-center">
                            <!-- <button data-href="complete" {{ ($result->steps_count != 1) ? 'disabled' : '' }} class="animated btn btn-large btn-host btn-primary" id="js-list-space-button" data-track="list_space_button_left_nav" style="">
          {{ trans('messages.lys.list_space') }}
                                    </button> -->
                            <!-- <div  class="animated text-lead text-muted steps-remaining js-steps-remaining {{ ($result->steps_count != 0) ? 'show' : 'show' }}" ><strong class="text-highlight"> <span id="steps_count">{{ 7-$result->steps_count }}</span> / 6 </strong>{{ trans('messages.lys.steps') }} completed</div> -->

                                <div class="animated text-lead text-muted steps-remaining js-steps-remaining show"
                                     style="opacity: 1;">
                                    <div class="col-2 row-space-top-1 next_step">
                                        <a class="back-section-button"
                                           href="{{ url('manage-listing/'.$room_id.'/photos') }}"
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
                            <a data-prevent-default="" href="{{ url('manage-listing/'.$room_id.'/calendar') }}"
                               class="btn btn-large btn-primary next-section-button {{ ($result->steps_count != 0) ? '' : 'hide'}}">
                                {{ trans('messages.lys.next') }}
                            </a>
                        </div>
                    @endif

                    @if($result->status != NULL)
                        <div class="col-2 text-right next_step">
                            <a data-prevent-default="" href="{{ url('manage-listing/'.$room_id.'/calendar') }}"
                               class="btn btn-large btn-primary next-section-button">
                                {{ trans('messages.lys.next') }}
                            </a>
                        </div>
                </div>
                @endif
            </div>
        <!--<div class="col-2 text-right next_step">
    
    @if($result->status == NULL)
            <a data-prevent-default="" href="{{ url('manage-listing/'.$room_id.'/calendar') }}" class="btn btn-large btn-primary next-section-button">
        {{ trans('messages.lys.next') }}
                    </a>
                  @endif

        @if($result->status != NULL)
            <a data-prevent-default="" href="{{ url('manage-listing/'.$room_id.'/basics') }}" class="btn btn-large btn-primary next-section-button">
        {{ trans('messages.lys.next') }}
                    </a>
                  @endif

                </div>-->
        </div>

    </div>
</div>
<div id="js-manage-listing-help" class="manage-listing-help">
    <div class="manage-listing-help-panel-wrapper">
        <div class="panel manage-listing-help-panel" style="top: 178.167px;">
            <div class="help-header-icon-container text-center va-container-h">
                <img width="50" height="50" class="col-center" src="{{ url('images/lightbulb2x.png') }}">
            </div>
            <div class="panel-body">
                <h4 class="text-center">{{ trans('messages.lys.nightly_price') }}</h4>

                <p>{{ trans('messages.lys.nightly_price_desc') }}</p>

            </div>
        </div>
    </div>


</div>
</div>
<div class="manage-listing-content-background"></div>
</div>