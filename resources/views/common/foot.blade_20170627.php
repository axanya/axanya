
 <div id="gmap-preload" class="hide"></div>

   <div class="ipad-interstitial-wrapper"><span data-reactid=".1"></span></div>

    <div id="fb-root"></div>

    <script type="text/javascript" src="https://maps.googleapis.com/maps/api/js?key={{ $map_key }}&sensor=false&libraries=places"></script>


    {!! Html::script('js/jquery-1.11.3.js') !!}
    {!! Html::script('js/jquery-ui.js') !!}
    {!! Html::script('js/i18n/datepicker-'.Session::get('language').'.js') !!}
    {!! Html::script('js/bootstrap.min.js') !!}


    {!! Html::script('js/angular.js') !!}
    {!! Html::script('js/angular-sanitize.js') !!}
      {!! Html::script('js/map-icons.js') !!}
      {!! Html::script('js/markerwithlabel.js') !!}

    <script>
    var app = angular.module('App', ['ngSanitize']);
    var APP_URL = {!! json_encode(url('/')) !!};
    var USER_ID = {!! @Auth::user()->user()->id !!}
    $.datepicker.setDefaults($.datepicker.regional[ "{{ (Session::get('language')) ? Session::get('language') : $default_language[0]->value }}" ])
    </script>

    {!! $head_code !!}

    {!! Html::script('js/common.js') !!}

    @if (!isset($exception))

      @if (Route::current()->uri() == 'rooms/new')
        {!! Html::script('js/rooms_new.js') !!}
      @endif

      @if (Route::current()->uri() == 'manage-listing/{id}/{page}')
        {!! Html::script('js/manage_listing.js') !!}
  {{--       {!! Html::script('js/jquery.mobile-1.4.5.js') !!} --}}


      @endif

      @if (Route::current()->uri() == 's')
        {!! Html::script('js/search.js') !!}
		{!! Html::script('js/infobubble.js') !!}
      @endif

      @if (Route::current()->uri() == 'rooms/{id}')
        {!! Html::script('js/rooms.js') !!}
        {!! Html::script('js/jquery.bxslider.min.js') !!}
        {!! Html::script('js/sweetalert.min.js') !!}
          <script>
              $('#toggle_error').on('click', function () {
                  $('#error_message').show();
              })
          </script>
      @endif

      @if (Route::current()->uri() == 'reservation/change')
        {!! Html::script('js/reservation.js') !!}
      @endif

      @if (Route::current()->uri() == 'wishlists/popular' || Route::current()->uri() == 'wishlists/my' || Route::current()->uri() == 'wishlists/{id}' || Route::current()->uri() == 'users/{id}/wishlists')
        {!! Html::script('js/wishlists.js') !!}
      @endif

      @if (Route::current()->uri() == 'inbox' || Route::current()->uri() == 'z/q/{id}' || Route::current()->uri() == 'messaging/qt_with/{id}')
        {!! Html::script('js/inbox.js') !!}
      @endif

      @if (Route::current()->uri() == 'reservation/{id}')
        {!! Html::script('js/reservation.js') !!}
      @endif

      @if (Route::current()->uri() == '/')
          {!! Html::script('js/home.js') !!}
      @endif

      @if (Route::current()->uri() == 'verification')
        {!! Html::script('js/mobile_verification.js') !!}
      @endif

    @endif

