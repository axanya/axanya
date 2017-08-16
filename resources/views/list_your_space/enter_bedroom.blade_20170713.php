
<div style = "padding: 14px 0px; text-align: center;">
    {{ $result->bedroom_type }} {{ $result->data_index }}
</div>
<div class="panel" style = "background:#fafafa; pading-top:10px;">
  <div class="flash-container" id="js-flash-error-clicked-frozen-field"></div>
  <form id="js-bedroom-fields-form" name="enter_bedroom">
  <div class="panel-body clearfix">
<!--    <div id="js-disaster-bedroom-alert" class="media row-space-2 hide">
      <i class="icon icon-flag icon-beach pull-left icon-size-2"></i>
      <div class="media-body">
        <strong>asdfasdf</strong><br>
        <span class="text-muted">{{ trans('messages.lys.price_reset_daily_rate') }}</span>
      </div>
    </div>-->


  <!--<div class="row-space-1">-->
    <input type="hidden" name="bedroom_id" id="bedroom_id" value="{{$result->data_id}}">
    @foreach($bed_type as $key => $val)
    <div class="col-sm-12 col-md-4">
        <label for="king">{{$val->name}}</label>
        <div id="king-select">
            <div class="select select-block">
                <?php
                    $selected_value = 0;
                    $value_array = array(0, 1, 2, 3, 4, 5, 6, 7, 8, 9, 10);
                    if($val->quantity != null)
                    $selected_value = $val->quantity;


                ?>

                <!--  1th: name, 2th: array, 3th: selected, 4th: additional-->
                {!! Form::select($val->id,$value_array,$selected_value,['id'=>'king']) !!}
            </div>
        </div>
    </div>
    @endforeach
    <?php /*<div class="col-sm-12 col-md-4">
        <label for="queen">{{ trans('messages.bedroom.queen') }}</label>
        <div id="queen-select">
            <div class="select select-block">
                <?php
                    $value_array = array(0, 1, 2, 3, 4, 5, 6, 7, 8, 9, 10);
                    $selected_value = 0;
                ?>

                <!--  1th: name, 2th: array, 3th: selected, 4th: additional-->
                {!! Form::select('2',$value_array,$selected_value,['id'=>'queen']) !!}
            </div>
        </div>
    </div>
      <div class="col-sm-12 col-md-4">
        <label for="double">{{ trans('messages.bedroom.double') }}</label>
        <div id="double-select">
            <div class="select select-block">
                <?php
                    $value_array = array(0, 1, 2, 3, 4, 5, 6, 7, 8, 9, 10);
                    $selected_value = 0;
                ?>

                <!--  1th: name, 2th: array, 3th: selected, 4th: additional-->
                {!! Form::select('4',$value_array,$selected_value,['id'=>'double']) !!}
            </div>
        </div>
    </div>
  <!--</div>-->

    <!--<div class="row-space-1">-->
    <div class="col-sm-12 col-md-4">
        <label for="single">{{ trans('messages.bedroom.single') }}</label>
        <div id="single-select">
            <div class="select select-block">
                <?php
                    $value_array = array(0, 1, 2, 3, 4, 5, 6, 7, 8, 9, 10);
                    $selected_value = 0;
                ?>

                <!--  1th: name, 2th: array, 3th: selected, 4th: additional-->
                {!! Form::select('3',$value_array,$selected_value,['id'=>'single']) !!}
            </div>
        </div>
    </div>
    <div class="col-sm-12 col-md-4">
        <label for="sofa_bed">{{ trans('messages.bedroom.sofa_bed') }}</label>
        <div id="sofa_bed-select">
            <div class="select select-block">
                <?php
                    $value_array = array(0, 1, 2, 3, 4, 5, 6, 7, 8, 9, 10);
                    $selected_value = 0;
                ?>

                <!--  1th: name, 2th: array, 3th: selected, 4th: additional-->
                {!! Form::select('5',$value_array,$selected_value,['id'=>'sofa_bed']) !!}
            </div>
        </div>
    </div>
      <div class="col-sm-12 col-md-4">
        <label for="couch">{{ trans('messages.bedroom.couch') }}</label>
        <div id="couch-select">
            <div class="select select-block">
                <?php
                    $value_array = array(0, 1, 2, 3, 4, 5, 6, 7, 8, 9, 10);
                    $selected_value = 0;
                ?>

                <!--  1th: name, 2th: array, 3th: selected, 4th: additional-->
                {!! Form::select('6',$value_array,$selected_value,['id'=>'couch']) !!}
            </div>
        </div>
    </div>
  <!--</div>-->

    <!--<div class="row-space-1">-->
    <div class="col-sm-12 col-md-4">
        <label for="air_mattress">{{ trans('messages.bedroom.air_mattress') }}</label>
        <div id="air_mattress-select">
            <div class="select select-block">
                <?php
                    $value_array = array(0, 1, 2, 3, 4, 5, 6, 7, 8, 9, 10);
                    $selected_value = 0;
                ?>

                <!--  1th: name, 2th: array, 3th: selected, 4th: additional-->
                {!! Form::select('7',$value_array,$selected_value,['id'=>'air_mattress']) !!}
            </div>
        </div>
    </div>
    <div class="col-sm-12 col-md-4">
        <label for="bunk_bed">{{ trans('messages.bedroom.bunk_bed') }}</label>
        <div id="bunk_bed-select">
            <div class="select select-block">
                <?php
                    $value_array = array(0, 1, 2, 3, 4, 5, 6, 7, 8, 9, 10);
                    $selected_value = 0;
                ?>

                <!--  1th: name, 2th: array, 3th: selected, 4th: additional-->
                {!! Form::select('8',$value_array,$selected_value,['id'=>'bunk_bed']) !!}
            </div>
        </div>
    </div>
      <div class="col-sm-12 col-md-4">
        <label for="floor_mattress">{{ trans('messages.bedroom.floor_mattress') }}</label>
        <div id="floor_mattress-select">
            <div class="select select-block">
                <?php
                    $value_array = array(0, 1, 2, 3, 4, 5, 6, 7, 8, 9, 10);
                    $selected_value = 0;
                ?>

                <!--  1th: name, 2th: array, 3th: selected, 4th: additional-->
                {!! Form::select('9',$value_array,$selected_value,['id'=>'floor_mattress']) !!}
            </div>
        </div>
    </div>
  <!--</div>-->


    <!--<div class="row-space-1">-->
    <div class="col-sm-12 col-md-4">
        <label for="toddler_bed">{{ trans('messages.bedroom.toddler_bed') }}</label>
        <div id="toddler_bed-select">
            <div class="select select-block">
                <?php
                    $value_array = array(0, 1, 2, 3, 4, 5, 6, 7, 8, 9, 10);
                    $selected_value = 0;
                ?>

                <!--  1th: name, 2th: array, 3th: selected, 4th: additional-->
                {!! Form::select('10',$value_array,$selected_value,['id'=>'toddler_bed']) !!}
            </div>
        </div>
    </div>
    <div class="col-sm-12 col-md-4">
        <label for="crib">{{ trans('messages.bedroom.crib') }}</label>
        <div id="crib-select">
            <div class="select select-block">
                <?php
                    $value_array = array(0, 1, 2, 3, 4, 5, 6, 7, 8, 9, 10);
                    $selected_value = 0;
                ?>

                <!--  1th: name, 2th: array, 3th: selected, 4th: additional-->
                {!! Form::select('11',$value_array,$selected_value,['id'=>'crib']) !!}
            </div>
        </div>
    </div>
      <div class="col-sm-12 col-md-4">
        <label for="water_bed">{{ trans('messages.bedroom.water_bed') }}</label>
        <div id="water_bed-select">
            <div class="select select-block">
                <?php
                    $value_array = array(0, 1, 2, 3, 4, 5, 6, 7, 8, 9, 10);
                    $selected_value = 0;
                ?>

                <!--  1th: name, 2th: array, 3th: selected, 4th: additional-->
                {!! Form::select('12',$value_array,$selected_value,['id'=>'water_bed']) !!}
            </div>
        </div>
    </div>
  <!--</div>-->


    <!--<div class="row-space-1">-->
    <div class="col-sm-12 col-md-4">
        <label for="hammock">{{ trans('messages.bedroom.hammock') }}</label>
        <div id="hammock-select">
            <div class="select select-block">
                <?php
                    $value_array = array(0, 1, 2, 3, 4, 5, 6, 7, 8, 9, 10);
                    $selected_value = 0;
                ?>

                <!--  1th: name, 2th: array, 3th: selected, 4th: additional-->
                {!! Form::select('13',$value_array,$selected_value,['id'=>'hammock']) !!}
            </div>
        </div>
    </div>*/?>
    <!--</div>-->


<!--  <div id="localized-fields">
  <div class="row-space-1">
    <label for="bedroom_line_1">{{ 'asdfasdf' }}</label>
    <input type="text" placeholder="asdfasdf" value="asdfasdf" class="focus" id="bedroom_line_1" name="bedroom_line_1" autocomplete="off">
  </div>
</div>-->



  </div>

  <div class="panel-footer">
    <div class="force-oneline">
        <button data-behavior="modal-close" class="btn js-secondary-btn">
          {{ trans('messages.your_reservations.cancel') }}
        </button>
      <button id="js-save-btn" class="btn btn-primary js-save-btn">
        {{ trans('messages.bedroom.save') }}
      </button>
    </div>
  </div>
  </form>
</div>
