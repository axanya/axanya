@extends('template')

@section('main')
<main id="site-content" role="main" ng-controller="payout_preferences">
@include('common.subheader')
<div class="page-container-responsive row-space-top-4 row-space-4">
  <div class="row">
    <div class="col-md-3">
	@include('common.sidenav')
<!-- <div class="space-top-4 space-4">
  <a href="{{ url('invite?r=5') }}" class="btn btn-block">Invite Friends</a>
</div> -->
    </div>
    <div class="col-md-9">
<div class="" id="payout_setup">
  <div class="panel row-space-4">
    <div class="panel-header">
        {{ trans('messages.account.payout_methods') }}
    </div>
    <div class="panel-body" id="payout_intro">
      <p>
        {{ trans('messages.account.payout_methods_desc') }}.
      </p>
        <table class="table table-striped" id="payout_methods">
        @if(count($payouts))
        <thead>
            <tr class="text-truncate">
              <th>{{ trans('messages.account.method') }}</th>
              <th>{{ trans('messages.your_reservations.details') }}</th>
              <th>{{ trans('messages.your_reservations.status') }}</th>
              <th>&nbsp;</th>
            </tr>
          </thead>
          <tbody>
          @foreach($payouts as $row)
            <tr>
              <td>
                PayPal
                @if($row->default == 'yes')
                <span class="label label-info">{{ trans('messages.account.default') }}</span>
                @endif
              </td>
              <td>
                {{ $row->paypal_email }} ({{ $row->currency_code }})
              </td>
              <td>
                  {{ trans('messages.account.ready') }}
              </td>
              <td class="payout-options">
              @if($row->default != 'yes')
              <li class="dropdown-trigger list-unstyled">
                  <a data-prevent-default="" href="javascript:void(0);" class="link-reset text-truncate" id="payout-options-{{ $row->id }}">
                    {{ trans('messages.your_trips.options') }}
                    <i class="icon icon-caret-down"></i>
                  </a>
                  <ul data-sticky="true" data-trigger="#payout-options-{{ $row->id }}" class="tooltip tooltip-top-left list-unstyled dropdown-menu" aria-hidden="true">
                    <li>
                      <a rel="nofollow" data-method="post" class="link-reset menu-item" href="{{ url() }}/users/payout_delete/{{ $row->id }}">{{ trans('messages.account.remove') }}</a>
                    </li>
                    <li>
                      <a rel="nofollow" data-method="post" class="link-reset menu-item" href="{{ url() }}/users/payout_default/{{ $row->id }}">{{ trans('messages.account.set_default') }}</a>
                    </li>
                  </ul>
              </li>
              @endif        
              </td>
            </tr>
            @endforeach
          </tbody>
        @endif
          <tfoot>
            <tr id="add_payout_method_section">
              <td colspan="4">
                  <a id="add-payout-method-button" class="btn btn-primary" href="javascript:void(0);">
                    {{ trans('messages.account.add_payout_method') }}
                  </a>
                <span class="text-muted">
                  &nbsp;
                  {{ trans('messages.account.direct_deposit') }}, PayPal, etc...
                </span>
              </td>
            </tr>
          </tfoot>
        </table>
    </div>
  </div>
  <div style="display:none;" class="add_payout_section" id="payout_new_select"></div>
  <div style="display:none;" class="add_payout_section" id="payout_edit"></div>
</div>
<div id="taxes"></div>
    </div>
  </div>
</div>
    </main>

    <div class="modal hide" id="payout_popup1" aria-hidden="false" style="" tabindex="-1"><div class="modal-table"><div class="modal-cell"><div id="modal-add-payout-set-address" class="modal-content">
  <div class="panel-header">
    <a data-behavior="modal-close" class="panel-close" href="javascript:void(0);"></a>
    {{ trans('messages.account.add_payout_method') }}
  </div>
  <div class="flash-container" id="popup1_flash-container"> </div>
  <form class="modal-add-payout-pref" method="post" id="address">
    {!! Form::token() !!}
    <div class="panel-body">
      <div>
        <label for="payout_info_payout_address1">{{ trans('messages.account.address') }}*</label>
        <input type="text" autocomplete="billing address-line1" id="payout_info_payout_address1" name="address1">
      </div>
      <div>
        <label for="payout_info_payout_address2">{{ trans('messages.account.address') }} 2 / {{ trans('messages.account.zone') }}</label>
        <input type="text" autocomplete="billing address-line2" id="payout_info_payout_address2" name="address2">
      </div>
      <div>
        <label for="payout_info_payout_city">{{ trans('messages.account.city') }}*</label>
        <input type="text" autocomplete="billing address-level2" id="payout_info_payout_city" name="city">
      </div>
      <div>
        <label for="payout_info_payout_state">{{ trans('messages.account.state') }} / {{ trans('messages.account.province') }}</label>
        <input type="text" autocomplete="billing address-level1" id="payout_info_payout_state" name="state">
      </div>
      <div>
        <label for="payout_info_payout_zip">{{ trans('messages.account.postal_code') }}*</label>
        <input type="text" autocomplete="billing postal-code" id="payout_info_payout_zip" name="postal_code">
      </div>
      <div>
        <label for="payout_info_payout_country">{{ trans('messages.account.country') }}*</label>
        <div class="select">
          {!! Form::select('country_dropdown', $country, $default_country, ['autocomplete' => 'billing country', 'id' => 'payout_info_payout_country']) !!}
        </div>
      </div>
    </div>
    <div class="panel-footer">
      <input type="submit" value="{{ trans('messages.account.next') }}" class="btn btn-primary">
    </div>
  </form>
