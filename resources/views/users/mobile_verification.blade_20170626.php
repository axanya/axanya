@extends('template')

@section('main')

<main id="site-content" role="main" ng-controller="mobile_verification">
@include('common.subheader')
  <div class="page-container-responsive row-space-top-4 row-space-4">
    <div class="row">
      <div class="col-md-3">
        @include('common.sidenav')
        <a href="{{ url('users/show/'.Auth::user()->user()->id) }}" class="btn btn-block row-space-top-4">{{ trans('messages.dashboard.view_profile') }}</a>
      </div>
      <?php $selected_phone = isset($phone_info) ? $phone_info->phone_code . ' ' . $phone_info->phone_number : ' ';?>
      <div class="col-md-9">
        <div class="col-md-6 col-md-offset-3 col-sm-12 col-sm-offset-0 text-center">
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
                  </p>
                  <p  class="text-center"><a href="javascript:void(0)" id="change_number">{{ trans('messages.lys.change_phone_number') }}</a></p>
                  </div>
                @else
                  <div class="lead" style="align:center" id="verification_discription">
                    <p>{{ trans('messages.lys.No_phone_number_added') }}</p>
                    <div id="change_phone_number">
                    <form method="post" id="veryfyotp4" action="">
                      <div id="change-number-section">
                        <div class="row">
                            <div class="col-md-4 col-sm-12 text-left">
                              <label>{{ trans('messages.lys.country_code') }}</label>
                              <select class="form-control" name="phone_code" id="phone_code">
                                <option value="0">{{ trans('messages.lys.select') }}...</option>
                                @foreach($country as $val)
                                <option value="{{$val->phone_code}}">({{$val->long_name}}) +{{$val->phone_code}}</option>
                                @endforeach
                              </select>
                            </div>
                            <div class="col-md-8 col-sm-12 text-left">
                              <label>{{ trans('messages.lys.phone_number') }}</label>
                              <input type="text" class="form-control" name="phone_number" id="phone_number" maxlength="15" placeholder="{{ trans('messages.lys.Enter_mobile_number') }}" required="">
                            </div>
                            <div class="col-sm-12" style="margin-top: 10px">
                               <button type="button" class="btn btn-primary  pull-right col-sm-3" id="mobile-verification-change-number" data-value="change">{{ trans('messages.lys.add') }}</button>
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
                    <div class="row">
                      <div class="form-group">
                         <span style="color:red;"></span>
                         <input type="text" class="form-control" name="otp" id="otp" ng-model="otp" maxlength="4" placeholder="{{ trans('messages.lys.Enter_your_code') }}" required="">
                      </div>
                      <div class="form-group mt10">
                        <button type="button" class="btn btn-primary  pull-right" id="mobile-verification-verify-code" data-value="verify" phone-id="{{$phone_info->id}}">{{ trans('messages.lys.verify') }}</button>
                      </div>
                    </div>
                    <p style="margin-top: 10px" class="text-left">
                      <a href="javascript:void(0)" id="mobile-verification-resend-code" data-value="resend" phone-id="{{$phone_info->id}}" class="pull-left">{{ trans('messages.lys.resend_otp') }}</a>

                      <a href="javascript:void(0)" id="change_number" class="pull-right">{{ trans('messages.lys.change_phone_number') }}</a>
                    </p>
                  </div>
                </form>

                <form method="post" id="change_phone_number" action="" style="display: none;">
                    <div id="change-number-section">
                      <div class="row">
                          <div class="col-md-4 col-sm-12 text-left">
                            <label>{{ trans('messages.lys.country_code') }}</label>
                            <select class="form-control" name="phone_code" id="phone_code">
                              <option value="0">{{ trans('messages.lys.select') }}...</option>
                              @foreach($country as $val)
                              <option value="{{$val->phone_code}}">({{$val->long_name}}) +{{$val->phone_code}} </option>
                              @endforeach
                            </select>
                          </div>
                          <div class="col-md-8 col-sm-12 text-left">
                            <label>{{ trans('messages.lys.phone_number') }}</label>
                            <input type="text" class="form-control" name="phone_number" id="phone_number" maxlength="15" placeholder="{{ trans('messages.lys.Enter_mobile_number') }}" required="">
                          </div>
                          <div class="col-sm-12 text-right" style="margin-top: 10px">
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
                        <div class="row">
                            <div class="col-md-4 col-sm-12 text-left">
                              <label>{{ trans('messages.lys.country_code') }}</label>
                              <select class="form-control" name="phone_code" id="phone_code">
                                <option value="0">{{ trans('messages.lys.select') }}...</option>
                                @foreach($country as $val)
                                <option value="{{$val->phone_code}}">({{$val->long_name}}) +{{$val->phone_code}}</option>
                                @endforeach
                              </select>
                            </div>
                            <div class="col-md-8 col-sm-12 text-left">
                              <label>{{ trans('messages.lys.phone_number') }}</label>
                              <input type="text" class="form-control" name="phone_number" id="phone_number" maxlength="15" placeholder="Enter mobile number" required="">
                            </div>
                            <div class="col-sm-12" style="margin-top: 10px">
                               <button type="button" class="btn btn-primary  pull-right col-sm-3" id="mobile-verification-change-number" data-value="change">{{ trans('messages.lys.change') }}</button>
                            </div>

                        </div>
                      </div>
                    </form>
                  </div>
                @endif

            <br><br>
            </div>
            <div class="col-sm-12 col-md-9 row-space-top-2 text-right">
              @if(Session::get('is_new_user') == 'yes')
                <a href="{{url('/dashboard')}}">Skip &gt;&gt;</a>
              @endif

            </div>
        {{-- <div class="col-sm-6 col-sm-offset-3 text-center">
            <br>
            @if(Request::get('type') == 'new')
              <img src="{{url('/')}}/images/SMS-512.png" class="img-responsive" style="width:50px; height:50px;margin:0 auto;"><br>
              <h3 class="text-center">Add Mobile Number</h3><br>
              <div class="lead" style="align:center" id="verification_discription">
                <p>Please enter your mobile number.</p>
              </div>
            @elseif($phone_info->status == 'Pending')
              <img src="{{url('/')}}/images/SMS-512.png" class="img-responsive" style="width:50px; height:50px;margin:0 auto;"><br>
              <span id="pending_description">
                <h3 class="text-center">Verify your mobile number</h3><br>
                <div class="lead" style="align:center" id="verification_discription">
                <p> Thanks for giving your details. An OTP has been sent to your Mobile Number +{{$phone_info->phone_code}} {{$phone_info->phone_number}}. Please enter the 4 digit OTP below for Listing Space.</p>  <p id="v_code">{{($app_mode == 'development') ? $phone_info->otp : ''}}</p>
                </div>
              </span>

              <span id="change_description" style="display: none;">
                <h3 class="text-center">Change your mobile number</h3><br>
                <div class="lead" style="align:center" id="verification_discription">
                <p> Please enter your new mobile number.</p>
                </div>
              </span>


            @elseif($phone_info->status == 'Confirmed')
              <img src="{{url('/')}}/images/20151012_561baec9780a5.png" class="img-responsive" style="width:50px; height:50px;margin:0 auto;"><br>
              <h3 class="text-center">Mobile number verified</h3><br>
              <div class="lead" style="align:center" id="verification_discription">
              <p>Mobile Number +{{$phone_info->phone_code}} {{$phone_info->phone_number}} is verified.</p>
              </div>
            @endif

        <br>
          <div class="row">
            <div class="js-saving-progress saving-progress" style="display: none;">
              <h5>Sending...</h5>
            </div>
          </div>
          <br>
          @if((Request::get('type') == 'new'))
                <form method="post" id="change_phone_number" action="">
                  <div id="change-number-section">
                    <div class="row">
                        <div class="col-sm-4 text-left">
                          <label>Country Code</label>
                          <select class="form-control" name="phone_code" id="phone_code">
                            <option value="0">Select...</option>
                            @foreach($country as $val)
                            <option value="{{$val->phone_code}}">({{$val->long_name}}) +{{$val->phone_code}}</option>
                            @endforeach
                          </select>
                        </div>
                        <div class="col-sm-8 text-left">
                          <label>Phone Number</label>
                          <input type="text" class="form-control" name="phone_number" id="phone_number" maxlength="15" placeholder="Enter mobile number" required="">
                        </div>
                        <div class="col-sm-12 text-right" style="margin-top: 10px">
                          <button type="button" class="btn btn-primary" id="mobile-verification-change-number" data-value="change">Add</button>
                        </div>

                    </div>

                    <div class="row-space-top-2 text-right">
                      @if(Session::get('is_new_user') == 'yes')
                        <a href="{{url('/dashboard')}}">Skip &gt;&gt;</a>
                      @else
                        <a href="{{url('/users/phone_list')}}">Back</a>
                      @endif

                    </div>

                  </div>
                </form>

            @elseif((Request::get('type') != 'new') && $phone_info->status == 'Pending')

                <form method="post" id="veryfyotp" action="" style="{{ (Request::get('type') == 'new') ? 'display: none;' : '' }}">
                  <div id="otp-section">
                    <div class="row">
                      <div class="form-group col-sm-8">
                         <span style="color:red;"></span>
                         <input type="text" class="form-control" name="otp" id="otp" ng-model="otp" maxlength="4" placeholder="Enter your OTP number" required="">
                      </div>
                      <button type="button" class="btn btn-primary  pull-right col-sm-3" id="mobile-verification-verify-code" data-value="verify" phone-id="{{$phone_info->id}}">Verify</button>
                    </div>
                    <p style="margin-top: 10px" class="text-left">
                      <a href="javascript:void(0)" id="mobile-verification-resend-code" data-value="resend" phone-id="{{$phone_info->id}}" class="">resend otp</a>
                      or
                      <a href="javascript:void(0)" id="change_number" class="">change phone number</a>
                    </p>


                    <div class="row text-right">
                      @if(Request::get('phone_id') && Request::get('phone_id') != '')
                        <a href="{{url('/users/phone_list')}}">Back</a>
                      @else
                        @if(Session::get('is_new_user') == 'yes')
                          <a href="{{url('/dashboard')}}">Skip &gt;&gt;</a>
                        @endif
                      @endif
                    </div>

                  </div>
                </form>

                <form method="post" id="change_phone_number" action="" style="{{ (Request::get('type') == 'new') ? '' : 'display: none;' }}">
                  <div id="change-number-section">
                    <div class="row">
                        <div class="col-sm-4 text-left">
                          <label>Country Code</label>
                          <select class="form-control" name="phone_code" id="phone_code">
                            <option value="0">Select...</option>
                            @foreach($country as $val)
                            <option value="{{$val->phone_code}}">({{$val->long_name}}) +{{$val->phone_code}}</option>
                            @endforeach
                          </select>
                        </div>
                        <div class="col-sm-8 text-left">
                          <label>Phone Number</label>
                          <input type="text" class="form-control" name="phone_number" id="phone_number" maxlength="15" placeholder="Enter mobile number" required="">
                        </div>
                        <div class="col-sm-12 text-right" style="margin-top: 10px">
                          <button type="button" class="btn btn-default" id="change_cancle">Cancel</button>
                          <button type="button" class="btn btn-primary" id="mobile-verification-change-number" data-value="change">Change</button>
                        </div>

                    </div>
                    <div class="row-space-top-2 text-right">
                      @if(Request::get('phone_id') && Request::get('phone_id') != '')
                        <a href="{{url('/users/phone_list')}}">Back</a>
                      @else
                        <a href="{{url('/dashboard')}}">Skip &gt;&gt;</a>
                      @endif
                    </div>
                  </div>
                </form>

            @elseif((Request::get('type') != 'new') && $phone_info->status == 'Confirmed')
              <div class="lead" style="align:center">
              <p>or change mobile number</p>
              </div>

              <form method="post" id="veryfyotp" action="">
                <div id="change-number-section">
                  <div class="row">
                      <div class="col-sm-4 text-left">
                        <label>Country Code</label>
                        <select class="form-control" name="phone_code" id="phone_code">
                          @foreach($country as $val)
                          <option value="{{$val->phone_code}}">({{$val->long_name}}) +{{$val->phone_code}}</option>
                          @endforeach
                        </select>
                      </div>
                      <div class="col-sm-8 text-left">
                        <label>Phone Number</label>
                        <input type="text" class="form-control" name="phone_number" id="phone_number" maxlength="15" placeholder="Enter mobile number" required="">
                      </div>
                      <div class="col-sm-12" style="margin-top: 10px">
                         <button type="button" class="btn btn-primary  pull-right col-sm-3" id="mobile-verification-change-number" data-value="change">Change</button>
                      </div>

                  </div>

                  <div class="row-space-top-2 text-right">
                    @if(Request::get('phone_id') && Request::get('phone_id') != '')
                      <a href="{{url('/users/phone_list')}}">Back</a>
                    @else
                      <a href="{{url('/dashboard')}}">Skip &gt;&gt;</a>
                    @endif
                  </div>
                </div>
              </form>
            @endif
        <br><br>
        </div> --}}

      </div>
    </div>
  </div>
</main>

@stop
