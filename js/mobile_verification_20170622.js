app.controller('mobile_verification', ['$scope', '$http', '$compile', function($scope, $http, $compile) {

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
				/*var data_params = {};
				var loading = '<div class="manage-listing-content-container" id="js-manage-listing-content-container"><div class="manage-listing-content-wrapper" style="height:100%;"><div class="manage-listing-content" id="js-manage-listing-content"><div><div class="row-space-top-6 basics-loading loading"></div></div></div></div></div>';

				$( "#ajax_container" ).html(loading);

				$http.get($(location).attr('href'), { data: data_params }).then(function(response) {
					$( "#ajax_container" ).html( $compile(response.data)($scope) );
			    });*/
			    if(response.data.is_new_user == 'yes'){
			    	window.location.replace(response.data.redirect_url);
			    }else{
			    	location.reload();	
			    }
			    

			}else if(data_value == 'change'){
				window.location.replace(response.data.redirect_url);

				//location.reload();
				/*var data_params = {};
				var loading = '<div class="manage-listing-content-container" id="js-manage-listing-content-container"><div class="manage-listing-content-wrapper" style="height:100%;"><div class="manage-listing-content" id="js-manage-listing-content"><div><div class="row-space-top-6 basics-loading loading"></div></div></div></div></div>';

				$( "#ajax_container" ).html(loading);

				$http.get($(location).attr('href'), { data: data_params }).then(function(response) {
					$( "#ajax_container" ).html( $compile(response.data)($scope) );
			    });*/
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

}]);
