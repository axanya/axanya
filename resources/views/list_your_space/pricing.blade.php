<div id="js-manage-listing-content-container" class="manage-listing-content-container">
    <div class="manage-listing-content-wrapper">
        <div id="js-manage-listing-content" class="manage-listing-content"><div>
                <!--<div class="row-space-4">
                <div class="row">

                <h3 class="col-12">{{ trans('messages.lys.pricing_title') }}</h3>

                </div>
                <p class="text-muted">{{ trans('messages.lys.pricing_desc') }}</p>
                </div>-->

                <!--<hr>-->
                <div class="list_frame" style = "margin-bottom: 30px">
                  <!-- {{ App\Models\Currency::user_symbol() }} -->
                    <div class="list_frame_label">
                        <!--{{ trans('messages.lys.property_room_type') }}-->
                        {{ trans('messages.lys.default_nightly_rate') }}
                    </div>
                    <!--                <form name="overview" class="list_inner_frame">-->
                    <div class = "list_inner_frame clearfix" style = "margin-bottom:10px;">
                        <div id="help-panel-nightly-price" class="js-section">
                            <div style="display:none;" class="js-saving-progress saving-progress base_price">
                                <h5>{{ trans('messages.lys.saving') }}...</h5>
                            </div>

                            <!--  <h4>{{ trans('messages.lys.base_price') }}</h4>-->

                            <!--  <label for="listing_price_native" class="label-large">{{ trans('messages.lys.nightly_price') }}</label>-->

                            <div class="row row-table row-space-1">
                                <div class="col-4 ">
                                    <label for="listing_price_native" class="label-large">{{ trans('messages.lys.base_price') }}</label>
                                    <div class="input-addon">
                                        <span class="input-prefix">{{ $result->rooms_price->currency->original_symbol }}</span>
                                        <input type="number" data-suggested="" id="price-night" value="{{ ($result->rooms_price->original_night == 0) ? '' : $result->rooms_price->original_night }}" name="night" class="input-stem input-large autosubmit-text" data-saving="base_price">
                                        <input type="hidden" id="price-night-old" value="{{ ($result->rooms_price->original_night == 0) ? '' : $result->rooms_price->original_night }}" name="night_old" class="input-stem input-large autosubmit-text">
                                    </div>
                                </div>
                                <div class="col-4 ">
                                    <label class="label-large">{{ trans('messages.account.currency') }}</label>
                                    <div id="currency-picker">
                                        <div class="select select-large select-block">
                                            <select name="currency_code" id="price-select-currency_code" data-saving="base_price">

                                                @foreach($get_currency as $val)
                                                @if($val->code == 'USD' || $val->code == 'AUD' || $val->code == 'GBP' || $val->code == 'EUR' || $val->code == 'CAD')
                                                <option value="{{$val->code}}"<?php echo $result->rooms_price->currency_code == $val->code ? ' selected="selected"' : '' ?>>{{$val->code}} - {{$val->name}}</option>
                                                @endif
                                                @endforeach

                                                <option disabled="">------------------------------------</option>

                                                @foreach($get_currency as $val)
                                                @if($val->code != 'USD' && $val->code != 'AUD' && $val->code != 'GBP' && $val->code != 'EUR' && $val->code != 'CAD')
                                                <option value="{{$val->code}}"<?php echo $result->rooms_price->currency_code == $val->code ? ' selected="selected"' : '' ?>>{{$val->code}} - {{$val->name}}</option>
                                                @endif
                                                @endforeach


                                            </select>
                                            {{-- {!! Form::select('currency_code',$get_currency, $result->rooms_price->currency_code, ['id' => 'price-select-currency_code', 'data-saving' => 'base_price']) !!} --}}
                                        </div>
                                    </div>
                                </div>
                                <div class="col-4 ">

                                    <!-- <span class="text-highlight">
                                    <strong>Price tip: ₹805</strong>
                                    </span>

                                    <span id="suggestion_disclaimer_daily" class="icon icon-question grey" <i=""></span> -->

                                </div>
                            </div>

                            <p data-error="price" class="ml-error"></p>

                            <div style = "">*{{ trans('messages.lys.unique_prices_for_specific_date_note') }}</div>

                        </div>
                    </div>
                </div>

                @if($result->rooms_price->original_week == 0 || $result->rooms_price->original_month == 0)
                {{-- <p id="js-set-long-term-prices" class="row-space-top-6 text-center text-muted set-long-term-prices">
                    {{ trans('messages.lys.offer_discounts') }} <a data-prevent-default="" href="#" id="show_long_term">{{ trans('messages.lys.weekly_monthly') }}</a> {{ trans('messages.lys.prices') }}.
                </p> --}}

               {{--  <hr class="row-space-top-6 row-space-5 set-long-term-prices"> --}}
                @endif

                <div id="js-advanced-pricing-content">
                    <!-- Modal for advanced pricing goes here -->
                </div>
                <div class="list_frame" style="margin-bottom: 30px;">
                    <div class="list_frame_label">
                        <!--{{ trans('messages.lys.property_room_type') }}-->
                        {{ trans('messages.lys.discounts_optional') }}
                    </div>
                    <!--                <form name="overview" class="list_inner_frame">-->
                    <div class = "list_inner_frame clearfix" style = "margin-bottom:10px;">
                        <!--<div class="row-space-top-8 row-space-8 {{ ($result->rooms_price->original_week == 0 || $result->rooms_price->original_month == 0) ? 'hide' : '' }}" id="js-long-term-prices">-->
                        <div id="js-long-term-prices">

                            <div id="js-long-term-prices" class="js-section">
                                <div style="display: none;" class="js-saving-progress saving-progress long_price">
                                    <h5>{{ trans('messages.lys.saving') }}...</h5>
                                </div>

                                <!--  <h4>{{ trans('messages.lys.long_term_prices') }}</h4>-->

                                <div class="row-space-3">
                                    <div>
                                        <label for="listing_weekly_price_native" class="label-large">
                                            {{ trans('messages.lys.weekly_price') }}
                                        </label>
                                        <div class="row row-table row-space-1">
                                            <div class="col-4 col-middle">
                                                <div class="input-addon">
                                                    <span class="input-prefix">{{ $result->rooms_price->currency->original_symbol }}</span>
                                                    <input type="number" data-suggested="" id="price-week" class="input-stem input-large autosubmit-text" value="{{ ($result->rooms_price->original_week == 0) ? '' : $result->rooms_price->original_week }}" name="week" data-saving="long_price">
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
                                                    <input type="number" data-suggested="₹16905" id="price-month" class="autosubmit-text input-stem input-large" value="{{ ($result->rooms_price->original_month == 0) ? '' : $result->rooms_price->original_month }}" name="month" data-saving="long_price">
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
                    </div>
                </div>

                <hr class="row-space-top-6 row-space-5 post-listed">

                <div class="list_frame" style = "margin-bottom: 30px">
                    <div class="list_frame_label">
                        <!--{{ trans('messages.lys.property_room_type') }}-->
                        {{ trans('messages.lys.extra_charges_optional') }}
                    </div>
                    <!--                <form name="overview" class="list_inner_frame">-->
                    <div class = "list_inner_frame clearfix" style = "margin-bottom:10px;">
                        <!--<div class="js-section {{ ($result->status != NULL) ? 'pre-listed' : 'post-listed' }}">-->
                        <div class="js-section pre-listed">
                            <div style="display: none;" class="js-saving-progress saving-progress additional-saving">
                                <h5>{{ trans('messages.lys.saving') }}...</h5>
                            </div>

                            <!--  <h4>{{ trans('messages.lys.additional_pricing') }}</h4>-->

                            <p class="text-muted">

                            </p>

                            <div id="js-cleaning-fee" class="row-space-3 js-tooltip-trigger">
                                <label for="listing_cleaning_fee_native_checkbox" class="label-large label-inline">
                                    <input type="checkbox" data-extras="true" ng-model="cleaning_checkbox" id="listing_cleaning_fee_native_checkbox" ng-init="cleaning_checkbox = {{ ($result->rooms_price->original_cleaning == 0) ? 'false' : 'true' }}" ng-checked="{{ ($result->rooms_price->original_cleaning == 0) ? 'false' : 'true' }}">
                                    {{ trans('messages.lys.cleaning') }}
                                </label>

                                <div class="right-indent" data-checkbox-id="listing_cleaning_fee_native_checkbox" ng-show="cleaning_checkbox" ng-cloak>
                                    <div class="row row-table row-space-1">
                                        <div class="col-4 col-middle">
                                            <div class="input-addon">
                                                <span class="input-prefix">{{ $result->rooms_price->currency->original_symbol }}</span>
                                                <input type="number" data-extras="true" id="price-cleaning" value="{{ ($result->rooms_price->original_cleaning == 0) ? '' : $result->rooms_price->original_cleaning }}" name="cleaning" class="autosubmit-text input-stem input-large" data-saving="additional-saving">
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

                            <div id="js-security-deposit" class="row-space-3 js-tooltip-trigger">
                                <label for="listing_security_deposit_native_checkbox" class="label-large label-inline">
                                    <input type="checkbox" data-extras="true" ng-model="security_checkbox" id="listing_security_deposit_native_checkbox" ng-init="security_checkbox = {{ ($result->rooms_price->original_security == 0) ? 'false' : 'true' }}" ng-checked="{{ ($result->rooms_price->original_security == 0) ? 'false' : 'true' }}">
                                    {{ trans('messages.lys.security_deposit') }}
                                </label>

                                <div class="right-indent" data-checkbox-id="listing_security_deposit_native_checkbox" ng-show="security_checkbox" ng-cloak>
                                    <div class="row row-space-1">
                                        <div class="col-4">
                                            <div class="input-addon">
                                                <span class="input-prefix">{{ $result->rooms_price->currency->original_symbol }}</span>
                                                <input type="number" data-extras="true" value="{{ ($result->rooms_price->original_security == 0) ? '' : $result->rooms_price->original_security }}" id="price-security" name="security" class="autosubmit-text input-stem input-large" data-saving="additional-saving">
                                            </div>
                                        </div>
                                    </div>
                                    <p data-error="security_deposit" class="ml-error"></p>
                                    <p class="text-muted">
                                        {{ trans('messages.lys.security_deposit_desc') }} <a href="{{ url('/terms_of_service') }}" target="_blank">{{ trans('messages.lys.click_here_to_learn_more') }}</a>
                                    </p>
                                </div>
                            </div>


                            <div id="js-weekend-pricing" class="row-space-3 js-tooltip-trigger">
                                <label for="listing_weekend_price_native_checkbox" class="label-large label-inline">
                                    <input type="checkbox" data-extras="true" ng-model="weekend_checkbox" id="listing_weekend_price_native_checkbox" ng-init="weekend_checkbox = {{ ($result->rooms_price->original_weekend == 0) ? 'false' : 'true' }}" ng-checked="{{ ($result->rooms_price->original_weekend == 0) ? 'false' : 'true' }}">
                                    {{ trans('messages.lys.weekend_pricing') }}
                                </label>

                                <div data-checkbox-id="listing_weekend_price_native_checkbox" ng-show="weekend_checkbox" ng-cloak>

                                    <div class="right-indent">

                                        <label class="label-large">1. {{ trans('messages.lys.select_which_days_to_apply') }}:</label>

                                        <label class="label-large label-inline terms-panel">
                                            <input id="listing_weekday_thursday_checkbox" type="checkbox" value="0" name="thursday" data-saving="additional-saving" ng-model="thursday_checkbox" ng-init="thursday_checkbox = {{ ($result->rooms_price->thursday == 'Yes') ? 'true' : 'false' }}" ng-checked="{{ ($result->rooms_price->thursday == 'Yes') ? 'true' : 'false' }}" class="weekend_check">
                                            <span>{{ trans('messages.lys.thursday_night') }}</span>
                                        </label>

                                        <label class="label-large label-inline terms-panel">
                                            <input id="listing_weekday_friday_checkbox" type="checkbox" value="0" name="friday" data-saving="additional-saving" ng-model="friday_checkbox" ng-init="friday_checkbox = {{ ($result->rooms_price->friday == 'Yes') ? 'true' : 'false' }}" ng-checked="{{ ($result->rooms_price->friday == 'Yes') ? 'true' : 'false' }}" class="weekend_check">
                                            <span>{{ trans('messages.lys.friday_night') }}</span>
                                        </label>

                                        <label class="label-large label-inline terms-panel">
                                            <input id="listing_weekday_saturday_checkbox" type="checkbox" value="0" name="saturday" data-saving="additional-saving" ng-model="saturday_checkbox" ng-init="saturday_checkbox = {{ ($result->rooms_price->saturday == 'Yes') ? 'true' : 'false' }}" ng-checked="{{ ($result->rooms_price->saturday == 'Yes') ? 'true' : 'false' }}" class="weekend_check">
                                            <span>{{ trans('messages.lys.saturday_night') }}</span>
                                        </label>

                                    </div>

                                    <div class="right-indent">

                                      <label class="label-large">2. {{ trans('messages.lys.weekend_pricing_desc') }}:</label>

                                      <div class="row row-table row-space-1">
                                          <div class="col-4">
                                              <div class="input-addon">
                                                  <span class="input-prefix">{{ $result->rooms_price->currency->original_symbol }}</span>
                                                  <input type="number" data-extras="true" value="{{ ($result->rooms_price->original_weekend == 0) ? '' : $result->rooms_price->original_weekend }}" id="price-weekend" name="weekend" class="autosubmit-text input-stem input-large" data-saving="additional-saving" ng-disabled="!thursday_checkbox && !friday_checkbox && !saturday_checkbox">
                                              </div>
                                          </div>
                                      </div>

                                  </div>

                                </div>
                            </div>

                        </div>
                    </div>
                </div>

                <div id="js-donation-tool-placeholder"></div>

                <div class="not-post-listed row row-space-top-6 progress-buttons">
                    <div class="col-12">
                        <div class="separator"></div>
                    </div>

                    <div class="row">
                        <div id="js-publish-button" style="float:right;">

                            <div class="not-post-listed text-center">
                                <!-- <div  class="animated text-lead text-muted steps-remaining js-steps-remaining {{ ($result->steps_count != 0) ? 'show' : 'show' }}" style="opacity: 1;"><strong class="text-highlight"> <span id="steps_count">{{ 7-$result->steps_count }}</span> / 6 </strong>{{ trans('messages.lys.steps') }} completed</div> -->

                                <div  class="animated text-lead text-muted steps-remaining js-steps-remaining show" style="opacity: 1;">
                                    <div class="col-3 row-space-top-1 next_step">
                                        <a class="back-section-button" href="{{ url('manage-listing/'.$room_id.'/amenities') }}" data-prevent-default="">{{ trans('messages.lys.back') }}</a>
                                    </div>

                                    @if($result->status == NULL)
                                    <button data-href="complete" class="animated btn btn-large btn-host btn-primary {{ ($result->steps_count == 0) ? '' : 'hide'}}" id="js-list-space-button" data-track="list_space_button_left_nav" style="">
                                        {{ trans('messages.lys.list_your_space') }}
                                    </button>
                                    <div class="col-3 text-right next_step">
                                        <a data-prevent-default="" href="{{ url('manage-listing/'.$room_id.'/calendar') }}" class="btn btn-large btn-primary next-section-button {{ ($result->steps_count != 0) ? '' : 'hide'}}" ng-show="!weekend_checkbox || ( thursday_checkbox || friday_checkbox || saturday_checkbox )">
                                            {{ trans('messages.lys.next') }}
                                        </a>
                                    </div>
                                    @endif
                                    @if($result->status != NULL)
                                    <div class="col-3 text-right next_step">
                                        <a data-prevent-default="" href="{{ url('manage-listing/'.$room_id.'/calendar') }}" class="btn btn-large btn-primary next-section-button" ng-show="!weekend_checkbox || ( thursday_checkbox || friday_checkbox || saturday_checkbox )">
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
<div id="js-manage-listing-help" class="manage-listing-help"><div class="manage-listing-help-panel-wrapper">
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
