$('#input_dob').datepicker({ 'dateFormat': 'dd-mm-yy'});

var v = $("#add_room_form").validate({
    rules: {
        calendar: { required: true },
        bedrooms: { required: true },
        beds: { required: true },
        bed_type: { required: true },
        bathrooms: { required: true },
        property_type: { required: true },
        room_type: { required: true },
        accommodates: { required: true },
        name: { required: true },
        summary: { required: true },
        country: { required: true },
        address_line_1: { required: true },
        city: { required: true },
        state: { required: true },
        night: { required: true, digits: true },
        currency_code: { required: true },
        'photos[]': { required: true },
        cancel_policy: { required: true },
        user_id: { required: true },
    },
    errorElement: "span",
    errorClass: "text-danger",
});

$('.frm').hide();
$('.frm:first').show();

/*function step(step)
{
    $('.step-btn').removeClass('btn-active');
    $('#btn_'+step).addClass('btn-active');
    if(step == 4){
        initMap2($scope);
    }
    $(".frm").hide();
    $("#sf"+step).show();
}*/

function next(step)
{
    if(v.form())
    {
        if(step != 10)
        {
            $(".frm").hide();
            $("#sf"+(step+1)).show();
        }
        else
        {
            $('#add_room_form').submit();
        }
    }
}

