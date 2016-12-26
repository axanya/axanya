@extends('admin.template')

@section('main')
<!-- Content Wrapper. Contains page content -->
  <div class="content-wrapper" ng-controller="rooms_admin">
    <!-- Content Header (Page header) -->
    <section class="content-header">
      <h1>
        Add Room
      </h1>
      <ol class="breadcrumb">
        <li><a href="#"><i class="fa fa-dashboard"></i> Home</a></li>
        <li><a href="#">Rooms</a></li>
        <li class="active">Add</li>
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
              <h3 class="box-title">Add Room Form</h3>
            </div>
            <!-- /.box-header -->
            <!-- form start -->
            {!! Form::open(['url' => 'admin/add_room', 'class' => 'form-horizontal', 'id' => 'add_room_form', 'files' => true]) !!}
              
            <div id="sf1" class="frm">
            <div class="box-header with-border">
              <h4 class="box-title">Step 1 of 10 - Calendar</h4>
            </div>
            <p class="text-danger">(*)Fields are Mandatory</p>
            <fieldset class="box-body">
            <div class="form-group">
              <label for="calendar" class="col-sm-3 control-label">Calendar<em class="text-danger">*</em></label>
              <div class="col-sm-6">
                {!! Form::select('calendar', ['Always'=>'Always', 'Sometimes'=>'Sometimes', 'Onetime'=>'Onetime'], '', ['class' => 'form-control', 'id' => 'calendar', 'placeholder' => 'Select...']) !!}
              </div>
            </div>
          </fieldset>

          <div class="box-footer">
                <button class="btn btn-primary open1 pull-right" type="button" onclick="next(1)">Next <span class="fa fa-arrow-right"></span></button>
          </div>
          </div>
 
            <div id="sf2" class="frm">
            <div class="box-header with-border">
              <h4 class="box-title">Step 2 of 10 - Basics</h4>
            </div>
            <p class="text-danger">(*)Fields are Mandatory</p>  
            <fieldset class="box-body">
            <p class="text-success text-bold">Rooms and Beds</p>
            <div class="form-group">
              <label for="bedrooms" class="col-sm-3 control-label">Bedrooms<em class="text-danger">*</em></label>
              <div class="col-sm-6">
                {!! Form::select('bedrooms', $bedrooms, '', ['class' => 'form-control', 'id' => 'bedrooms', 'placeholder' => 'Select...']) !!}
              </div>
            </div>
            <div class="form-group">
              <label for="beds" class="col-sm-3 control-label">Beds<em class="text-danger">*</em></label>
              <div class="col-sm-6">
                {!! Form::select('beds', $beds, '', ['class' => 'form-control', 'id' => 'beds', 'placeholder' => 'Select...']) !!}
              </div>
            </div>
            <div class="form-group">
              <label for="bed_type" class="col-sm-3 control-label">Bed Type<em class="text-danger">*</em></label>
              <div class="col-sm-6">
                {!! Form::select('bed_type', $bed_type, '', ['class' => 'form-control', 'id' => 'bed_type', 'placeholder' => 'Select...']) !!}
              </div>
            </div>
            <div class="form-group">
              <label for="bathrooms" class="col-sm-3 control-label">Bathrooms<em class="text-danger">*</em></label>
              <div class="col-sm-6">
                {!! Form::select('bathrooms', $bathrooms, '', ['class' => 'form-control', 'id' => 'bathrooms', 'placeholder' => 'Select...']) !!}
              </div>
            </div>
            <p class="text-success text-bold">Listing</p>
            <div class="form-group">
              <label for="property_type" class="col-sm-3 control-label">Property Type<em class="text-danger">*</em></label>
              <div class="col-sm-6">
                {!! Form::select('property_type', $property_type, '', ['class' => 'form-control', 'id' => 'property_type', 'placeholder' => 'Select...']) !!}
              </div>
            </div>
            <div class="form-group">
              <label for="room_type" class="col-sm-3 control-label">Room Type<em class="text-danger">*</em></label>
              <div class="col-sm-6">
                {!! Form::select('room_type', $room_type, '', ['class' => 'form-control', 'id' => 'room_type', 'placeholder' => 'Select...']) !!}
              </div>
            </div>
            <div class="form-group">
              <label for="accommodates" class="col-sm-3 control-label">Accommodates<em class="text-danger">*</em></label>
              <div class="col-sm-6">
                {!! Form::select('accommodates', $accommodates, '', ['class' => 'form-control', 'id' => 'accommodates', 'placeholder' => 'Select...']) !!}
              </div>
            </div>
            </fieldset>
            <div class="box-footer">
                <button class="btn btn-warning back2" type="button" onclick="back(2)"><span class="fa fa-arrow-left"></span> Back</button>
                <button class="btn btn-primary open2 pull-right" type="button" onclick="next(2)">Next <span class="fa fa-arrow-right"></span></button>
              </div>
            </div>
 
            <div id="sf3" class="frm">
            <div class="box-header with-border">
              <h4 class="box-title">Step 3 of 10 - Description</h4>
            </div>
            <p class="text-danger">(*)Fields are Mandatory</p>  
            <fieldset class="box-body">
            <div class="form-group">
              <label for="name" class="col-sm-3 control-label">Listing Name<em class="text-danger">*</em></label>
              <div class="col-sm-6">
                {!! Form::text('name', '', ['class' => 'form-control', 'id' => 'name', 'placeholder' => 'Be clear and descriptive']) !!}
              </div>
            </div>
            <div class="form-group">
              <label for="summary" class="col-sm-3 control-label">Summary<em class="text-danger">*</em></label>
              <div class="col-sm-6">
                {!! Form::textarea('summary', '', ['class' => 'form-control', 'id' => 'summary', 'placeholder' => 'Tell travelers what you love about the space. You can include details about the decor, the amenities it includes, and the neighborhood.', 'rows' => 5]) !!}
              </div>
            </div>
            <div class="form-group">
              <label for="space" class="col-sm-3 control-label">Space</label>
              <div class="col-sm-6">
                {!! Form::textarea('space', '', ['class' => 'form-control', 'id' => 'space', 'rows' => 5]) !!}
              </div>
            </div>
            <div class="form-group">
              <label for="access" class="col-sm-3 control-label">Guest Access</label>
              <div class="col-sm-6">
                {!! Form::textarea('access', '', ['class' => 'form-control', 'id' => 'access', 'rows' => 5]) !!}
              </div>
            </div>
            <div class="form-group">
              <label for="interaction" class="col-sm-3 control-label">Interaction with Guests</label>
              <div class="col-sm-6">
                {!! Form::textarea('interaction', '', ['class' => 'form-control', 'id' => 'interaction', 'rows' => 5]) !!}
              </div>
            </div>
            <div class="form-group">
              <label for="notes" class="col-sm-3 control-label">Other Things to Note</label>
              <div class="col-sm-6">
                {!! Form::textarea('notes', '', ['class' => 'form-control', 'id' => 'notes', 'rows' => 5]) !!}
              </div>
            </div>
            <div class="form-group">
              <label for="house_rules" class="col-sm-3 control-label">House Rules</label>
              <div class="col-sm-6">
                {!! Form::textarea('house_rules', '', ['class' => 'form-control', 'id' => 'house_rules', 'rows' => 5]) !!}
              </div>
            </div>
            <div class="form-group">
              <label for="neighborhood_overview" class="col-sm-3 control-label">Overview</label>
              <div class="col-sm-6">
                {!! Form::textarea('neighborhood_overview', '', ['class' => 'form-control', 'id' => 'neighborhood_overview', 'rows' => 5]) !!}
              </div>
            </div>
            <div class="form-group">
              <label for="transit" class="col-sm-3 control-label">Getting Around</label>
              <div class="col-sm-6">
                {!! Form::textarea('transit', '', ['class' => 'form-control', 'id' => 'transit', 'rows' => 5]) !!}
              </div>
            </div>
            </fieldset>
              <div class="box-footer">
                <button class="btn btn-warning" type="button" onclick="back(3)"><span class="fa fa-arrow-left"></span> Back</button>
                <button class="btn btn-primary pull-right" type="button" onclick="next(3)">Next <span class="fa fa-arrow-right"></span></button>
              </div>
            </div>
 
            <div id="sf4" class="frm">
            <div class="box-header with-border">
              <h4 class="box-title">Step 4 of 10 - Location</h4>
            </div>
            <p class="text-danger">(*)Fields are Mandatory</p>  
            <fieldset class="box-body">
            <div class="form-group">
              <label for="country" class="col-sm-3 control-label">Country<em class="text-danger">*</em></label>
              <div class="col-sm-6">
                {!! Form::select('country', $country, '', ['class' => 'form-control', 'id' => 'country', 'placeholder' => 'Select...']) !!}
              </div>
            </div> 
            <div class="form-group">
              <label for="address_line_1" class="col-sm-3 control-label">Address Line 1<em class="text-danger">*</em></label>
              <div class="col-sm-6">
                {!! Form::text('address_line_1', '', ['class' => 'form-control', 'id' => 'address_line_1', 'placeholder' => 'House name/number + street/road', 'autocomplete' => 'off']) !!}
              </div>
            </div>  
            <div class="form-group">
              <label for="address_line_2" class="col-sm-3 control-label">Address Line 2</label>
              <div class="col-sm-6">
                {!! Form::text('address_line_2', '', ['class' => 'form-control', 'id' => 'address_line_2', 'placeholder' => 'Apt., suite, building access code']) !!}
              </div>
            </div>    
            <div class="form-group">
              <label for="city" class="col-sm-3 control-label">City / Town / District<em class="text-danger">*</em></label>
              <div class="col-sm-6">
                {!! Form::text('city', '', ['class' => 'form-control', 'id' => 'city', 'placeholder' => '']) !!}
              </div>
            </div>     
            <div class="form-group">
              <label for="state" class="col-sm-3 control-label">State / Province / County / Region<em class="text-danger">*</em></label>
              <div class="col-sm-6">
                {!! Form::text('state', '', ['class' => 'form-control', 'id' => 'state', 'placeholder' => '']) !!}
              </div>
            </div>     
            <div class="form-group">
              <label for="postal_code" class="col-sm-3 control-label">ZIP / Postal Code</label>
              <div class="col-sm-6">
                {!! Form::text('postal_code', '', ['class' => 'form-control', 'id' => 'postal_code', 'placeholder' => '']) !!}
              </div>
            </div>  
            <input type="hidden" name="latitude" id="latitude">         
            <input type="hidden" name="longitude" id="longitude">         
            </fieldset>
              <div class="box-footer">
                <button class="btn btn-warning" type="button" onclick="back(4)"><span class="fa fa-arrow-left"></span> Back</button>
                <button class="btn btn-primary pull-right" type="button" onclick="next(4)">Next <span class="fa fa-arrow-right"></span></button>
              </div>
            </div>
 
            <div id="sf5" class="frm">
            <div class="box-header with-border">
              <h4 class="box-title">Step 5 of 10 - Amenities</h4>
            </div>
            <fieldset class="box-body">
            <ul class="list-unstyled" id="triple">
              @foreach($amenities as $row)
                <li>
                <span>&nbsp;&nbsp;</span>
                <label class="label-large label-inline amenity-label">
                <input type="checkbox" value="{{ $row->id }}" name="amenities[]">
                <span>{{ $row->name }}</span>
                </label>
                <span>&nbsp;</span>
                </li>
              @endforeach
            </ul>
            </fieldset>
              <div class="box-footer">
                <button class="btn btn-warning" type="button" onclick="back(5)"><span class="fa fa-arrow-left"></span> Back</button>
                <button class="btn btn-primary pull-right" type="button" onclick="next(5)">Next <span class="fa fa-arrow-right"></span></button>
              </div>
            </div>
 
            <div id="sf6" class="frm">
            <div class="box-header with-border">
              <h4 class="box-title">Step 6 of 10 - Photos</h4>
            </div>
            <p class="text-danger">(*)Fields are Mandatory</p>  
            <fieldset class="box-body">
              <div class="form-group">
              <label for="night" class="col-sm-3 control-label">Photos<em class="text-danger">*</em></label>
              <div class="col-sm-6">
                <input type="file" name="photos[]" multiple="true" id="upload_photos" accept="image/*">
              </div>
              </div>
            </fieldset>
              <div class="box-footer">
                <button class="btn btn-warning" type="button" onclick="back(6)"><span class="fa fa-arrow-left"></span> Back</button>
                <button class="btn btn-primary pull-right" type="button" onclick="next(6)">Next <span class="fa fa-arrow-right"></span></button>
              </div>
            </div>
 
            <div id="sf7" class="frm">
            <div class="box-header with-border">
              <h4 class="box-title">Step 7 of 10 - Pricing</h4>
            </div>
            <p class="text-danger">(*)Fields are Mandatory</p>  
            <fieldset class="box-body">
            <div class="form-group">
              <label for="night" class="col-sm-3 control-label">Night<em class="text-danger">*</em></label>
              <div class="col-sm-6">
                {!! Form::text('night', '', ['class' => 'form-control', 'id' => 'night', 'placeholder' => '']) !!}
              </div>
            </div>
            <div class="form-group">
              <label for="currency_code" class="col-sm-3 control-label">Currency Code<em class="text-danger">*</em></label>
              <div class="col-sm-6">
                {!! Form::select('currency_code', $currency, '', ['class' => 'form-control', 'id' => 'currency_code', 'placeholder' => 'Select...']) !!}
              </div>
            </div>  
            <div class="form-group">
              <label for="week" class="col-sm-3 control-label">Weekly</label>
              <div class="col-sm-6">
                {!! Form::text('week', '', ['class' => 'form-control', 'id' => 'week', 'placeholder' => '']) !!}
              </div>
            </div> 
            <div class="form-group">
              <label for="month" class="col-sm-3 control-label">Monthly</label>
              <div class="col-sm-6">
                {!! Form::text('month', '', ['class' => 'form-control', 'id' => 'month', 'placeholder' => '']) !!}
              </div>
            </div> 
            <div class="form-group">
              <label for="cleaning" class="col-sm-3 control-label">Cleaning</label>
              <div class="col-sm-6">
                {!! Form::text('cleaning', '', ['class' => 'form-control', 'id' => 'cleaning', 'placeholder' => '']) !!}
              </div>
            </div> 
            <div class="form-group">
              <label for="additional_guest" class="col-sm-3 control-label">Additional Guest</label>
              <div class="col-sm-6">
                {!! Form::text('additional_guest', '', ['class' => 'form-control', 'id' => 'additional_guest', 'placeholder' => '']) !!}
              </div>
            </div> 
            <div class="form-group">
              <label for="guests" class="col-sm-3 control-label">Guests</label>
              <div class="col-sm-6">
                {!! Form::select('guests', $accommodates, 1, ['class' => 'form-control', 'id' => 'guests']) !!}
              </div>
            </div> 
            <div class="form-group">
              <label for="security" class="col-sm-3 control-label">Security</label>
              <div class="col-sm-6">
                {!! Form::text('security', '', ['class' => 'form-control', 'id' => 'security', 'placeholder' => '']) !!}
              </div>
            </div> 
            <div class="form-group">
              <label for="weekend" class="col-sm-3 control-label">Weekend</label>
              <div class="col-sm-6">
                {!! Form::text('weekend', '', ['class' => 'form-control', 'id' => 'weekend', 'placeholder' => '']) !!}
              </div>
            </div> 
            </fieldset>
              <div class="box-footer">
                <button class="btn btn-warning" type="button" onclick="back(7)"><span class="fa fa-arrow-left"></span> Back</button>
                <button class="btn btn-primary pull-right" type="button" onclick="next(7)"><span class="fa fa-arrow-right"></span>Next </button>
              </div>
            </div>

            <div id="sf8" class="frm">
            <div class="box-header with-border">
              <h4 class="box-title">Step 8 of 10 - Booking Type</h4>
            </div>  
            <fieldset class="box-body">
            <div class="form-group">
              <label for="booking_type" class="col-sm-3 control-label">Booking Type</label>
              <div class="col-sm-6">
                {!! Form::select('booking_type', ['request_to_book'=>'Request To Book', 'instant_book'=>'Instant Book'], 'request_to_book', ['class' => 'form-control', 'id' => 'booking_type']) !!}
              </div>
            </div>
          </fieldset>
              <div class="box-footer">
                <button class="btn btn-warning" type="button" onclick="back(8)"><span class="fa fa-arrow-left"></span> Back</button>
                <button class="btn btn-primary pull-right" type="button" onclick="next(8)"><span class="fa fa-arrow-right"></span>Next </button>
              </div>
            </div>

            <div id="sf9" class="frm">
            <div class="box-header with-border">
              <h4 class="box-title">Step 9 of 10 - Terms</h4>
            </div>
            <p class="text-danger">(*)Fields are Mandatory</p>  
            <fieldset class="box-body">
              <div class="form-group">
              <label for="cancel_policy" class="col-sm-3 control-label">Cancellation Policy<em class="text-danger">*</em></label>
              <div class="col-sm-6">
                {!! Form::select('cancel_policy', ['Flexible'=>'Flexible', 'Moderate'=>'Moderate'], '', ['class' => 'form-control', 'id' => 'cancel_policy', 'placeholder' => 'Select...']) !!}
              </div>
              </div>
            </fieldset>
              <div class="box-footer">
                <button class="btn btn-warning" type="button" onclick="back(9)"><span class="fa fa-arrow-left"></span> Back</button>
                <button class="btn btn-primary pull-right" type="button" onclick="next(9)"><span class="fa fa-arrow-right"></span>Next </button>
              </div>
            </div>

            <div id="sf10" class="frm">
            <div class="box-header with-border">
              <h4 class="box-title">Step 10 of 10 - User</h4>
            </div>
            <p class="text-danger">(*)Fields are Mandatory</p>  
            <fieldset class="box-body">
              <div class="form-group">
              <label for="user_id" class="col-sm-3 control-label">Username<em class="text-danger">*</em></label>
              <div class="col-sm-6">
                {!! Form::select('user_id', $users_list, '', ['class' => 'form-control', 'id' => 'user_id', 'placeholder' => 'Select...']) !!}
              </div>
              </div>
            </fieldset>
              <div class="box-footer">
                <button class="btn btn-warning" type="button" onclick="back(10)"><span class="fa fa-arrow-left"></span> Back</button>
                <button class="btn btn-primary pull-right" type="button" onclick="next(10)">Submit</button>
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
  width:760px;
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