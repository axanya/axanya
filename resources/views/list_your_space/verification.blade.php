<div class="manage-listing-content-container" id="js-manage-listing-content-container">
  <div class="manage-listing-content-wrapper">
    <div class="manage-listing-content" id="js-manage-listing-content"><div>

      @if(Auth::user()->user()->users_verification->email == 'no' || Auth::user()->user()->users_verification->phone == 'no' || Auth::user()->user()->gender == '' || Auth::user()->user()->profile_picture->src == '' || Auth::user()->user()->profile_picture->src == url('/images/user_pic-225x225.png'))

      <div class = "list_frame" id="listing-verification">
        <div class = "list_frame_label">{{ trans('messages.lys.verification') }}</div>

        <div class="js-section" >
          <div class="container be-detail-container" style="padding: 15px 15px 0 15px;">

            <p>{{ trans('messages.lys.verification_intro') }}</p>

            <ol class="list-orderd hdb-light-bg">

              @if(Auth::user()->user()->users_verification->email == 'no')
                <li class="space-2">
                  <span class="dashboard_alert_text">
                    {{ trans('messages.lys.verification_email', ['email_address' => Auth::user()->user()->email]) }} <a href="{{ url('users/request_new_confirm_email') }}" data-popup="email-otp">{{ trans('messages.dashboard.request_confirmation_email') }}</a> {{ trans('messages.login.or') }} <a href="javascript:void(0)" data-popup="email-verification">{{ trans('messages.dashboard.change_email_address') }}</a>.
                  </span>
                </li>
              @endif

              @if(Auth::user()->user()->users_verification->phone == 'no')
                <li class="space-2">
                  <a href="javascript:void(0)" data-popup="phone-verification">{{ trans('messages.lys.verification_phone') }}</a>
                </li>
              @endif

              @if(Auth::user()->user()->gender == '')
              <li class="space-2">
                <a href="javascript:void(0)" data-popup="change-gender">{{ trans('messages.lys.verification_gender') }}</a>
              </li>
              @endif

              @if(Auth::user()->user()->profile_picture->src == '' || (Auth::user()->user()->profile_picture->src == url('/images/user_pic-225x225.png')))
                <li class="space-2">
                  <input type="file" name="profile_pic_uploader" id="profile_pic_uploader" data-user_id="{{ Auth::user()->user()->id }}">
                  <a href="javascript:void(0)" id="trigger_profile_uploader">{{ trans('messages.dashboard.add_profile_photo') }}</a>
                </li>
              @endif

            </ol>

          </div>
        </div>
      </div>

      <br><br>

      @endif

