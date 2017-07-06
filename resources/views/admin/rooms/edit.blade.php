@extends('admin.template')

@section('main')
    <!-- Content Wrapper. Contains page content -->
    <div class="content-wrapper" ng-controller="rooms_admin">
        <!-- Content Header (Page header) -->
        <section class="content-header">
            <h1>
                Edit Room
            </h1>
            <ol class="breadcrumb">
                <li><a href="#"><i class="fa fa-dashboard"></i> Home</a></li>
                <li><a href="#">Rooms</a></li>
                <li class="active">Edit</li>
            </ol>
        </section>

        <!-- Main content -->
        <section class="content">
            <div class="row">
                <!-- right column -->
                <div class="col-md-12">
                    <!-- Horizontal Form -->
                    <div class="box box-info">
                        <div class="box-header with-border">
                            <h3 class="box-title">Edit Room Form</h3>
                        </div>
                        <!-- /.box-header -->
                        <!-- form start -->
                        {!! Form::open(['url' => 'admin/edit_room/'.$room_id, 'class' => 'form-horizontal', 'id' => 'add_room_form', 'files' => true]) !!}
                        <input type="hidden" value="{{ $room_id }}" name="room_id" id="room_id">

                        <div class="box-header with-border">
                          <a href="javascript:void(0);" class="step-btn btn btn-warning mt10 btn-active" id="btn_1" ng-click="step(1)">Overview</a>

                          <a href="javascript:void(0);" class="step-btn btn btn-warning mt10" id="btn_2" {{ (@$rooms_status->description != 1) ? 'disabled' : '' }} ng-click="step(2)">The Space</a>

                          <a href="javascript:void(0);" class="step-btn btn btn-warning mt10" id="btn_3" {{ (@$rooms_status->basics != 1) ? 'disabled' : '' }} ng-click="step(3)">Photos</a>

                          <a href="javascript:void(0);" class="step-btn btn btn-warning mt10" id="btn_4" {{ (@$rooms_status->photos != 1) ? 'disabled' : '' }} ng-click="step(4)">Location</a>

                          <a href="javascript:void(0);" class="step-btn btn btn-warning mt10" id="btn_5" {{ (@$rooms_status->location != 1) ? 'disabled' : '' }} ng-click="step(5)">Amenities & Accommodations</a>

                          <a href="javascript:void(0);" class="step-btn btn btn-warning mt10" id="btn_6" {{ (@$rooms_status->amenities != 1) ? 'disabled' : '' }} ng-click="step(6)">Fees & Discounts</a>

                          <a href="javascript:void(0);" class="step-btn btn btn-warning mt10" id="btn_7" {{ (@$rooms_status->pricing != 1) ? 'disabled' : '' }} ng-click="step(7)">Availability</a>

                          <a href="javascript:void(0);" class="step-btn btn btn-warning mt10" id="btn_8" {{ (@$rooms_status->calendar != 1) ? 'disabled' : '' }} ng-click="step(8)">Policies</a>

                          <a href="javascript:void(0);" class="step-btn btn btn-warning mt10" id="btn_9" {{ (@$rooms_status->terms != 1) ? 'disabled' : '' }} ng-click="step(9)">Select User</a>

                      </div>
                        <div id="sf1" class="frm">
                            {{-- <div class="box-header with-border">
                                <a href="javascript:void(0);" class="btn btn-warning mt10" onclick="step(1)" disabled>Overview</a>
                                <a href="javascript:void(0);" class="btn btn-warning mt10" {{ (@$rooms_status->description != 1) ? 'disabled' : '' }} onclick="step(2)">The Space</a>
                                <a href="javascript:void(0);" class="btn btn-warning mt10" {{ (@$rooms_status->basics != 1) ? 'disabled' : '' }} onclick="step(3)">Photos</a>
                                <a href="javascript:void(0);" class="btn btn-warning mt10" {{ (@$rooms_status->photos != 1) ? 'disabled' : '' }} onclick="step(4)">Location</a>
                                <a href="javascript:void(0);" class="btn btn-warning mt10" {{ (@$rooms_status->location != 1) ? 'disabled' : '' }} onclick="step(5)">Amenities & Accommodations</a>
                                <a href="javascript:void(0);" class="btn btn-warning mt10" {{ (@$rooms_status->amenities != 1) ? 'disabled' : '' }} onclick="step(6)">Fees & Discounts</a>
                                <a href="javascript:void(0);" class="btn btn-warning mt10" {{ (@$rooms_status->pricing != 1) ? 'disabled' : '' }} onclick="step(7)">Availability</a>
                                <a href="javascript:void(0);" class="btn btn-warning mt10" {{ (@$rooms_status->calendar != 1) ? 'disabled' : '' }} onclick="step(8)">Policies</a>

                            </div> --}}

                            <fieldset class="box-body">
                                <div class = "list_frame">
                                    <div class = "list_frame_label">
                                        {{ trans('messages.lys.list_name_summary') }}
                                    </div>
                                    <form name="overview" class = "list_inner_frame">
                                        <div class="js-section" ng-init="name='{{ $result->name }}'; summary='{{ $result->summary }}'; ">
                                            <div class="js-saving-progress saving-progress" style="display: none;">
                                                <h5>{{ trans('messages.lys.saving') }}...</h5>
                                            </div>

                                            <div class="row-space-2 clearfix" id="help-panel-name">
                                                <div class="row-space-top-2">
                                                    <div class="col-4">
                                                        <label class="label-large">{{ trans('messages.lys.listing_name') }}</label>
                                                    </div>
                                                    <div class="col-8">
                                                        <div id="js-name-count" class="row-space-top-1 h6 label-large text-right">
                                                            <span ng-bind="50 - name.length">50</span> {{ trans('messages.lys.characters_left') }}
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="col-12">
                                                    <input type="text" name="name" value="{{ $result->name }}" class="overview-title input-large name_required" placeholder="{{ trans('messages.lys.name_placeholder') }}" maxlength="50" ng-model="name">
                                                    <p class="hide icon-rausch error-too-long row-space-top-1">{{ trans('messages.lys.shorten_to_save_changes') }}</p>

                                                    <p class="hide icon-rausch error-value-required row-space-top-1 name_required_msg">{{ trans('messages.lys.value_is_required') }}</p>
                                                </div>
                                            </div>

                                            <div class="row-space-2 clearfix" id="">
                                                <div class="row-space-top-2">
                                                    <div class="col-4">
                                                        <label class="label-large">{{ trans('messages.lys.summary') }} ({{ trans('messages.reviews.required') }})</label>
                                                    </div>
                                                    <div class="col-8">
                                                        <div id="js-summary-count" class="row-space-top-1 h6 label-large text-right">
                                                            <span ng-bind="500 - summary.length">500</span> {{ trans('messages.lys.characters_left') }}
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="col-12">
                                                    <textarea class="overview-summary input-large summary_required" name="summary" rows="6" placeholder="{{ trans('messages.lys.summary_placeholder') }}" maxlength="500" ng-model="summary">{{ $result->summary }}</textarea>

                                                    <p class="hide icon-rausch error-too-long row-space-top-1">{{ trans('messages.lys.shorten_to_save_changes') }}</p>

                                                    <p class="hide icon-rausch error-value-required row-space-top-1 summary_required_msg">{{ trans('messages.lys.value_is_required') }}</p>

                                                </div>
                                            </div>



                                        </div>


                                    </form>
                                </div>
                            </fieldset>
                            <div class="box-footer">
                                <a href="{{ url('/admin/rooms') }}" class="btn btn-default" type="submit" name="cancel" value="cancel">Cancel</a>
                                {{-- <button class="btn btn-info pull-right" type="submit" name="submit" value="description">Submit</button> --}}
                            </div>
                        </div>

                        <div id="sf2" class="frm">
                            <div class="box-header with-border">
                                {{-- <a href="javascript:void(0);" class="btn btn-warning mt10" onclick="step(1)">Overview</a>
                                <a href="javascript:void(0);" class="btn btn-warning mt10" onclick="step(2)" disabled>The Space</a>
                                <a href="javascript:void(0);" class="btn btn-warning mt10" onclick="step(3)">Photos</a>
                                <a href="javascript:void(0);" class="btn btn-warning mt10" onclick="step(4)">Location</a>
                                <a href="javascript:void(0);" class="btn btn-warning mt10" onclick="step(5)">Amenities & Accommodations</a>
                                <a href="javascript:void(0);" class="btn btn-warning mt10" onclick="step(6)">Fees & Discounts</a>
                                <a href="javascript:void(0);" class="btn btn-warning mt10" onclick="step(7)">Availability</a>
                                <a href="javascript:void(0);" class="btn btn-warning mt10" onclick="step(8)">Policies</a> --}}

                            </div>
                            {{-- <p class="text-danger">(*)Fields are Mandatory</p> --}}
                            <fieldset class="box-body">

                                <div class="list_frame">
                                    <div class="list_frame_label">{{ trans('messages.lys.property_room_type') }}</div>
                                    <div class="js-section list_inner_frame">
                                        <div class="js-saving-progress saving-progress basics2" style="display: none;">
                                            <h5>{{ trans('messages.lys.select') }}...</h5>
                                        </div>
                                        <!-- HTML for listing info subsection -->
                                        <div class="row row-space-2">
                                            <div class="col-4">
                                                <label class="label-large">{{ trans('messages.lys.property_type') }}</label>
                                                <div id="property-type-select">
                                                    <div class="select select-large select-block">
                                                        {!! Form::select('property_type',$property_type, $result->property_type, ['id' => 'basics-select-property_type', 'data-saving' => 'basics2']) !!}
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-4">
                                                <label class="label-large">{{ trans('messages.lys.room_type') }}</label>
                                                <div id="room-type-select">
                                                    <div class="select select-large select-block">
                                                        {!! Form::select('room_type',$room_type, $result->room_type, ['id' => 'basics-select-room_type', 'data-saving' => 'basics2']) !!}
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-4">
                                                <label class="label-large">{{ trans('messages.lys.accommodates') }}</label>
                                                <div id="person-capacity-select">
                                                    <div class="select select-large select-block">
                                                        {!! Form::select('accommodates', $accommodates, $result->accommodates, ['class' => 'form-control', 'id' => 'basics-select-accommodates', 'placeholder' => 'Select...', 'data-saving' => 'basics2']) !!}
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        {{--///+++--}}
                                        <div class="row row-space-2">
                                            <div class="col-md-4 col-sm-12">
                                                <label class="label-large">{{ trans('messages.lys.guest_gender') }}</label>
                                                <div id="guest-gender-select">
                                                    <div class="select select-large select-block">
                                                        {!! Form::select('guest_gender',$guest_gender, $result->guest_gender, ['id' => 'basics-select-guest_gender', 'data-saving' => 'basics2']) !!}
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        {{--///+++end--}}
                                    </div>
                                </div>

                                <div class="row-space-top-6 row-space-5"></div>
                                <div class="list_frame">
                                    <div class="list_frame_label">
                                        {{ trans('messages.lys.bedrooms') }}
                                    </div>
                                    <div class="list_inner_frame">
                                        <div class="js-saving-progress saving-progress basics1" style="display: none;">
                                            <h5>{{ trans('messages.lys.saving') }}...</h5>
                                        </div>
                                        <!-- HTML for rooms and beds subsection -->
                                        <div class="row row-space-2">
                                            <div class="col-4">
                                                <label class="label-large">{{ trans('messages.lys.how_many_bedrooms') }}</label>
                                                <div id="bedrooms-select">
                                                    <div class="select select-large select-block" data-behavior="tooltip" data-position="right"
                                                         aria-label="Number of bedrooms can only be set if the room type is &lt;strong&gt;Entire home/apt&lt;/strong&gt;.">
                                                        <select name="bedrooms" id="basics-select-bedrooms" data-saving="basics1">
                                                            <option disabled="" selected="" value="">{{ trans('messages.lys.select') }}...</option>
                                                            @for($i=1;$i<=14;$i++)
                                                                <option value="{{ $i }}" {{ ($i == $result->bedrooms) ? 'selected' : '' }}>
                                                                    {{ $i}}
                                                                </option>
                                                            @endfor
                                                        </select>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>

                                        <div class = "row row-space-2 bedroom_parent" id = "bedroom_parent">
                                            <h4 class="text-center">{{ trans('messages.lys.loading') }}</h4>
                                        </div>

                                    </div>
                                </div>


                                <div class="row-space-top-6 row-space-5"></div>
                                <div class="list_frame">
                                    <div class="list_frame_label">
                                        {{ trans('messages.lys.bathrooms') }}
                                    </div>
                                    <div class="list_inner_frame">
                                        <div class="js-saving-progress saving-progress basics3" style="display: none;">
                                            <h5>{{ trans('messages.lys.select') }}...</h5>
                                        </div>
                                        <!-- HTML for rooms and beds subsection -->
                                        <div class="row row-space-2">
                                            <div class="col-4">
                                                <label class="label-large">{{ trans('messages.lys.how_many_bathrooms') }}</label>
                                                <div id="bathrooms-select">
                                                    <div class="select select-large select-block" data-behavior="tooltip" data-position="right"
                                                         aria-label="Number of bathrooms can only be set if the room type is &lt;strong&gt;Entire home/apt&lt;/strong&gt;.">
                                                        <select name="bathrooms" id="basics-select-bathrooms" data-saving="basics3">
                                                            <option disabled="" selected="" value="">{{ trans('messages.lys.select') }}...</option>
                                                            @for($i=1;$i<=14;$i++)
                                                                <option value="{{ $i }}" {{ ($i == $result->bathrooms) ? 'selected' : '' }}>
                                                                    {{ $i}}
                                                                </option>
                                                            @endfor
                                                        </select>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <!--            ///+++working-->
                                        <div class = "row-space-2 bathroom_parent" id = "bathroom_parent">
                                            <h4 class="text-center">{{ trans('messages.lys.loading') }}</h4>

                                        </div>
                                    </div>
                                </div>
                            </fieldset>
                            <div class="box-footer">
                                <a href="{{ url('/admin/rooms') }}" class="btn btn-default" type="submit" name="cancel" value="cancel">Cancel</a>
                                {{-- <button class="btn btn-info pull-right" type="submit" name="submit" value="basics">Submit</button> --}}
                            </div>
                        </div>

                        <div id="sf3" class="frm">
                            <div class="box-header with-border">
                                {{-- <a href="javascript:void(0);" class="btn btn-warning mt10" onclick="step(1)">Overview</a>
                                <a href="javascript:void(0);" class="btn btn-warning mt10" onclick="step(2)">The Space</a>
                                <a href="javascript:void(0);" class="btn btn-warning mt10" onclick="step(3)" disabled>Photos</a>
                                <a href="javascript:void(0);" class="btn btn-warning mt10" onclick="step(4)">Location</a>
                                <a href="javascript:void(0);" class="btn btn-warning mt10" onclick="step(5)">Amenities & Accommodations</a>
                                <a href="javascript:void(0);" class="btn btn-warning mt10" onclick="step(6)">Fees & Discounts</a>
                                <a href="javascript:void(0);" class="btn btn-warning mt10" onclick="step(7)">Availability</a>
                                <a href="javascript:void(0);" class="btn btn-warning mt10" onclick="step(8)">Policies</a> --}}

                            </div>
                            {{-- <p class="text-danger">(*)Fields are Mandatory</p> --}}
                            <fieldset class="box-body">
                                <div class="list_frame">
                                    <div class="list_frame_label">
                                        {{ trans('messages.lys.photos') }}
                                    </div>
                                    <div class="js-section list_inner_frame">
                                        <div id="photos">
                                            <div class="col-md-12" style="margin-bottom: 10px;">
                                                <div id="js-first-photo-text" class="row-space-top-2 h5 invisible">
                                                    {{ trans('messages.lys.first_photo_appears_search_results') }}!
                                                </div>
                                            </div>


                                            <ul id="js-photo-grid" class="row list-unstyled sortable" ng-init="photos_lists()">
                                                <li ng-repeat="item in photos_list track by $index" class="col-4 row-space-4" data-id="photo_li_@{{ item.id }}" data-index="0" draggable="true" style="display: list-item;" ng-mouseover="over_first_photo($index)" ng-mouseout="out_first_photo($index)" id="photo_li_@{{ item.id }}">
                                                    <div class="panel photo-item">
                                                        <div class="first-photo-ribbon"><i class="icon icon-star text-center"></i></div>
                                                        <div class="photo-size photo-drag-target js-photo-link" id="photo-@{{ item.id }}"></div>
                                                        <a class="media-photo media-photo-block text-center photo-size" href="#">
                                                            {!! Html::image('images/rooms/@{{ item.room_id }}/@{{ item.name }}', '', ['class' => 'img-responsive-height']) !!}
                                                        </a>
                                                        <button data-photo-id="@{{ item.id }}" ng-click="delete_photo(item,item.id)" class="delete-photo-btn overlay-btn js-delete-photo-btn">
                                                            <i class="icon icon-trash"></i>
                                                        </button>
                                                        <div class="panel-body panel-condensed">
                                                            <textarea id="text_heighlight_@{{$index}}" name="@{{ item.id }}" ng-model="item.highlights" ng-keyup="keyup_highlights(item.id, item.highlights)" rows="3" placeholder="{{ trans('messages.lys.highlights_photo') }}" class="input-large highlightss" tabindex="1"></textarea>
                                                        </div>
                                                    </div>
                                                </li>
                                                <li>
                                                    <div class="js-saving-progress saving-progress basics1" style="display: none;">
                                                        <h5>{{ trans('messages.lys.saving') }}...</h5>
                                                    </div>
                                                </li>


                                                <li id="js-photo-grid-placeholder" class="col-4 row-space-4" draggable="true" >
                                                    <div id="photo-placeholder" class="panel photo-item add-photos empty-photo-frame">
                                                        <div class="empty-photo-image">
                                                            <i class="icon icon-picture icon-light-gray icon-size-3"></i>
                                                        </div>
                                                        <p class="row-space-top-4 row-space-4 text-center text-rausch">
                                                            <a data-prevent-default="" id="add-photo-text" href="#" style="text-decoration: none;" class="a_text">+{{ trans_choice('messages.lys.add_photo',1) }}</a>
                                                        </p>
                                                    </div>
                                                </li>
                                                <input type="file" class="hide" name="photos[]" multiple="true" id="upload_photos2" accept="image/*">
                                            </ul>

                                        </div>
                                    </div>
                                </div>

                            </fieldset>
                            <div class="box-footer">
                                <a href="{{ url('/admin/rooms') }}" class="btn btn-default" type="submit" name="cancel" value="cancel">Cancel</a>
                                {{-- <button class="btn btn-info pull-right" type="submit" name="submit" value="photos">Submit</button> --}}
                            </div>
                        </div>

                        <div id="sf4" class="frm">
                            <div class="box-header with-border">
                                {{-- <a href="javascript:void(0);" class="btn btn-warning mt10" onclick="step(1)">Overview</a>
                                <a href="javascript:void(0);" class="btn btn-warning mt10" onclick="step(2)">The Space</a>
                                <a href="javascript:void(0);" class="btn btn-warning mt10" onclick="step(3)">Photos</a>
                                <a href="javascript:void(0);" class="btn btn-warning mt10" onclick="step(4)" disabled>Location</a>
                                <a href="javascript:void(0);" class="btn btn-warning mt10" onclick="step(5)">Amenities & Accommodations</a>
                                <a href="javascript:void(0);" class="btn btn-warning mt10" onclick="step(6)">Fees & Discounts</a>
                                <a href="javascript:void(0);" class="btn btn-warning mt10" onclick="step(7)">Availability</a>
                                <a href="javascript:void(0);" class="btn btn-warning mt10" onclick="step(8)">Policies</a> --}}

                            </div>
                            {{-- <p class="text-danger">(*)Fields are Mandatory</p> --}}
                            <fieldset class="box-body">
                                <div class="list_frame">
                                    <div class="list_frame_label">
                                        {{ trans('messages.your_trips.location') }}
                                    </div>
                                    <div class="list_inner_frame">
                                        <!-- HTML for rooms and beds subsection -->
                                        <div class="row row-space-2">
                                            <p class="text-muted">{{ trans('messages.lys.reservation_confirmed_rext') }}</p>
                                        </div>
                                        <div class="js-saving-progress saving-progress location-panel-saving" style="display: none;">
                                            <h5>{{ trans('messages.lys.saving') }}...</h5>
                                        </div>
                                        <div class="row">
                                            @if(@$result->rooms_address->address_line_1 == '')
                                                <div class="row-space-2" id="edit_address_field">
                                                    <label class="label-large">{{ trans('messages.lys.where_your_space') }}</label>
                                                    <input type="text" placeholder="{{ trans('messages.lys.enter_the_address') }}" value="{{ $result->rooms_address->address_line_1 }}" class="focus" id="pac-input" name="space_location" autocomplete="off">
                                                </div>

                                                <div class="row row-space-2" id="edited_address_field" style="display: none;">
                                                    <div class="col-8">
                                                        <div class="panel-body">
                                                            <address id="saved_address">
                                                                <span class="address-line" ng-init="address_line_1 = '{{ $result->rooms_address->address_line_1 }}'; address_line_2 = '{{ $result->rooms_address->address_line_2 }}'">{{ $result->rooms_address->address_line_1 }} {{ ($result->rooms_address->address_line_2) ? '/ '.$result->rooms_address->address_line_2 : '' }}</span>
                                                                <span class="" ng-init="city = '{{ $result->rooms_address->city }}'; state = '{{ $result->rooms_address->state }}'">{{ $result->rooms_address->city }} {{ $result->rooms_address->state }}</span>
                                                                <span class="" ng-init="postal_code = '{{ $result->rooms_address->postal_code }}'">{{ $result->rooms_address->postal_code }}</span>
                                                                <span class="" ng-init="country='{{ $result->rooms_address->country }}';latitude='{{ $result->rooms_address->latitude }}';longitude='{{ $result->rooms_address->longitude }}'">{{ $result->rooms_address->country_name }}</span>
                                                            </address>
                                                        </div>
                                                    </div>
                                                    <div class="col-4">
                                                        <div style="padding: 8px 0">
                                                            <button id="js-edit-address" class="btn btn-large btn-primary" style="width: 100%">
                                                                {{ trans('messages.lys.edit_address') }}
                                                            </button>
                                                        </div>
                                                    </div>
                                                </div>
                                            @else
                                                <div class="row-space-2" id="edit_address_field" style="display: none;">
                                                    <label class="label-large">{{ trans('messages.lys.where_your_space') }}</label>
                                                    <input type="text" placeholder="{{ trans('messages.lys.enter_the_address') }}" value="{{ $result->rooms_address->address_line_1 }}" class="focus" id="pac-input" name="space_location" autocomplete="off">
                                                </div>

                                                <div class="row row-space-2" id="edited_address_field">
                                                    <div class="col-8">
                                                        <div class="panel-body">
                                                            <address class="{{ (@$result->rooms_address->address_line_1 == '') ? 'hide' : '' }}" id="saved_address">
                                                                <span class="address-line" ng-init="address_line_1 = '{{ $result->rooms_address->address_line_1 }}'; address_line_2 = '{{ $result->rooms_address->address_line_2 }}'">{{ $result->rooms_address->address_line_1 }} {{ ($result->rooms_address->address_line_2) ? '/ '.$result->rooms_address->address_line_2 : '' }}</span>
                                                                <span class="" ng-init="city = '{{ $result->rooms_address->city }}'; state = '{{ $result->rooms_address->state }}'">{{ $result->rooms_address->city }} {{ $result->rooms_address->state }}</span>
                                                                <span class="" ng-init="postal_code = '{{ $result->rooms_address->postal_code }}'">{{ $result->rooms_address->postal_code }}</span>
                                                                <span class="" ng-init="country='{{ $result->rooms_address->country }}';latitude='{{ $result->rooms_address->latitude }}';longitude='{{ $result->rooms_address->longitude }}'">{{ $result->rooms_address->country_name }}</span>
                                                            </address>
                                                        </div>
                                                    </div>
                                                    <div class="col-4">
                                                        <div style="padding: 8px 0">
                                                            <button id="js-edit-address" class="btn btn-large btn-primary" style="width: 100%">
                                                                {{ trans('messages.lys.edit_address') }}
                                                            </button>
                                                        </div>
                                                    </div>
                                                </div>
                                            @endif



                                            <div class="row-space-top-6">
                                                <div id="map" style="height: 500px; width: 100%"></div>
                                                <div id="infowindow-content" style="display: none;">
                                                    <img src="" width="16" height="16" id="place-icon">
                                                    <span id="place-name"  class="title"></span><br>
                                                    <span id="place-address"></span>
                                                </div>
                                                <br />
                                                <p><?php echo html_entity_decode(trans('messages.lys.Your_local_laws_notes')); ?>....<a href="{{ url('/legal') }}" target="_blank">Show More</a></p>

                                            </div>

                                            <div class="row-space-2" id="help-panel-direction-helpful-tips">
                                                <label class="label-large">{{ trans('messages.lys.direction_helpful_tips') }}</label>
                                                <textarea class="input-large textarea-resize-vertical" name="direction_helpful_tips" rows="4" placeholder="Share direction to your place, and public transportation and picking tips." >{{$result->rooms_description->direction_helpful_tips}}</textarea>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div class="row-space-top-6 row-space-5"></div>

                                <div class="list_frame">
                                    <div class="list_frame_label">
                                        {{ trans('messages.lys.the_neighborhood_optional') }}
                                    </div>
                                    <div class="list_inner_frame">
                                        <div class="js-saving-progress saving-progress help-panel-saving" style="display: none;">
                                            <h5>{{ trans('messages.lys.saving') }}...</h5>
                                        </div>

                                        <!-- HTML for rooms and beds subsection -->
                                        <div class="row row-space-2">
                                            <div class="row-space-2" id="help-panel-overview">
                                                <label class="label-large">{{ trans('messages.lys.overview') }}</label>
                                                <textarea class="input-large textarea-resize-vertical" name="overview" rows="4" placeholder="Show people looking at your listing page what make your neighborhood unique.">{{ $result->rooms_description->overview }}</textarea>
                                            </div>

                                            <div class="row-space-2" id="help-panel-getting-arround">
                                                <label class="label-large">{{ trans('messages.lys.getting_around') }}</label>
                                                <textarea class="input-large textarea-resize-vertical" name="getting_arround" rows="4" placeholder="You can let travelers know if your listing is close to public transportation (or far from it). You can also mention nearby parking options.">{{ $result->rooms_description->getting_arround }}</textarea>
                                            </div>

                                            <div class="row-space-2" id="help-panel-local-jewish-life">
                                                <label class="label-large">{{ trans('messages.lys.local_jewish_life') }}</label>
                                                <textarea class="input-large textarea-resize-vertical" name="local_jewish_life" rows="4" placeholder="Share direction to your place, and public transportation and picking tips." >{{ $result->rooms_description->local_jewish_life }}</textarea>
                                            </div>





                                        </div>

                                    </div>
                                </div>

                            </fieldset>
                            <div class="box-footer">
                                <a href="{{ url('/admin/rooms') }}" class="btn btn-default" type="submit" name="cancel" value="cancel">Cancel</a>
                                {{-- <button class="btn btn-info pull-right" type="submit" name="submit" value="location">Submit</button> --}}
                            </div>
                        </div>

                        <div id="sf5" class="frm">
                            <div class="box-header with-border">
                                {{-- <a href="javascript:void(0);" class="btn btn-warning mt10" onclick="step(1)">Overview</a>
                                <a href="javascript:void(0);" class="btn btn-warning mt10" onclick="step(2)">The Space</a>
                                <a href="javascript:void(0);" class="btn btn-warning mt10" onclick="step(3)">Photos</a>
                                <a href="javascript:void(0);" class="btn btn-warning mt10" onclick="step(4)">Location</a>
                                <a href="javascript:void(0);" class="btn btn-warning mt10" onclick="step(5)" disabled>Amenities & Accommodations</a>
                                <a href="javascript:void(0);" class="btn btn-warning mt10" onclick="step(6)">Fees & Discounts</a>
                                <a href="javascript:void(0);" class="btn btn-warning mt10" onclick="step(7)">Availability</a>
                                <a href="javascript:void(0);" class="btn btn-warning mt10" onclick="step(8)">Policies</a> --}}

                            </div>
                            <fieldset class="box-body">
                                <div class="list_frame">
                                    <div class="list_frame_label">
                                    <!--{{ trans('messages.lys.property_room_type') }}-->
                                        {{ trans('messages.lys.amentities_optional') }}
                                    </div>
                                    <!--                <form name="overview" class="list_inner_frame">-->
                                    <div class = "list_inner_frame clearfix" style = "margin-bottom:10px;">
                                        <?php $i = 0;?>
                                        @foreach($amenities_type as $row_type)
                                            <?php $i++;?>
                                            <?php
if ($i == 1 || $i == 3) {
	echo "<div class = 'col-sm-12 col-md-6'>";
}
?>
                                            <div>
                                                <div class="js-section">
                                                    <div style="display:none;" class="js-saving-progress saving-progress {{ $row_type->id }}">
                                                        <h5>{{ trans('messages.lys.saving') }}...</h5>
                                                    </div>

                                                    <h4 style = "margin-top:20px; margin-bottom: 10px;">{{ $row_type->name }}</h4>

                                                    @if($row_type->description != '')
                                                        <p class="text-muted" style = "display:none;">{{ $row_type->description }}</p>
                                                    @endif

                                                    <ul class="list-unstyled">
                                                        @foreach($amenities as $row_amenities)

                                                            @if($row_amenities->type_id == $row_type->id)
                                                                <li>
                                                                    <span>&nbsp;&nbsp;</span>
                                                                    <label class="label-large label-inline amenity-label">
                                                                        <input type="checkbox" value="{{ $row_amenities->id }}" name="amenities" data-saving="{{ $row_type->id }}" {{ in_array($row_amenities->id, $prev_amenities) ? 'checked' : '' }}>
                                                                        <span>{{ $row_amenities->name }}</span>
                                                                    </label>
                                                                    <span>&nbsp;</span>

                                                                    @if($row_amenities->description != '')
                                                                        <span data-toggle="tooltip" class="icon icon-question" title="{{ $row_amenities->description }}"></span>
                                                                    @endif

                                                                </li>
                                                            @endif

                                                        @endforeach

                                                    </ul>

                                                    <!--        <hr class="space-top-4 space-4">-->
                                                </div>
                                            </div>
                                        <?php
if ($i == 2 || $i == 7) {
	echo "</div>";
}
?>
                                    @endforeach

                                    <!--</form>-->
                                    </div>
                                </div>

                                <div class="list_frame" style = "margin-top: 30px;">
                                    <div class="list_frame_label">
                                        {{ trans('messages.lys.religious_accomodations_optional') }}
                                    </div>
                                    <div class = "list_inner_frame clearfix" style = "margin-bottom:10px;">

                                        <div class="js-section">
                                            <div style="display:none;" class="js-saving-progress saving-progress save_religious_amenities">
                                                <h5>{{ trans('messages.lys.saving') }}...</h5>
                                            </div>
                                            <h4>{{trans('messages.new.religious_accommadations')}}</h4>
                                            <div class="row" >
                                                @foreach($religious_amenities_types->chunk(ceil($religious_amenities_types->count()/2)) as $religious_amenities_type_array)
                                                    <div class="col-md-6">
                                                        @foreach($religious_amenities_type_array as $religious_amenities_type)
                                                            <div class="space-3">
                                                                <h5>{{$religious_amenities_type->name}} @if($religious_amenities_type->description != '')<span class="" style="font-weight:normal;">({{$religious_amenities_type->description}})</span>  @endif</h5>
                                                                <ul class="list-unstyled">
                                                                    @foreach($religious_amenities as $religous_amenity)
                                                                        @if($religous_amenity->type_id == $religious_amenities_type->id)
                                                                            <li style="margin-left:10px;">
                                                                                <!-- <span>&nbsp;&nbsp;</span> -->
                                                                                <label class="label-large label-inline amenity-label">
                                                                                    <input type="checkbox" data-extra="@if($religous_amenity->extra_input == 'text')Yes @endif" value="{{ $religous_amenity->id }}" name="religious_amenities" data-saving="save_religious_amenities" {{ in_array($religous_amenity->id, $prev_religious_amenities) ? 'checked' : '' }}>
                                                                                    <span>{{ $religous_amenity->name }} @if($religous_amenity->description != '')({{$religous_amenity->description}})@endif</span>
                                                                                </label>
                                                                                @if($religous_amenity->extra_input == 'text')
                                                                                    <div class="row row-condensed religious_amenity_extra_block {{ in_array($religous_amenity->id, $prev_religious_amenities) ? '' : 'hide' }}" id="religious_amenity_extra_{{$religous_amenity->id}}">
                                                                                        <div class="col-md-1">
                                                                                        </div>
                                                                                        <div class="col-md-6">
                                                                                            <label class="label-inline">{{trans('messages.new.distance')}}</label>
                                                                                        </div>
                                                                                        <div class="col-md-5">
                                                                                            <input type="text" id="religious_amenities_extra_data_{{$religous_amenity->id}}" name="{{$religous_amenity->id}}"  data-saving="save_religious_amenities" value="{{@$religious_amenities_extra_data[$religous_amenity->id]}}">
                                                                                        </div>
                                                                                    </div>
                                                                                @endif
                                                                                <span>&nbsp;</span>
                                                                            </li>
                                                                        @endif
                                                                    @endforeach
                                                                </ul>
                                                            </div>
                                                        @endforeach
                                                    </div>
                                                @endforeach
                                            </div>
                                            <!--                <hr class="space-top-4 space-4">-->
                                        </div>
                                    </div>
                                </div>
                                {{-- <ul class="list-unstyled" id="triple">
                                    @foreach($amenities as $row)
                                        <li>
                                            <span>&nbsp;&nbsp;</span>
                                            <label class="label-large label-inline amenity-label">
                                                <input type="checkbox" value="{{ $row->id }}" name="amenities[]" {{ in_array($row->id, $prev_amenities) ? 'checked' : '' }}>
                                                <span>{{ $row->name }}</span>
                                            </label>
                                            <span>&nbsp;</span>
                                        </li>
                                    @endforeach
                                </ul> --}}
                            </fieldset>
                            <div class="box-footer">
                                <a href="{{ url('/admin/rooms') }}" class="btn btn-default" type="submit" name="cancel" value="cancel">Cancel</a>
                                {{-- <button class="btn btn-info pull-right" type="submit" name="submit" value="amenities">Submit</button> --}}
                            </div>
                        </div>


                        <div id="sf6" class="frm">
                            <div class="box-header with-border">
                                {{-- <a href="javascript:void(0);" class="btn btn-warning mt10" onclick="step(1)">Overview</a>
                                <a href="javascript:void(0);" class="btn btn-warning mt10" onclick="step(2)">The Space</a>
                                <a href="javascript:void(0);" class="btn btn-warning mt10" onclick="step(3)">Photos</a>
                                <a href="javascript:void(0);" class="btn btn-warning mt10" onclick="step(4)">Location</a>
                                <a href="javascript:void(0);" class="btn btn-warning mt10" onclick="step(5)">Amenities & Accommodations</a>
                                <a href="javascript:void(0);" class="btn btn-warning mt10" onclick="step(6)" disabled>Fees & Discounts</a>
                                <a href="javascript:void(0);" class="btn btn-warning mt10" onclick="step(7)">Availability</a>
                                <a href="javascript:void(0);" class="btn btn-warning mt10" onclick="step(8)">Policies</a> --}}

                            </div>
                            {{-- <p class="text-danger">(*)Fields are Mandatory</p> --}}
                            <fieldset class="box-body">
                                <div class="list_frame" style = "margin-bottom: 30px">
                                    <div class="list_frame_label">
                                    <!--{{ trans('messages.lys.property_room_type') }}-->
                                        {{ trans('messages.lys.default_nightly_rate') }}
                                    </div>
                                    <div class = "list_inner_frame clearfix" style = "margin-bottom:10px;">
                                        <div id="help-panel-nightly-price" class="js-section">
                                            <div style="display:none;" class="js-saving-progress saving-progress base_price">
                                                <h5>{{ trans('messages.lys.saving') }}...</h5>
                                            </div>

                                            <div class="row row-table row-space-1">
                                                <div class="col-4 ">
                                                    <label for="listing_price_native" class="label-large">{{ trans('messages.lys.base_price') }}</label>
                                                    <div class="input-addon">
                                                        <span class="input-prefix">{{ $result->rooms_price->currency->original_symbol }}</span>
                                                        <input type="text" data-suggested="" id="price-night" value="{{ ($result->rooms_price->original_night == 0) ? '' : $result->rooms_price->original_night }}" name="night" class="input-stem input-large autosubmit-text" data-saving="base_price">
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
                                                                        <option value="{{$val->code}}">{{$val->code}} - {{$val->name}}</option>
                                                                    @endif
                                                                @endforeach

                                                                <option disabled="">------------------------------------</option>

                                                                @foreach($get_currency as $val)
                                                                    @if($val->code != 'USD' && $val->code != 'AUD' && $val->code != 'GBP' && $val->code != 'EUR' && $val->code != 'CAD')
                                                                        <option value="{{$val->code}}">{{$val->code}} - {{$val->name}}</option>
                                                                    @endif
                                                                @endforeach


                                                            </select>

                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="col-4 ">
                                                </div>
                                            </div>

                                            <p data-error="price" class="ml-error"></p>

                                            <div style = "">*{{ trans('messages.lys.unique_prices_for_specific_date_note') }}</div>

                                        </div>
                                    </div>
                                </div>


                                @if($result->rooms_price->original_week == 0 || $result->rooms_price->original_month == 0)
                                    <p id="js-set-long-term-prices" class="row-space-top-6 text-center text-muted set-long-term-prices">
                                        {{ trans('messages.lys.offer_discounts') }} <a data-prevent-default="" href="#" id="show_long_term">{{ trans('messages.lys.weekly_monthly') }}</a> {{ trans('messages.lys.prices') }}.
                                    </p>

                                    <hr class="row-space-top-6 row-space-5 set-long-term-prices">
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
                                                                    <input type="text" data-suggested="" id="price-week" class="input-stem input-large autosubmit-text" value="{{ ($result->rooms_price->original_week == 0) ? '' : $result->rooms_price->original_week }}" name="week" data-saving="long_price">
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
                                                                    <input type="text" data-suggested="₹16905" id="price-month" class="autosubmit-text input-stem input-large" value="{{ ($result->rooms_price->original_month == 0) ? '' : $result->rooms_price->original_month }}" name="month" data-saving="long_price">
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

                                                <div data-checkbox-id="listing_cleaning_fee_native_checkbox" ng-show="cleaning_checkbox" ng-cloak>
                                                    <div class="row row-table row-space-1">
                                                        <div class="col-4 col-middle">
                                                            <div class="input-addon">
                                                                <span class="input-prefix">{{ $result->rooms_price->currency->original_symbol }}</span>
                                                                <input type="text" data-extras="true" id="price-cleaning" value="{{ ($result->rooms_price->original_cleaning == 0) ? '' : $result->rooms_price->original_cleaning }}" name="cleaning" class="autosubmit-text input-stem input-large" data-saving="additional-saving">
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

                                                <div data-checkbox-id="listing_security_deposit_native_checkbox" ng-show="security_checkbox" ng-cloak>
                                                    <div class="row row-space-1">
                                                        <div class="col-4">
                                                            <div class="input-addon">
                                                                <span class="input-prefix">{{ $result->rooms_price->currency->original_symbol }}</span>
                                                                <input type="text" data-extras="true" value="{{ ($result->rooms_price->original_security == 0) ? '' : $result->rooms_price->original_security }}" id="price-security" name="security" class="autosubmit-text input-stem input-large" data-saving="additional-saving">
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
                                                    <div class="row row-table row-space-1">
                                                        <div class="col-4">
                                                            <div class="input-addon">
                                                                <span class="input-prefix">{{ $result->rooms_price->currency->original_symbol }}</span>
                                                                <input type="text" data-extras="true" value="{{ ($result->rooms_price->original_weekend == 0) ? '' : $result->rooms_price->original_weekend }}" id="price-weekend" name="weekend" class="autosubmit-text input-stem input-large" data-saving="additional-saving">
                                                            </div>
                                                        </div>
                                                    </div>

                                                    <p class="text-muted">
                                                        {{ trans('messages.lys.weekend_pricing_desc') }}
                                                    </p>

                                                    <div class="">
                                                        <label class="label-large">{{ trans('messages.lys.select_which_days_to_apply') }}:</label>

                                                        <label class="label-large label-inline terms-panel">
                                                            <input id="listing_weekday_thursday_checkbox" type="checkbox" value="0" name="thursday" data-saving="additional-saving" ng-model="thursday_checkbox" ng-init="thursday_checkbox = {{ ($result->rooms_price->thursday == 'Yes') ? 'true' : 'false' }}" ng-checked="{{ ($result->rooms_price->thursday == 'Yes') ? 'true' : 'false' }}" class="weekend_check">
                                                            <span>{{ trans('messages.lys.thursday') }}</span>
                                                        </label>



                                                        <label class="label-large label-inline terms-panel">
                                                            <input id="listing_weekday_friday_checkbox" type="checkbox" value="0" name="friday" data-saving="additional-saving" ng-model="friday_checkbox" ng-init="friday_checkbox = {{ ($result->rooms_price->friday == 'Yes') ? 'true' : 'false' }}" ng-checked="{{ ($result->rooms_price->friday == 'Yes') ? 'true' : 'false' }}" class="weekend_check">
                                                            <span>{{ trans('messages.lys.friday') }}</span>
                                                        </label>

                                                        <label class="label-large label-inline terms-panel">
                                                            <input id="listing_weekday_saturday_checkbox" type="checkbox" value="0" name="saturday" data-saving="additional-saving" ng-model="saturday_checkbox" ng-init="saturday_checkbox = {{ ($result->rooms_price->saturday == 'Yes') ? 'true' : 'false' }}" ng-checked="{{ ($result->rooms_price->saturday == 'Yes') ? 'true' : 'false' }}" class="weekend_check">
                                                            <span>{{ trans('messages.lys.saturday') }}</span>
                                                        </label>

                                                    </div>

                                                </div>
                                            </div>

                                        </div>
                                    </div>
                                </div>

                            </fieldset>
                            <div class="box-footer">
                                <a href="{{ url('/admin/rooms') }}" class="btn btn-default" type="submit" name="cancel" value="cancel">Cancel</a>
                                {{-- <button class="btn btn-info pull-right" type="submit" name="submit" value="pricing">Submit</button> --}}
                            </div>
                        </div>

                        <div id="sf7" class="frm">
                            <div class="box-header with-border">
                               {{--  <a href="javascript:void(0);" class="btn btn-warning mt10" onclick="step(1)">Overview</a>
                                <a href="javascript:void(0);" class="btn btn-warning mt10" onclick="step(2)">The Space</a>
                                <a href="javascript:void(0);" class="btn btn-warning mt10" onclick="step(3)">Photos</a>
                                <a href="javascript:void(0);" class="btn btn-warning mt10" onclick="step(4)">Location</a>
                                <a href="javascript:void(0);" class="btn btn-warning mt10" onclick="step(5)">Amenities & Accommodations</a>
                                <a href="javascript:void(0);" class="btn btn-warning mt10" onclick="step(6)">Fees & Discounts</a>
                                <a href="javascript:void(0);" class="btn btn-warning mt10" onclick="step(7)" disabled>Availability</a>
                                <a href="javascript:void(0);" class="btn btn-warning mt10" onclick="step(8)">Policies</a> --}}

                            </div>
                            <fieldset class="box-body">
                                <div id="ajax_container" class="my-calendar">
                                    {!! $calendar !!}
                                </div>
                            </fieldset>

                        </div>


                        <div id="sf8" class="frm">
                            <div class="box-header with-border">
                                {{-- <a href="javascript:void(0);" class="btn btn-warning mt10" onclick="step(1)">Overview</a>
                                <a href="javascript:void(0);" class="btn btn-warning mt10" onclick="step(2)">The Space</a>
                                <a href="javascript:void(0);" class="btn btn-warning mt10" onclick="step(3)">Photos</a>
                                <a href="javascript:void(0);" class="btn btn-warning mt10" onclick="step(4)">Location</a>
                                <a href="javascript:void(0);" class="btn btn-warning mt10" onclick="step(5)">Amenities & Accommodations</a>
                                <a href="javascript:void(0);" class="btn btn-warning mt10" onclick="step(6)">Fees & Discounts</a>
                                <a href="javascript:void(0);" class="btn btn-warning mt10" onclick="step(7)">Availability</a>
                                <a href="javascript:void(0);" class="btn btn-warning mt10" onclick="step(8)" disabled>Policies</a> --}}

                            </div>
                            <fieldset class="box-body">
                                <!-- Check-in and Check-out html-->
                                <div class="row-space-top-4">
                                    <div class="list_frame">
                                        <div class="list_frame_label">
                                            {{ trans('messages.lys.check_in_check_out') }}
                                        </div>
                                        <form name="check_in_check_out" class="">

                                            <div class="js-section list_inner_frame">
                                                <div class="js-saving-progress saving-progress basics2" style="display: none;">
                                                    <h5>{{ trans('messages.lys.select') }}...</h5>
                                                </div>

                                                <!-- HTML for listing info subsection -->
                                                <div class="row row-space-2">
                                                    <div class="col-4">
                                                        <label class="label-large">{{ trans('messages.lys.check_in_window') }}</label>
                                                        <div id="terms-type-select">
                                                            <div class="select select-large select-block">
                                                                {!! Form::select('from_time',$time_array, @$rooms_policies->from_time, ['id' => 'terms-select-from-time', 'data-saving' => 'basics2']) !!}
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="col-1"> <label class="label-large">&nbsp;&nbsp;</label>
                                                        <label class="label-large text-center">to</label> </div>
                                                    <div class="col-4">
                                                        <label class="label-large">&nbsp;&nbsp;</label>
                                                        <div id="terms-type-select">
                                                            <div class="select select-large select-block">
                                                                {!! Form::select('to_time',$time_array, @$rooms_policies->to_time, ['id' => 'terms-select-to-time', 'data-saving' => 'basics2']) !!}
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="row row-space-2">
                                                    <div class="col-6">
                                                        <label class="label-large">{{ trans('messages.lys.checkout_by') }}</label>
                                                        <div id="terms-type-select">
                                                            <div class="select select-large select-block">
                                                                {!! Form::select('checkout_time',$time_array, @$rooms_policies->checkout_time, ['id' => 'terms-select-checkout-time', 'data-saving' => 'basics2']) !!}
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>


                                            </div>
                                        </form>
                                    </div>
                                </div>

                                <!--cancellation policy html-->
                                <div class="row-space-top-4">
                                    <div class="list_frame">
                                        <div class="list_frame_label">
                                            {{ trans('messages.payments.cancellation_policy') }}
                                        </div>
                                        <form name="cancellation_policy" class="">

                                            <div class="js-section list_inner_frame">
                                                <div class="js-saving-progress saving-progress basics4" style="display: none;">
                                                    <h5>{{ trans('messages.lys.select') }}...</h5>
                                                </div>

                                                <!-- HTML for listing info subsection -->
                                                <div class="row row-space-top-2">
                                                    <div class="col-12">
                                                        <div class="radio">
                                                            <label>
                                                                <input type="radio" name="cancel_policy" ng-model="cancel_policy" ng-init="cancel_policy='{{ @$rooms_policies->cancel_policy }}'" id="cancel_policy1" value="Flexible" {{ (@$rooms_policies->cancel_policy == 'Flexible') ? 'checked' : '' }} data-saving="basics4">
                                                                {{ trans('messages.lys.flexible_desc') }}
                                                            </label>
                                                        </div>
                                                    </div>

                                                    <div class="col-12">
                                                        <div class="radio">
                                                            <label>
                                                                <input type="radio" name="cancel_policy" ng-model="cancel_policy" ng-init="cancel_policy='{{ @$rooms_policies->cancel_policy }}'" id="cancel_policy2" value="Moderate" {{ (@$rooms_policies->cancel_policy == 'Moderate') ? 'checked' : '' }} data-saving="basics4">
                                                                {{ trans('messages.lys.moderate_desc') }}
                                                            </label>
                                                        </div>
                                                    </div>

                                                    <div class="col-12">
                                                        <div class="radio">
                                                            <label>
                                                                <input type="radio" name="cancel_policy" ng-model="cancel_policy" ng-init="cancel_policy='{{ @$rooms_policies->cancel_policy }}'" id="cancel_policy3" value="Strict" {{ (@$rooms_policies->cancel_policy == 'Strict') ? 'checked' : '' }} data-saving="basics4">
                                                                {{ trans('messages.lys.strict_desc') }}
                                                            </label>
                                                        </div>
                                                    </div>

                                                    <div class="col-12">
                                                        <div class="" style="margin-left: 30px">
                                                            <label>
                                                                <a href="{{ url('/terms_of_service') }}" target="_blank">{{ trans('messages.lys.click_here_for_policy_details') }}</a>
                                                            </label>
                                                        </div>
                                                    </div>

                                                </div>
                                        </form>
                                    </div>
                                </div>

                                <!--  Guest Requirements (Optional) html -->
                                <div class="row-space-top-4">
                                    <div class="list_frame">
                                        <div class="list_frame_label">
                                            {{ trans('messages.lys.guest_requirements') }}
                                        </div>

                                        <form name="guest_requirements" class="">

                                            <div class="js-section list_inner_frame">
                                                <div class="row row-space-top-4">
                                                    <div class="col-12">
                                                        <p class="text-muted">{{ trans('messages.lys.guest_requirements_desc') }}</p>
                                                    </div>
                                                </div>

                                                <div class="js-saving-progress saving-progress save_guests_req" style="display: none;">
                                                    <h5>{{ trans('messages.lys.select') }}...</h5>
                                                </div>

                                                <!-- HTML for listing info subsection -->
                                                <div class="row row-space-2">
                                                    <div class="col-6">
                                                        <label class="label-large label-inline">
                                                            <input type="checkbox" value="0"  disabled="" checked="checked">
                                                            <span>{{ trans('messages.lys.confirmed_phone_number') }}</span>
                                                        </label>
                                                    </div>

                                                    <div class="col-6">
                                                        <label class="label-large label-inline">
                                                            <input type="checkbox" value="0" disabled="" checked="checked">
                                                            <span>{{ trans('messages.lys.profile_photo') }}</span>
                                                        </label>
                                                    </div>

                                                    <div class="col-6">
                                                        <label class="label-large label-inline">
                                                            <input type="checkbox" value="0" disabled=""  checked="checked">
                                                            <span>{{ trans('messages.login.email_address') }}</span>
                                                        </label>
                                                    </div>

                                                    <div class="col-6">
                                                        <label class="label-large label-inline">
                                                            <input type="checkbox" value="0" disabled=""  checked="checked">
                                                            <span>{{ trans('messages.lys.payment_information') }}</span>
                                                        </label>
                                                    </div>

                                                </div>

                                                <div class="row row-space-top-4">
                                                    <div class="col-12">
                                                        <label class="label-large label-inline terms-panel">{{ trans('messages.lys.additional_requirements') }}</label>
                                                        <p class="text-muted">{{ trans('messages.lys.additional_requirements_notes') }}</p>
                                                    </div>

                                                    <div class="col-6">
                                                        <label class="label-large label-inline terms-panel">
                                                            <input id="is_personal_ref_required_termscheckbox" type="checkbox" value="0" name="is_personal_ref_required" data-saving="save_guests_req" {{ (@$rooms_policies->is_personal_ref_required == 'Yes') ? 'checked' : '' }}>
                                                            <span>{{ trans('messages.lys.personal_references') }}</span>
                                                        </label>
                                                    </div>

                                                    <div class="col-6">
                                                        <label class="label-large label-inline terms-panel">
                                                            <input id="is_government_id_required_termscheckbox" type="checkbox" value="0" name="is_government_id_required" data-saving="save_guests_req" {{ (@$rooms_policies->is_government_id_required == 'Yes') ? 'checked' : '' }}>
                                                            <span>{{ trans('messages.lys.government_issued_id') }} <i class="fa fa-address-card-o" aria-hidden="true"></i></span>
                                                        </label>
                                                    </div>

                                                </div>

                                            </div>
                                        </form>
                                    </div>
                                </div>

                                <!-- House Rules html-->
                                <div class="row-space-top-4"><?php $yes_no_array = ['' => 'Select...', 'No' => 'No', 'Yes' => 'Yes'];?>
                                    <div class="list_frame">
                                        <div class="list_frame_label">
                                            {{ trans('messages.lys.house_rules') }}
                                        </div>
                                        <form name="house_rules" class="">
                                            <div class="js-saving-progress saving-progress basics3" style="display: none;">
                                                <h5>{{ trans('messages.lys.select') }}...</h5>
                                            </div>
                                            <div class="js-section list_inner_frame">


                                                <!-- HTML for listing info subsection -->
                                                <div class="row row-space-top-4">
                                                    <div class="col-8">
                                                        <label class="label-large">{{ trans('messages.lys.suitable_for_children') }}</label>
                                                    </div>
                                                    <div class="col-4">
                                                        <div id="terms-type-select">
                                                            <div class="select select-large select-block">
                                                                {!! Form::select('suitable_for_children',$yes_no_array, @$rooms_policies->suitable_for_children, ['id' => 'terms-select-suitable-for-children', 'data-saving' => 'basics3']) !!}
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>

                                                <div class="row row-space-top-4">
                                                    <div class="col-8">
                                                        <label class="label-large">{{ trans('messages.lys.suitable_for_infants') }}</label>
                                                    </div>
                                                    <div class="col-4">
                                                        <div id="terms-type-select">
                                                            <div class="select select-large select-block">
                                                                {!! Form::select('suitable_for_infants',$yes_no_array, @$rooms_policies->suitable_for_infants, ['id' => 'terms-select-suitable-for-infants', 'data-saving' => 'basics3']) !!}
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>

                                                <div class="row row-space-top-4">
                                                    <div class="col-8">
                                                        <label class="label-large">{{ trans('messages.lys.suitable_for_pets') }}</label>
                                                    </div>
                                                    <div class="col-4">
                                                        <div id="terms-type-select">
                                                            <div class="select select-large select-block">
                                                                {!! Form::select('suitable_for_pets',$yes_no_array, @$rooms_policies->suitable_for_pets, ['id' => 'terms-select-suitable-for-pets', 'data-saving' => 'basics3']) !!}
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>

                                                <div class="row row-space-top-4">
                                                    <div class="col-8">
                                                        <label class="label-large">{{ trans('messages.lys.smoking_allowed') }}</label>
                                                    </div>
                                                    <div class="col-4">
                                                        <div id="terms-type-select">
                                                            <div class="select select-large select-block">
                                                                {!! Form::select('smoking_allowed',$yes_no_array, @$rooms_policies->smoking_allowed, ['id' => 'terms-select-smoking-allowed', 'data-saving' => 'basics3']) !!}
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>

                                                <div class="row row-space-top-4">
                                                    <div class="col-8">
                                                        <label class="label-large">{{ trans('messages.lys.events_parties_allowed') }}</label>
                                                    </div>
                                                    <div class="col-4">
                                                        <div id="terms-type-select">
                                                            <div class="select select-large select-block">
                                                                {!! Form::select('parties_allowed',$yes_no_array, @$rooms_policies->parties_allowed, ['id' => 'terms-select-parties-allowed', 'data-saving' => 'basics3']) !!}
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>

                                                <div class="row row-space-top-4">
                                                    <div class="col-12">
                                                        <label class="label-large label-inline terms-panel">{{ trans('messages.lys.additional_rules') }}</label>
                                                        <p class="text-muted">{{ trans('messages.lys.additional_rules_note') }}</p>
                                                    </div>

                                                    <div class="col-12" id="terms-panel-additional-rules">
                                                        <textarea class="input-large textarea-resize-vertical" name="additional_rules" rows="4" placeholder="{{ trans('messages.lys.additional_rules_placeholder') }}" data-saving="basics3">{{@$rooms_policies->additional_rules}}</textarea>
                                                    </div>

                                                </div>


                                            </div>
                                        </form>
                                    </div>
                                </div>


                                <!-- Kosher html-->
                                <div class="row-space-top-4">
                                    <div class="list_frame">
                                        <div class="list_frame_label">
                                            {{ trans('messages.lys.kosher_optional') }}
                                        </div>
                                        <form name="house_rules" class="">

                                            <div class="js-section list_inner_frame">
                                                <div class="js-saving-progress saving-progress save_guests_req2" style="display: none;">
                                                    <h5>{{ trans('messages.lys.select') }}...</h5>
                                                </div>

                                                <div class="row row-space-top-4">
                                                    <div class="col-12">
                                                        <label class="label-large label-inline terms-panel">
                                                            <input id="is_kosher_termscheckbox" type="checkbox" value="0" name="is_kosher" data-saving="save_guests_req2" {{ (@$rooms_policies->is_kosher == 'Yes') ? 'checked' : '' }}>
                                                            <span>{{ trans('messages.lys.this_space_must_be_kept_kosher') }} </span><a href="{{ url('/kosher') }}" target="_blank">{{ trans('messages.search.learn_more') }}</a>
                                                        </label>
                                                    </div>
                                                </div>

                                                <div class="row row-space-top-4">

                                                    <div class="col-12" id="terms-panel-kosher-expectations">
                                                        <label class="label-large label-inline terms-panel">{{ trans('messages.lys.define_your_kosher') }}</label>
                                                        <textarea class="input-large textarea-resize-vertical" name="kosher_expectations" rows="4" placeholder="" data-saving="save_guests_req2">{{@$rooms_policies->kosher_expectations}}</textarea>
                                                    </div>

                                                </div>


                                            </div>
                                        </form>
                                    </div>
                                </div>


                                <!-- Host/Guest Interaction (Optional) html-->
                                <div class="row-space-top-4">
                                    <div class="list_frame">
                                        <div class="list_frame_label">
                                            {{ trans('messages.lys.host_guest_interaction') }}
                                        </div>
                                        <form name="house_rules" class="">

                                            <div class="js-section list_inner_frame">
                                                <div class="js-saving-progress saving-progress save_guests_req3" style="display: none;">
                                                    <h5>{{ trans('messages.lys.select') }}...</h5>
                                                </div>

                                                <div class="row row-space-top-4">

                                                    <div class="col-12" id="terms-panel-host-interaction">
                                                        <label class="label-large label-inline terms-panel">{{ trans('messages.lys.host_guest_interaction_note') }}</label>
                                                        <textarea class="input-large textarea-resize-vertical" name="host_interaction" rows="4" placeholder="" data-saving="save_guests_req3">{{@$rooms_policies->host_interaction}}</textarea>
                                                    </div>

                                                </div>


                                            </div>
                                        </form>
                                    </div>
                                </div>
                                {{-- <div class="form-group">
                                    <label for="cancel_policy" class="col-sm-3 control-label">Cancellation Policy</label>
                                    <div class="col-sm-6">
                                        {!! Form::select('cancel_policy', ['Flexible'=>'Flexible', 'Moderate'=>'Moderate'], $result->cancel_policy, ['class' => 'form-control', 'id' => 'cancel_policy']) !!}
                                    </div>
                                </div> --}}
                            </fieldset>
                            <div class="box-footer">
                                <a href="{{ url('/admin/rooms') }}" class="btn btn-default" type="submit" name="cancel" value="cancel">Cancel</a>
                                {{-- <button class="btn btn-info pull-right" type="submit" name="submit" value="terms">Submit</button> --}}
                            </div>
                        </div>

                        <div id="sf9" class="frm">

                            <fieldset class="box-body">
                                <div class = "list_frame">
                                    <div class = "list_frame_label">
                                        Assign User
                                    </div>
                                    <div class="col-md-8">
                                        <div class="js-saving-progress saving-progress basics9" style="display: none;">
                                            <h5>{{ trans('messages.lys.saving') }}...</h5>
                                        </div>
                                    </div>
                                    <form name="overview" class = "list_inner_frame">

                                        <div class="js-section mt10" ng-init="user_id='{{ $result->user_id }}'; ">


                                            <div class="row-space-2 clearfix" id="help-panel-name">
                                                <div class="row-space-top-2">
                                                    <div class="col-4">
                                                        <label class="label-large">User Name</label>
                                                    </div>
                                                    <div class="col-8">
                                                        {!! Form::select('user_id', $users_list, $result->user_id, ['class' => 'form-control', 'id' => 'basics-select-user_id', 'placeholder' => 'Select...', 'data-saving' => 'basics9']) !!}
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </form>
                                </div>
                            </fieldset>
                            <div class="box-footer">
                                <a href="{{ url('/admin/rooms') }}" class="btn btn-default" type="submit" name="cancel" value="cancel">Cancel</a>
                                {{-- <button class="btn btn-info pull-right" type="submit" name="submit" value="description">Submit</button> --}}
                            </div>
                        </div>




                        <div id="sf18" class="frm">
                            <div class="box-header with-border">
                                {{-- <a href="javascript:void(0);" class="btn btn-warning mt10" onclick="step(1)">Calendar</a>
                                <a href="javascript:void(0);" class="btn btn-warning mt10" onclick="step(2)">Basics</a>
                                <a href="javascript:void(0);" class="btn btn-warning mt10" onclick="step(3)">Description</a>
                                <a href="javascript:void(0);" class="btn btn-warning mt10" onclick="step(4)">Location</a>
                                <a href="javascript:void(0);" class="btn btn-warning mt10" onclick="step(5)">Amenities</a>
                                <a href="javascript:void(0);" class="btn btn-warning mt10" onclick="step(6)">Photos</a>
                                <a href="javascript:void(0);" class="btn btn-warning mt10" onclick="step(7)">Pricing</a>
                                <a href="javascript:void(0);" class="btn btn-warning mt10" onclick="step(8)" disabled>Booking Type</a> --}}

                            </div>
                            <fieldset class="box-body">
                                <div class="form-group">
                                    <label for="booking_type" class="col-sm-3 control-label">Booking Type</label>
                                    <div class="col-sm-6">
                                        {!! Form::select('booking_type', ['request_to_book'=>'Request To Book', 'instant_book'=>'Instant Book'], $result->booking_type, ['class' => 'form-control', 'id' => 'booking_type']) !!}
                                    </div>
                                </div>
                            </fieldset>
                            <div class="box-footer">
                                <button class="btn btn-default" type="submit" name="cancel" value="cancel">Cancel</button>
                                <button class="btn btn-info pull-right" type="submit" name="submit" value="booking_type">Submit</button>
                            </div>
                        </div>


                        <!-- /.box-body -->

                        <!-- /.box-footer -->
                        {!! Form::close() !!}
                    </div>
                    <!-- /.box -->
                </div>
                <!--/.col (right) -->
            </div>
            <!-- /.row -->
        </section>
        <!-- /.content -->
    </div>


    <div id="bedroom-flow-view">
        <div aria-hidden="true" style="" class="modal" role="dialog" data-sticky="true">
            <div class="modal-table">
                <div class="modal-cell">
                    <div class="modal-content">
                        <a class="modal1-close" data-behavior="modal-close" href=""></a>
                        <div id="js-bedroom-container">

                        </div>

                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- /.content-wrapper -->
    <style type="text/css">
        ul.list-unstyleds {
            margin-bottom:20px;
            overflow:hidden;
        }
        .list-unstyleds > li{
            line-height:1.5em;
            float:left;
            display:inline;
        }
        #double li  { width:50%;}
        #triple li  { width:33.333%; }
        #quad li    { width:25%; }
        #six li     { width:16.666%; }
    </style>

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
        select, textarea, input[type="text"], input[type="password"], input[type="datetime"], input[type="datetime-local"], input[type="date"], input[type="month"], input[type="time"], input[type="week"], input[type="number"], input[type="email"], input[type="url"], input[type="search"], input[type="tel"], input[type="color"], .uneditable-input{
            margin-bottom: 0px !important;
        }
  .btn-active {
    background: #ec971f none repeat scroll 0 0;
    color: #fff;
  }
    </style>
@stop
<script src="https://code.jquery.com/jquery-1.12.4.min.js"></script>
<script>
    get_bedroom_details();
    function get_bedroom_details()
    {
        var url = window.location.href.replace('admin/edit_room/','get_bedroom_details/');
        url = url+'/basics';

        $.getJSON(url, function(response, status){

            $("#bedroom_parent").empty();
            var total_bedrooms = response.total_bedrooms;
            for( var i = 1 ; i <= total_bedrooms.length ; i++ ) {
                var bed_option = '';
                if(total_bedrooms[i-1].bed_options != null){
                    bed_option = total_bedrooms[i-1].bed_options;
                }
                var html = '<div class = "row bedroom_child" id = "bedroom_child">'
                    + '<div class="col-3">'
                    + '<label class="label-large" id = "bedroom_child_label">{{ trans("messages.lys.bedroom") }} ' + i + '</label>'
                    + '</div>';
                if(bed_option != ''){
                    html +=  '<div class="col-5 label-large">'
                        + '<span class ="">' + bed_option + '</span>'
                        + '</div>'
                        +'<div class="col-4 label-large">'
                        + '<a id = "bedroom_child_add_beds" class ="a_text bedroom_child_add_beds">Modify<span style="display:none" class="data_index" data_index=' + i + ' data_id=' + total_bedrooms[i-1].bedroom_id + ' ></span>\n\
<span style="display:none" class="bedroom_type" bedroom_type=' + "Bedroom" + '></span></a>'
                        + '</div>';
                }else{
                    html +=  '<div class="col-4 label-large">'
                        + '<a id = "bedroom_child_add_beds" class ="a_text bedroom_child_add_beds">{{ trans("messages.lys.add_beds") }}<span style="display:none" class="data_index" data_index=' + i + ' data_id=' + total_bedrooms[i-1].bedroom_id + ' ></span>\n\
<span style="display:none" class="bedroom_type" bedroom_type=' + "Bedroom" + '></span></a>'
                        + '</div>';
                }


                html += '</div>';

                $('#bedroom_parent').append(html);
            }

            var total_bathrooms = response.total_bathrooms;
            $('#bathroom_parent').html('');
            if(total_bathrooms.length > 0){
                var html1 = '<div class="row">'
                    +'<div class="col-4"></div>'
                    +'<div class="col-4"><label class="label-large">{{ trans("messages.lys.bathroom_details") }}</label></div>'
                    +'<div class="col-4"><label class="label-large">{{ trans("messages.account.type") }}</label></div>'
                    +'</div>';
                $('#bathroom_parent').append(html1);
            }
            for( var i = 1 ; i <= total_bathrooms.length ; i++ ) {


                var html = '<div class="row row-space-top-2 bathroom_child" id="bathroom_child">'
                    +'<div class="col-4"><label class="label-large" id="bathroom_child_label">{{ trans("messages.lys.bathroom") }} '+i+'</label></div>'
                    +'<div class="col-md-4 col-sm-12"><div class="select select-block">'
                    +'<select name="bathrooms_details" id="basics-select-bathrooms-details-'+i+'" data-id="'+total_bathrooms[i-1].id+'" data-saving="basics3"><option disabled="" selected="" value="">{{ trans("messages.lys.select") }}...</option><option value="private">Private Bathroom</option><option value="shared">Shared Bathroom</option></select>'
                    +'</div></div>'
                    +'<div class="col-md-4 col-sm-12"><div class="select select-block">'
                    +'<select name="bathrooms_type" id="basics-select-bathrooms-type-'+i+'" data-id="'+total_bathrooms[i-1].id+'"  data-saving="basics3"><option disabled="" selected="" value="">{{ trans("messages.lys.select") }}...</option><option value="toilet_shower">{{ trans("messages.lys.bathroom_toilet_shower") }}</option><option value="toilet_only">{{ trans("messages.lys.bathroom_toilet_only") }}</option><option value="shower_only">{{ trans("messages.lys.bathroom_shower_only") }}</option></select>'
                    +'</div></div>'
                    +'</div>';


                $('#bathroom_parent').append(html);
                $('#basics-select-bathrooms-details-'+i).val(total_bathrooms[i-1].bathroom_details);
                $('#basics-select-bathrooms-type-'+i).val(total_bathrooms[i-1].type);
            }

        });
    }

    $( "#basics-select-bedrooms" ).change(function() {
//alert( "Handler for .change() called." );
        var bedrooms_num = $('#basics-select-bedrooms').val();
//alert('bedrooms=' + bedrooms_num);
        if (bedrooms_num < 1) {
//$('#bedroom_parent').hide();
//$('#bedroom_child').hide();
            $("#bedroom_parent").empty();
        } else {
            $("#bedroom_parent").empty();

            for( var i = 1 ; i <= bedrooms_num ; i++ ) {

                var html = '<div class = "row bedroom_child" id = "bedroom_child">'
                    + '<div class="col-3">'
                    + '<label class="label-large" id = "bedroom_child_label">{{ trans("messages.lys.bedroom") }} ' + i + '</label>'
                    + '</div>'
                    + '<div class="col-2 label-large">'
                    + '<a id = "bedroom_child_add_beds" class ="a_text bedroom_child_add_beds">Add beds<span style="display:none" class="data_index" data_index=' + i + '>\n\
<span style="display:none" class="bedroom_type" bedroom_type=' + "Bedroom" + '></a>'
                    + '</div>'
                    + '<div class="col-5">'
                    + '<label class="label-large bedroom_child_content_label" id="bedroom_child_content_label" style = ""></label>'
                    + '</div>'
                    + '<div class="col-2 label-large">'
                    + '<a id = "bedroom_child_modify" class = "a_text bedroom_child_modify" style = "display:none;">Modify</a>'
                    + '</div>'
                    + '</div>';

// $('#bedroom_parent').append(html);
            }
        }
    });

    function show_popup(i, is_add_beds){
        if (is_add_beds){
            alert(i + "Add_beds");
            $( "#dialog" ).dialog();
        }
        else
            alert(i + "Modify");
    }



</script>
@push('scripts')

<script type="text/javascript">
    var lat = <?php if ($result->rooms_address->latitude != '') {echo $result->rooms_address->latitude;} else {echo "-33.8688";}?>;
    var lng = <?php if ($result->rooms_address->longitude != '') {echo $result->rooms_address->longitude;} else {echo "-151.2195";}?>;

</script>
@endpush
<script type="text/javascript">
    var lat = <?php if ($result->rooms_address->latitude != '') {echo $result->rooms_address->latitude;} else {echo "-33.8688";}?>;
    var lng = <?php if ($result->rooms_address->longitude != '') {echo $result->rooms_address->longitude;} else {echo "-151.2195";}?>;

</script>