</div>
</div>
</div>
</div>

<div class="modal hide" id="payout_popup2" aria-hidden="false" style="" tabindex="-1"><div class="modal-table"><div class="modal-cell"><div id="modal-add-payout-set-address" class="modal-content">
  <div class="panel-header">
    <a data-behavior="modal-close" class="panel-close" href="javascript:void(0);"></a>
    {{ trans('messages.account.add_payout_method') }}
  </div>
  <div class="flash-container" id="popup2_flash-container"> </div>
  <form class="modal-add-payout-pref" id="country_options" accept-charset="UTF-8">
  {!! Form::token() !!}
  
    <input type="hidden" id="payout_info_payout2_address1" value="" name="address1">
    <input type="hidden" id="payout_info_payout2_address2" value="" name="address2">
    <input type="hidden" id="payout_info_payout2_city" value="" name="city">
    <input type="hidden" id="payout_info_payout2_country" value="" name="country">
    <input type="hidden" id="payout_info_payout2_state" value="" name="state">
    <input type="hidden" id="payout_info_payout2_zip" value="" name="postal_code">

  <div class="panel-body">
      <div>
        <p>{{ trans('messages.account.payout_released_desc1') }}</p>
        <p>{{ trans('messages.account.payout_released_desc2') }}</b> {{ trans('messages.account.payout_released_desc3') }}</p>
      </div>
      <table id="payout_method_descriptions" class="table table-striped">
        <thead><tr>
          <th></th>
          <th>{{ trans('messages.account.payout_method') }}</th>
          <th>{{ trans('messages.account.processing_time') }}</th>
          <th>{{ trans('messages.account.additional_fees') }}</th>
          <th>{{ trans('messages.account.currency') }}</th>
          <th>{{ trans('messages.your_reservations.details') }}</th>
        </tr></thead>
        <tbody>
          <tr>
            <td>
              <input type="radio" value="PayPal" name="payout_method" id="payout2_method">
            </td>
            <td class="type"><label for="payout_method">PayPal</label></td>
            <td>3-5 {{ trans('messages.account.business_days') }}</td>
            <td>{{ trans('messages.account.none') }}</td>
            <td>EUR</td>
            <td>{{ trans('messages.account.business_day_processing') }}</td>
          </tr>
        </tbody>
      </table>
  </div>
  <div class="panel-footer">
      <input type="submit" value="{{ trans('messages.account.next') }}" id="select-payout-method-submit" class="btn btn-primary">
  </div>
</form>

</div></div></div></div>

<div class="modal hide" id="payout_popup3" aria-hidden="false" style="" tabindex="-1"><div class="modal-table"><div class="modal-cell"><div id="modal-add-payout-set-address" class="modal-content">
  <div class="panel-header">
    <a data-behavior="modal-close" class="panel-close" href="javascript:void(0);"></a>
    {{ trans('messages.account.add_payout_method') }}
  </div>
  <div class="flash-container"></div>
  <form method="post" id="payout_paypal" action="{{ url('users/payout_preferences/'.Auth::user()->user()->id) }}" accept-charset="UTF-8">
  {!! Form::token() !!}

  <input type="hidden" id="payout_info_payout3_address1" value="" name="address1">
  <input type="hidden" id="payout_info_payout3_address2" value="" name="address2">
  <input type="hidden" id="payout_info_payout3_city" value="" name="city">
  <input type="hidden" id="payout_info_payout3_country" value="" name="country">
  <input type="hidden" id="payout_info_payout3_state" value="" name="state">
  <input type="hidden" id="payout_info_payout3_zip" value="" name="postal_code">
  <input type="hidden" id="payout3_method" value="" name="payout_method">

  <div class="panel-body">
  PayPal {{ trans('messages.account.email_id') }}
    <input type="email" name="paypal_email" id="paypal_email" required>
  </div>

  <div class="panel-footer">
    <input type="submit" value="{{ trans('messages.account.submit') }}" id="modal-paypal-submit" class="btn btn-primary">
  </div>
</form>
</div>
</div>
</div>
</div>

@foreach($payouts as $row)
<ul data-sticky="true" data-trigger="#payout-options-{{ $row->id }}" class="tooltip tooltip-top-left list-unstyled dropdown-menu" aria-hidden="true" role="tooltip" style="left: 1019.45px; top: 232.967px;">
  @if($row->default != 'yes')
  <li>
    <a rel="nofollow" data-method="post" class="link-reset menu-item" href="{{ url() }}/users/payout_delete/{{ $row->id }}">{{ trans('messages.account.remove') }}</a>
  </li>
  @endif
  <li>
    <a rel="nofollow" data-method="post" class="link-reset menu-item" href="{{ url() }}/users/payout_default/{{ $row->id }}">{{ trans('messages.account.set_default') }}</a>
  </li>
</ul>
@endforeach
@stop