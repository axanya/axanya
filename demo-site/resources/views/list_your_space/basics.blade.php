<!-- Center Part Starting  -->
<div class="manage-listing-content-container" id="js-manage-listing-content-container">
    <div class="manage-listing-content-wrapper">
        <div class="manage-listing-content" id="js-manage-listing-content">

            <div>
                <div class="row-space-4">
                    <div class="row">


                        <h3 class="col-12">{{ trans('messages.lys.basics_title') }}</h3>

                    </div>
                    <p class="text-muted">{{ trans('messages.lys.basics_desc',['site_name'=>$site_name]) }}</p>
                </div>

                <hr class="row-space-top-6 row-space-5">


                <div class="js-section">
                    <div class="js-saving-progress saving-progress basics2" style="display: none;">
                        <h5>{{ trans('messages.lys.select') }}...</h5>
                    </div>

                    <h4>{{ trans('messages.lys.listing') }}</h4>

                    <!-- HTML for listing info subsection -->
                    <div class="row row-space-2">
                        <div class="col-4">
                            <label class="label-large">{{ trans('messages.lys.property_type') }}</label>
                            <div id="property-type-select">
                                <div class="select
            select-large
            select-block">
                                    {!! Form::select('property_type',$property_type, $result->property_type, ['id' => 'basics-select-property_type', 'data-saving' => 'basics2']) !!}
                                </div>
                            </div>
                        </div>
                        <div class="col-4">
                            <label class="label-large">{{ trans('messages.lys.room_type') }}</label>
                            <div id="room-type-select">
                                <div class="select
            select-large
            select-block">
                                    {!! Form::select('room_type',$room_type, $result->room_type, ['id' => 'basics-select-room_type', 'data-saving' => 'basics2']) !!}
                                </div>
                            </div>
                        </div>
                        <div class="col-4">
                            <label class="label-large">{{ trans('messages.lys.accommodates') }}</label>
                            <div id="person-capacity-select">
                                <div class="select
            select-large
            select-block">
                                    <select name="accommodates" id="basics-select-accommodates" data-saving="basics2">

                                        @for($i=1;$i<=16;$i++)
                                            <option class="accommodates"
                                                    value="{{ $i }}" {{ ($i == $result->accommodates) ? 'selected' : '' }}>
                                                {{ ($i == '16') ? $i.'+' : $i }}
                                            </option>
                                        @endfor

                                    </select>
                                </div>
                            </div>
                        </div>
                    </div>

                </div>
                <hr class="row-space-top-6 row-space-5">
                <div class="js-section"
                     ng-init="bedrooms='{{ $result->bedrooms }}';beds='{{ $result->beds }}';bathrooms='{{ $result->bathrooms }}';bed_type='{{ $result->bed_type }}';">
                    <div class="js-saving-progress saving-progress basics1" style="display: none;">
                        <h5>{{ trans('messages.lys.saving') }}...</h5>
                    </div>

                    <h4>{{ trans('messages.lys.rooms_beds') }}</h4>

                    <!-- HTML for rooms and beds subsection -->
                    <div class="row row-space-2">
                        <div class="col-4">
                            <label class="label-large">{{ trans('messages.lys.bedrooms') }}</label>
                            <div id="bedrooms-select">
                                <div class="select
            select-large
            select-block" data-behavior="tooltip" data-position="right"
                                     aria-label="Number of bedrooms can only be set if the room type is &lt;strong&gt;Entire home/apt&lt;/strong&gt;.">
                                    <select name="bedrooms" id="basics-select-bedrooms" data-saving="basics1"
                                            ng-model="bedrooms">

                                        <option disabled="" selected="" value="">{{ trans('messages.lys.select') }}...
                                        </option>

                                        <option value="0" {{ ('0' == $result->bedrooms) ? 'selected' : '' }}>{{ trans('messages.lys.studio') }}</option>

                                        @for($i=1;$i<=10;$i++)
                                            <option value="{{ $i }}" {{ ($i == $result->bedrooms) ? 'selected' : '' }}>
                                                {{ $i}}
                                            </option>
                                        @endfor

                                    </select>
                                </div>
                            </div>
                        </div>
                        <div class="col-4">
                            <label class="label-large">{{ trans('messages.lys.beds') }}</label>
                            <div id="beds-select">
                                <div class="select
            select-large
            select-block">
                                    <select name="beds" id="basics-select-beds" data-saving="basics1" ng-model="beds">

                                        <option disabled="" selected="" value="">{{ trans('messages.lys.select') }}...
                                        </option>

                                        @for($i=1;$i<=16;$i++)
                                            <option value="{{ $i }}" {{ ($i == $result->beds) ? 'selected' : '' }}>
                                                {{ ($i == '16') ? $i.'+' : $i }}
                                            </option>
                                        @endfor

                                    </select>
                                </div>
                            </div>
                        </div>
                        <div id="js-bathrooms_select-container" class="col-4">
                            <label class="label-large">{{ trans('messages.lys.bathrooms') }}</label>
                            <div id="bathrooms-select">
                                <div class="select
            select-large
            select-block">
                                    <select name="bathrooms" id="basics-select-bathrooms" data-saving="basics1"
                                            ng-model="bathrooms">

                                        <option disabled="" value="" selected="">{{ trans('messages.lys.select') }}...
                                        </option>

                                        <option value="0" {{ ('0' == $result->bathrooms) ? 'selected' : '' }}>0</option>

                                        @for($i=0.5;$i<=8;$i+=0.5)
                                            <option class="bathrooms"
                                                    value="{{ $i }}" {{ ($i == $result->bathrooms) ? 'selected' : '' }}>
                                                {{ ($i == '8') ? $i.'+' : $i }}
                                            </option>
                                        @endfor

                                    </select>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="row row-space-2" id="beds_show" style="{{ (!$result->beds) ? 'display:none;' : '' }}">
                        <div class="col-4">
                            <label class="label-large">{{ trans('messages.lys.bed_type') }}</label>
                            <div id="bedtype-select">
                                <div class="select
            select-large
            select-block">
                                    <select id="basics-select-bed_type" name="bed_type" data-saving="basics1"
                                            ng-model="bed_type">

                                        <option value="" selected="" disabled="">{{ trans('messages.lys.select') }}...
                                        </option>

                                        @foreach($bed_type as $row_bed_type)
                                            <option value="{{ $row_bed_type->id }}" {{ ($row_bed_type->id == $result->bed_type) ? 'selected' : '' }}>{{ $row_bed_type->name }}</option>
                                        @endforeach

                                    </select>
                                </div>
                            </div>
                        </div>
                    </div>

                </div>

                <div class="not-post-listed row row-space-top-6 progress-buttons">
                    <div class="col-12">
                        <div class="separator"></div>
                    </div>


                    <div class="col-10">
                        <div id="js-publish-button" style="float:right;">

                        </div>
                    </div>
                    @if($result->status == NULL)
                        <button data-href="complete"
                                class="animated btn btn-large btn-host btn-primary {{ ($result->steps_count == 0) ? '' : 'hide'}}"
                                id="js-list-space-button" data-track="list_space_button_left_nav" style="">
                            {{ trans('messages.lys.list_space') }}
                        </button>
                        <div class="col-2 text-right next_step">
                            <a data-prevent-default="" href="{{ url('manage-listing/'.$room_id.'/description') }}"
                               class="btn btn-large btn-primary next-section-button {{ ($result->steps_count != 0) ? '' : 'hide'}}">
                                {{ trans('messages.lys.next') }}
                            </a>
                        </div>
                </div>
                @endif

                @if($result->status != NULL)
                    <div class="col-2 text-right next_step">
                        <a data-prevent-default="" href="{{ url('manage-listing/'.$room_id.'/description') }}"
                           class="btn btn-large btn-primary next-section-button">
                            {{ trans('messages.lys.next') }}
                        </a>
                    </div>
            </div>
            @endif

        </div>

    </div>
</div>
<div class="manage-listing-help hide" id="js-manage-listing-help"></div>
</div>
<div class="manage-listing-content-background"></div>
</div>
<!-- Center Part Ending -->