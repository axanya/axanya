<div id="js-manage-listing-content-container" class="manage-listing-content-container">
      <div class="manage-listing-content-wrapper">
        <div id="js-manage-listing-content" class="manage-listing-content"><div><div id="calendar-container">


        <div id="log"></div>
<div class="list_frame"  ng-init="calendar_lengths = '{{$result->calendar_length}}'" style = "margin-bottom: 30px">
    <div class="list_frame_label">
        {{ trans('messages.lys.calendar') }}
    </div>
    <div class="list_note">
        {{trans('messages.lys.cal_note')}}
    </div>
    <div class = "list_inner_frame_margin-10 clearfix" style = "margin-bottom:10px;margin-left: 10px;">
        <div style="display:none;" class="js-saving-progress saving-progress calendar1">
            <h5>{{ trans('messages.lys.saving') }}...</h5>
        </div>
            <div id="calendar" class="my-calendar" ng-init="get_calendar(18)">
                <h3 class="text-center" style="margin-top: 30px">{{ trans('messages.lys.loading') }}</h3>
              </div>
    </div>
</div>




  <div class="calendar-settings-btn-container pull-right post-listed">
    <span class="label-contrast label-new
      hide">{{ trans('messages.lys.new') }}</span>
    <a href="#" id="js-calendar-settings-btn" class="text-normal link-icon">
      <i class="icon icon-cog text-lead"></i>
      <span class="link-icon__text">{{ trans('messages.lys.header') }}</span>
    </a>
  </div>
  <div id="calendar">
<div id="calendar-settings-container" class="row-space-6 hide">
  <hr>

<div class="js-section">
  <div style="display: none;" class="js-saving-progress saving-progress">
  <h5>{{ trans('messages.lys.saving') }}...</h5>
</div>

  <h4>{{ trans('messages.lys.calendar_settings') }}</h4>

  <div class="row">
    <div class="col-5">
      <div id="min-max-nights-container" class="row"></div>
      <div id="advance-notice-container" class="row row-space-top-3"></div>
    </div>
  </div>

</div>

</div>

<div id="calendar-wizard-navigation">
  <div class="not-post-listed row row-space-top-6 progress-buttons">
  <div class="col-12">
    <div class="separator"></div>
  </div>

<div class="modal calendar-modal hide adminpopover1" aria-hidden="false" style="" tabindex="-1">
  <div class="modal-table">
    <div class="modal-cell">
      <div class="modal-content content-container modal_onlycal">
        <div class="panel">
          <!-- <a data-behavior="modal-close" class="modal-close" href=""></a> -->

          <div id="calendar-edit-container" >
          <!-- ng-class="selected_calendar == 'always' ? 'hide' : ''"" -->
              <div id="calendar" >
                {!! $calendar !!}
              </div>
            </div>

        </div>
      </div>
    </div>
  </div>
</div>





    <div class="row">
      <div id="js-publish-button" style="float:right;">

        <div class="not-post-listed text-center">

          <div  class="animated text-lead text-muted steps-remaining js-steps-remaining show" style="opacity: 1;">
            <div class="col-3 row-space-top-1 next_step">
              <a class="back-section-button" href="{{ url('manage-listing/'.$room_id.'/pricing') }}" data-prevent-default="">{{ trans('messages.lys.back') }}</a>
            </div>

            @if($result->status == NULL)
              <button data-href="complete" class="animated btn btn-large btn-host btn-primary {{ ($result->steps_count == 0) ? '' : 'hide'}}" id="js-list-space-button" data-track="list_space_button_left_nav" style="">
                        {{ trans('messages.lys.list_your_space') }}
              </button>
               <div class="col-3 text-right next_step">
                  <a data-prevent-default="" href="{{ url('manage-listing/'.$room_id.'/terms') }}" class="btn btn-large btn-primary next-section-button {{ ($result->steps_count != 0) ? '' : 'hide'}}">
                      {{ trans('messages.lys.next') }}
                    </a>
                </div>
              @endif
              @if($result->status != NULL)
              <div class="col-3 text-right next_step">
                  <a data-prevent-default="" href="{{ url('manage-listing/'.$room_id.'/terms') }}" class="btn btn-large btn-primary next-section-button">
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

<div class="pricing-tips-sidebar-container"></div>
</div></div>





    </div>
    <div class="manage-listing-content-background"></div>
  </div>

  <style type="text/css">
    .month-name{
      font-weight: bold;
      color: #ff5a5f;
      position: absolute;
    }
    .calendar-month .tile.other-day-selected{
      opacity: 0.4 !important;
    }



    .host-calendar-sidebar .modal-cell{
      background: transparent;
    }
    .modal-container, .modal{
      background: transparent;
    }
    .tile.no-tile-status.both.selected {
      background-color: #fff !important;
    }
    .host-calendar-container{
      overflow: visible;
    }
    .price-green{
      color: green !important;
    }
    .tile.no-tile-status.both.bottom-green {
      border-bottom: 1px solid green;
  }
  .new-year{
    width: 100%;
    display: block;
    float: left;
    height: 90px;
    text-align: center;
  }
  .new-year > h4 {
    color: red;
    margin-top: 15px;
  }
  .cal-close {
    color: #cacccd;
    cursor: pointer;
    float: right;
    font-size: 2em;
    font-style: normal;
    font-weight: normal;
    line-height: 0.7;
    padding: 8px;
    position: absolute;
    right: 0;
    vertical-align: middle;
    z-index: 111111;
  }
  .cal-close:before {
    content: "\00d7";
}
@media(max-width: 360px) {
    .cal-close {
        padding: 16px;
    }
}
  </style>
