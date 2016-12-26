<div class="panel">
  <div class="flash-container" id="js-flash-error-clicked-frozen-field"></div>
  <div class="panel-body">
    <div id="js-disaster-address-alert" class="media row-space-2 hide">
      <i class="icon icon-flag icon-beach pull-left icon-size-2"></i>
      <div class="media-body">
        <strong>{{ trans('messages.lys.new_location_outside_disaster') }}</strong><br>
        <span class="text-muted">{{ trans('messages.lys.price_reset_daily_rate') }}</span>
      </div>
    </div>

<form id="js-address-fields-form" name="enter_address">
  <div class="row-space-1">
    <label for="country">{{ trans('messages.account.country') }}</label>
    <div id="country-select"><div class="select select-block">
    <?php
      // $user_ip = getenv('REMOTE_ADDR');
      // $geo = unserialize(file_get_contents("http://www.geoplugin.net/php.gp?ip=$user_ip"));
      // $country_code = ($result->country == "") ? $geo["geoplugin_countryCode"] : $result->country;
    $country_code = "US";
    ?>

  {!! Form::select('country_code',$country,$country_code,['id'=>'country']) !!}
</div>
</div>
  </div>
  <div id="localized-fields">
  <div class="row-space-1">
    <label for="address_line_1">{{ trans('messages.lys.address_line_1') }}</label>
    <input type="text" placeholder="{{ trans('messages.lys.address1_placeholder') }}" value="{{ $result->address_line_1 }}" class="focus" id="address_line_1" name="address_line_1" autocomplete="off">
  </div>

  <div class="row-space-1">
    <label for="address_line_2">{{ trans('messages.lys.address_line_2') }}</label>
    <input type="text" placeholder="{{ trans('messages.lys.address2_placeholder') }}" value="{{ $result->address_line_2 }}" class="focus" id="address_line_2" name="address_line_2">
  </div>

  <div class="row-space-1">
    <label for="city">{{ trans('messages.lys.city_town_district') }}</label>
    <input type="text" placeholder="" class="focus" value="{{ $result->city }}" id="city" name="city">
  </div>

  <div class="row-space-1">
    <label for="state">{{ trans('messages.lys.state_province_country_region') }}</label>
    <input type="text" placeholder="" class="focus" value="{{ $result->state }}" id="state" name="state">
  </div>

  <div class="row-space-1">
    <label for="postal_code">{{ trans('messages.lys.zip_postal_code') }}</label>
    <input type="text" placeholder="" class="focus" value="{{ $result->postal_code }}" id="postal_code" name="postal_code">
  </div>

</div>
</form>
  </div>

  <div class="panel-footer">
    <div class="force-oneline">
        <button data-behavior="modal-close" class="btn js-secondary-btn">
          {{ trans('messages.your_reservations.cancel') }}
        </button>
      <button id="js-next-btn" class="btn btn-primary js-next-btn">
        {{ trans('messages.lys.next') }}
      </button>
    </div>
  </div>
</div>