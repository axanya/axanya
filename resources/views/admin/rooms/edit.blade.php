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
            <div id="sf1" class="frm">
            <div class="box-header with-border">
              <a href="javascript:void(0);" class="btn btn-warning" onclick="step(1)" disabled>Calendar</a>
              <a href="javascript:void(0);" class="btn btn-warning" onclick="step(2)">Basics</a>
              <a href="javascript:void(0);" class="btn btn-warning" onclick="step(3)">Description</a>
              <a href="javascript:void(0);" class="btn btn-warning" onclick="step(4)">Location</a>
              <a href="javascript:void(0);" class="btn btn-warning" onclick="step(5)">Amenities</a>
              <a href="javascript:void(0);" class="btn btn-warning" onclick="step(6)">Photos</a>
              <a href="javascript:void(0);" class="btn btn-warning" onclick="step(7)">Pricing</a>
              <a href="javascript:void(0);" class="btn btn-warning" onclick="step(8)">Booking Type</a>
              <a href="javascript:void(0);" class="btn btn-warning" onclick="step(9)">Terms</a>
            </div>
            <fieldset class="box-body">
            <div id="ajax_container">
            {!! $calendar !!}
            </div>
            </fieldset>
          <!-- <div class="box-footer">
                <button class="btn btn-default" type="submit" name="cancel" value="cancel">Cancel</button>
                <button class="btn btn-info pull-right" type="submit" name="submit" value="calendar">Submit</button>
          </div> -->
          </div>
 
            <div id="sf2" class="frm">
            <div class="box-header with-border">
              <a href="javascript:void(0);" class="btn btn-warning" onclick="step(1)">Calendar</a>
              <a href="javascript:void(0);" class="btn btn-warning" onclick="step(2)" disabled>Basics</a>
              <a href="javascript:void(0);" class="btn btn-warning" onclick="step(3)">Description</a>
              <a href="javascript:void(0);" class="btn btn-warning" onclick="step(4)">Location</a>
              <a href="javascript:void(0);" class="btn btn-warning" onclick="step(5)">Amenities</a>
              <a href="javascript:void(0);" class="btn btn-warning" onclick="step(6)">Photos</a>
              <a href="javascript:void(0);" class="btn btn-warning" onclick="step(7)">Pricing</a>
              <a href="javascript:void(0);" class="btn btn-warning" onclick="step(8)">Booking Type</a>
              <a href="javascript:void(0);" class="btn btn-warning" onclick="step(9)">Terms</a>
            </div>
            <p class="text-danger">(*)Fields are Mandatory</p>  
            <fieldset class="box-body">
            <p class="text-success text-bold">Rooms and Beds</p>
            <div class="form-group">
              <label for="bedrooms" class="col-sm-3 control-label">Bedrooms<em class="text-danger">*</em></label>
              <div class="col-sm-6">
                {!! Form::select('bedrooms', $bedrooms, $result->bedrooms, ['class' => 'form-control', 'id' => 'bedrooms', 'placeholder' => 'Select...']) !!}
              </div>
            </div>
            <div class="form-group">
              <label for="beds" class="col-sm-3 control-label">Beds<em class="text-danger">*</em></label>
              <div class="col-sm-6">
                {!! Form::select('beds', $beds, $result->beds, ['class' => 'form-control', 'id' => 'beds', 'placeholder' => 'Select...']) !!}
              </div>
            </div>
            <div class="form-group">
              <label for="bed_type" class="col-sm-3 control-label">Bed Type<em class="text-danger">*</em></label>
              <div class="col-sm-6">
                {!! Form::select('bed_type', $bed_type, $result->bed_type, ['class' => 'form-control', 'id' => 'bed_type', 'placeholder' => 'Select...']) !!}
              </div>
            </div>
            <div class="form-group">
              <label for="bathrooms" class="col-sm-3 control-label">Bathrooms<em class="text-danger">*</em></label>
              <div class="col-sm-6">
                {!! Form::select('bathrooms', $bathrooms, $result->bathrooms, ['class' => 'form-control', 'id' => 'bathrooms', 'placeholder' => 'Select...']) !!}
              </div>
            </div>
            <p class="text-success text-bold">Listing</p>
            <div class="form-group">
              <label for="property_type" class="col-sm-3 control-label">Property Type<em class="text-danger">*</em></label>
              <div class="col-sm-6">
                {!! Form::select('property_type', $property_type, $result->property_type, ['class' => 'form-control', 'id' => 'property_type', 'placeholder' => 'Select...']) !!}
              </div>
            </div>
            <div class="form-group">
              <label for="room_type" class="col-sm-3 control-label">Room Type<em class="text-danger">*</em></label>
              <div class="col-sm-6">
                {!! Form::select('room_type', $room_type, $result->room_type, ['class' => 'form-control', 'id' => 'room_type', 'placeholder' => 'Select...']) !!}
              </div>
            </div>
            <div class="form-group">
              <label for="accommodates" class="col-sm-3 control-label">Accommodates<em class="text-danger">*</em></label>
              <div class="col-sm-6">
                {!! Form::select('accommodates', $accommodates, $result->accommodates, ['class' => 'form-control', 'id' => 'accommodates', 'placeholder' => 'Select...']) !!}
              </div>
            </div>
            </fieldset>
            <div class="box-footer">
                <button class="btn btn-default" type="submit" name="cancel" value="cancel">Cancel</button>
                <button class="btn btn-info pull-right" type="submit" name="submit" value="basics">Submit</button>
              </div>
            </div>
 
            <div id="sf3" class="frm">
            <div class="box-header with-border">
              <a href="javascript:void(0);" class="btn btn-warning" onclick="step(1)">Calendar</a>
              <a href="javascript:void(0);" class="btn btn-warning" onclick="step(2)">Basics</a>
              <a href="javascript:void(0);" class="btn btn-warning" onclick="step(3)" disabled>Description</a>
              <a href="javascript:void(0);" class="btn btn-warning" onclick="step(4)">Location</a>
              <a href="javascript:void(0);" class="btn btn-warning" onclick="step(5)">Amenities</a>
              <a href="javascript:void(0);" class="btn btn-warning" onclick="step(6)">Photos</a>
              <a href="javascript:void(0);" class="btn btn-warning" onclick="step(7)">Pricing</a>
              <a href="javascript:void(0);" class="btn btn-warning" onclick="step(8)">Booking Type</a>
              <a href="javascript:void(0);" class="btn btn-warning" onclick="step(9)">Terms</a>
            </div>
            <p class="text-danger">(*)Fields are Mandatory</p>  
            <fieldset class="box-body">
            <div class="form-group">
              <label for="name" class="col-sm-3 control-label">Listing Name<em class="text-danger">*</em></label>
              <div class="col-sm-6">
                {!! Form::text('name', $result->name, ['class' => 'form-control', 'id' => 'name', 'placeholder' => 'Be clear and descriptive']) !!}
              </div>
            </div>
            <div class="form-group">
              <label for="summary" class="col-sm-3 control-label">Summary<em class="text-danger">*</em></label>
              <div class="col-sm-6">
                {!! Form::textarea('summary', $result->summary, ['class' => 'form-control', 'id' => 'summary', 'placeholder' => 'Tell travelers what you love about the space. You can include details about the decor, the amenities it includes, and the neighborhood.', 'rows' => 5]) !!}
              </div>
            </div>
            <div class="form-group">
              <label for="space" class="col-sm-3 control-label">Space</label>
              <div class="col-sm-6">
                {!! Form::textarea('space', $result->rooms_description->space, ['class' => 'form-control', 'id' => 'space', 'rows' => 5]) !!}
              </div>
            </div>
            <div class="form-group">
              <label for="access" class="col-sm-3 control-label">Guest Access</label>
              <div class="col-sm-6">
                {!! Form::textarea('access', $result->rooms_description->access, ['class' => 'form-control', 'id' => 'access', 'rows' => 5]) !!}
              </div>
            </div>
            <div class="form-group">
              <label for="interaction" class="col-sm-3 control-label">Interaction with Guests</label>
              <div class="col-sm-6">
                {!! Form::textarea('interaction', $result->rooms_description->interaction, ['class' => 'form-control', 'id' => 'interaction', 'rows' => 5]) !!}
              </div>
            </div>
            <div class="form-group">
              <label for="notes" class="col-sm-3 control-label">Other Things to Note</label>
              <div class="col-sm-6">
                {!! Form::textarea('notes', $result->rooms_description->notes, ['class' => 'form-control', 'id' => 'notes', 'rows' => 5]) !!}
              </div>
            </div>
            <div class="form-group">
              <label for="house_rules" class="col-sm-3 control-label">House Rules</label>
              <div class="col-sm-6">
                {!! Form::textarea('house_rules', $result->rooms_description->house_rules, ['class' => 'form-control', 'id' => 'house_rules', 'rows' => 5]) !!}
              </div>
            </div>
            <div class="form-group">
              <label for="neighborhood_overview" class="col-sm-3 control-label">Overview</label>
              <div class="col-sm-6">
                {!! Form::textarea('neighborhood_overview', $result->rooms_description->neighborhood_overview, ['class' => 'form-control', 'id' => 'neighborhood_overview', 'rows' => 5]) !!}
              </div>
            </div>
            <div class="form-group">
              <label for="transit" class="col-sm-3 control-label">Getting Around</label>
              <div class="col-sm-6">
                {!! Form::textarea('transit', $result->rooms_description->transit, ['class' => 'form-control', 'id' => 'transit', 'rows' => 5]) !!}
              </div>
            </div>
            </fieldset>
              <div class="box-footer">
                <button class="btn btn-default" type="submit" name="cancel" value="cancel">Cancel</button>
                <button class="btn btn-info pull-right" type="submit" name="submit" value="description">Submit</button>
              </div>
            </div>
 
            <div id="sf4" class="frm">
            <div class="box-header with-border">
              <a href="javascript:void(0);" class="btn btn-warning" onclick="step(1)">Calendar</a>
              <a href="javascript:void(0);" class="btn btn-warning" onclick="step(2)">Basics</a>
              <a href="javascript:void(0);" class="btn btn-warning" onclick="step(3)">Description</a>
              <a href="javascript:void(0);" class="btn btn-warning" onclick="step(4)" disabled>Location</a>
              <a href="javascript:void(0);" class="btn btn-warning" onclick="step(5)">Amenities</a>
              <a href="javascript:void(0);" class="btn btn-warning" onclick="step(6)">Photos</a>
              <a href="javascript:void(0);" class="btn btn-warning" onclick="step(7)">Pricing</a>
              <a href="javascript:void(0);" class="btn btn-warning" onclick="step(8)">Booking Type</a>
              <a href="javascript:void(0);" class="btn btn-warning" onclick="step(9)">Terms</a>
            </div>
            <p class="text-danger">(*)Fields are Mandatory</p>  
            <fieldset class="box-body">
            <div class="form-group">
              <label for="country" class="col-sm-3 control-label">Country<em class="text-danger">*</em></label>
              <div class="col-sm-6">
                {!! Form::select('country', $country, $result->rooms_address->country, ['class' => 'form-control', 'id' => 'country', 'placeholder' => 'Select...']) !!}
              </div>
            </div> 
            <div class="form-group">
              <label for="address_line_1" class="col-sm-3 control-label">Address Line 1<em class="text-danger">*</em></label>
              <div class="col-sm-6">
                {!! Form::text('address_line_1', $result->rooms_address->address_line_1, ['class' => 'form-control', 'id' => 'address_line_1', 'placeholder' => 'House name/number + street/road', 'autocomplete' => 'off']) !!}
              </div>
            </div>  
            <div class="form-group">
              <label for="address_line_2" class="col-sm-3 control-label">Address Line 2</label>
              <div class="col-sm-6">
                {!! Form::text('address_line_2', $result->rooms_address->address_line_2, ['class' => 'form-control', 'id' => 'address_line_2', 'placeholder' => 'Apt., suite, building access code']) !!}
              </div>
            </div>    
            <div class="form-group">
              <label for="city" class="col-sm-3 control-label">City / Town / District<em class="text-danger">*</em></label>
              <div class="col-sm-6">
                {!! Form::text('city', $result->rooms_address->city, ['class' => 'form-control', 'id' => 'city', 'placeholder' => '']) !!}
              </div>
            </div>     
            <div class="form-group">
              <label for="state" class="col-sm-3 control-label">State / Province / County / Region<em class="text-danger">*</em></label>
              <div class="col-sm-6">
                {!! Form::text('state', $result->rooms_address->state, ['class' => 'form-control', 'id' => 'state', 'placeholder' => '']) !!}
              </div>
            </div>     
            <div class="form-group">
              <label for="postal_code" class="col-sm-3 control-label">ZIP / Postal Code</label>
              <div class="col-sm-6">
                {!! Form::text('postal_code', $result->rooms_address->postal_code, ['class' => 'form-control', 'id' => 'postal_code', 'placeholder' => '']) !!}
              </div>
            </div>  
            <input type="hidden" name="latitude" id="latitude">         
            <input type="hidden" name="longitude" id="longitude">         
            </fieldset>
              <div class="box-footer">
                <button class="btn btn-default" type="submit" name="cancel" value="cancel">Cancel</button>
                <button class="btn btn-info pull-right" type="submit" name="submit" value="location">Submit</button>
              </div>
            </div>
 
            <div id="sf5" class="frm">
            <div class="box-header with-border">
              <a href="javascript:void(0);" class="btn btn-warning" onclick="step(1)">Calendar</a>
              <a href="javascript:void(0);" class="btn btn-warning" onclick="step(2)">Basics</a>
              <a href="javascript:void(0);" class="btn btn-warning" onclick="step(3)">Description</a>
              <a href="javascript:void(0);" class="btn btn-warning" onclick="step(4)">Location</a>
              <a href="javascript:void(0);" class="btn btn-warning" onclick="step(5)" disabled>Amenities</a>
              <a href="javascript:void(0);" class="btn btn-warning" onclick="step(6)">Photos</a>
              <a href="javascript:void(0);" class="btn btn-warning" onclick="step(7)">Pricing</a>
              <a href="javascript:void(0);" class="btn btn-warning" onclick="step(8)">Booking Type</a>
              <a href="javascript:void(0);" class="btn btn-warning" onclick="step(9)">Terms</a>
            </div>
            <fieldset class="box-body">
            <ul class="list-unstyled" id="triple">
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
            </ul>
            </fieldset>
              <div class="box-footer">
                <button class="btn btn-default" type="submit" name="cancel" value="cancel">Cancel</button>
                <button class="btn btn-info pull-right" type="submit" name="submit" value="amenities">Submit</button>
              </div>
            </div>
 
            <div id="sf6" class="frm">
            <div class="box-header with-border">
              <a href="javascript:void(0);" class="btn btn-warning" onclick="step(1)">Calendar</a>
              <a href="javascript:void(0);" class="btn btn-warning" onclick="step(2)">Basics</a>
              <a href="javascript:void(0);" class="btn btn-warning" onclick="step(3)">Description</a>
              <a href="javascript:void(0);" class="btn btn-warning" onclick="step(4)">Location</a>
              <a href="javascript:void(0);" class="btn btn-warning" onclick="step(5)">Amenities</a>
              <a href="javascript:void(0);" class="btn btn-warning" onclick="step(6)" disabled>Photos</a>
              <a href="javascript:void(0);" class="btn btn-warning" onclick="step(7)">Pricing</a>
              <a href="javascript:void(0);" class="btn btn-warning" onclick="step(8)">Booking Type</a>
              <a href="javascript:void(0);" class="btn btn-warning" onclick="step(9)">Terms</a>
            </div>
            <p class="text-danger">(*)Fields are Mandatory</p>  
            <fieldset class="box-body">
              <div class="form-group">
              <label for="night" class="col-sm-3 control-label">Photos<em class="text-danger">*</em></label>
              <div class="col-sm-6">
                <input type="file" name="photos[]" multiple="true" id="upload_photos" accept="image/*">
              </div>
              <span class="text-success text-bold" style="display:none;" id="saved_message">Saved..</span>
              </div>
              <ul class="row list-unstyled sortable" id="js-photo-grid">
                @foreach($rooms_photos as $row)
                <li style="display: list-item;width:200px;" id="photo_li_{{ $row->id }}" class="col-4 row-space-4 ng-scope">
                    <div class="panel photo-item">                      
                      <div id="photo-5" class="photo-size photo-drag-target js-photo-link"></div>
                      <a href="#" class="media-photo media-photo-block text-center photo-size">
                        
                      <img alt="" class="img-responsive-height" src="{{ url('images/rooms/'.$room_id.'/'.$row->name) }}">
                
                      </a>
                      <button class="delete-photo-btn overlay-btn js-delete-photo-btn" data-photo-id="{{ $row->id }}" type="button">
                        <i class="fa fa-trash" style="color:white;"></i>
                      </button>
                
                      <div class="panel-body panel-condensed">
                        <textarea tabindex="1" class="input-large highlights ng-pristine ng-untouched ng-valid" data-photo-id="{{ $row->id }}" placeholder="What are the highlights of this photo?" rows="3" name="5">{{ $row->highlights }}</textarea>
                      </div>
                    </div>
                </li>
                @endforeach
              </ul>
            </fieldset>
              <div class="box-footer">
                <button class="btn btn-default" type="submit" name="cancel" value="cancel">Cancel</button>
                <button class="btn btn-info pull-right" type="submit" name="submit" value="photos">Submit</button>
              </div>
            </div>
 
            <div id="sf7" class="frm">
            <div class="box-header with-border">
              <a href="javascript:void(0);" class="btn btn-warning" onclick="step(1)">Calendar</a>
              <a href="javascript:void(0);" class="btn btn-warning" onclick="step(2)">Basics</a>
              <a href="javascript:void(0);" class="btn btn-warning" onclick="step(3)">Description</a>
              <a href="javascript:void(0);" class="btn btn-warning" onclick="step(4)">Location</a>
              <a href="javascript:void(0);" class="btn btn-warning" onclick="step(5)">Amenities</a>
              <a href="javascript:void(0);" class="btn btn-warning" onclick="step(6)">Photos</a>
              <a href="javascript:void(0);" class="btn btn-warning" onclick="step(7)" disabled>Pricing</a>
              <a href="javascript:void(0);" class="btn btn-warning" onclick="step(8)">Booking Type</a>
              <a href="javascript:void(0);" class="btn btn-warning" onclick="step(9)">Terms</a>
            </div>
            <p class="text-danger">(*)Fields are Mandatory</p>  
            <fieldset class="box-body">
            <div class="form-group">
              <label for="night" class="col-sm-3 control-label">Night<em class="text-danger">*</em></label>
              <div class="col-sm-6">
                {!! Form::text('night', $result->rooms_price->night, ['class' => 'form-control', 'id' => 'night', 'placeholder' => '']) !!}
              </div>
            </div>
            <div class="form-group">
              <label for="currency_code" class="col-sm-3 control-label">Currency Code<em class="text-danger">*</em></label>
              <div class="col-sm-6">
                {!! Form::select('currency_code', $currency, $result->rooms_price->currency_code, ['class' => 'form-control', 'id' => 'currency_code', 'placeholder' => 'Select...']) !!}
              </div>
            </div>  
            <div class="form-group">
              <label for="week" class="col-sm-3 control-label">Weekly</label>
              <div class="col-sm-6">
                {!! Form::text('week', $result->rooms_price->week, ['class' => 'form-control', 'id' => 'week', 'placeholder' => '']) !!}
              </div>
            </div> 
            <div class="form-group">
              <label for="month" class="col-sm-3 control-label">Monthly</label>
              <div class="col-sm-6">
                {!! Form::text('month', $result->rooms_price->month, ['class' => 'form-control', 'id' => 'month', 'placeholder' => '']) !!}
              </div>
            </div> 
            <div class="form-group">
              <label for="cleaning" class="col-sm-3 control-label">Cleaning</label>
              <div class="col-sm-6">
                {!! Form::text('cleaning', $result->rooms_price->cleaning, ['class' => 'form-control', 'id' => 'cleaning', 'placeholder' => '']) !!}
              </div>
            </div> 
            <div class="form-group">
              <label for="additional_guest" class="col-sm-3 control-label">Additional Guest</label>
              <div class="col-sm-6">
                {!! Form::text('additional_guest', $result->rooms_price->additional_guest, ['class' => 'form-control', 'id' => 'additional_guest', 'placeholder' => '']) !!}
              </div>
            </div> 
            <div class="form-group">
              <label for="guests" class="col-sm-3 control-label">Guests</label>
              <div class="col-sm-6">
                {!! Form::select('guests', $accommodates, $result->rooms_price->guests, ['class' => 'form-control', 'id' => 'guests']) !!}
              </div>
            </div> 
            <div class="form-group">
              <label for="security" class="col-sm-3 control-label">Security</label>
              <div class="col-sm-6">
                {!! Form::text('security', $result->rooms_price->security, ['class' => 'form-control', 'id' => 'security', 'placeholder' => '']) !!}
              </div>
            </div> 
            <div class="form-group">
              <label for="weekend" class="col-sm-3 control-label">Weekend</label>
              <div class="col-sm-6">
                {!! Form::text('weekend', $result->rooms_price->weekend, ['class' => 'form-control', 'id' => 'weekend', 'placeholder' => '']) !!}
              </div>
            </div> 
            </fieldset>
              <div class="box-footer">
                <button class="btn btn-default" type="submit" name="cancel" value="cancel">Cancel</button>
                <button class="btn btn-info pull-right" type="submit" name="submit" value="pricing">Submit</button>
              </div>
            </div>
 
            <div id="sf8" class="frm">
            <div class="box-header with-border">
              <a href="javascript:void(0);" class="btn btn-warning" onclick="step(1)">Calendar</a>
              <a href="javascript:void(0);" class="btn btn-warning" onclick="step(2)">Basics</a>
              <a href="javascript:void(0);" class="btn btn-warning" onclick="step(3)">Description</a>
              <a href="javascript:void(0);" class="btn btn-warning" onclick="step(4)">Location</a>
              <a href="javascript:void(0);" class="btn btn-warning" onclick="step(5)">Amenities</a>
              <a href="javascript:void(0);" class="btn btn-warning" onclick="step(6)">Photos</a>
              <a href="javascript:void(0);" class="btn btn-warning" onclick="step(7)">Pricing</a>
              <a href="javascript:void(0);" class="btn btn-warning" onclick="step(8)" disabled>Booking Type</a>
              <a href="javascript:void(0);" class="btn btn-warning" onclick="step(9)">Terms</a>
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
 
            <div id="sf9" class="frm">
            <div class="box-header with-border">
              <a href="javascript:void(0);" class="btn btn-warning" onclick="step(1)">Calendar</a>
              <a href="javascript:void(0);" class="btn btn-warning" onclick="step(2)">Basics</a>
              <a href="javascript:void(0);" class="btn btn-warning" onclick="step(3)">Description</a>
              <a href="javascript:void(0);" class="btn btn-warning" onclick="step(4)">Location</a>
              <a href="javascript:void(0);" class="btn btn-warning" onclick="step(5)">Amenities</a>
              <a href="javascript:void(0);" class="btn btn-warning" onclick="step(6)">Photos</a>
              <a href="javascript:void(0);" class="btn btn-warning" onclick="step(7)">Pricing</a>
              <a href="javascript:void(0);" class="btn btn-warning" onclick="step(8)">Booking Type</a>
              <a href="javascript:void(0);" class="btn btn-warning" onclick="step(9)" disabled>Terms</a>
            </div> 
            <fieldset class="box-body">
              <div class="form-group">
              <label for="cancel_policy" class="col-sm-3 control-label">Cancellation Policy</label>
              <div class="col-sm-6">
                {!! Form::select('cancel_policy', ['Flexible'=>'Flexible', 'Moderate'=>'Moderate'], $result->cancel_policy, ['class' => 'form-control', 'id' => 'cancel_policy']) !!}
              </div>
              </div>
            </fieldset>
              <div class="box-footer">
                <button class="btn btn-default" type="submit" name="cancel" value="cancel">Cancel</button>
                <button class="btn btn-info pull-right" type="submit" name="submit" value="terms">Submit</button>
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
  <!-- /.content-wrapper -->
  <style type="text/css">
  ul.list-unstyled {
  width:888px;
  margin-bottom:20px;
  overflow:hidden;
}
.list-unstyled > li{
  line-height:1.5em;
  float:left;
  display:inline;
}
#double li  { width:50%;}
#triple li  { width:33.333%; }
#quad li    { width:25%; }
#six li     { width:16.666%; }
</style>
@stop