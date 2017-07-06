(function(window, undefined){

  var $ = window.jQuery;
  var document = window.document;

  $(function() {

    var $body = $(document.body);
    var $profileUploader = $('#profile_pic_uploader');

    $profileUploader.on('change', function(e) {
      var files = e.target.files;
      if(files.length === 0) {
        return false;
      }
      var file = files[0];
      if(file.size > 10 * 1024 * 1024) {
        alert('File size cannot be more than 10 MB.');
        $profileUploader.val('');
        return false;
      }
      if(file.type !== 'image/jpeg' && file.type !== 'image/png' && file.type !== 'image/gif') {
        alert('Please upload only image');
        $profileUploader.val('');
        return false;
      }
      var fd = new FormData();
      fd.append('profile_pic', file);
      fd.append('user_id', $profileUploader.data('user_id'));
      $('.profile-overlay').show();
      $('.profile-overlay .progress .bar').css({
        'width': '0%'
      });
      var xhr = new XMLHttpRequest();
      xhr.onreadystatechange = function() {
        if(xhr.readyState === 4 && xhr.status === 200) {
          console.log(xhr.response);
          $('.profile-overlay').hide();
          try {
            var response = JSON.parse(xhr.response);
            if(response.success) {
              $('#profile_picture').attr('src', response.src);
              $('.user-profile-image').css({
                'background-image': 'url(' + response.src + ')'
              });
              window.location.reload(true);
            } else {
              alert(response.error);
            }
          } catch (ex) {

          }
        }
      };
      xhr.upload.addEventListener('progress', function(e) {
        console.log(e.loaded, e.total);
        var percent = Math.round(e.loaded / e.total * 100);
        console.log(percent);
        $('.profile-overlay .progress .bar').css({
          'width': percent + '%'
        });
      }, false);
      xhr.open('POST', 'ajax_upload_profile_image');
      xhr.send(fd);
      console.log(file);
    });

    $('#trigger_profile_uploader').on('click', function(e) {
      $profileUploader.trigger('click');
      e.preventDefault();
      return false;
    });


    var overlay_selector = '.page-overlay';
    var popup_selector = '.dashboard-popup'
    var $pageOverlay = $(overlay_selector);
    var $popup = $(popup_selector);
    $popup.find('[data-action="close"]').on('click', function(e) {
      var $self = $(this);
      $self.closest(popup_selector).hide();
      var $visible = $popup.filter(':visible');
      if($visible.length < 1) {
        $pageOverlay.hide();
      }
      e.preventDefault();
      return false;
    });

    $body.on('click', '[data-popup]', function(e) {
      var $self = $(this);
      var id = $self.data('popup');
      var $target = $('#' + id);
      if($target.length > 0) {
        $pageOverlay.show();
        $target.show();
        $(window).trigger('popupdisplayed', {
          'id': id
        });
      }
      e.preventDefault();
      return false;
    });

    $body.on('submit', '#dashboard-change-number', function(e) {
      e.preventDefault();
      var $form = $(this);
      var $number = $form.find('#phone_number');
      var phone_number = $number.val();
      if(phone_number.match(/^(\+\d{1,3}[- ]?)?\d{10}$/) && ! (phone_number.match(/0{5,}/)) ){
				$.ajax({
          url: 'mobile_verification',
          method: 'post',
          data: {
            phone_number: phone_number,
            type: 'change'
          },
          dataType: 'json',
          beforeSend: function() {
            $('#dashboard-phone-ajax-status').text('Saving...').show();
          },
          success: function(response) {
            console.log(response);
            if(response.success) {
              $('#hidden_phone_number').val(phone_number);
              $('#hidden_phone_status').val('Pending');
              $form.hide();
              $('#dashboard-verify-number').show();
              $('#dashboard-phone-ajax-status').text('Saved').delay(2000).hide();
            }
          }
        });
			} else {
				alert('Please enter valid mobile number');
				return false;
			}
      return false;
    });

    $body.on('submit', '#dashboard-verify-number', function(e) {
      e.preventDefault();
      var $form = $(this);
			$.ajax({
        url: 'mobile_verification',
        method: 'post',
        data: {
          phone_id: $form.data('phone-id'),
          verification_code: $form.find('#otp').val(),
          type: 'verify'
        },
        dataType: 'json',
        beforeSend: function() {
          $('#dashboard-phone-ajax-status').text('Verifying...').show();
        },
        success: function(response) {
          console.log(response);
          if(response.success == 'true') {
            $('#dashboard-phone-ajax-status').text('Verification Successful');
            window.location.reload(true);
          } else {
            $('#dashboard-phone-ajax-status').text('Verification Unsuccessful');
            alert(response.msg);
          }
        }
      });
      return false;
    });

    $('#mobile-verification-change-number').on('click', function(e) {
      $('#dashboard-verify-number').hide();
      $('#dashboard-change-number').show();
      $('#dashboard-change-number').find('#phone_number').val('');
      $('#dashboard-change-number').find('#phone_code').trigger('change');
      e.preventDefault();
      return false;
    });

    $('#mobile-verification-resend-code').on('click', function(e) {
      var $self = $(this);
      $.ajax({
        url: 'mobile_verification',
        method: 'post',
        data: {
          phone_id: $self.data('phone-id'),
          type: 'resend'
        },
        dataType: 'json',
        beforeSend: function() {
          $('#dashboard-phone-ajax-status').text('Resending...').show();
        },
        success: function(response) {
          console.log(response);
          if(response.success == 'true') {
            $('#dashboard-verify-number').show();
            $('#dashboard-phone-ajax-status').text('Sent').delay(2000).hide();
          } else {
            alert(response.msg);
          }
        }
      });
      e.preventDefault();
      return false;
    });

    $(window).on('popupdisplayed', function(e, data) {
      var id = data.id;
      if(id === 'phone-verification') {
        var hidden_phone_number = $('#hidden_phone_number').val();
        var hidden_phone_status = $('#hidden_phone_status').val();
        console.log(hidden_phone_number);
        if(hidden_phone_number == '') {
          $('#dashboard-change-number').show();
        }
        if(hidden_phone_number != '' && hidden_phone_status == 'Pending') {
          $('#dashboard-verify-number').show();
        }
        
      }
    });

  });

})(window);
