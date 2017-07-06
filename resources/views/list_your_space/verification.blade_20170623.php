<div class="manage-listing-content-container" id="js-manage-listing-content-container">
  <div class="manage-listing-content-wrapper">
    <div class="manage-listing-content" id="js-manage-listing-content"><div>

    <div class = "list_frame">
      <div class = "list_frame_label">
        {{ trans('messages.lys.mobile_verification') }}
      </div>

      <div class="js-section" >

        <div class="container be-detail-container">
          <div class="row">
            <div class="col-md-8 col-md-offset-2 col-sm-12 col-sm-offset-0 text-center">
                <br>
                @if(@$phone_info->status == 'Pending')


                   <span id="pending_description">
                    <div class="lead" style="align:center" id="verification_discription">
                    <p><?php echo html_entity_decode(str_replace("[PHONE_NUMBER]", @$selected_phone, trans('messages.lys.thankyou_verify_mobile_text'))); ?></p>  <p id="v_code">{{($app_mode == 'development') ? $phone_info->otp : ''}}</p>
                    <p>{{ trans('messages.lys.Enter_code_you_received') }}</p>
                    </div>
                  </span>

                  <span id="change_description" style="display: none;">
                    <div class="lead" style="align:center" id="verification_discription">
                    <p> {{ trans('messages.lys.Please_enter_your_new_mobile_number') }}</p>
                    </div>
                  </span>



                @elseif(@$phone_info->status == 'Confirmed')

                  <div class="lead" style="align:center" id="verification_discription">
                  <p><?php echo html_entity_decode(str_replace("[PHONE_NUMBER]", @$selected_phone, trans('messages.lys.Mobile_number_is_verified'))); ?>
                  @if($result->status != 'Listed')
                    {{-- {{ trans('messages.lys.now_you_can_list_your_space') }} --}}
                  @endif</p>
                  <p  class="text-center"><a href="javascript:void(0)" id="change_number">{{ trans('messages.lys.change_phone_number') }}</a></p>
                  </div>
                @else
                  <div class="lead" style="align:center" id="verification_discription">
                    <p>{{ trans('messages.lys.No_phone_number_added') }}</p>
                    <div id="change_phone_number">
                    <form method="post" id="veryfyotp4" action="">
                      <div id="change-number-section">
                        <div class="col-md-12">
                            <div class="col-md-5 col-sm-12 text-left">
                              <label>{{ trans('messages.lys.country_code') }}</label>
                              <select class="form-control" name="phone_code" id="phone_code">
                                <option value="0">{{ trans('messages.lys.select') }}...</option>
                                @foreach($country as $val)
                                <option value="{{$val->phone_code}}">({{$val->long_name}}) +{{$val->phone_code}}</option>
                                @endforeach
                              </select>
                            </div>
                            <div class="col-md-7 col-sm-12 text-left">
                              <label>{{ trans('messages.lys.phone_number') }}</label>
                              <input type="text" class="form-control" name="phone_number" id="phone_number" maxlength="15" placeholder="{{ trans('messages.lys.Enter_mobile_number') }}" required="">
                            </div>
                            <div class="col-sm-12" style="margin: 10px 0px 10px">
                               <button type="button" class="btn btn-primary  pull-right" id="mobile-verification-change-number" data-value="change">{{ trans('messages.lys.add') }}</button>
                            </div>

                        </div>
                      </div>
                    </form>
                  </div>
                  </div>
                @endif

            <br>
              <div class="row">
                <div class="js-saving-progress saving-progress" style="display: none;">
                  <h5>Sending...</h5>
                </div>
              </div>
              <br>
                @if(@$phone_info->status == 'Pending')
                <form method="post" id="veryfyotp" action="">
                  <div id="otp-section">
                    <div class="col-md-12">
                      <div class="form-group">
                         <span style="color:red;"></span>
                         <input type="text" class="form-control" name="otp" id="otp" ng-model="otp" maxlength="4" placeholder="{{ trans('messages.lys.Enter_your_code') }}" required="">
                      </div>
                      <div class="form-group mt10">
                        <button type="button" class="btn btn-primary  pull-right" id="mobile-verification-verify-code" data-value="verify" phone-id="{{$phone_info->id}}">{{ trans('messages.lys.verify') }}</button>
                      </div>
                    </div>
                    <div class="clearfix"></div>
                    <div style="margin-top: 10px" class="col-md-12 text-left">
                      <a href="javascript:void(0)" id="mobile-verification-resend-code" data-value="resend" phone-id="{{$phone_info->id}}" class="pull-left">{{ trans('messages.lys.resend_otp') }}</a>

                      <a href="javascript:void(0)" id="change_number" class="pull-right">{{ trans('messages.lys.change_phone_number') }}</a>
                    </div>
                  </div>
                </form>

                <form method="post" id="change_phone_number" action="" style="display: none;">
                    <div id="change-number-section">
                      <div class="col-md-12">
                          <div class="col-md-5 col-sm-12 text-left">
                            <label>{{ trans('messages.lys.country_code') }}</label>
                            <select class="form-control" name="phone_code" id="phone_code">
                              <option value="0">{{ trans('messages.lys.select') }}...</option>
                              @foreach($country as $val)
                              <option value="{{$val->phone_code}}">({{$val->long_name}}) +{{$val->phone_code}} </option>
                              @endforeach
                            </select>
                          </div>
                          <div class="col-md-7 col-sm-12 text-left">
                            <label>{{ trans('messages.lys.phone_number') }}</label>
                            <input type="text" class="form-control" name="phone_number" id="phone_number" maxlength="15" placeholder="{{ trans('messages.lys.Enter_mobile_number') }}" required="">
                          </div>
                          <div class="col-sm-12 text-right" style="margin: 10px 0px 10px">
                            <button type="button" class="btn btn-default" id="change_cancle">{{ trans('messages.lys.cancel') }}</button>
                            <button type="button" class="btn btn-primary" id="mobile-verification-change-number" data-value="change">{{ trans('messages.lys.change') }}</button>
                          </div>

                      </div>
                    </div>
                  </form>

                @elseif(@$phone_info->status == 'Confirmed')
                  <div id="change_phone_number" style="display: none;">
                    <form method="post" id="veryfyotp4" action="">
                      <div id="change-number-section">
                        <div class="col-md-12">
                            <div class="col-md-5 col-sm-12 text-left">
                              <label>{{ trans('messages.lys.country_code') }}</label>
                              <select class="form-control" name="phone_code" id="phone_code">
                                <option value="0">{{ trans('messages.lys.select') }}...</option>
                                @foreach($country as $val)
                                <option value="{{$val->phone_code}}">({{$val->long_name}}) +{{$val->phone_code}}</option>
                                @endforeach
                              </select>
                            </div>
                            <div class="col-md-7 col-sm-12 text-left">
                              <label>{{ trans('messages.lys.phone_number') }}</label>
                              <input type="text" class="form-control" name="phone_number" id="phone_number" maxlength="15" placeholder="{{ trans('messages.lys.Enter_mobile_number') }}" required="">
                            </div>
                            <div class="col-sm-12" style="margin: 10px 0px 10px">
                               <button type="button" class="btn btn-primary  pull-right" id="mobile-verification-change-number" data-value="change">{{ trans('messages.lys.change') }}</button>
                            </div>

                        </div>
                      </div>
                    </form>
                  </div>
                @endif

            <br><br>
            </div>
          </div>
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
            <a class="back-section-button" href="{{ url('manage-listing/'.$room_id.'/terms') }}" data-prevent-default="">{{ trans('messages.lys.back') }}</a>
        </div>
        <div class="col-7 row-space-top-1 next_step">
        @if($result->status == NULL)
          <button data-href="complete" class="animated btn btn-large btn-host btn-primary {{ ($result->steps_count == 0) ? '' : 'hide'}}" id="js-list-space-button" data-track="list_space_button_left_nav" style="">
                    {{ trans('messages.lys.list_your_space') }}
          </button>
          @endif

          @if($result->steps_count != 0)
            <a data-prevent-default="" href="{{ url('manage-listing/'.$room_id.'/referral') }}" class="animated btn btn-large btn-host btn-primary {{ ($result->steps_count != 0) ? '' : 'hide'}}">
                  {{ trans('messages.lys.next') }}
            </a>
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


@if(@$phone_info->status == 'Confirmed')
<script type="text/javascript">
  $('[data-track="verification"] a div div .transition').removeClass('visible');
  $('[data-track="verification"] a div div .transition').addClass('hide');
  $('[data-track="verification"] a div div .pull-right .nav-icon').removeClass('hide');
</script>
@endif