<!--
    <div class = "list_frame">
      <div class = "list_frame_label">Phone Verification</div>

      <div class="js-section" >

        <div class="container be-detail-container" ng-init="phone_verification_status = '{{ $phone_info->status }}'">
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
                                <option value="{{$val->phone_code}}">{{$val->long_name}}</option>
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
                              <option value="{{$val->phone_code}}">{{$val->long_name}}</option>
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
                                <option value="{{$val->phone_code}}">{{$val->long_name}}</option>
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

  -->




    <div class = "list_frame">
      <div class = "list_frame_label">{{ trans('messages.lys.verification_referral_section') }}</div>

      <div class="js-section" >

        <div class="container be-detail-container">
          <form name="referral" class = "list_inner_frame" style="text-align: center;">
            <div class="row">
              <div class="js-saving-progress saving-progress referral-saving" style="display: none;">
                <h5>Saving...</h5>
              </div>
              <div class="col-md-6 col-md-offset-3 col-sm-11 col-sm-offset-1  mt10" ng-init="referral_code='{{ $result->referral_code }}'">
                <p>{{ trans('messages.lys.verification_referral_code') }}</p>

                <div>
                  <input type="text" class="referral" name="referral_code" ng-model="referral_code" id="referral_code" maxlength="15"  value="{{ $result->referral_code }}" placeholder="{{ trans('messages.lys.verification_referral_code_placeholder') }}" data-saving="referral-saving" ng-disabled="is_referral">
                </div>

                <div>
                  <br>
                  <p>{{ trans('messages.lys.verification_referral_or_select') }}</p>
                  <label class="label-large label-inline">
                      <input id="is_referral" type="checkbox" value="0" name="is_referral" data-saving="referral-saving" ng-model="is_referral" ng-init="is_referral = {{ ($result->is_referral == 'No') ? 'true' : 'false' }}" ng-checked="{{ ($result->is_referral == 'No') ? 'true' : 'false' }}" ng-disabled="referral_code">
                      <span>{{ trans('messages.lys.verification_referral_dont_have_code') }}</span>
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
            <a class="back-section-button" href="{{ url('manage-listing/'.$room_id.'/terms') }}" data-prevent-default="">{{ trans('messages.lys.back') }}</a>
        </div>
        <div class="col-7 row-space-top-1 next_step">
        @if($result->status == NULL)
          <button data-href="complete" class="animated btn btn-large btn-host btn-primary {{ ($result->steps_count == 0) ? '' : 'hide'}}" id="js-list-space-button" data-track="list_space_button_left_nav" style="">
                    {{ trans('messages.lys.list_your_space') }}
          </button>
          @endif

          @if($result->steps_count != 0)
            <button class="next-section-button animated btn btn-large btn-host btn-primary {{ ($result->steps_count != 0) ? '' : 'hide'}}" onclick="alert('You have one or more incomplete fields.')" style="">
                    {{ trans('messages.lys.list_your_space') }}
            </button>
            <!--
            <a data-prevent-default="" href="{{ url('manage-listing/'.$room_id.'/referral') }}" class="animated btn btn-large btn-host btn-primary {{ ($result->steps_count != 0) ? '' : 'hide'}}">
                  {{ trans('messages.lys.next') }}
            </a>
          -->
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


    <div class="page-overlay"></div>

    <div class="dashboard-popup" id="email-verification">
      <a class="close" href="#" data-action="close">&times;</a>

      <div id="update_email_status" class="text-center" style="display: none; margin-bottom: 15px;"></div>

      {!! Form::open(['url' => url('users/ajax_update_email/'.Auth::user()->user()->id), 'id' => 'form_update_email']) !!}

      <div class="row row-condensed space-4">
        <label class="text-right col-sm-3" for="user_email">
          {{ trans('messages.dashboard.email_address') }} <i class="icon icon-lock icon-ebisu" data-behavior="tooltip" aria-label="Private"></i>
        </label>
        <div class="col-sm-6">
          {!! Form::email('email', Auth::user()->user()->email, ['id' => 'user_email', 'size' => '30', 'class' => 'focus', 'required' => 'required']) !!}
        </div>
        <div class="col-sm-3">
          <button type="submit" class="btn btn-primary">Change</button>
        </div>
      </div>

      {!! Form::close() !!}

    </div>

    <div class="dashboard-popup" id="email-otp">
      <a class="close" href="#" data-action="close">&times;</a>

      <div id="otp-sending" class="text-center">Sending verification code...</div>

      <div id="email-otp-verification-status" class="text-center" style="display: none;"></div>

      <form method="post" id="form-verify-email-otp" action="">
        <div class="row">
          <p class="text-center">Enter the code you've received via email.</p>
        </div>
          <div class="row">
            <div class="col-sm-9">
              <div class="form-group">
                 <input type="text" class="form-control" name="input-email-otp" id="input-email-otp" maxlength="8" required="">
              </div>
            </div>
            <div class="col-sm-3">
              <div class="form-group">
                <button type="submit" class="btn btn-primary">{{ trans('messages.lys.verify') }}</button>
              </div>
            </div>
          </div>
      </form>

    </div>

    <div class="dashboard-popup" id="change-gender">
      <a class="close" href="#" data-action="close">&times;</a>

      {!! Form::open(['url' => url('users/update_gender/'.Auth::user()->user()->id), 'id' => 'update_form']) !!}

      <div class="row row-condensed space-4">
        <div class="col-sm-8 col-sm-offset-2 text-center">{{ trans('messages.lys.verification_gender_policy') }}</div>
      </div>

      <div class="row row-condensed space-4">
        <label class="text-right col-sm-5 rtl-right rtl-text-left" for="user_gender">
          {{ trans('messages.profile.i_am') }} <i class="icon icon-lock icon-ebisu" data-behavior="tooltip" aria-label="Private"></i>
        </label>
        <div class="col-sm-6 rtl-right">
          <div class="select">
            {!! Form::select('gender', ['Male' => trans('messages.profile.male'), 'Female' => trans('messages.profile.female')], Auth::user()->user()->gender, ['id' => 'user_gender', 'placeholder' => trans('messages.profile.gender'), 'class' => 'focus', 'required' => 'required']) !!}
          </div>
          <button type="submit" class="btn btn-primary" style="margin-left: 15px;">Done</button>
        </div>
      </div>

      {!! Form::close() !!}

    </div>


    <div class="dashboard-popup" id="phone-verification">
      <a class="close" href="#" data-action="close">&times;</a>

      <input type="hidden" id="hidden_phone_number" value="{{ $phone_info->phone_number }}">
      <input type="hidden" id="hidden_phone_status" value="{{ $phone_info->status }}">

      <div id="dashboard-phone-ajax-status" class="text-center"></div>

        <form method="post" id="dashboard-change-number" action="" style="display: none;">
          <div class="row" id="change-number-section">
            <div class="col-md-4 col-sm-12 text-left">
              <label>{{ trans('messages.lys.country_code') }}</label>
              <select class="form-control" name="phone_code" id="phone_code">
                <option value="0">{{ trans('messages.lys.select') }}...</option>
                @foreach($country as $val)
                <option value="{{$val->phone_code}}">{{$val->long_name}}</option>
                @endforeach
              </select>
            </div>
            <div class="col-md-5 col-sm-12 text-left">
              <label>{{ trans('messages.lys.phone_number') }}</label>
              <input type="text" class="form-control" name="phone_number" id="phone_number" maxlength="15" placeholder="{{ trans('messages.lys.Enter_mobile_number') }}" required="">
            </div>
            <div class="col-md-3 col-sm-12 text-left">
              <label>&nbsp;</label>
               <button type="submit" class="btn btn-primary" data-value="change">Submit</button>
            </div>
          </div>
        </form>

      <form method="post" id="dashboard-verify-number" action="" style="display: none;" data-phone-id="{{$phone_info->id}}">
        <div class="row">
          <p class="text-center">Enter the code you've received via SMS.</p>
        </div>
          <div class="row">
            <div class="col-sm-9">
              <div class="form-group">
                 <input type="text" class="form-control" name="otp" id="otp" ng-model="otp" maxlength="4" placeholder="{{ trans('messages.lys.Enter_your_code') }}" required="">
              </div>
            </div>
            <div class="col-sm-3">
              <div class="form-group">
                <button type="submit" class="btn btn-primary" data-value="verify">{{ trans('messages.lys.verify') }}</button>
              </div>
            </div>
          </div>
          <p style="margin-top: 10px; max-width: 80%; margin-left: auto; margin-right: auto;" class="text-left">
            <a href="javascript:void(0)" id="mobile-verification-resend-code" data-value="resend" data-phone-id="{{$phone_info->id}}" class="pull-left">{{ trans('messages.lys.resend_otp') }}</a>
            <a href="javascript:void(0)" id="mobile-verification-change-number" class="pull-right">{{ trans('messages.lys.change_phone_number') }}</a>
          </p>
      </form>

      <br><br>
    </div>


@if( @$phone_info->status == 'Confirmed' && ( @$result->referral_code != '' || @$result->is_referral == 'No' ) )
<script type="text/javascript">
/*
$( function() {
  $('[data-track="verification"] a div div .transition').removeClass('visible');
  $('[data-track="verification"] a div div .transition').addClass('hide');
  $('[data-track="verification"] a div div .pull-right .nav-icon').removeClass('hide');
} );
*/
</script>
@else
<script type="text/javascript">
/*
  $( function() {
    $('[data-track="verification"] a div div .transition').addClass('visible');
    $('[data-track="verification"] a div div .transition').removeClass('hide');
    $('[data-track="verification"] a div div .pull-right .nav-icon').addClass('hide');
    console.log(10);
  } );
  */
</script>
@endif