function back(step)
{
    $(".frm").hide();
    $("#sf"+(step-1)).show();
}
function initMap22($scope) {
    console.log('map')

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
                var url = window.location.href.replace('admin/edit_room/','manage-listing/');
                        url = url+'/update_locations';
                var data = JSON.stringify(results[0]);

                $.post(url,{ data:data }, function(response, status){
                    var add_array = JSON.parse(data);
                    var add_html = '<span class="address-line">'+add_array.address_line_1+'</span>'
                        +'<span>'+add_array.city+' '+add_array.state+' '+add_array.postal_code+' '+add_array.country_full_name+'</span>';
                    $('#saved_address').html(add_html);
                    $('.location-panel-saving').hide();
                    $('#edit_address_field').hide();
                    $('#edited_address_field').show();

                    $scope.check_rooms_status();
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

app.controller('rooms_admin', ['$scope', '$http', '$compile', function($scope, $http, $compile) {

$scope.step = function(step)
{
    $('.step-btn').removeClass('btn-active');
    $('#btn_'+step).addClass('btn-active');
    if(step == 4){
        initMap2($scope);
    }
    if(step == 7){
        $scope.get_calendar(18);
    }
    $(".frm").hide();
    $("#sf"+step).show();
}

    initAutocomplete(); // Call Google Autocomplete Initialize Function

// Google Place Autocomplete Code

    var autocomplete;

    function initAutocomplete()
    {
        autocomplete = new google.maps.places.Autocomplete(document.getElementById('address_line_1'));
        autocomplete.addListener('place_changed', fillInAddress);
    }

    function fillInAddress()
    {
        fetchMapAddress(autocomplete.getPlace());
    }

    function fetchMapAddress(data)
    {
        var componentForm = {
            street_number: 'short_name',
            route: 'long_name',
            locality: 'long_name',
            administrative_area_level_1: 'long_name',
            country: 'short_name',
            postal_code: 'short_name'
        };

        $('#city').val('');
        $('#state').val('');
        $('#country').val('');
        $('#address_line_1').val('');
        $('#address_line_2').val('');
        $('#postal_code').val('');

        var place = data;

        for (var i = 0; i < place.address_components.length; i++)
        {
            var addressType = place.address_components[i].types[0];
            if (componentForm[addressType])
            {
                var val = place.address_components[i][componentForm[addressType]];

                if(addressType       == 'street_number')
                    $scope.street_number = val;
                if(addressType       == 'route')
                    $('#address_line_1').val(val);
                if(addressType       == 'postal_code')
                    $('#postal_code').val(val);
                if(addressType       == 'locality')
                    $('#city').val(val);
                if(addressType       == 'administrative_area_level_1')
                    $('#state').val(val);
                if(addressType       == 'country')
                    $('#country').val(val);
            }
        }

        var address   = $('#address_line_1').val();
        var latitude  = place.geometry.location.lat();
        var longitude = place.geometry.location.lng();

        if($('#address_line_1').val() == '')
            $('#address_line_1').val($('#city').val());

        if($('#city').val() == '')
            $('#city').val('');
        if($('#state').val() == '')
            $('#state').val('');
        if($('#postal_code').val() == '')
            $('#postal_code').val('');

        $('#latitude').val(latitude);
        $('#longitude').val(longitude);
    }

    $( "#username" ).autocomplete({
        source: APP_URL+'/admin/rooms/users_list',
        select: function(event, ui)
        {
            $('#user_id').val(ui.item.id);
        }
    });

    $(document).on('click', '.month-nav', function()
    {
        var month = $(this).attr('data-month');
        var year = $(this).attr('data-year');

        var data_params = {};

        data_params['month'] = month;
        data_params['year'] = year;

        var data = JSON.stringify(data_params);

    /*$('.ui-datepicker-backdrop').removeClass('hide');
     $('.spinner-next-to-month-nav').addClass('loading');*/

        $http.post(APP_URL+'/admin/ajax_calendar/'+$('#room_id').val(), { data:data }).then(function(response) {
            $( "#ajax_container" ).html( $compile(response.data)($scope) );
      /*$('.spinner-next-to-month-nav').removeClass('loading');
       $('.ui-datepicker-backdrop').addClass('hide');*/
        });
        return false;
    });

    $(document).on('change', '#calendar_dropdown', function()
    {
        var year_month = $(this).val();
        var year = year_month.split('-')[0];
        var month = year_month.split('-')[1];

        var data_params = {};

        data_params['month'] = month;
        data_params['year'] = year;

        var data = JSON.stringify(data_params);

        $http.post(APP_URL+'/admin/ajax_calendar/'+$('#room_id').val(), { data:data }).then(function(response) {
            $( "#ajax_container" ).html( $compile(response.data)($scope) );
        });
        return false;
    });

    $(document).on('click', '.delete-photo-btn', function()
    {
        var id = $(this).attr('data-photo-id');

        if($('[id^="photo_li_"]').size() > 1)
        {
            $http.post(APP_URL+'/admin/delete_photo', { photo_id : id }).then(function(response)
            {
                if(response.data.success == 'true')
                {
                    $('#photo_li_'+id).remove();
                }
            });
        }
        else
        {
            alert('You cannnot delete last photo. Please upload alternate photos and delete this photo.');
        }
    });

    $(document).on('keyup', '.highlights', function()
    {
        var value = $(this).val();
        var id = $(this).attr('data-photo-id');
        $('#saved_message').fadeIn();
        $http.post(APP_URL+'/admin/photo_highlights', { photo_id : id, data : value }).then(function(response)
        {
            $('#saved_message').fadeOut();
        });
    });



  /*Start - Calendar Date Selection*/

    $(document).on('click', '.available-date', function()
    {
        if($(this).hasClass('status-b')){
            $scope.segment_status = 'available';
        }else{
            $scope.segment_status = 'available';
        }

        if(!$(this).hasClass('other-day-selected') && !$(this).hasClass('selected') && !$(this).hasClass('tile-previous'))
        {

            var window_width = $(window).width();
            var sub = 141, top = 50;
            if(window_width > 768){
                sub = 141;
            }else if(window_width <= 768 && window_width > 360){
                sub = 117;
            }else{
                sub = 82;
                top = 32;
            }
            $(window).resize(function() {
                var window_width = $(window).width();
                var sub = 141, top = 50;
                if(window_width > 768){
                    sub = 141;
                }else if(window_width <= 768 && window_width > 360){
                    sub = 117;
                }else{
                    sub = 82;
                    top = 32;
                }
            });


            var current_tile = $(this).attr('id');
            $('#'+current_tile).addClass('first-day-selected last-day-selected selected');
            $('<div class="tile-selection-container"><div class="tile-selection"></div></div>').insertBefore('#'+current_tile+'> .date');

            var clicked_li = $(this).index();

            var start_top = $(this).position().top+top, start_left = $(this).position().left, end_top = start_top, end_left = start_left+sub;


            $('.days-container').append('<div><div style="left:'+start_left+'px;top:'+start_top+'px;" class="tile-selection-handle tile-handle-left"><div class="tile-selection-handle__inner"><span class="tile-selection-handle__ridge"></span><span class="tile-selection-handle__ridge"></span><span class="tile-selection-handle__ridge"></span></div></div></div><div><div style="left: '+end_left+'px; top: '+end_top+'px;" class="tile-selection-handle tile-handle-right"><div class="tile-selection-handle__inner"><span class="tile-selection-handle__ridge"></span><span class="tile-selection-handle__ridge"></span><span class="tile-selection-handle__ridge"></span></div></div></div>');

            $('.tile').each(function()
            {
                if(current_tile != $(this).attr('id'))
                    $(this).addClass('other-day-selected');
            });

            calendar_edit_form();
        }
    });

    var selected_li_status = 0;
    var direction = '';

    $(document).on('mousedown', '.tile-selection-handle', function()
    {
        selected_li_status = 1;
        if($(this).hasClass('tile-handle-left'))
            direction = 'left';
        else
            direction = 'right';
    });

    var oldx = 0;
    var oldy = 0;

    $(document).on('mouseover', '.tile', function(e)
    {
        if(e.pageX > oldx && direction == 'right')
        {
            if(selected_li_status == 1 && !$(this).hasClass('tile-previous'))
            {
                var id = $(this).attr('id');
                $('#'+id).removeClass('other-day-selected');
                $('#'+id).addClass('selected');

                if(!$('#'+$(this).attr('id')+' > div').hasClass('tile-selection-container'))
                {
                    $('<div class="tile-selection-container"><div class="tile-selection"></div></div>').insertBefore('#'+$(this).attr('id')+'> .date');
                }

                var last_index = $('.selected').last().index();
                var first_index = $('.selected').first().index();

                $('*').removeClass('first-day-selected last-day-selected');
                $('.selected').first().addClass('first-day-selected');
                $('.selected').last().addClass('last-day-selected');

                for(var i=(first_index+1); i<=last_index; i++)
                {
                    var id = $(".days-container > .list-unstyled > li:nth-child("+i+")").attr('id');
                    $('#'+id).addClass('selected');
                    $('#'+id).removeClass('other-day-selected');

                    if(!$('#'+id+' > div').hasClass('tile-selection-container'))
                    {
                        $('<div class="tile-selection-container"><div class="tile-selection"></div></div>').insertBefore('#'+id+'> .date');
                    }
                }
            }
        }
        else if(e.pageX < oldx && direction == 'right')
        {
            if(selected_li_status == 1 && !$(this).hasClass('tile-previous'))
            {
                var id = $(".days-container > .list-unstyled > li:nth-child("+$(this).index()+")").next().next().attr('id');

                var id2 = $(".days-container > .list-unstyled > li:nth-child("+$(this).index()+")").next().attr('id');

                $('*').removeClass('first-day-selected last-day-selected');
                $('.selected').first().addClass('first-day-selected');
                $('.selected').last().addClass('last-day-selected');

                $('#'+id).removeClass('selected');
                $('#'+id).addClass('other-day-selected');
                $(this).removeClass('selected');
                $(this).addClass('other-day-selected');
                $('#'+id2+' > div.tile-selection-container').remove();
            }

            if($('.selected').length == 0)
            {
                selected_li_status = 0;
                $('.tile').each(function()
                {
                    $(this).removeClass('other-day-selected last-day-selected first-day-selected');
                    $('.tile-selection-container').remove();
                    $('.tile-selection-handle').remove();
                });
            }
        }

        if(e.pageX > oldx && direction == 'left')
        {
            if(selected_li_status == 1 && !$(this).hasClass('tile-previous'))
            {
                var id = $(".days-container > .list-unstyled > li:nth-child("+$(this).index()+")").attr('id');

                var id2 = $(".days-container > .list-unstyled > li:nth-child("+$(this).index()+")").attr('id');

                $('*').removeClass('first-day-selected last-day-selected');
                $('.selected').first().addClass('first-day-selected');
                $('.selected').last().addClass('last-day-selected');

                $('#'+id).removeClass('selected');
                $('#'+id).addClass('other-day-selected');
                $(this).removeClass('selected');
                $(this).addClass('other-day-selected');
                $('#'+id2+' > div.tile-selection-container').remove();
            }
        }
        else if(e.pageX < oldx && direction == 'left')
        {
            if(selected_li_status == 1 && !$(this).hasClass('tile-previous'))
            {
                var id = $(".days-container > .list-unstyled > li:nth-child("+$(this).index()+")").next().next().attr('id');

                var id2 = $(".days-container > .list-unstyled > li:nth-child("+$(this).index()+")").next().attr('id');

                $('#'+id).addClass('selected');
                $('#'+id).removeClass('other-day-selected');
                $(this).addClass('selected');
                $(this).removeClass('other-day-selected');

                var last_index = $('.selected').last().index();
                var first_index = $('.selected').first().index();

                for(var i=(first_index+1); i<=last_index; i++)
                {
                    var id = $(".days-container > .list-unstyled > li:nth-child("+i+")").attr('id');
                    $('#'+id).addClass('selected');
                    $('#'+id).removeClass('other-day-selected');

                    if(!$('#'+id+' > div').hasClass('tile-selection-container'))
                    {
                        $('<div class="tile-selection-container"><div class="tile-selection"></div></div>').insertBefore('#'+id+'> .date');
                    }
                    $('#'+id).removeClass('first-day-selected last-day-selected');
                }

                $('*').removeClass('first-day-selected last-day-selected');
                $('.selected').first().addClass('first-day-selected');
                $('.selected').last().addClass('last-day-selected');

                if(!$('#'+id+' > div').hasClass('tile-selection-container'))
                {
                    $('<div class="tile-selection-container"><div class="tile-selection"></div></div>').insertBefore('#'+id+'> .date');
                }
            }

            if($('.selected').length == 0)
            {
                selected_li_status = 0;
                $('.tile').each(function()
                {
                    $(this).removeClass('other-day-selected last-day-selected first-day-selected');
                    $('.tile-selection-container').remove();
                    $('.tile-selection-handle').remove();
                });
            }
        }

        if(e.pageY > oldy && direction == 'right')
        {
            if(selected_li_status == 1 && !$(this).hasClass('tile-previous'))
            {
                var id = $(this).attr('id');
                $('#'+id).removeClass('other-day-selected');
                $('#'+id).addClass('selected');

                if(!$('#'+$(this).attr('id')+' > div').hasClass('tile-selection-container'))
                {
                    $('<div class="tile-selection-container"><div class="tile-selection"></div></div>').insertBefore('#'+$(this).attr('id')+'> .date');
                }

                var last_index = $('.selected').last().index();
                var first_index = $('.selected').first().index();

                $('*').removeClass('first-day-selected last-day-selected');
                $('.selected').first().addClass('first-day-selected');
                $('.selected').last().addClass('last-day-selected');

                for(var i=(first_index+1); i<=last_index; i++)
                {
                    var id = $(".days-container > .list-unstyled > li:nth-child("+i+")").attr('id');
                    $('#'+id).addClass('selected');
                    $('#'+id).removeClass('other-day-selected');

                    if(!$('#'+id+' > div').hasClass('tile-selection-container'))
                    {
                        $('<div class="tile-selection-container"><div class="tile-selection"></div></div>').insertBefore('#'+id+'> .date');
                    }
                }
            }
        }

        if(e.pageY < oldy && direction == 'right')
        {
            if(selected_li_status == 1 && !$(this).hasClass('tile-previous'))
            {
                if(!$(this).hasClass('selected'))
                {
                    var id = $(this).attr('id');
                    var last_index = $(this).index();
                    var first_index = $('.selected').first().index();

                    $('.selected').addClass('other-day-selected');
                    $('.selected').removeClass('selected');

                    for(var i=(first_index+1); i<=(last_index+1); i++)
                    {
                        var id = $(".days-container > .list-unstyled > li:nth-child("+i+")").attr('id');
                        $('#'+id).addClass('selected');
                        $('#'+id).removeClass('other-day-selected');

                        if(!$('#'+id+' > div').hasClass('tile-selection-container'))
                        {
                            $('<div class="tile-selection-container"><div class="tile-selection"></div></div>').insertBefore('#'+id+'> .date');
                        }
                    }
                    $('*').removeClass('first-day-selected last-day-selected');
                    $('.selected').first().addClass('first-day-selected');
                    $('.selected').last().addClass('last-day-selected');
                }
            }
        }

        if(e.pageY < oldy && direction == 'left')
        {
            if(selected_li_status == 1 && !$(this).hasClass('tile-previous'))
            {
                var id = $(this).attr('id');
                $('#'+id).removeClass('other-day-selected');
                $('#'+id).addClass('selected');

                if(!$('#'+$(this).attr('id')+' > div').hasClass('tile-selection-container'))
                {
                    $('<div class="tile-selection-container"><div class="tile-selection"></div></div>').insertBefore('#'+$(this).attr('id')+'> .date');
                }

                var last_index = $('.selected').last().index();
                var first_index = $('.selected').first().index();

                $('*').removeClass('first-day-selected last-day-selected');
                $('.selected').first().addClass('first-day-selected');
                $('.selected').last().addClass('last-day-selected');

                for(var i=(first_index+1); i<=last_index; i++)
                {
                    var id = $(".days-container > .list-unstyled > li:nth-child("+i+")").attr('id');
                    $('#'+id).addClass('selected');
                    $('#'+id).removeClass('other-day-selected');

                    if(!$('#'+id+' > div').hasClass('tile-selection-container'))
                    {
                        $('<div class="tile-selection-container"><div class="tile-selection"></div></div>').insertBefore('#'+id+'> .date');
                    }
                }
            }
        }

        if(e.pageY > oldy && direction == 'left')
        {
            if(selected_li_status == 1 && !$(this).hasClass('tile-previous'))
            {
                if(!$(this).hasClass('selected'))
                {
                    var id = $(this).attr('id');
                    var first_index = $(this).index();
                    var last_index = $('.selected').last().index();

                    $('.selected').addClass('other-day-selected');
                    $('.selected').removeClass('selected');

                    for(var i=(first_index+1); i<=(last_index+1); i++)
                    {
                        var id = $(".days-container > .list-unstyled > li:nth-child("+i+")").attr('id');
                        $('#'+id).addClass('selected');
                        $('#'+id).removeClass('other-day-selected');

                        if(!$('#'+id+' > div').hasClass('tile-selection-container'))
                        {
                            $('<div class="tile-selection-container"><div class="tile-selection"></div></div>').insertBefore('#'+id+'> .date');
                        }
                    }
                    $('*').removeClass('first-day-selected last-day-selected');
                    $('.selected').first().addClass('first-day-selected');
                    $('.selected').last().addClass('last-day-selected');
                }
            }
        }
        oldx = e.pageX;
        oldy = e.pageY;
    });

    $(document).on('mouseup', '.available-date', function()
    {
        $scope.segment_status = 'available';
        selected_li_status = 0;

        var last_id = $('.selected').last().attr('id');
        var first_id = $('.selected').first().attr('id');

        $('*').removeClass('first-day-selected last-day-selected');
        $('.selected').first().addClass('first-day-selected');
        $('.selected').last().addClass('last-day-selected');

        if(first_id != undefined){
            var position = $('#'+last_id).position();
            var first_position = $('#'+first_id).position();

            var window_width = $(window).width();
            var sub = 141, top = 50;
            if(window_width > 768){
                sub = 141;
            }else if(window_width <= 768 && window_width > 360){
                sub = 117;
            }else{
                sub = 82;
                top = 32;
            }
            $(window).resize(function() {
                var window_width = $(window).width();
                var sub = 141, top = 50;
                if(window_width > 768){
                    sub = 141;
                }else if(window_width <= 768 && window_width > 360){
                    sub = 117;
                }else{
                    sub = 82;
                    top = 32;
                }
            });

            var start_top = first_position.top+top, start_left = first_position.left, end_top = position.top+top, end_left = position.left+sub;

            $('.days-container > div > .tile-selection-handle:last').remove();
            $('.days-container > div > .tile-selection-handle:first').remove();

            $('.tile-handle-left').remove();
            $('.tile-handle-right').remove();
//alert(first_id+'----'+last_id)
            $('.days-container').append('<div><div style="left:'+start_left+'px;top:'+start_top+'px;" class="tile-selection-handle tile-handle-left"><div class="tile-selection-handle__inner"><span class="tile-selection-handle__ridge"></span><span class="tile-selection-handle__ridge"></span><span class="tile-selection-handle__ridge"></span></div></div></div><div><div style="left: '+end_left+'px; top: '+end_top+'px;" class="tile-selection-handle tile-handle-right"><div class="tile-selection-handle__inner"><span class="tile-selection-handle__ridge"></span><span class="tile-selection-handle__ridge"></span><span class="tile-selection-handle__ridge"></span></div></div></div>');
        }


        calendar_edit_form();
    });


    $(document).on('click', '.cal-close', function()
    {
        $('.calendar-sub-modal').attr('aria-hidden','true');
        $('.calendar-sub-modal').addClass('hide');
        $('#custom_calendar').hide();

    });

    function calendar_edit_form()
    {

//$('.calendar-edit-form').removeClass('hide');

        $('.calendar-sub-modal').attr('aria-hidden','false');
        $('.calendar-sub-modal').removeClass('hide');

        if($('.selected').length > 1)
        {
            $('.calendar-edit-form > form > .panel-body').first().show();
            $('.calendar-sub-modal').show();
            $('#custom_calendar').show();
        }
        else
        {
            $('.calendar-edit-form > form > .panel-body').first().show();
            $('.calendar-sub-modal').show();
            $('#custom_calendar').show();
        }
    /*if($scope.segment_status == 'available'){
     $('#available_label').addClass('segmented-control__option--selected');
     $('#not_available_label').removeClass('segmented-control__option--selected');
     }else{
     $('#available_label').removeClass('segmented-control__option--selected');
     $('#not_available_label').addClass('segmented-control__option--selected');
     }*/


    /*if($('.selected').hasClass('status-b'))
     $scope.segment_status = 'not available';
     else
     $scope.segment_status = 'available';*/

        var start_date = $('.first-day-selected').first().attr('id');
        var end_date = $('.last-day-selected').last().attr('id');
        console.log(end_date);
        $scope.calendar_edit_price = $('#'+start_date).find('.price > span:last').text();
        $('.sidebar-price').val($scope.calendar_edit_price);
        $scope.notes = $('#'+start_date).find('.tile-notes-text').text();
        $scope.isAddNote = ($scope.notes != '') ? true : false;

        $("#calendar-edit-start").datepicker({
            dateFormat: "mm-dd-yy",
            minDate: 0,
            onSelect: function (date)
            {
                var checkin = $("#calendar-edit-start").datepicker('getDate');
                var checkout = $("#calendar-edit-start").datepicker('getDate');
                $('#calendar-edit-end').datepicker('option', 'minDate', checkin);
                $('#calendar-edit-start').datepicker('option', 'maxDate', checkout);
                setTimeout(function(){
                    $('#calendar-edit-end').datepicker("show");
                },20);
            }
        });

        $('#calendar-edit-end').datepicker({
            dateFormat: "mm-dd-yy",
            minDate: 1,
            onClose: function ()
            {
                var checkin = $("#calendar-edit-start").datepicker('getDate');
                var checkout = $('#calendar-edit-end').datepicker('getDate');
                $('#calendar-edit-end').datepicker('option', 'minDate', checkin);
                $('#calendar-edit-start').datepicker('option', 'maxDate', checkout);
                if (checkout <= checkin)
                {
                    var minDate = $('#calendar-edit-end').datepicker('option', 'minDate');
                    $('#calendar-edit-end').datepicker('setDate', minDate);
                }
            }
        });
        console.log(start_date+'========='+end_date)


        $('#calendar-edit-start').datepicker('option', 'maxDate', change_format(start_date));
        $('#calendar-edit-end').datepicker('option', 'minDate', change_format(end_date));

        $('#calendar-edit-start').datepicker('setDate', change_format(start_date));
        $('#calendar-edit-end').datepicker('setDate', change_format(end_date));
    }

    function change_format(date)
    {
        if(date != undefined){
            var split_date = date.split('-');
            return split_date[1]+'-'+split_date[2]+'-'+split_date[0];
        }

    }

    $scope.calendar_edit_submit = function(href)
    {
        var url = window.location.href.replace('admin/edit_room/','manage-listing/');
        url = url+'/calendar_edit';

        $http.post(url, { status: $scope.segment_status, start_date: $('#calendar-edit-start').val(), end_date: $('#calendar-edit-end').val(), price: $scope.calendar_edit_price, notes: $scope.notes }).then(function(response)
        {

            $('.calendar-sub-modal').attr('aria-hidden','true');
            $('.calendar-sub-modal').addClass('hide');
            $('li.tile').removeClass('other-day-selected first-day-selected last-day-selected selected');
            $('.tile-selection-handle').remove();
            $('.tile-selection-container').remove();

            $('.my-calendar').html('<h3 class="text-center" style="margin-top: 30px">Loading...</h3>');
            $('.load_show').removeClass('hide').hide();

            $('#custom_calendar').hide();
            $scope.get_calendar(18);

            $scope.check_rooms_status();

            return false;
        });
    };

    $scope.get_calendar = function(months)
    {

        var url = window.location.href.replace('admin/edit_room/','manage-listing/');
        url = url+'/get-calendar';

        $http.post(url, { months: months }).then(function(response)
        {
            $('.my-calendar').html($compile(response.data)($scope) );

            //if($scope.calendar_lengths == 0){
                var data_params = {};

                data_params['calendar_length'] = 18;
                /*var data = JSON.stringify(data_params);
                $http.post('update_rooms', { data:data }).then(function(response)
                {

                });*/
                $scope.check_rooms_status();
            //}


        });
    };

  /*End - Calendar Date Selection*/

    $(document).on('change', '#availability-dropdown > div > select', function()
    {
        var data_params = {};

        data_params['status'] = $(this).val();

        var data = JSON.stringify(data_params);

        $http.post('update_rooms', { data:data }).then(function(response)
        {
            if(data_params['status'] == 'Unlisted')
            {
                $('#availability-dropdown > i').addClass('dot-danger');
                $('#availability-dropdown > i').removeClass('dot-success');
            }
            else if(data_params['status'] == 'Listed')
            {
                $('#availability-dropdown > i').removeClass('dot-danger');
                $('#availability-dropdown > i').addClass('dot-success');
            }
        });
    });
    $(document).on('click', '#calendar_edit_cancel', function(event)
    {
        event.preventDefault();
        $('.calendar-sub-modal').attr('aria-hidden','true');
        $('.calendar-sub-modal').addClass('hide');
        $('li.tile').removeClass('other-day-selected first-day-selected last-day-selected selected');
        $('.tile-selection-handle').remove();
        $('.tile-selection-container').remove();
        $('#custom_calendar').hide();
    });



    /***start The Space section***/
    $(document).on('change', '[id^="basics-select-"], [id^="select-"]', function()
    {
        var data_params = {};

        data_params[$(this).attr('name')] = $(this).val();
        if($(this).attr('name') == 'bathrooms_details' || $(this).attr('name') == 'bathrooms_type'){
            data_params['data_id'] = $(this).attr('data-id');
        }
        var data = JSON.stringify(data_params);

        var saving_class = $(this).attr('data-saving');

        $('.'+saving_class+' h5').text('Saving...');
        $('.'+saving_class).fadeIn();
        var select_type = $(this).attr('name');
        if($(this).attr('name') == 'bathrooms_details' || $(this).attr('name') == 'bathrooms_type'){
            var url = window.location.href.replace('admin/edit_room/','manage-listing/');
            url = url+'/update_rooms_bathroom';

            $http.post(url, { data:data }).then(function(response) {
//get_bedroom_details();
                if(response.data.success == 'true')
                {
                    $('.'+saving_class+' h5').text('Saved!');
                    $('.'+saving_class).fadeOut();
                    $scope.check_rooms_status();
                }
            });
        }else{
            var url = window.location.href.replace('admin/edit_room/','manage-listing/');
            url = url+'/update_rooms';

            $http.post(url, { data:data }).then(function(response)
            {
                if(response.data.success == 'true')
                {
                    $('.'+saving_class+' h5').text('Saved!');
                    $('.'+saving_class).fadeOut();
                    $scope.check_rooms_status();

                }

                if(select_type == 'bedrooms'){
                    $("#bedroom_parent").empty();
                    var total_bedrooms = response.data.total_bedrooms;
                    for( var i = 1 ; i <= total_bedrooms.length ; i++ ) {

                        var bed_option = '';
                        if(total_bedrooms[i-1].bed_options != null){
                            bed_option = total_bedrooms[i-1].bed_options;
                        }
                        var html = '<div class = "row bedroom_child" id = "bedroom_child">'
                            + '<div class="col-3">'
                            + '<label class="label-large" id = "bedroom_child_label">Bedroom ' + i + '</label>'
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
                                + '<a id = "bedroom_child_add_beds" class ="a_text bedroom_child_add_beds">Add Beds<span style="display:none" class="data_index" data_index=' + i + ' data_id=' + total_bedrooms[i-1].bedroom_id + ' ></span>\n\
<span style="display:none" class="bedroom_type" bedroom_type=' + "Bedroom" + '></span></a>'
                                + '</div>';
                        }


                        html += '</div>';

                        $('#bedroom_parent').append(html);
                    }
                }

                if(select_type == 'bathrooms'){
                    $("#bathroom_parent").empty();
                    var total_bathrooms = response.data.total_bathrooms;
                    var html1 = '<div class="row">'
                        +'<div class="col-4"></div>'
                        +'<div class="col-4"><label class="label-large">Bathroom Details</label></div>'
                        +'<div class="col-4"><label class="label-large">Type</label></div>'
                        +'</div>';
                    $('#bathroom_parent').append(html1);
                    for( var i = 1 ; i <= total_bathrooms.length ; i++ ) {


                        var html = '<div class="row row-space-top-2 bathroom_child" id="bathroom_child">'
                            +'<div class="col-4"><label class="label-large" id="bathroom_child_label">Bathroom '+i+'</label></div>'
                            +'<div class="col-md-4 col-sm-12 "><div class="select select-block">'
                            +'<select name="bathrooms_details" id="basics-select-bathrooms-details-'+i+'" data-id="'+total_bathrooms[i-1].id+'" data-saving="basics3"><option disabled="" selected="" value="">Select...</option><option value="private">Private Bathroom</option><option value="shared">Shared Bathroom</option></select>'
                            +'</div></div>'
                            +'<div class="col-md-4 col-sm-12 "><div class="select select-block">'
                            +'<select name="bathrooms_type" id="basics-select-bathrooms-type-'+i+'" data-id="'+total_bathrooms[i-1].id+'" data-saving="basics3"><option disabled="" selected="" value="">Select...</option><option value="toilet_shower">Toilet + Shower</option><option value="toilet_only">Toilet Only</option><option value="shower_only">Shower Only</option></select>'
                            +'</div></div>'
                            +'</div>';


                        $('#bathroom_parent').append(html);
                        $('#basics-select-bathrooms-details-'+i).val(total_bathrooms[i-1].bathroom_details);
                        $('#basics-select-bathrooms-type-'+i).val(total_bathrooms[i-1].type);
                    }
                }



            });
        }

    });


    $(document).on('click', '.bedroom_child_add_beds, .bedroom_child_modify', function()
    {
//alert("WWSSS:" + $(this).find('.data_index', 0).attr('data_index'));
//alert("WWSSS:" + $(this).find('.bedroom_type', 0).attr('bedroom_type'));
//    return;
        var data_params = {};

        data_params['data_id'] = $(this).find('.data_index', 0).attr('data_id');
        data_params['data_index'] = $(this).find('.data_index', 0).attr('data_index');
        data_params['bedroom_type'] = $(this).find('.bedroom_type', 0).attr('bedroom_type');

        var data = JSON.stringify(data_params);

        $('#js-bedroom-container').addClass('enter_bedroom');
        $('#bedroom-flow-view .modal').fadeIn();
        $('#bedroom-flow-view .modal').attr('aria-hidden','false');

        var url = window.location.href.replace('admin/edit_room/','enter_bedroom/');
        url = url+'/basics';

        $http.post(url, { data:data }).then(function(response)
        {
            $( "#js-bedroom-container" ).html( $compile(response.data)($scope) );
            initAutocomplete();
            $scope.check_rooms_status();
        });

    });

    $(document).on('click', '#js-save-btn', function()
    {
//alert("save_button_clicked");///+++working
        var saving_class = 'basics1';

        $('.'+saving_class+' h5').text('Saving...');
        $('.'+saving_class).fadeIn();


        var data_params = {};
        $('form#js-bedroom-fields-form').find('input, textarea, select').each(function(i, field) {
            data_params[field.name] = field.value;
        });
        var data = JSON.stringify(data_params);
        console.log(JSON.stringify(data_params));
        var url = window.location.href.replace('admin/edit_room/','enter_bed_option/');
        url = url+'/basics';

        $http.post(url, { data:data }).then(function(response) {
            get_bedroom_details();
            $('.'+saving_class+' h5').text('Saved!');
            $('.'+saving_class).fadeOut();
            $scope.check_rooms_status();

        });
        $(".bedroom_child:nth-child(3) .bedroom_child_content_label").html("king_text");

        $('#bedroom-flow-view .modal').fadeOut();
        $('#bedroom-flow-view .modal').attr('aria-hidden','true');
    });

    $(document).on('click', '.modal-close, [data-behavior="modal-close"], .panel-close', function()
    {
        $('.modal').fadeOut();
        $('.tooltip').css('opacity','0');
        $('.tooltip').attr('aria-hidden','true');
        $('.modal').attr('aria-hidden','true');
    });

    $(document).on('blur', '[id^="help-panel"] > textarea', function()
    {
        var data_params = {};

        var input_name = $(this).attr('name');

        data_params[input_name] = $(this).val();

        var data = JSON.stringify(data_params);

        $('.saving-progress h5').text('Saving...');

        if(input_name != 'neighborhood_overview' && input_name != 'transit')
            $('.help-panel-saving').fadeIn();
        else
            $('.help-panel-neigh-saving').fadeIn();

        var url = window.location.href.replace('admin/edit_room/','manage-listing/');
        url = url+'/update_description';

        $http.post(url, { data:data }).then(function(response)
        {
            if(response.data.success == 'true')
            {
                $scope.steps_count = response.data.steps_count;
                $('.saving-progress h5').text('Saved!');

                if(input_name != 'neighborhood_overview' && input_name != 'transit')
                    $('.help-panel-saving').fadeOut();
                else
                    $('.help-panel-neigh-saving').fadeOut();
                $scope.check_rooms_status();
            }
            if($scope.direction_helpful_tips != "" || $scope.overview != "" || $scope.getting_arround != "" || $scope.local_jewish_life != "" || $scope.location != 0)
            {
                $('[data-track="details"] a div div .transition').removeClass('visible');
                $('[data-track="details"] a div div .transition').addClass('hide');
                $('[data-track="details"] a div div div .icon-ok-alt').removeClass('hide');
            }
            else
            {
                $('[data-track="details"] a div div .transition').removeClass('hide');
                $('[data-track="details"] a div div .transition .icon').removeClass('hide');
                $('[data-track="details"] a div div div .icon-ok-alt').addClass('hide');
                $('[data-track="details"] a div div div .icon-ok-alt').removeClass('visible');
            }
        });
    });

    $(document).on('click', '[id^="help-panel"] > input[type="checkbox"]', function()
    {
        var data_params = {};

        var input_name = $(this).attr('name');

        if($(this).is(':checked'))
        {
            data_params[input_name] = 1;
            $('[data-track="details"] a div div div .icon-ok-alt').removeClass('hide');
        }
        else
        {
            data_params[input_name] = 0;
            $('[data-track="details"] a div div div .icon-ok-alt').addClass('hide');
        }

        var data = JSON.stringify(data_params);

        $('.saving-progress h5').text('Saving...');

        if(input_name != 'neighborhood_overview' && input_name != 'transit')
            $('.help-panel-saving').fadeIn();
        else
            $('.help-panel-neigh-saving').fadeIn();

        var url = window.location.href.replace('admin/edit_room/','manage-listing/');
        url = url+'/update_description';

        $http.post(url, { data:data }).then(function(response)
        {
            if(response.data.success == 'true')
            {
                $scope.steps_count = response.data.steps_count;
                $('.saving-progress h5').text('Saved!');

                if(input_name != 'neighborhood_overview' && input_name != 'transit')
                    $('.help-panel-saving').fadeOut();
                else
                    $('.help-panel-neigh-saving').fadeOut();

                $scope.check_rooms_status();
            }
        });
    });


    $(document).on('blur', '[class^="overview-"]', function()
    {
        var data_params = {};

        if($(this).attr('name') == 'koshire')
        {
            if($(this).is(":checked"))
                data_params[$(this).attr('name')] = 1;
            else
                data_params[$(this).attr('name')] = 0;
        }
        else
            data_params[$(this).attr('name')] = $(this).val();




        var data = JSON.stringify(data_params);

        if($(this).val() != '')
        {
            $('.saving-progress h5').text('Saving...');
            $('.saving-progress').fadeIn();

            $('.name_required_msg').addClass('hide');
            $('.summary_required_msg').addClass('hide');
            $('.name_required').removeClass('invalid');
            $('.summary_required').removeClass('invalid');

            var url = window.location.href.replace('admin/edit_room/','manage-listing/');
            url = url+'/update_rooms';

            $http.post(url, { data:data }).then(function(response)
            {
                if(response.data.success == 'true')
                {
                    $('.saving-progress h5').text('Saved!');
                    $('.saving-progress').fadeOut();

                    $scope.check_rooms_status();
                }
                
            });
        }
        else
        {
      /*if($(this).attr('name') == 'name')
       {
       $('.name_required').addClass('invalid');
       $('.name_required_msg').removeClass('hide');
       }
       else
       {
       $('.summary_required').addClass('invalid');
       $('.summary_required_msg').removeClass('hide');
       }*/
            $('[data-track="description"] a div div .transition').removeClass('hide');
            $('[data-track="description"] a div div .transition .icon').removeClass('hide');
            $('[data-track="description"] a div div div .icon-ok-alt').addClass('hide');
            $('[data-track="description"] a div div div .icon-ok-alt').removeClass('visible');
        }
    });
    /****end The Space section***/

    /***start Photo section********/
    $scope.photos_lists = function() {
        var url = window.location.href.replace('admin/edit_room/','manage-listing/');
        url = url+'/photos_list';

        $http.get(url, { }).then(function(response) {
            $scope.photos_list = response.data;
            if(response.data.length > 0)
            {
                $('#photo_count').css('display','block');
            }
        });
    }

  /* ajaxfileupload */
    jQuery.extend({ handleError: function( s, xhr, status, e ) {if ( s.error ) s.error( xhr, status, e ); else if(xhr.responseText) console.log(xhr.responseText); } });
    jQuery.extend({createUploadIframe:function(e,t){var r="jUploadFrame"+e;if(window.ActiveXObject){var n=document.createElement("iframe");n.id=n.name=r,"boolean"==typeof t?n.src="javascript:false":"string"==typeof t&&(n.src=t)}else{var n=document.createElement("iframe");n.id=r,n.name=r}return n.style.position="absolute",n.style.top="-1000px",n.style.left="-1000px",document.body.appendChild(n),n},createUploadForm:function(e,t){var r="jUploadForm"+e,n="jUploadFile"+e,o=jQuery('<form  action="" method="POST" name="'+r+'" id="'+r+'" enctype="multipart/form-data"></form>'),a=jQuery("#"+t),u=jQuery(a).clone();return jQuery(a).attr("id",n),jQuery(a).before(u),jQuery(a).appendTo(o),jQuery(o).css("position","absolute"),jQuery(o).css("top","-1200px"),jQuery(o).css("left","-1200px"),jQuery(o).appendTo("body"),o},ajaxFileUpload:function(e){e=jQuery.extend({},jQuery.ajaxSettings,e);var t=(new Date).getTime(),r=jQuery.createUploadForm(t,e.fileElementId),n=(jQuery.createUploadIframe(t,e.secureuri),"jUploadFrame"+t),o="jUploadForm"+t;e.global&&!jQuery.active++&&jQuery.event.trigger("ajaxStart");var a=!1,u={};e.global&&jQuery.event.trigger("ajaxSend",[u,e]);var c=function(t){var o=document.getElementById(n);try{o.contentWindow?(u.responseText=o.contentWindow.document.body?o.contentWindow.document.body.innerHTML:null,u.responseXML=o.contentWindow.document.XMLDocument?o.contentWindow.document.XMLDocument:o.contentWindow.document):o.contentDocument&&(u.responseText=o.contentDocument.document.body?o.contentDocument.document.body.innerHTML:null,u.responseXML=o.contentDocument.document.XMLDocument?o.contentDocument.document.XMLDocument:o.contentDocument.document)}catch(c){jQuery.handleError(e,u,null,c)}if(u||"timeout"==t){a=!0;var d;try{if(d="timeout"!=t?"success":"error","error"!=d){var l=jQuery.uploadHttpData(u,e.dataType);e.success&&e.success(l,d),e.global&&jQuery.event.trigger("ajaxSuccess",[u,e])}else jQuery.handleError(e,u,d)}catch(c){d="error",jQuery.handleError(e,u,d,c)}e.global&&jQuery.event.trigger("ajaxComplete",[u,e]),e.global&&!--jQuery.active&&jQuery.event.trigger("ajaxStop"),e.complete&&e.complete(u,d),jQuery(o).unbind(),setTimeout(function(){try{jQuery(o).remove(),jQuery(r).remove()}catch(t){jQuery.handleError(e,u,null,t)}},100),u=null}};e.timeout>0&&setTimeout(function(){a||c("timeout")},e.timeout);try{var r=jQuery("#"+o);jQuery(r).attr("action",e.url),jQuery(r).attr("method","POST"),jQuery(r).attr("target",n),r.encoding?r.encoding="multipart/form-data":r.enctype="multipart/form-data",jQuery(r).submit()}catch(d){jQuery.handleError(e,u,null,d)}return window.attachEvent?document.getElementById(n).attachEvent("onload",c):document.getElementById(n).addEventListener("load",c,!1),{abort:function(){}}},uploadHttpData:function(r,type){var data=!type;return data="xml"==type||data?r.responseXML:r.responseText,"script"==type&&jQuery.globalEval(data),"json"==type&&eval("data = "+data),"html"==type&&jQuery("<div>").html(data).evalScripts(),data}});


    $(document).on('click', '#js-photo-grid-placeholder', function()
    {

        $('#upload_photos2').trigger('click');
    });


  /*function upload2()
   {*/
    $(document).on("change", '#upload_photos2', function() {
        var saving_class = 'basics1';

        $('.'+saving_class+' h5').text('Saving...');
        $('.'+saving_class).fadeIn();


        jQuery.ajaxFileUpload({
            url: "../../add_photos/"+$('#room_id').val(),
            secureuri: false,
            fileElementId: "upload_photos2",
            dataType: "json",
            async: false,
            success: function(response){

                $('.'+saving_class+' h5').text('Saved!');
                $('.'+saving_class).fadeOut();
                $scope.check_rooms_status();
                if(response.error_title)
                {
                    $('#js-error .panel-header').text(response.error_title);
                    $('#js-error .panel-body').text(response.error_description);
                    $('.js-delete-photo-confirm').addClass('hide');
                    $('#js-error').attr('aria-hidden',false);
                }
                else
                {
                    $scope.$apply(function()
                    {
                        $scope.photos_list = response;
                        $('#photo_count').css('display','block');
                        $('#steps_count').text(response[0].steps_completed);
                        $scope.steps_count = response[0].steps_completed;
                    });

                    $('#upload_photos2').reset();
                }
                //upload2();
            }
        });
    });
//}

    $scope.keyup_highlights = function(id, value)
    {
        var url = window.location.href.replace('admin/edit_room/','manage-listing/');
        url = url+'/photo_highlights';

        $http.post(url, { photo_id : id, data : value }).then(function(response) {

        });
    };

    $scope.delete_photo = function(item, id)
    {
        $('#js-error .panel-header').text("Delete Photo");
        $('#js-error .panel-body').text("Are you sure you wish to delete this photo?");
        $('.js-delete-photo-confirm').removeClass('hide');
        $('#js-error').attr('aria-hidden',false);
        $('.js-delete-photo-confirm').attr('data-id',id);
        var index=$scope.photos_list.indexOf(item);
        $('.js-delete-photo-confirm').attr('data-index',index);
    };

    /*****end photo section*****/


    /*******Start Locatin Section*******/
    function initMap2($scope) {
        console.log('map')

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
                    var url = window.location.href.replace('admin/edit_room/','manage-listing/');
                        url = url+'/update_locations';

                    var data = JSON.stringify(results[0]);

                    $.post(url,{ data:data }, function(response, status){
                        var add_array = JSON.parse(data);
                        var add_html = '<span class="address-line">'+add_array.address_line_1+'</span>'
                            +'<span>'+add_array.city+' '+add_array.state+' '+add_array.postal_code+' '+add_array.country_full_name+'</span>';
                        $('#saved_address').html(add_html);
                        $('.location-panel-saving').hide();
                        $('#edit_address_field').hide();
                        $('#edited_address_field').show();

                        $scope.check_rooms_status();
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

    $(document).on('click', '#js-add-address, #js-edit-address', function()
    {
        $('#edit_address_field').show();
        $('#edited_address_field').hide();
    });

    /******End Location Section********/

    /******Start Amenities section ******/

    $(document).on('change', '[name="amenities"], [name="religious_amenities"], [id^="religious_amenities_extra_data_"]', function()
    {
        var value = '';
        var religious_value = '';
        var religious_extra_value = {};
        if($(this).val() == 0){
            $('[name="amenities"]').prop('checked', false);
            $('[name="religious_amenities"]').prop('checked', false);
            $('.religious_amenity_extra_block').addClass('hide');
            $(this).prop('checked', true);
        }else{
            $('[name="amenities"][value="0"]').prop('checked', false);
        }
        if($(this).attr('data-extra') == 'Yes '){
            if($(this).prop('checked')){
                $(this).parent().parent().find('#religious_amenity_extra_'+$(this).val()).removeClass('hide');
            }else{
                $(this).parent().parent().find('#religious_amenity_extra_'+$(this).val()).addClass('hide');
            }
        }
        $('[name="amenities"]').each(function()
        {
            if ($(this).prop('checked')==true)
            {
                value = value+$(this).val()+',';
            }
        });

        $('[name="religious_amenities"]').each(function()
        {
            if ($(this).prop('checked')==true)
            {
                religious_value = religious_value+$(this).val()+',';
            }
        });

        $('[id^="religious_amenities_extra_data_"]').each(function()
        {
            religious_extra_value[$(this).attr('name')] = $(this).val();
        });

        var saving_class = $(this).attr('data-saving');

        $('.'+saving_class+' h5').text('Saving...');
        $('.'+saving_class).fadeIn();

        var url = window.location.href.replace('admin/edit_room/','manage-listing/');
        url = url+'/update_amenities';

        $http.post(url, { data : value, religious_data : religious_value, religious_extra_data : JSON.stringify(religious_extra_value)}).then(function(response)
        {
            if(response.data.success == 'true')
            {
                $('.'+saving_class+' h5').text('Saved!');
                $('.'+saving_class).fadeOut();

                $scope.check_rooms_status();

            }
        });
        $scope.amenities_status = $('[name="amenities"]:checked').length-0+$('[name="religious_amenities"]:checked').length;
        if($scope.amenities_status != 0 && $scope.amenities_status != null && $scope.amenities_status != ''  && $scope.amenities_status != '0')
        {
            $('[data-track="amenities"] a div div .transition').removeClass('visible');
            $('[data-track="amenities"] a div div .transition').addClass('hide');
            $('[data-track="amenities"] a div div div .icon-ok-alt').removeClass('hide');
        }
        else
        {
            $('[data-track="amenities"] a div div .transition').removeClass('hide');
            $('[data-track="amenities"] a div div div .icon-ok-alt').addClass('hide');
        }
    });

    /******End Amenities section ******/

    /******Start Pricing section ******/
    $(document).on('change', '[id^="price-select-"]', function()
    {
        var data_params = {};

        data_params[$(this).attr('name')] = $(this).val();
        data_params['night'] = $('#price-night').val();

        var data = JSON.stringify(data_params);

        var saving_class = $(this).attr('data-saving');

        $('.'+saving_class+' h5').text('Saving...');
        $('.'+saving_class).fadeIn();

        var url = window.location.href.replace('admin/edit_room/','manage-listing/');
        url = url+'/update_price';

        $http.post(url, { data:data }).then(function(response)
        {
            if(response.data.success == 'true')
            {
                $('[data-error="price"]').text('');
                $('.input-prefix').html(response.data.currency_symbol);
                $('.'+saving_class+' h5').text('Saved!');
                $('.'+saving_class).fadeOut();

                $scope.check_rooms_status();

                //$('html, body').animate({ scrollTop: $('#js-list-space-button').offset().top+1000 }, 'slow');
            }
            else
            {
                $('[data-error="price"]').html(response.data.msg);
                $('.'+saving_class).fadeOut();
            }
        });
    });
// $(document).on('keypress', '.autosubmit-text[id^="price-"]', function()
// {
//  setTimeout(function(){
//   $('.autosubmit-text[id^="price-"]').trigger('blur');
// },300);
// });
    $(document).on('blur', '.autosubmit-text[id^="price-"]', function()
    {

        var data_params = {};

        data_params[$(this).attr('name')] = $(this).val();

        data_params['currency_code'] = $('#price-select-currency_code').val();

        var data = JSON.stringify(data_params);

        var saving_class = $(this).attr('data-saving');

        $('.'+saving_class+' h5').text('Saving...');

        if($('#price-night').val() != 0)
        {
            $('.'+saving_class).fadeIn();
            var url = window.location.href.replace('admin/edit_room/','manage-listing/');
            url = url+'/update_price';

            $http.post(url, { data:data }).then(function(response)
            {
                if(response.data.success == 'true')
                {
                    $('[data-error="price"]').text('');
                    $('.input-prefix').html(response.data.currency_symbol);
                    $('.'+saving_class+' h5').text('Saved!');
                    $('.'+saving_class).fadeOut();

                    $scope.check_rooms_status();

                }
                else
                {
                    $('[data-error="price"]').html(response.data.msg);
                    $('.'+saving_class).fadeOut();
                }


            });
        }



    });

    $(document).on('change', '[id$="_checkbox"]', function()
    {
        var input_name = $(this).attr('name');
        if(($(this).prop('checked') == false) && (input_name != 'thursday') && (input_name != 'friday') && (input_name != 'saturday') )
        {
            var data_params = {};

            var id = $(this).attr('id');
            var selector = '[data-checkbox-id="'+id+'"] > div > div > div > input';

            $(selector).val('');

            if(id == 'price_for_extra_person_checkbox')
            {
                $('[data-checkbox-id="'+id+'"] > div > div > #guests-included-select > div > select').val(1);

                data_params[$('[data-checkbox-id="'+id+'"] > div > div > #guests-included-select > div > select').attr('name')] = 1;
            }
            if($(selector).attr('name') == 'weekend'){
                $('.weekend_check').prop('checked', false); // Unchecks it
            }
            data_params[$(selector).attr('name')] = $(selector).val();

            var data = JSON.stringify(data_params);

            var saving_class = $(selector).attr('data-saving');

            $('.'+saving_class+' h5').text('Saving...');
            $('.'+saving_class).fadeIn();

            var url = window.location.href.replace('admin/edit_room/','manage-listing/');
            url = url+'/update_price';

            $http.post(url, { data:data }).then(function(response)
            {
                if(response.data.success == 'true')
                {
                    $('.input-prefix').html(response.data.currency_symbol);
                    $('.'+saving_class+' h5').text('Saved!');
                    $('.'+saving_class).fadeOut();

                    $scope.check_rooms_status();
                    //$('html, body').animate({ scrollTop: $('#js-list-space-button').offset().top+1000 }, 'slow');
                }
            });
        }

        if(input_name == 'thursday' || input_name == 'friday' || input_name == 'saturday'){
            if($(this).prop('checked') == false){
                var value = 'No';
            }else{
                var value = 'Yes';
            }
            var id = $(this).attr('id');

            var data_params = {};
            data_params[input_name] = value;
            var data = JSON.stringify(data_params);
            var saving_class = $(this).attr('data-saving');

            $('.'+saving_class+' h5').text('Saving...');
            $('.'+saving_class).fadeIn();

            var url = window.location.href.replace('admin/edit_room/','manage-listing/');
            url = url+'/update_price';

            $http.post(url, { data:data }).then(function(response)
            {
                if(response.data.success == 'true')
                {
                    $('.input-prefix').html(response.data.currency_symbol);
                    $('.'+saving_class+' h5').text('Saved!');
                    $('.'+saving_class).fadeOut();

                    $scope.check_rooms_status();
                    //$('html, body').animate({ scrollTop: $('#js-list-space-button').offset().top+1000 }, 'slow');
                }
            });
        }
    });

    /******End Pricing section ******/

    /******Start Policies section ******/

    $(document).on('change', '[id^="terms-select-"], [id^="select-"], [name="cancel_policy"]', function()
    {
        var data_params = {};

        data_params[$(this).attr('name')] = $(this).val();

        var data = JSON.stringify(data_params);

        var saving_class = $(this).attr('data-saving');

        $('.'+saving_class+' h5').text('Saving...');
        $('.'+saving_class).fadeIn();
        var select_type = $(this).attr('name');

        var url = window.location.href.replace('admin/edit_room/','manage-listing/');
        url = url+'/update_rooms_policies';

        $http.post(url, { data:data }).then(function(response) {

            if(response.data.success == 'true'){
                $('.'+saving_class+' h5').text('Saved!');
                $('.'+saving_class).fadeOut();

                $scope.check_rooms_status();
            }

        });


        if($(this).attr('name') == 'beds')
        {
            if($(this).val() != '')
                $('#beds_show').show();
        }

    });

    $(document).on('change', '[id$="_termscheckbox"]', function()
    {
        var data_params = {};

        var input_name = $(this).attr('name');

        if($(this).is(':checked'))
        {
            data_params[input_name] = 'Yes';
            $('[data-track="details"] a div div div .icon-ok-alt').removeClass('hide');
        }
        else
        {
            data_params[input_name] = 'No';
            $('[data-track="details"] a div div div .icon-ok-alt').addClass('hide');
        }

        var data = JSON.stringify(data_params);

        var saving_class = $(this).attr('data-saving');

        $('.'+saving_class+' h5').text('Saving...');
        $('.'+saving_class).fadeIn();

        var url = window.location.href.replace('admin/edit_room/','manage-listing/');
        url = url+'/update_rooms_policies';

        $http.post(url, { data:data }).then(function(response)
        {
            if(response.data.success == 'true')
            {
                $('.'+saving_class+' h5').text('Saved!');
                $('.'+saving_class).fadeOut();

                $scope.check_rooms_status();
            }
        });
    });

    $(document).on('blur', '[id^="terms-panel"] > textarea', function()
    {
        var data_params = {};

        var input_name = $(this).attr('name');

        data_params[input_name] = $(this).val();

        var data = JSON.stringify(data_params);

        var saving_class = $(this).attr('data-saving');

        $('.'+saving_class+' h5').text('Saving...');
        $('.'+saving_class).fadeIn();

        var url = window.location.href.replace('admin/edit_room/','manage-listing/');
        url = url+'/update_rooms_policies';

        $http.post(url, { data:data }).then(function(response)
        {
            if(response.data.success == 'true')
            {
                $('.'+saving_class+' h5').text('Saved!');
                $('.'+saving_class).fadeOut();

                $scope.check_rooms_status();
            }
        });
    });

    /******End Policies section ******/

    $scope.check_rooms_status = function() {
        var url = window.location.href.replace('admin/edit_room/','get_rooms_status/');
       
        $http.get(url, { }).then(function(response) {
            $scope.rooms_status = response.data.rooms_status;
            
            if($scope.rooms_status.description == 1){
                $('#btn_2').removeAttr('disabled');
            }
            if($scope.rooms_status.basics == 1){
                $('#btn_3').removeAttr('disabled');
            }
            if($scope.rooms_status.photos == 1){
                $('#btn_4').removeAttr('disabled');
            }
            if($scope.rooms_status.location == 1){
                $('#btn_5').removeAttr('disabled');
            }
            if($scope.rooms_status.amenities == 1){
                $('#btn_6').removeAttr('disabled');
            }
            if($scope.rooms_status.pricing == 1){
                $('#btn_7').removeAttr('disabled');
            }
            if($scope.rooms_status.calendar == 1){
                $('#btn_8').removeAttr('disabled');
            }
            if($scope.rooms_status.terms == 1){
                $('#btn_9').removeAttr('disabled');
            }
            
        });
    }
}]);