<script type="text/javascript">
    function initMap() {


    var myLatLng = {lat: lat, lng: lng};

        var map = new google.maps.Map(document.getElementById('map'), {
          center: myLatLng,
          zoom: 15
        });
        var card = document.getElementById('pac-card');
        var input = document.getElementById('pac-input');
        var types = document.getElementById('type-selector');
        var strictBounds = document.getElementById('strict-bounds-selector');

        map.controls[google.maps.ControlPosition.TOP_RIGHT].push(card);

        var autocomplete = new google.maps.places.Autocomplete(input);

        // Bind the map's bounds (viewport) property to the autocomplete object,
        // so that the autocomplete requests use the current map bounds for the
        // bounds option in the request.
        autocomplete.bindTo('bounds', map);

        var infowindow = new google.maps.InfoWindow();
        var infowindowContent = document.getElementById('infowindow-content');
        infowindow.setContent(infowindowContent);
        var marker = new google.maps.Marker({
          position: myLatLng,
          map: map
        });

        var latlng = new google.maps.LatLng(myLatLng);
        var sunCircle = {
                            strokeColor: "#c3fc49",
                            strokeOpacity: 0.8,
                            strokeWeight: 2,
                            fillColor: "#c3fc49",
                            fillOpacity: 0.35,
                            map: map,
                            center: latlng,
                            radius: 500 // in meters
                        };
        cityCircle = new google.maps.Circle(sunCircle)
        cityCircle.bindTo('center', marker, 'position');



        autocomplete.addListener('place_changed', function() {
          $('.location-panel-saving').show();
          infowindow.close();

          marker.setVisible(false);
          var place = autocomplete.getPlace();
          if (!place.geometry) {
            // User entered the name of a Place that was not suggested and
            // pressed the Enter key, or the Place Details request failed.
            window.alert("No details available for input: '" + place.name + "'");
            return;
          }

          // If the place has a geometry, then present it on a map.
          if (place.geometry.viewport) {
            map.fitBounds(place.geometry.viewport);
            map.setZoom(15);
          } else {
            map.setCenter(place.geometry.location);
            map.setZoom(15);  // Why 17? Because it looks good.
          }
          marker.setPosition(place.geometry.location);
          marker.setVisible(true);


          var latlng = new google.maps.LatLng(place.geometry.location);
          var sunCircle = {
                              strokeColor: "#c3fc49",
                              strokeOpacity: 0.8,
                              strokeWeight: 2,
                              fillColor: "#c3fc49",
                              fillOpacity: 0.35,
                              map: map,
                              center: latlng,
                              radius: 500 // in meters
                          };
          cityCircle = new google.maps.Circle(sunCircle)
          cityCircle.bindTo('center', marker, 'position');

          var address = '';
          if (place.address_components) {
            console.log(JSON.stringify(place.address_components));

            var results = get_address_components(place);
            if(results){
              var url = window.location.href.replace('location','update_locations');
              var data = JSON.stringify(results[0]);

              $.post(url,{ data:data }, function(response, status){
                var add_array = JSON.parse(data);
                  var add_html = '<span class="address-line">'+add_array.address_line_1+'</span>'
                        +'<span>'+add_array.city+' '+add_array.state+' '+add_array.postal_code+' '+add_array.country_full_name+'</span>';
                  $('#saved_address').html(add_html);
                  $('.location-panel-saving').hide();
                  $('#edit_address_field').hide();
                  $('#edited_address_field').show();

                  var track = 'location';//saving_class.substring(0, saving_class.length - 1);
                  $('[data-track="'+track+'"] a div div .transition').removeClass('visible');
                  $('[data-track="'+track+'"] a div div .transition').addClass('hide');
                  $('[data-track="'+track+'"] a div div .pull-right .nav-icon').removeClass('hide');
                  //alert("Data: " + data + "\nStatus: " + status);
              });
            }
            //alert(JSON.stringify(results));
            address = [
              (place.address_components[0] && place.address_components[0].short_name || ''),
              (place.address_components[1] && place.address_components[1].short_name || ''),
              (place.address_components[2] && place.address_components[2].short_name || '')
            ].join(' ');
          }

          infowindowContent.children['place-icon'].src = place.icon;
          infowindowContent.children['place-name'].textContent = place.name;
          infowindowContent.children['place-address'].textContent = address;
          //infowindow.open(map, marker);
        });

        // Sets a listener on a radio button to change the filter type on Places
        // Autocomplete.
        function setupClickListener(id, types) {
          var radioButton = document.getElementById(id);
          radioButton.addEventListener('click', function() {
            autocomplete.setTypes(types);
          });
        }



      }

function get_address_components(args){
  //alert(JSON.stringify(args.geometry.location))
  //alert(args.geometry.location)
        var address = [];
        var streetNumber = '';
        var route = '';
        var city = '';
        var state = '';
        var postalCode = '';
        var country = '';
        var country_full_name = '';

        for (var i = 0; i < args.address_components.length; i++) {
            var component = args.address_components[i];
            var addressType = component.types[0];

            switch (addressType) {
                case 'street_number':
                    streetNumber = component.long_name;
                    break;
                case 'route':
                    route = component.short_name;
                    break;
                case 'locality':
                    city = component.long_name;
                    break;
                case 'administrative_area_level_1':
                    state = component.long_name;
                    break;
                case 'postal_code':
                    postalCode = component.long_name;
                    break;
                case 'country':
                    country_full_name = component.long_name;
                    country = component.short_name;
                    break;
            }
        }
        var latitude = args.geometry.location.lat();
        var longitude = args.geometry.location.lng();

        address.push({'address_line_1': streetNumber+' '+route, 'street': streetNumber, 'city': city, 'state': state, 'postal_code': postalCode, 'country': country, 'latitude': latitude, 'longitude': longitude, 'country_full_name': country_full_name});
        return address;
        //alert('streetNumber: '+streetNumber+ ', route: '+route+', city: '+city+', state: '+state+', postalCode: '+postalCode+', country: '+country)
}

</script>
    @stack('scripts')

<!-- ver. 87c23752f8dfbd60bf83837d2c8b2dcd0ec660a9 -->
<div class="tooltip tooltip-bottom-middle" role="tooltip" aria-hidden="true">
	<p class="panel-body">To sign up, you must be 18 or older. Other people won’t see your birthday.</p>
	</div></body></html>