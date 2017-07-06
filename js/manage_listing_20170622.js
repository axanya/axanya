app.controller('manage_listing', ['$scope', '$http', '$compile', function($scope, $http, $compile) {

$(document).on('click', '[data-track="welcome_modal_finish_listing"]', function()
{
	var data_params = {};

	data_params['started'] = 'Yes';

	var data = JSON.stringify(data_params);

	$http.post('update_rooms', { data:data }).then(function(response)
	{
		$('.welcome-new-host-modal').attr('aria-hidden', 'true');
	});
});

$(document).on('change', '[id^="calendar-select-"], [id^="select-"]', function()///+++working
{
	var data_params = {};

	data_params[$(this).attr('name')] = $(this).val();

	var data = JSON.stringify(data_params);
    var months =  $(this).val();

	var saving_class = $(this).attr('data-saving');

	$('.'+saving_class+' h5').text('Saving...');
	$('.'+saving_class).fadeIn();

	$http.post('update_rooms', { data:data }).then(function(response)
	{
		if(response.data.success == 'true')
		{
			$('.'+saving_class+' h5').text('Saved!');
     		$('.'+saving_class).fadeOut();
     		$('#steps_count').text(response.data.steps_count);
     		$scope.steps_count = response.data.steps_count;
        	$('.my-calendar').html('<h3 class="text-center" style="margin-top: 30px">Loading...</h3>');
        	$scope.get_calendar(months);

        	if(months != 0){
        		$('[data-track="calendar"] a div div .transition').removeClass('visible');
		    	$('[data-track="calendar"] a div div .transition').addClass('hide');
		    	$('[data-track="calendar"] a div div .pull-right .nav-icon').removeClass('hide');
        	}else{
        		$('[data-track="calendar"] a div div .transition').addClass('visible');
		    	$('[data-track="calendar"] a div div .transition').removeClass('hide');
		    	$('[data-track="calendar"] a div div .pull-right .nav-icon').addClass('hide');
        	}

        }
    });

});
$scope.get_calendar = function(months)
{

	$http.post('get-calendar', { months: months }).then(function(response)
	{
		$('.my-calendar').html($compile(response.data)($scope) );

		if($scope.calendar_lengths == 0){
			var data_params = {};

			data_params['calendar_length'] = 18;
			var data = JSON.stringify(data_params);
			$http.post('update_rooms', { data:data }).then(function(response)
			{
	    		$('[data-track="calendar"] a div div .transition').removeClass('visible');
	    		$('[data-track="calendar"] a div div .transition').addClass('hide');
	    		$('[data-track="calendar"] a div div div .icon-ok-alt').removeClass('hide');
		    });
		}


	});
};

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
		$http.post('update_rooms_bathroom', { data:data }).then(function(response) {
			//get_bedroom_details();
			if(response.data.success == 'true')
			{
				$scope.basics = 1;
				$('.'+saving_class+' h5').text('Saved!');
	     		$('.'+saving_class).fadeOut();
	     		$('#steps_count').text(response.data.steps_count);
	     		$scope.steps_count = response.data.steps_count;

	     		var track = "basics";
	    		$('[data-track="'+track+'"] a div div .transition').removeClass('visible');
	    		$('[data-track="'+track+'"] a div div .transition').addClass('hide');
	    		$('[data-track="'+track+'"] a div div .pull-right .nav-icon').removeClass('hide');

	     	}
		});
	}else{
		$http.post('update_rooms', { data:data }).then(function(response)
		{
			if(response.data.success == 'true')
			{
				$('.'+saving_class+' h5').text('Saved!');
	     		$('.'+saving_class).fadeOut();
	     		$('#steps_count').text(response.data.steps_count);
	     		$scope.steps_count = response.data.steps_count;
	     	}
	     	if($scope.basics != '' && $scope.basics != 0)
	    	{
	    		var track = saving_class.substring(0, saving_class.length - 1);
	    		$('[data-track="'+track+'"] a div div .transition').removeClass('visible');
	    		$('[data-track="'+track+'"] a div div .transition').addClass('hide');
	    		$('[data-track="'+track+'"] a div div .pull-right .nav-icon').removeClass('hide');
	    	}
	    	if($scope.cancel_policy != "" && typeof($scope.cancel_policy) != "undefined")
	    		$('[data-track="terms"] a div div div .icon-ok-alt').removeClass('hide');
	    	else
	    		$('[data-track="terms"] a div div div .icon-ok-alt').addClass('hide');

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


    if($(this).attr('name') == 'beds')
    {
    	if($(this).val() != '')
    	$('#beds_show').show();
    }

});



$(document).on('change', '[id^="terms-select-"], [id^="select-"], [name="cancel_policy"]', function()
{
	var data_params = {};

	data_params[$(this).attr('name')] = $(this).val();

	var data = JSON.stringify(data_params);

	var saving_class = $(this).attr('data-saving');

	$('.'+saving_class+' h5').text('Saving...');
	$('.'+saving_class).fadeIn();
	var select_type = $(this).attr('name');
	$http.post('update_rooms_policies', { data:data }).then(function(response) {

		if(response.data.success == 'true'){
			$('.'+saving_class+' h5').text('Saved!');
	 		$('.'+saving_class).fadeOut();
	 		$('#steps_count').text(response.data.steps_count);
	 		$scope.steps_count = response.data.steps_count;

	 		if($scope.cancel_policy != ''){
	 			$('[data-track="terms"] a div div .transition').removeClass('visible');
		    	$('[data-track="terms"] a div div .transition').addClass('hide');
		    	$('[data-track="terms"] a div div .pull-right .nav-icon').removeClass('hide');
	 		}

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

	$http.post('update_rooms_policies', { data:data }).then(function(response)
	{
		if(response.data.success == 'true')
		{
			$('.'+saving_class+' h5').text('Saved!');
	 		$('.'+saving_class).fadeOut();
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

	$http.post('update_rooms_policies', { data:data }).then(function(response)
	{
		if(response.data.success == 'true')
		{
			$('.'+saving_class+' h5').text('Saved!');
	 		$('.'+saving_class).fadeOut();
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

	$http.post('update_rooms', { data:data }).then(function(response)
	{
		if(response.data.success == 'true')
		{
			$('.saving-progress h5').text('Saved!');
     		$('.saving-progress').fadeOut();
     		$('#steps_count').text(response.data.steps_count);
     		$scope.steps_count = response.data.steps_count;
     	}
     	if($scope.name != '' && $scope.summary != '')
    	{
    		$('[data-track="description"] a div div .transition').removeClass('visible');
    		$('[data-track="description"] a div div .transition').addClass('hide');
    		$('[data-track="description"] a div div div .icon-ok-alt').removeClass('hide');
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

$(document).on('click', '.nav-item a, .next_step a', function()
{
	if($(this).attr('href') != '')
	{
	var data_params = {};
	var loading = '<div class="manage-listing-content-container" id="js-manage-listing-content-container"><div class="manage-listing-content-wrapper" style="height:100%;"><div class="manage-listing-content" id="js-manage-listing-content"><div><div class="row-space-top-6 basics-loading loading"></div></div></div></div></div>';

	$( "#ajax_container" ).html(loading);

	$http.post($(this).attr('href').replace('manage-listing','ajax-manage-listing'), { data: data_params }).then(function(response) {
		$( "#ajax_container" ).html( $compile(response.data)($scope) );

		if(next_step == 'location')
		{
			initMap2();
		}
    });

	var ex_pathname = (window.location.href).split('/');
	var cur_step = $(ex_pathname).get(-1);

	$('#href_'+cur_step).attr('href',window.location.href);

	var ex_pathname = $(this).attr('href').split('/');
	var next_step = $(ex_pathname).get(-1);

	if(next_step != 'calendar')
	{
		/*$('.manage-listing-row-container').removeClass('has-collapsed-nav');*/
	}
	else
	{
		if($('#room_status').val() != '')
		{
		/*$('.manage-listing-row-container').addClass('has-collapsed-nav');
		$('#js-manage-listing-nav').addClass('collapsed');*/
		}
	}

	if(cur_step == 'calendar' || next_step == 'calendar')
	{
	$http.post($(this).attr('href').replace('manage-listing','ajax-header'), { }).then(function(response) {
		$( "#ajax_header" ).html( $compile(response.data)($scope) );
    });
	}

	$scope.step = next_step;

	window.history.pushState({path:$(this).attr('href')},'',$(this).attr('href'));
	$("html, body").animate({ scrollTop: 0 }, "slow");

	return false;
	}
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
// $(document).on('click', '.nav-item a, .next_step a, #calendar_edit_cancel', function()
// {
// 	var pathname = document.getElementById("href_calendar").href;

// 	// if($scope.rooms_status_value == 'Listed' && $(location).attr('href')== pathname){
// 	// 	$( "#ajax_container" ).html( $compile(response.data)($scope) );
// 	// }else{
// 	// 	$( "#js-address-container" ).html( $compile(response.data)($scope) );
// 	// }
// 	$( "#js-address-container" ).html( $compile(response.data)($scope) );

// });

$(document).on('click', '#show_long_term', function()
{
	$('#js-long-term-prices').removeClass('hide');
	$('#js-set-long-term-prices').addClass('hide');
});
$(document).on('click', '.calender_pop', function()
{
	var data_params = {};
	$('#address-flow-view .modal').fadeIn();
	$('#address-flow-view .modal').attr('aria-hidden','false');

	$http.post($(this).attr('href').replace('manage-listing','ajax-manage-listing'), { data:data_params }).then(function(response)
	{
	$("#address-flow-view .modal-content").css({'max-width':'80%'});
		$("#address-flow-view").css({'z-index': 10000,'position': 'fixed'});
		$( "#js-address-container" ).html( $compile(response.data)($scope) );
    });
	return false;
});
$(document).on('click', '#js-add-address, #js-edit-address', function()
{
	$('#edit_address_field').show();
    $('#edited_address_field').hide();
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
//	data_params['country'] = $scope.country;
//	data_params['address_line_1'] = $scope.address_line_1;
//	data_params['address_line_2'] = $scope.address_line_2;
//	data_params['city'] = $scope.city;
//	data_params['state'] = $scope.state;
//	data_params['postal_code'] = $scope.postal_code;
//	data_params['latitude'] = $scope.latitude;
//	data_params['longitude'] = $scope.longitude;

	var data = JSON.stringify(data_params);

	$('#js-bedroom-container').addClass('enter_bedroom');
	$('#bedroom-flow-view .modal').fadeIn();
	$('#bedroom-flow-view .modal').attr('aria-hidden','false');
	$http.post((window.location.href).replace('manage-listing','enter_bedroom'), { data:data }).then(function(response)
	{
		$( "#js-bedroom-container" ).html( $compile(response.data)($scope) );
		initAutocomplete();
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
	$http.post((window.location.href).replace('manage-listing','enter_bed_option'), { data:data }).then(function(response) {
		get_bedroom_details();
		$('.'+saving_class+' h5').text('Saved!');
	 	$('.'+saving_class).fadeOut();


		//$( "#js-bedroom-container" ).html( $compile(response.data)($scope) );
		initAutocomplete();
	});

	//bedroom_child_content_label
	//data_params['king'] = $(this).find('#king', 0).attr('king');
//        $scope.bedroom_child_content_label = "aaaawww";
        //$("#bedroom_child_content_label").index(3).html("<b>Hello world!</b>");
        //$(".bedroom_child").get(3).$("#bedroom_child_content_label").html("TTTTTTTTTTTTTTT");
         //$(".bedroom_child").get(3).$("#bedroom_child_content_label").html("TTTTTTTTTTTTTTT");

         $(".bedroom_child:nth-child(3) .bedroom_child_content_label").html("king_text");
//	data_params['address_line_1'] = $scope.address_line_1 = $('#address_line_1').val();
//	data_params['address_line_2'] = $scope.address_line_2 = $('#address_line_2').val();
//	data_params['city'] = $scope.city = $('#city').val();
//	data_params['state'] = $scope.state = $('#state').val();
//	data_params['postal_code'] = $scope.postal_code = $('#postal_code').val();
//	data_params['latitude'] = $scope.latitude;
//	data_params['longitude'] = $scope.longitude;

//	var data = JSON.stringify(data_params);
//	if(!$scope.autocomplete_used)
//		$scope.location_found = true;
//	$('#js-address-container .panel').addClass('loading');
//	$http.post((window.location.href).replace('manage-listing','location_not_found'), { data:data }).then(function(response)
//	{
//		$('#js-bedroom-container .panel').removeClass('loading');
//		$('#js-bedroom-container').addClass('location_not_found');
//		$( "#js-bedroom-container" ).html( $compile(response.data)($scope) );
//		pin_address();
//    });
    $('#bedroom-flow-view .modal').fadeOut();
    $('#bedroom-flow-view .modal').attr('aria-hidden','true');
});

$(document).on('click', '#js-next-btn', function()
{
	var data_params = {};

	data_params['country'] = $scope.country = $('#country').val();
	data_params['address_line_1'] = $scope.address_line_1 = $('#address_line_1').val();
	data_params['address_line_2'] = $scope.address_line_2 = $('#address_line_2').val();
	data_params['city'] = $scope.city = $('#city').val();
	data_params['state'] = $scope.state = $('#state').val();
	data_params['postal_code'] = $scope.postal_code = $('#postal_code').val();
	data_params['latitude'] = $scope.latitude;
	data_params['longitude'] = $scope.longitude;

	var data = JSON.stringify(data_params);
	if(!$scope.autocomplete_used)
		$scope.location_found = true;
	$('#js-address-container .panel').addClass('loading');
	$http.post((window.location.href).replace('manage-listing','location_not_found'), { data:data }).then(function(response)
	{
		$('#js-address-container .panel').removeClass('loading');
		$('#js-address-container').addClass('location_not_found');
		$( "#js-address-container" ).html( $compile(response.data)($scope) );
		pin_address();
    });

});

/*$(document).on('click', '#js-next-btn2', function()
{*/
function pin_address(){
	var data_params = {};

	data_params['country'] = $scope.country;
	data_params['address_line_1'] = $scope.address_line_1;
	data_params['address_line_2'] = $scope.address_line_2;
	data_params['city'] = $scope.city;
	data_params['state'] = $scope.state;
	data_params['postal_code'] = $scope.postal_code;
	data_params['latitude'] = $scope.latitude;
	data_params['longitude'] = $scope.longitude;

	var data = JSON.stringify(data_params);
	$('#js-address-container .panel').addClass('loading');
	$http.post((window.location.href).replace('manage-listing','verify_location'), { data:data }).then(function(response)
	{
		$('#js-address-container .panel').removeClass('loading');
		$('#js-address-container').addClass('location_not_found');
		$( "#js-address-container" ).html( $compile(response.data)($scope) );
		setTimeout(function()
		{
			initMap();
			confirm_pin();
		},100);
    });
}



$(document).on('change', '#pac-input', function(){
		
	var geocoder = new google.maps.Geocoder();
	var address = $('#pac-input').val();

	geocoder.geocode( { 'address': address}, function(place, status) {
		console.log(JSON.stringify(place));
		if(place.length == 0){
			$(".add_error").show().html("Location not found. Please correct your address.");
        	return false;
		}else{
			$(".add_error").hide().html("");
		}
		var results = get_address_components(place[0]);
            if(results){
              var url = window.location.href.replace('location','update_locations');
              var data = JSON.stringify(results[0]);
              console.log(data);
              $.post(url,{ data:data }, function(response, status){
                var add_array = JSON.parse(data);
                  /*var add_html = '<span class="address-line">'+add_array.address_line_1+'</span>'
                        +'<span>'+add_array.city+' '+add_array.state+' '+add_array.postal_code+' '+add_array.country_full_name+'</span>';*/
                  $('#saved_address').html(add_array.full_address);
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


		//console.log(JSON.stringify(place));
	  if (status == google.maps.GeocoderStatus.OK) {
	    var latitude = place[0].geometry.location.lat();
	    var longitude = place[0].geometry.location.lng();
	   // alert(latitude);
	  } 
	}); 



		
});



 function initMap2() {
//console.log('map')

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
            //console.log(JSON.stringify(place));

            var results = get_address_components(place);
            if(results){
              var url = window.location.href.replace('location','update_locations');
              var data = JSON.stringify(results[0]);

              $.post(url,{ data:data }, function(response, status){
                var add_array = JSON.parse(data);
                  var add_html = '<span class="address-line">'+add_array.address_line_1+'</span>'
                        +'<span>'+add_array.city+' '+add_array.state+' '+add_array.postal_code+' '+add_array.country_full_name+'</span>';
                  $('#saved_address').html(add_array.full_address);
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
        if(args.address_components != undefined){
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

	        address.push({'full_address': $('#pac-input').val(), 'address_line_1': streetNumber+' '+route, 'street': streetNumber, 'city': city, 'state': state, 'postal_code': postalCode, 'country': country, 'latitude': latitude, 'longitude': longitude, 'country_full_name': country_full_name});
	        return address;
        }else{
        	$(".add_error").show().html("Location not found. Please correct your address.");
        	return false;
        }
        
        //alert('streetNumber: '+streetNumber+ ', route: '+route+', city: '+city+', state: '+state+', postalCode: '+postalCode+', country: '+country)
}









/*});*/

/*$(document).on('click', '#js-next-btn3', function()
{*/

function confirm_pin(){
	var data_params = {};

	data_params['country'] = $scope.country = $('#country').val();
	data_params['address_line_1'] = $scope.address_line_1 = $('#address_line_1').val();
	data_params['address_line_2'] = $scope.address_line_2 = $('#address_line_2').val();
	data_params['city'] = $scope.city = $('#city').val();
	data_params['state'] = $scope.state = $('#state').val();
	data_params['postal_code'] = $scope.postal_code = $('#postal_code').val();
	data_params['latitude'] = $scope.latitude;
	data_params['longitude'] = $scope.longitude;

	var data = JSON.stringify(data_params);

	$('#js-address-container .panel:first').addClass('loading');
	$http.post((window.location.href).replace('manage-listing','finish_address'), { data:data }).then(function(response)
	{
		$('#js-address-container .panel').removeClass('loading');

		$('.location-map-container-v2').removeClass('empty-map');
		$('.location-map-container-v2').addClass('map-spotlight-v2');

		$('.location-map-pin-v2').removeClass('moving');
		$('.location-map-pin-v2').addClass('set');
		$('.address-static-map img').remove();
		$('.address-static-map').append('<img width="820" height="275" src="https://maps.googleapis.com/maps/api/staticmap?size=820x275&amp;center='+response.data.latitude+','+response.data.longitude+'&amp;zoom=15&amp;maptype=roadmap&amp;sensor=false&key='+map_key+'">');

		$('.panel-body .text-center').remove();

		$('.panel-body address').removeClass('hide');
		$('.panel-body .js-edit-address-link').removeClass('hide');
		var address_line_2 = (response.data.address_line_2 != '') ? ' / '+response.data.address_line_2 : '';
		$('.panel-body address span:nth-child(1)').text(response.data.address_line_1+address_line_2);
		$('.panel-body address span:nth-child(2)').text(response.data.city + ' '+ response.data.state);
		$('.panel-body address span:nth-child(3)').text(response.data.postal_code);
		$('.panel-body address span:nth-child(4)').text(response.data.country_name);

		$('[data-track="location"] a div div .transition').removeClass('visible');
    	$('[data-track="location"] a div div .transition').addClass('hide');
    	$('[data-track="location"] a div div div .icon-ok-alt').removeClass('hide');

		$('#address-flow-view .modal').fadeOut();
		$('#address-flow-view .modal').attr('aria-hidden','true');
		$('#steps_count').text(response.data.steps_count);
		$scope.steps_count = response.data.steps_count;
		$scope.location_found = false;
    });
}
/*});*/


$(document).on('click', '.modal-close, [data-behavior="modal-close"], .panel-close', function()
{
	$('.modal').fadeOut();
	$('.tooltip').css('opacity','0');
	$('.tooltip').attr('aria-hidden','true');
	$('.modal').attr('aria-hidden','true');
});

$(document).on('click', '.modal1-close', function()
{
	$('#address-flow-view .modal').fadeOut();
	$('#address-flow-view .modal').attr('aria-hidden','true');
});

initAutocomplete(); // Call Google Autocomplete Initialize Function

// Google Place Autocomplete Code
$scope.location_found = false;
$scope.autocomplete_used = false;
var autocomplete;

function initAutocomplete()
{
	autocomplete = new google.maps.places.Autocomplete(document.getElementById('address_line_1'));
  	autocomplete.addListener('place_changed', fillInAddress);
}

function fillInAddress()
{
	$scope.autocomplete_used = true;
  	fetchMapAddress(autocomplete.getPlace());
}

if($('#address_line_1').val() == '')
{
	$('#js-next-btn').prop('disabled', true);
}

$(document).on('click', '#address_line_1', function()
{
	if($(this).val() == '')
		$('#js-next-btn').prop('disabled', true);
	else
		$('#js-next-btn').prop('disabled', false);
});


var map, geocoder;
function initMap() {

  geocoder = new google.maps.Geocoder();
  map = new google.maps.Map(document.getElementById('map'), {
    center: {lat: parseFloat($('#latitude2').val()), lng: parseFloat($('#longitude2').val()) },
    zoom: 15,
    mapTypeId: google.maps.MapTypeId.ROADMAP,
    disableDefaultUI: true,
    zoomControl: true,
    zoomControlOptions: {
      style: google.maps.ZoomControlStyle.SMALL
    }
  });

  $('<div/>').addClass('verify-map-pin').appendTo(map.getDiv()).click(function(){
});

map.addListener('dragend', function(){
 	geocoder.geocode({'latLng': map.getCenter()}, function(results, status)
 	{
		if (status == google.maps.GeocoderStatus.OK)
		{
			if (results[0])
			{
				fetchMapAddress(results[0]);
				$('#js-next-btn3').prop('disabled',false);
			}
		}
	});
       $('.verify-map-pin').removeClass('moving');
       $('.verify-map-pin').addClass('unset');
});

map.addListener('zoom_changed', function(){
 	geocoder.geocode({'latLng': map.getCenter()}, function(results, status)
 	{
		if (status == google.maps.GeocoderStatus.OK)
		{
			if (results[0])
			{
				fetchMapAddress(results[0]);
			}
		}
	});
});

map.addListener('drag', function(){
    $('.verify-map-pin').removeClass('unset');
    $('.verify-map-pin').addClass('moving');
});

}

function fetchMapAddress(data)
{
	if(data['types'] == 'street_address')
		$scope.location_found = true;
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
  	$scope.street_number = '';
  	for (var i = 0; i < place.address_components.length; i++)
  	{
    	var addressType = place.address_components[i].types[0];
    	if (componentForm[addressType])
    	{
    	  var val = place.address_components[i][componentForm[addressType]];

			if(addressType       == 'street_number')
				$scope.street_number = val;
			if(addressType       == 'route') {
				var street_address = $scope.street_number+' '+val;
				$('#address_line_1').val($.trim(street_address));
			}
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

    /*if($('#address_line_1').val() == '')
    	$('#address_line_1').val($('#city').val());*/

    if($('#address_line_1').val() == '')
		$('#js-next-btn').prop('disabled', true);
	else
		$('#js-next-btn').prop('disabled', false);

	$scope.latitude = latitude;
	$scope.longitude = longitude;
}

$(document).on('change', '[name="is_referral"], [name="referral_code"]', function()
{
	var data_params = {};
	var referral_code = '';
	var status = 'No';
	if($(this).attr('name') == 'is_referral'){
		if($(this).prop('checked')==true){
			status = 'No';
		}else{
			status = 'Yes';
		}
		data_params[$(this).attr('name')] = status;
		data_params['referral_code'] = '';
		$('#referral_code').val('');
		$scope.referral_code = '';
	}else{
		referral_code = $(this).val();
		data_params[$(this).attr('name')] = referral_code;
		data_params['is_referral'] = 'Yes';
		$scope.is_referral == 'Yes';
		$("#is_referral").prop('checked', false);
	}


	var data = JSON.stringify(data_params);

	var saving_class = $(this).attr('data-saving');

	$('.'+saving_class+' h5').text('Saving...');
	$('.'+saving_class).fadeIn();

	$http.post('update_rooms', { data : data}).then(function(response)
	{
		if(response.data.success == 'true')
		{
			$('.'+saving_class+' h5').text('Saved!');
     		$('.'+saving_class).fadeOut();

     		$('#steps_count').text(response.data.steps_count);
     		$scope.steps_count = response.data.steps_count;

     		if(referral_code != '' || status == 'No'){

     			$('[data-track="referral"] a div div .transition').removeClass('visible');
		    	$('[data-track="referral"] a div div .transition').addClass('hide');
		    	$('[data-track="referral"] a div div div .icon-ok-alt').removeClass('hide');
     		}else{
     			$('[data-track="referral"] a div div .transition').removeClass('hide');
    			$('[data-track="referral"] a div div div .icon-ok-alt').addClass('hide');
     		}
     	}
    });

    /*$scope.amenities_status = $('[name="amenities"]:checked').length;
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
	}*/
});

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

	$http.post('update_amenities', { data : value, religious_data : religious_value, religious_extra_data : JSON.stringify(religious_extra_value)}).then(function(response)
	{
		if(response.data.success == 'true')
		{
			$('.'+saving_class+' h5').text('Saved!');
     		$('.'+saving_class).fadeOut();

     		$('#steps_count').text(response.data.steps_count);
     		$scope.steps_count = response.data.steps_count;

     	}
    });
    //$scope.amenities_status = $('[name="amenities"]:checked').length-0+$('[name="religious_amenities"]:checked').length;
    $scope.amenities_status = $('[name="amenities"]:checked').length;
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

/* ajaxfileupload */
jQuery.extend({ handleError: function( s, xhr, status, e ) {if ( s.error ) s.error( xhr, status, e ); else if(xhr.responseText) console.log(xhr.responseText); } });
jQuery.extend({createUploadIframe:function(e,t){var r="jUploadFrame"+e;if(window.ActiveXObject){var n=document.createElement("iframe");n.id=n.name=r,"boolean"==typeof t?n.src="javascript:false":"string"==typeof t&&(n.src=t)}else{var n=document.createElement("iframe");n.id=r,n.name=r}return n.style.position="absolute",n.style.top="-1000px",n.style.left="-1000px",document.body.appendChild(n),n},createUploadForm:function(e,t){var r="jUploadForm"+e,n="jUploadFile"+e,o=jQuery('<form  action="" method="POST" name="'+r+'" id="'+r+'" enctype="multipart/form-data"></form>'),a=jQuery("#"+t),u=jQuery(a).clone();return jQuery(a).attr("id",n),jQuery(a).before(u),jQuery(a).appendTo(o),jQuery(o).css("position","absolute"),jQuery(o).css("top","-1200px"),jQuery(o).css("left","-1200px"),jQuery(o).appendTo("body"),o},ajaxFileUpload:function(e){e=jQuery.extend({},jQuery.ajaxSettings,e);var t=(new Date).getTime(),r=jQuery.createUploadForm(t,e.fileElementId),n=(jQuery.createUploadIframe(t,e.secureuri),"jUploadFrame"+t),o="jUploadForm"+t;e.global&&!jQuery.active++&&jQuery.event.trigger("ajaxStart");var a=!1,u={};e.global&&jQuery.event.trigger("ajaxSend",[u,e]);var c=function(t){var o=document.getElementById(n);try{o.contentWindow?(u.responseText=o.contentWindow.document.body?o.contentWindow.document.body.innerHTML:null,u.responseXML=o.contentWindow.document.XMLDocument?o.contentWindow.document.XMLDocument:o.contentWindow.document):o.contentDocument&&(u.responseText=o.contentDocument.document.body?o.contentDocument.document.body.innerHTML:null,u.responseXML=o.contentDocument.document.XMLDocument?o.contentDocument.document.XMLDocument:o.contentDocument.document)}catch(c){jQuery.handleError(e,u,null,c)}if(u||"timeout"==t){a=!0;var d;try{if(d="timeout"!=t?"success":"error","error"!=d){var l=jQuery.uploadHttpData(u,e.dataType);e.success&&e.success(l,d),e.global&&jQuery.event.trigger("ajaxSuccess",[u,e])}else jQuery.handleError(e,u,d)}catch(c){d="error",jQuery.handleError(e,u,d,c)}e.global&&jQuery.event.trigger("ajaxComplete",[u,e]),e.global&&!--jQuery.active&&jQuery.event.trigger("ajaxStop"),e.complete&&e.complete(u,d),jQuery(o).unbind(),setTimeout(function(){try{jQuery(o).remove(),jQuery(r).remove()}catch(t){jQuery.handleError(e,u,null,t)}},100),u=null}};e.timeout>0&&setTimeout(function(){a||c("timeout")},e.timeout);try{var r=jQuery("#"+o);jQuery(r).attr("action",e.url),jQuery(r).attr("method","POST"),jQuery(r).attr("target",n),r.encoding?r.encoding="multipart/form-data":r.enctype="multipart/form-data",jQuery(r).submit()}catch(d){jQuery.handleError(e,u,null,d)}return window.attachEvent?document.getElementById(n).attachEvent("onload",c):document.getElementById(n).addEventListener("load",c,!1),{abort:function(){}}},uploadHttpData:function(r,type){var data=!type;return data="xml"==type||data?r.responseXML:r.responseText,"script"==type&&jQuery.globalEval(data),"json"==type&&eval("data = "+data),"html"==type&&jQuery("<div>").html(data).evalScripts(),data}});

$(document).on('click', '#photo-uploader', function()
{
	$('#upload_photos').trigger('click');
});

$(document).on('click', '#js-photo-grid-placeholder', function()
{
	$('#upload_photos2').trigger('click');
});

function upload()
{
upload2();
$(document).on("change", '#upload_photos', function() {
	jQuery.ajaxFileUpload({
        url: "../../add_photos/"+$('#room_id').val(),
        secureuri: false,
        fileElementId: "upload_photos",
        dataType: "json",
        async: false,
        success: function(response){
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

           	$('#upload_photos').reset();
       	}
       	upload();
        }
    });
});
}

function upload2()
{
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
       	upload2();
        }
    });
});
}

function photos_list()
{
$http.get('photos_list', { }).then(function(response)
{
	$scope.photos_list = response.data;
	if(response.data.length > 0)
	{
	$('#photo_count').css('display','block');
	}
});
}

upload();



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

$(document).on('click', '.js-delete-photo-confirm', function()
{
	var index = $(this).attr('data-index');
	$http.post('delete_photo', { photo_id : $(this).attr('data-id') }).then(function(response)
	{
		if(response.data.success == 'true')
		{
			$scope.photos_list.splice(index,1);
			$('#js-error').attr('aria-hidden',true);
			// photos_list();
			$('#steps_count').text(response.data.steps_count);
			$scope.steps_count = response.data.steps_count;

     	}
    });
});

$scope.$watchCollection('photos_list', function (newCol, oldCol, scope) {

	if($scope.photos_list != undefined)
	{
     if($scope.photos_list.length != 0)
	{
		$('[data-track="photos"] a div div .transition').removeClass('visible');
    	$('[data-track="photos"] a div div .transition').addClass('hide');
    	$('[data-track="photos"] a div div div .icon-ok-alt').removeClass('hide');
	}
	else
	{
		$('[data-track="photos"] a div div .transition').removeClass('hide');
    	$('[data-track="photos"] a div div div .icon-ok-alt').addClass('hide');
	}
	}
});
$(document).ready(function(){
	var pathname = document.getElementById("href_calendar").href;
	if($scope.rooms_status_value == 'Listed' && $(location).attr('href')== pathname){
		$('#address-flow-view').html('');
		console.log('listed');
		/*$('.manage-listing-row-container').removeClass('page-container');
        $('#js-manage-listing-nav').removeClass('pos-fix');
         $('#ajax_container').removeClass('mar-left-cont');*/
	}
});

$scope.$watch('steps_count', function (value) {

	if($scope.steps_count != undefined)
	{
		if($scope.steps_count == 10)
		{


			$('#finish_step').hide();
			$('.next-section-button').addClass('hide');
			$('#js-list-space-button').removeClass('hide');
			$('.js-steps-remaining').addClass('show');
			$('.js-steps-remaining').removeClass('show');
			//$('#js-list-space-button').css('display','');
			$('#js-list-space-button').removeAttr('disabled');
			//$('#js-list-space-tooltip').attr('aria-hidden','false');
			//$('#js-list-space-tooltip').css({'opacity':'1','top': '290px'});
			//$('#js-list-space-tooltip').removeClass("animated").addClass("animated");
			 /*$('.manage-listing-row-container').removeClass('page-container');
             $('#js-manage-listing-nav').removeClass('pos-fix');*/

		}
		else
		{
			$('#finish_step').show();
			$('.next-section-button').removeClass('hide');
			$('#js-list-space-button').addClass('hide');
			$('.js-steps-remaining').removeClass('show');
			$('.js-steps-remaining').addClass('show');
			//$('#js-list-space-button').css('display','none');
			$('#js-list-space-button').attr('disabled', 'disabled');
			//$('#js-list-space-tooltip').attr('aria-hidden','true');
			//$('#js-list-space-tooltip').css({'opacity':'0','top': '290px'});
		}
	}
});

$scope.over_first_photo = function(index)
{
	if(index == 0)
	$('#js-first-photo-text').removeClass('invisible');

	//alert($(this).attr('class'));
	$('#text_heighlight_'+index).addClass('border');
};

$scope.out_first_photo = function(index)
{
	if(index == 0)
	$('#js-first-photo-text').addClass('invisible');

	$('#text_heighlight_'+index).removeClass('border');
};

$scope.keyup_highlights = function(id, value)
{
	$http.post('photo_highlights', { photo_id : id, data : value }).then(function(response)
	{

    });
};

$(document).on('change', '[id^="price-select-"]', function()
{
	var data_params = {};

	data_params[$(this).attr('name')] = $(this).val();
	data_params['night'] = $('#price-night').val();

	var data = JSON.stringify(data_params);

	var saving_class = $(this).attr('data-saving');

	$('.'+saving_class+' h5').text('Saving...');
	$('.'+saving_class).fadeIn();

	$http.post('update_price', { data:data }).then(function(response)
	{
		if(response.data.success == 'true')
		{
			$('[data-error="price"]').text('');
			$('.input-prefix').html(response.data.currency_symbol);
			$('.'+saving_class+' h5').text('Saved!');
     		$('.'+saving_class).fadeOut();
     		$('#steps_count').text(response.data.steps_count);
     		$scope.steps_count = response.data.steps_count;
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
// 	 $('.autosubmit-text[id^="price-"]').trigger('blur');
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
		$http.post('update_price', { data:data }).then(function(response)
		{
			if(response.data.success == 'true')
			{
				$('[data-error="price"]').text('');
				$('.input-prefix').html(response.data.currency_symbol);
				$('.'+saving_class+' h5').text('Saved!');
				$('.'+saving_class).fadeOut();
				$('#steps_count').text(response.data.steps_count);
				$scope.steps_count = response.data.steps_count;

			}
			else
			{
				$('[data-error="price"]').html(response.data.msg);
				$('.'+saving_class).fadeOut();
			}
			if($('#price-night').val() != 0)
			{
				$('#price-night-old').val($('#price-night').val());
				$('[data-track="pricing"] a div div .transition').removeClass('visible');
				$('[data-track="pricing"] a div div .transition').addClass('hide');
				$('[data-track="pricing"] a div div div .icon-ok-alt').removeClass('hide');
				//$('html, body').animate({ scrollTop: $('#js-list-space-button').offset().top+1000 }, 'slow');
			}

		});
		}
		else
		{
			if($('#price-night-old').val() == 0)
			{
				$('#price-night').val($('#price-night-old').val());
				$('[data-track="pricing"] a div div .transition').removeClass('hide');
				$('[data-track="pricing"] a div div div .icon-ok-alt').addClass('hide');
			}
			else
			{
				$('#price-night').val($('#price-night-old').val());
				$('[data-track="pricing"] a div div .transition').removeClass('visible');
				$('[data-track="pricing"] a div div .transition').addClass('hide');
				$('[data-track="pricing"] a div div div .icon-ok-alt').removeClass('hide');
			}
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

	$http.post('update_price', { data:data }).then(function(response)
	{
		if(response.data.success == 'true')
		{
			$('.input-prefix').html(response.data.currency_symbol);
			$('.'+saving_class+' h5').text('Saved!');
     		$('.'+saving_class).fadeOut();
     		$('#steps_count').text(response.data.steps_count);
     		$scope.steps_count = response.data.steps_count;
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

		$http.post('update_price', { data:data }).then(function(response)
		{
			if(response.data.success == 'true')
			{
				$('.input-prefix').html(response.data.currency_symbol);
				$('.'+saving_class+' h5').text('Saved!');
	     		$('.'+saving_class).fadeOut();
	     		$('#steps_count').text(response.data.steps_count);
	     		$scope.steps_count = response.data.steps_count;
				//$('html, body').animate({ scrollTop: $('#js-list-space-button').offset().top+1000 }, 'slow');
	     	}
	    });
	}
});

$(document).on('click', '[id^="available-"]', function()
{
	var data_params = {};

	var value = $(this).attr('data-slug');

	data_params['calendar_type'] = value.charAt(0).toUpperCase() + value.slice(1);;

	var data = JSON.stringify(data_params);

	$('.saving-progress h5').text('Saving...');

	$('.saving-progress').fadeIn();

	$http.post('update_rooms', { data:data }).then(function(response)
	{
		if(response.data.success == 'true')
		{
			$scope.selected_calendar = value;
			$('.selected_calendar_type').removeClass('selected_calendar_type');
			$('[data-slug="'+value+'"]').addClass('selected_calendar_type');
			$('.saving-progress h5').text('Saved!');
     		$('.saving-progress').fadeOut();
     		$('#steps_count').text(response.data.steps_count);
     		$scope.steps_count = response.data.steps_count;
     	}

     	if($scope.selected_calendar == 'always'){
     		//$('#calendar-edit-container').addClass('hide');
     		$('.calendar-modal').attr('aria-hidden','true');
			$('.calendar-modal').addClass('hide');
     	}else{
     		//$('#calendar-edit-container').removeClass('hide');
     		//$('html, body').animate({ scrollTop: $('#js-list-space-button').offset().top+1000 }, 'slow');
     		$('.calendar-modal').attr('aria-hidden','false');
			$('.calendar-modal').removeClass('hide');
			$('.calendar-modal').show();
     	}
    	$('[data-track="calendar"] a div div .transition').removeClass('visible');
    	$('[data-track="calendar"] a div div .transition').addClass('hide');
    	$('[data-track="calendar"] a div div .pull-right .nav-icon').removeClass('hide');
    });
});


$(document).on('click', '[id^="mobile-verification-"]', function()
{
	var data_params = {};
	var first_msg = last_msg = '';
	var data_value = $(this).attr('data-value');
	if(data_value == 'verify'){
		var phone_id = $(this).attr('phone-id');
		data_params['phone_id'] = phone_id;
		data_params['verification_code'] = $scope.otp;

		first_msg = 'Verifying...';
		last_msg = 'Verified!';
		var post_data = { phone_id: phone_id, verification_code: $scope.otp, type: 'verify'};
	}else if(data_value == 'resend'){
		var phone_id = $(this).attr('phone-id');
		data_params['phone_id'] = phone_id;
		data_params['resend'] = '';
		first_msg = 'Sending...';
		last_msg = 'Sent!';
		var post_data = { phone_id: phone_id, type: 'resend'};
	}else if(data_value == 'change'){
		var phone_number = $('#phone_number').val();
		var phone_code = $('#phone_code').val();

		if(phone_code != '' && phone_number != ''){

			if(phone_number.match(/^(\+\d{1,3}[- ]?)?\d{10}$/) && ! (phone_number.match(/0{5,}/)) ){

				data_params['phone_number'] = phone_number;
				data_params['phone_code'] = phone_code;
				first_msg = 'Sending...';
				last_msg = 'Sent!';
				var post_data = { phone_code: phone_code, phone_number: phone_number, type: 'change'};
			}else{
				alert('Please enter valid mobile number');
				return false;
			}


		}else{
			alert('Please enter valid mobile number');
			return false;
		}
	}
	//var value = $(this).attr('data-slug');

	var data = JSON.stringify(data_params);

	$('.saving-progress h5').text(first_msg);

	$('.saving-progress').fadeIn();
	$http.post('mobile_verification', post_data).then(function(response)
	{
		if(response.data.success == 'true')
		{
			if(data_value == 'verify'){
				$('[data-track="verification"] a div div .transition').removeClass('visible');
		    	$('[data-track="verification"] a div div .transition').addClass('hide');
		    	$('[data-track="verification"] a div div .pull-right .nav-icon').removeClass('hide');

		    	//var success_msg = 'Your verification done successfully. Now you can list you space.';
		    	//$('#verification_discription').html(success_msg);
		    	//$('#veryfyotp').hide();
				var data_params = {};
				var loading = '<div class="manage-listing-content-container" id="js-manage-listing-content-container"><div class="manage-listing-content-wrapper" style="height:100%;"><div class="manage-listing-content" id="js-manage-listing-content"><div><div class="row-space-top-6 basics-loading loading"></div></div></div></div></div>';

				$( "#ajax_container" ).html(loading);

				$http.post($(location).attr('href').replace('manage-listing','ajax-manage-listing'), { data: data_params }).then(function(response) {
					$( "#ajax_container" ).html( $compile(response.data)($scope) );
			    });

			}else if(data_value == 'change'){
				var data_params = {};
				var loading = '<div class="manage-listing-content-container" id="js-manage-listing-content-container"><div class="manage-listing-content-wrapper" style="height:100%;"><div class="manage-listing-content" id="js-manage-listing-content"><div><div class="row-space-top-6 basics-loading loading"></div></div></div></div></div>';

				$( "#ajax_container" ).html(loading);

				$http.post($(location).attr('href').replace('manage-listing','ajax-manage-listing'), { data: data_params }).then(function(response) {
					$( "#ajax_container" ).html( $compile(response.data)($scope) );

					$('[data-track="verification"] a div div .transition').addClass('visible');
			    	$('[data-track="verification"] a div div .transition').removeClass('hide');
			    	$('[data-track="verification"] a div div .pull-right .nav-icon').addClass('hide');

			    });
			}else{
				$('#v_code').html(response.data.new_code);
			}
			$('.saving-progress h5').text(last_msg);
     		$('.saving-progress').fadeOut();
     		$('#steps_count').text(response.data.steps_count);
     		$scope.steps_count = response.data.steps_count;

     	}else{
     		$('.saving-progress h5').text('Invalid otp');
     		$('.saving-progress').fadeOut();
     		alert(response.data.msg);
     	}

     	/*if($scope.selected_calendar == 'always'){
     		//$('#calendar-edit-container').addClass('hide');
     		$('.calendar-modal').attr('aria-hidden','true');
			$('.calendar-modal').addClass('hide');
     	}else{
     		//$('#calendar-edit-container').removeClass('hide');
     		//$('html, body').animate({ scrollTop: $('#js-list-space-button').offset().top+1000 }, 'slow');
     		$('.calendar-modal').attr('aria-hidden','false');
			$('.calendar-modal').removeClass('hide');
			$('.calendar-modal').show();
     	}
    	$('[data-track="calendar"] a div div .transition').removeClass('visible');
    	$('[data-track="calendar"] a div div .transition').addClass('hide');
    	$('[data-track="calendar"] a div div .pull-right .nav-icon').removeClass('hide');*/
    });
});


$(document).on('click', '[id^="change_number"]', function()
{
	$('#veryfyotp').hide();
    $('#change_phone_number').show();
});


$(document).on('click', '[id^="change_cancle"]', function()
{
	$('#veryfyotp').show();
    $('#change_phone_number').hide();
});



$(document).on('mouseover', '[id^="available-"]', function()
{
	$('[id^="available-"]').removeClass('selected_calendar_type');
});

$(document).on('mouseout', '[id^="available-"]', function()
{
	$('[id="available-'+$scope.selected_calendar+'"]').addClass('selected_calendar_type');
});

var ex_pathname = (window.location.href).split('/');
$scope.step = $(ex_pathname).get(-1);
if($scope.step == 'location'){
	initMap2();
}
	photos_list();


$(document).on('click', '#finish_step', function()
{
	$http.get('rooms_steps_status', { }).then(function(response)
	{
		for(var key in response.data)
		{
			if(response.data[key] == '0')
			{
				$('#href_'+key).trigger('click');
				return false;
			}
		}
	});
});

$(document).on('click', '#js-list-space-button', function()
{
	var data_params = {};

	$('.load_show').removeClass('hide');

	data_params['status'] = 'Listed';

	var data = JSON.stringify(data_params);

	$http.post('update_rooms', { data:data }).then(function(response)
	{
		$http.get('rooms_data', {}).then(function(response)
		{
			$('#symbol_finish').html(response.data.symbol);
			$scope.popup_photo_name         = response.data.photo_name;
			$scope.popup_night              = response.data.night;
			$scope.popup_room_name          = response.data.name;
			$scope.popup_room_type_name     = response.data.room_type_name;
			$scope.popup_property_type_name = response.data.property_type_name;
			$scope.popup_state              = response.data.state;
			$scope.popup_country            = response.data.country_name;
			$('.finish-modal').attr('aria-hidden','false');
			$('.finish-modal').removeClass('hide');
			$('#js-list-space-button').remove();
			$('.load_show').addClass('hide');
		});
	});
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

	$http.post('update_description', { data:data }).then(function(response)
	{
		if(response.data.success == 'true')
		{
			$scope.steps_count = response.data.steps_count;
			$('.saving-progress h5').text('Saved!');

			if(input_name != 'neighborhood_overview' && input_name != 'transit')
				$('.help-panel-saving').fadeOut();
			else
				$('.help-panel-neigh-saving').fadeOut();
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

	$http.post('update_description', { data:data }).then(function(response)
	{
		if(response.data.success == 'true')
		{
			$scope.steps_count = response.data.steps_count;
			$('.saving-progress h5').text('Saved!');

			if(input_name != 'neighborhood_overview' && input_name != 'transit')
				$('.help-panel-saving').fadeOut();
			else
				$('.help-panel-neigh-saving').fadeOut();
		}
	});
});


$(document).on('click', '#collapsed-nav', function()
{
	if($('#js-manage-listing-nav').hasClass('collapsed'))
    	$('#js-manage-listing-nav').removeClass('collapsed');
	else
    	$('#js-manage-listing-nav').addClass('collapsed');
 });

$(document).on('click', '.month-nav', function()
{
	var month = $(this).attr('data-month');
	var year = $(this).attr('data-year');

	var data_params = {};

	data_params['month'] = month;
	data_params['year'] = year;

	var data = JSON.stringify(data_params);

	$('.ui-datepicker-backdrop').removeClass('hide');
	$('.spinner-next-to-month-nav').addClass('loading');

	$http.post($(this).attr('href').replace('manage-listing','ajax-manage-listing'), { data:data }).then(function(response) {
		$( "#calendar-edit-container" ).html($compile($(response.data).find('#calendar-edit-container').html())($scope));
		$('.spinner-next-to-month-nav').removeClass('loading');
		$('.ui-datepicker-backdrop').addClass('hide');
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

	$('.ui-datepicker-backdrop').removeClass('hide');
	$('.spinner-next-to-month-nav').addClass('loading');

	$http.post($(this).attr('data-href').replace('manage-listing','ajax-manage-listing'), { data:data }).then(function(response) {
		$('.ui-datepicker-backdrop').addClass('hide');
		$('.spinner-next-to-month-nav').removeClass('loading');
		//$( "#js-address-container" ).html( $compile(response.data)($scope) );
		$( "#calendar-edit-container" ).html($compile($(response.data).find('#calendar-edit-container').html())($scope));
    });
	return false;
});

 /*Start - Calendar Date Selection*/

$(document).on('click', '.available-date', function()
{
	if($(this).hasClass('bottom-green')){
		//alert('available')
		$scope.available_status = 'available';
		$('#available_status').prop('checked', true);
		$('#blocked_status').prop('checked', false);
		//$('input[name="available_status"][value="not available"]').attr('checked',true);
	}else{
		$scope.available_status = 'not available';
		$('#available_status').prop('checked', false);
		$('#blocked_status').prop('checked', true);


		//$('input[name="available_status"][value="available"]').attr('checked',true);
	}
	
	if(!$(this).hasClass('other-day-selected') && !$(this).hasClass('selected') && !$(this).hasClass('tile-previous'))
	{

		var window_width = $(window).width();
		var sub = 121, top = 50;
		if(window_width > 768){
			sub = 121;
		}else if(window_width <= 768 && window_width > 360){
			sub = 117;
		}else{
			sub = 82;
			top = 32;
		}
		$(window).resize(function() {
		  	var window_width = $(window).width();
			var sub = 121, top = 50;
			if(window_width > 768){
				sub = 121;
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

		var html = '<div><div style="left:'+start_left+'px;top:'+start_top+'px;" class="tile-selection-handle tile-handle-left"><div class="tile-selection-handle__inner"><span class="tile-selection-handle__ridge"></span><span class="tile-selection-handle__ridge"></span><span class="tile-selection-handle__ridge"></span></div></div></div><div><div style="left: '+end_left+'px; top: '+end_top+'px;" class="tile-selection-handle tile-handle-right"><div class="tile-selection-handle__inner"><span class="tile-selection-handle__ridge"></span><span class="tile-selection-handle__ridge"></span><span class="tile-selection-handle__ridge"></span></div></div></div>';

		$('.days-container').append(html);
		
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

$(document).on('mouseup', '.available-date', function()
{
	
	//alert('mouseup');
	calendar_edit_form();
	
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
		var sub = 121, top = 50;
		if(window_width > 768){
			sub = 121;
		}else if(window_width <= 768 && window_width > 360){
			sub = 117;
		}else{
			sub = 82;
			top = 32;
		}
		$(window).resize(function() {
		  	var window_width = $(window).width();
			var sub = 121, top = 50;
			if(window_width > 768){
				sub = 121;
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

	var current_price = '';
	$('li.selected').each(function(i, obj) {

		if(current_price != '' && current_price != $(this).attr('data-price')){
			$('.sidebar-price').val('');
			$scope.calendar_edit_price = '';
			return false; 
		}
		current_price = $(this).attr('data-price');

	});
	
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
	
	if($scope.available_status == 'available' && $scope.calendar_edit_price == ''){
		$('#price-error').show().html('The price field is required');
		return false;
	}else if($scope.available_status == 'available' && $scope.calendar_edit_price == 0){
		$('#price-error').show().html('Please enter valid price');
		return false;
	}else{
		$('#price-error').hide().html('');
	}

	$http.post('calendar_edit', { status: $scope.available_status, start_date: $('#calendar-edit-start').val(), end_date: $('#calendar-edit-end').val(), price: $scope.calendar_edit_price, notes: $scope.notes }).then(function(response)
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

	/*var year_month = $('#calendar_dropdown').val();
	var year = year_month.split('-')[0];
	var month = year_month.split('-')[1];

	var data_params = {};

	data_params['month'] = month;
	data_params['year'] = year;

	var data = JSON.stringify(data_params);*/

	// $('.ui-datepicker-backdrop').removeClass('hide');
	// $('.spinner-next-to-month-nav').addClass('loading');



	/*$http.post(href.replace('manage-listing','ajax-manage-listing'), { data:data }).then(function(response) {
		// $('.ui-datepicker-backdrop').addClass('hide');
		// $('.spinner-next-to-month-nav').removeClass('loading');
		$('.load_show').addClass('hide');
		var pathname = document.getElementById("href_calendar").href;
		// if($scope.rooms_status_value == 'Listed' && $(location).attr('href')== pathname){
		// 	$( "#ajax_container" ).html( $compile(response.data)($scope) );
		// }else{
		// 	//$( "#ajax_container" ).html( $compile(response.data)($scope) );
		// 	$( "#calendar-edit-container" ).html($compile($(response.data).find('#calendar-edit-container').html())($scope));
		// }
		$( "#calendar-edit-container" ).html($compile($(response.data).find('#calendar-edit-container').html())($scope));
		$('.calendar-sub-modal').attr('aria-hidden','true');
		$('.calendar-sub-modal').addClass('hide');
    });*/
	return false;
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

$(document).on('click', '#export_button', function()
{
	$('#export_popup').attr('aria-hidden', 'false');
});

$(document).on('click', '#import_button', function()
{
	$('#import_popup').attr('aria-hidden', 'false');
});

$scope.booking_select = function(value)
{
	var data_params = {};

	data_params['booking_type'] = value;

	var data = JSON.stringify(data_params);

	$http.post('update_rooms', { data:data }).then(function(response)
	{
		if(response.data.success == 'true')
		{
			// $('#before_select').addClass('hide');
			// $('#'+value).removeClass('hide');
			$('[data-track="how-guests-book"] a div div .transition').removeClass('visible');
			$('[data-track="how-guests-book"] a div div .transition').addClass('hide');
			$('[data-track="how-guests-book"] a div div div .icon-ok-alt').removeClass('hide');
     	}

    });
}

$scope.booking_change = function(value)
{
	var data_params = {};

	data_params['booking_type'] = '';

	var data = JSON.stringify(data_params);

	$http.post('update_rooms', { data:data }).then(function(response)
	{
		if(response.data.success == 'true')
		{
			$('#before_select').removeClass('hide');
			$('#'+value).addClass('hide');
     	}
    });
}

}]);

$('.calendar-popup').click(function()
{
  //    $('.manage-listing-row-container').removeClass('page-container');
  //    $('#js-manage-listing-nav').removeClass('pos-fix');
  //    $('#ajax_container').removeClass('mar-left-cont');
  //    if($('#js-manage-listing-nav').hasClass('collapsed') === true){
		// $('#js-manage-listing-nav').removeClass('collapsed');
  //    }

});

$(document).on('click', '.nav-min', function()
{
     $('#js-manage-listing-nav').removeClass('pos-fix');
      $('#js-manage-listing-nav').addClass('listing-nav-sm')

});
$(document).on('click', '.list-nav-link a', function()
{
    $('.listing-nav-sm').removeClass('collapsed');
});
/*var pathname = document.getElementById("href_calendar").href;
if($(location).attr('href')== pathname){
    $('.manage-listing-row-container').removeClass('page-container');
     $('#js-manage-listing-nav').removeClass('pos-fix');
   }*/
$(window).resize(function () {
        if ($(window).width() < 760){
         $('#js-manage-listing-nav').removeClass('pos-fix');
          $('#js-manage-listing-nav').addClass('listing-nav-sm')
        }
        else{ $('#js-manage-listing-nav').removeClass('pos-fix');
          $('#js-manage-listing-nav').addClass('listing-nav-sm')}
    });

$(document).on('click', '#href_pricing', function()
{
  $('#js-manage-listing-nav').addClass('pos-fix');
   $('.manage-listing-row-container').addClass('page-container');
    $('#ajax_container').addClass('mar-left-cont');
});
$(document).on('click', '#href_terms', function()
{
  $('#js-manage-listing-nav').addClass('pos-fix');
  $('.manage-listing-row-container').addClass('page-container');
   $('#ajax_container').addClass('mar-left-cont');
});
$(document).on('click', '#remove-manage', function()
{
 $('#js-manage-listing-nav').addClass('pos-fix');
 $('.manage-listing-row-container').addClass('page-container');
  $('#ajax_container').addClass('mar-left-cont');
});
$(document).on('click', '#href_booking', function()
{
  $('#js-manage-listing-nav').addClass('pos-fix');
  $('.manage-listing-row-container').addClass('page-container');
   $('#ajax_container').addClass('mar-left-cont');
});
$(document).on('click', '#href_basics', function()
{
 $('#js-manage-listing-nav').addClass('pos-fix');
 $('.manage-listing-row-container').addClass('page-container');
  $('#ajax_container').addClass('mar-left-cont');
});
$(document).on('click', '#href_description', function()
{
  $('#js-manage-listing-nav').addClass('pos-fix');
  $('.manage-listing-row-container').addClass('page-container');
   $('#ajax_container').addClass('mar-left-cont');
});
$(document).on('click', '#href_location', function()
{
	$('#js-manage-listing-nav').addClass('pos-fix');
	$('.manage-listing-row-container').addClass('page-container');
 	$('#ajax_container').addClass('mar-left-cont');
});
$(document).on('click', '#href_amenities', function()
{
   $('#js-manage-listing-nav').addClass('pos-fix');
   $('.manage-listing-row-container').addClass('page-container');
    $('#ajax_container').addClass('mar-left-cont');
});
$(document).on('click', '#href_photos', function()
{

   $('#js-manage-listing-nav').addClass('pos-fix');
   $('.manage-listing-row-container').addClass('page-container');
    $('#ajax_container').addClass('mar-left-cont');
});
$(document).on('click', '#href_details', function()
{
$('#js-manage-listing-nav').addClass('pos-fix');
$('.manage-listing-row-container').addClass('page-container');
 $('#ajax_container').addClass('mar-left-cont');
});
$(document).on('click', '#href_guidebook', function()
{
   $('#js-manage-listing-nav').addClass('pos-fix');
   $('.manage-listing-row-container').addClass('page-container');
    $('#ajax_container').addClass('mar-left-cont');
});

$(document).on('click', '[id^="change_number"]', function()
{
	var html_des = '<h1 class="text-center">Change your mobile number</h1><br><div class="lead" style="align:center" id="verification_discription"><p> Please enter your new mobile number.</p></div>';
	$('#pending_description').hide();
	$('#change_description').show();

	$('#veryfyotp').hide();
    $('#change_phone_number').show();
});


$(document).on('click', '[id^="change_cancle"]', function()
{
	var html_des = '<h1 class="text-center">Change your mobile number</h1><br><div class="lead" style="align:center" id="verification_discription"><p> Please enter your new mobile number.</p></div>';
	$('#pending_description').show();
	$('#change_description').hide();

	$('#veryfyotp').show();
    $('#change_phone_number').hide();
});
