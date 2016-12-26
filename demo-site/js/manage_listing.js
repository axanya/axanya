app.controller('manage_listing', ['$scope', '$http', '$compile', function ($scope, $http, $compile)
{

    $(document).on('click', '[data-track="welcome_modal_finish_listing"]', function ()
    {
        var data_params = {};

        data_params['started'] = 'Yes';

        var data = JSON.stringify(data_params);

        $http.post('update_rooms', {data: data}).then(function (response)
        {
            $('.welcome-new-host-modal').attr('aria-hidden', 'true');
        });
    });

    $(document).on('change', '[id^="basics-select-"], [id^="select-"]', function ()
    {
        var data_params = {};

        data_params[$(this).attr('name')] = $(this).val();

        var data = JSON.stringify(data_params);

        var saving_class = $(this).attr('data-saving');

        $('.' + saving_class + ' h5').text('Saving...');
        $('.' + saving_class).fadeIn();

        $http.post('update_rooms', {data: data}).then(function (response)
        {
            if (response.data.success == 'true')
            {
                $('.' + saving_class + ' h5').text('Saved!');
                $('.' + saving_class).fadeOut();
                $('#steps_count').text(response.data.steps_count);
                $scope.steps_count = response.data.steps_count;
            }
            if ($scope.beds != '' && $scope.bedrooms != '' && $scope.bathrooms != '' && $scope.bed_type != '')
            {
                var track = saving_class.substring(0, saving_class.length - 1);
                $('[data-track="' + track + '"] a div div .transition').removeClass('visible');
                $('[data-track="' + track + '"] a div div .transition').addClass('hide');
                $('[data-track="' + track + '"] a div div .pull-right .nav-icon').removeClass('hide');
            }
            if ($scope.cancel_policy != "" && typeof($scope.cancel_policy) != "undefined")
                $('[data-track="terms"] a div div div .icon-ok-alt').removeClass('hide');
            else
                $('[data-track="terms"] a div div div .icon-ok-alt').addClass('hide');


        });

        if ($(this).attr('name') == 'beds')
        {
            if ($(this).val() != '')
                $('#beds_show').show();
        }

    });

    $(document).on('blur', '[class^="overview-"]', function ()
    {
        var data_params = {};

        if ($(this).attr('name') == 'koshire')
        {
            if ($(this).is(":checked"))
                data_params[$(this).attr('name')] = 1;
            else
                data_params[$(this).attr('name')] = 0;
        }
        else
            data_params[$(this).attr('name')] = $(this).val();


        var data = JSON.stringify(data_params);

        if ($(this).val() != '')
        {
            $('.saving-progress h5').text('Saving...');
            $('.saving-progress').fadeIn();

            $('.name_required_msg').addClass('hide');
            $('.summary_required_msg').addClass('hide');
            $('.name_required').removeClass('invalid');
            $('.summary_required').removeClass('invalid');

            $http.post('update_rooms', {data: data}).then(function (response)
            {
                if (response.data.success == 'true')
                {
                    $('.saving-progress h5').text('Saved!');
                    $('.saving-progress').fadeOut();
                    $('#steps_count').text(response.data.steps_count);
                    $scope.steps_count = response.data.steps_count;
                }
                if ($scope.name != '' && $scope.summary != '')
                {
                    $('[data-track="description"] a div div .transition').removeClass('visible');
                    $('[data-track="description"] a div div .transition').addClass('hide');
                    $('[data-track="description"] a div div div .icon-ok-alt').removeClass('hide');
                }
            });
        }
        else
        {
            if ($(this).attr('name') == 'name')
            {
                $('.name_required').addClass('invalid');
                $('.name_required_msg').removeClass('hide');
            }
            else
            {
                $('.summary_required').addClass('invalid');
                $('.summary_required_msg').removeClass('hide');
            }
            $('[data-track="description"] a div div .transition').removeClass('hide');
            $('[data-track="description"] a div div .transition .icon').removeClass('hide');
            $('[data-track="description"] a div div div .icon-ok-alt').addClass('hide');
            $('[data-track="description"] a div div div .icon-ok-alt').removeClass('visible');
        }
    });

    $(document).on('click', '.nav-item a, .next_step a', function ()
    {
        if ($(this).attr('href') != '')
        {
            var data_params = {};
            var loading = '<div class="manage-listing-content-container" id="js-manage-listing-content-container"><div class="manage-listing-content-wrapper" style="height:100%;"><div class="manage-listing-content" id="js-manage-listing-content"><div><div class="row-space-top-6 basics-loading loading"></div></div></div></div></div>';

            $("#ajax_container").html(loading);

            $http.post($(this).attr('href').replace('manage-listing', 'ajax-manage-listing'), {data: data_params}).then(function (response)
            {
                $("#ajax_container").html($compile(response.data)($scope));
            });

            var ex_pathname = (window.location.href).split('/');
            var cur_step = $(ex_pathname).get(-1);

            $('#href_' + cur_step).attr('href', window.location.href);

            var ex_pathname = $(this).attr('href').split('/');
            var next_step = $(ex_pathname).get(-1);

            if (next_step != 'calendar')
            {
                /*$('.manage-listing-row-container').removeClass('has-collapsed-nav');*/
            }
            else
            {
                if ($('#room_status').val() != '')
                {
                    /*$('.manage-listing-row-container').addClass('has-collapsed-nav');
                     $('#js-manage-listing-nav').addClass('collapsed');*/
                }
            }

            if (cur_step == 'calendar' || next_step == 'calendar')
            {
                $http.post($(this).attr('href').replace('manage-listing', 'ajax-header'), {}).then(function (response)
                {
                    $("#ajax_header").html($compile(response.data)($scope));
                });
            }

            $scope.step = next_step;

            window.history.pushState({path: $(this).attr('href')}, '', $(this).attr('href'));

            return false;
        }
    });

    $(document).on('click', '#calendar_edit_cancel', function (event)
    {
        event.preventDefault();
        $('.calendar-sub-modal').attr('aria-hidden', 'true');
        $('.calendar-sub-modal').addClass('hide');
        $('li.tile').removeClass('other-day-selected first-day-selected last-day-selected selected');
        $('.tile-selection-handle').remove();
        $('.tile-selection-container').remove();
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

    $(document).on('click', '#show_long_term', function ()
    {
        $('#js-long-term-prices').removeClass('hide');
        $('#js-set-long-term-prices').addClass('hide');
    });
    $(document).on('click', '.calender_pop', function ()
    {
        var data_params = {};
        $('#address-flow-view .modal').fadeIn();
        $('#address-flow-view .modal').attr('aria-hidden', 'false');

        $http.post($(this).attr('href').replace('manage-listing', 'ajax-manage-listing'), {data: data_params}).then(function (response)
        {
            $("#address-flow-view .modal-content").css({'max-width': '80%'});
            $("#address-flow-view").css({'z-index': 10000, 'position': 'fixed'});
            $("#js-address-container").html($compile(response.data)($scope));
        });
        return false;
    });
    $(document).on('click', '#js-add-address, #js-edit-address', function ()
    {
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

        $('#js-address-container').addClass('enter_address');
        $('#address-flow-view .modal').fadeIn();
        $('#address-flow-view .modal').attr('aria-hidden', 'false');
        $http.post((window.location.href).replace('manage-listing', 'enter_address'), {data: data}).then(function (response)
        {
            $("#js-address-container").html($compile(response.data)($scope));
            initAutocomplete();
        });
    });

    $(document).on('click', '#js-next-btn', function ()
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
        if (!$scope.autocomplete_used)
            $scope.location_found = true;
        $('#js-address-container .panel').addClass('loading');
        $http.post((window.location.href).replace('manage-listing', 'location_not_found'), {data: data}).then(function (response)
        {
            $('#js-address-container .panel').removeClass('loading');
            $('#js-address-container').addClass('location_not_found');
            $("#js-address-container").html($compile(response.data)($scope));
            pin_address();
        });

    });

    /*$(document).on('click', '#js-next-btn2', function()
     {*/
    function pin_address()
    {
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
        $http.post((window.location.href).replace('manage-listing', 'verify_location'), {data: data}).then(function (response)
        {
            $('#js-address-container .panel').removeClass('loading');
            $('#js-address-container').addClass('location_not_found');
            $("#js-address-container").html($compile(response.data)($scope));
            setTimeout(function ()
            {
                initMap();
                confirm_pin();
            }, 100);
        });
    }

    /*});*/

    /*$(document).on('click', '#js-next-btn3', function()
     {*/

    function confirm_pin()
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

        $('#js-address-container .panel:first').addClass('loading');
        $http.post((window.location.href).replace('manage-listing', 'finish_address'), {data: data}).then(function (response)
        {
            $('#js-address-container .panel').removeClass('loading');

            $('.location-map-container-v2').removeClass('empty-map');
            $('.location-map-container-v2').addClass('map-spotlight-v2');

            $('.location-map-pin-v2').removeClass('moving');
            $('.location-map-pin-v2').addClass('set');
            $('.address-static-map img').remove();
            $('.address-static-map').append('<img width="820" height="275" src="https://maps.googleapis.com/maps/api/staticmap?size=820x275&amp;center=' + response.data.latitude + ',' + response.data.longitude + '&amp;zoom=15&amp;maptype=roadmap&amp;sensor=false&key=' + map_key + '">');

            $('.panel-body .text-center').remove();

            $('.panel-body address').removeClass('hide');
            $('.panel-body .js-edit-address-link').removeClass('hide');
            var address_line_2 = (response.data.address_line_2 != '') ? ' / ' + response.data.address_line_2 : '';
            $('.panel-body address span:nth-child(1)').text(response.data.address_line_1 + address_line_2);
            $('.panel-body address span:nth-child(2)').text(response.data.city + ' ' + response.data.state);
            $('.panel-body address span:nth-child(3)').text(response.data.postal_code);
            $('.panel-body address span:nth-child(4)').text(response.data.country_name);

            $('[data-track="location"] a div div .transition').removeClass('visible');
            $('[data-track="location"] a div div .transition').addClass('hide');
            $('[data-track="location"] a div div div .icon-ok-alt').removeClass('hide');

            $('#address-flow-view .modal').fadeOut();
            $('#address-flow-view .modal').attr('aria-hidden', 'true');
            $('#steps_count').text(response.data.steps_count);
            $scope.steps_count = response.data.steps_count;
            $scope.location_found = false;
        });
    }

    /*});*/


    $(document).on('click', '.modal-close, [data-behavior="modal-close"], .panel-close', function ()
    {
        $('.modal').fadeOut();
        $('.tooltip').css('opacity', '0');
        $('.tooltip').attr('aria-hidden', 'true');
        $('.modal').attr('aria-hidden', 'true');
    });

    $(document).on('click', '.modal1-close', function ()
    {
        $('#address-flow-view .modal').fadeOut();
        $('#address-flow-view .modal').attr('aria-hidden', 'true');
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

    if ($('#address_line_1').val() == '')
    {
        $('#js-next-btn').prop('disabled', true);
    }

    $(document).on('click', '#address_line_1', function ()
    {
        if ($(this).val() == '')
            $('#js-next-btn').prop('disabled', true);
        else
            $('#js-next-btn').prop('disabled', false);
    });


    var map, geocoder;

    function initMap()
    {

        geocoder = new google.maps.Geocoder();
        map = new google.maps.Map(document.getElementById('map'), {
            center: {lat: parseFloat($('#latitude2').val()), lng: parseFloat($('#longitude2').val())},
            zoom: 15,
            mapTypeId: google.maps.MapTypeId.ROADMAP,
            disableDefaultUI: true,
            zoomControl: true,
            zoomControlOptions: {
                style: google.maps.ZoomControlStyle.SMALL
            }
        });

        $('<div/>').addClass('verify-map-pin').appendTo(map.getDiv()).click(function ()
        {
        });

        map.addListener('dragend', function ()
        {
            geocoder.geocode({'latLng': map.getCenter()}, function (results, status)
            {
                if (status == google.maps.GeocoderStatus.OK)
                {
                    if (results[0])
                    {
                        fetchMapAddress(results[0]);
                        $('#js-next-btn3').prop('disabled', false);
                    }
                }
            });
            $('.verify-map-pin').removeClass('moving');
            $('.verify-map-pin').addClass('unset');
        });

        map.addListener('zoom_changed', function ()
        {
            geocoder.geocode({'latLng': map.getCenter()}, function (results, status)
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

        map.addListener('drag', function ()
        {
            $('.verify-map-pin').removeClass('unset');
            $('.verify-map-pin').addClass('moving');
        });

    }

    function fetchMapAddress(data)
    {
        if (data['types'] == 'street_address')
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

                if (addressType == 'street_number')
                    $scope.street_number = val;
                if (addressType == 'route')
                {
                    var street_address = $scope.street_number + ' ' + val;
                    $('#address_line_1').val($.trim(street_address));
                }
                if (addressType == 'postal_code')
                    $('#postal_code').val(val);
                if (addressType == 'locality')
                    $('#city').val(val);
                if (addressType == 'administrative_area_level_1')
                    $('#state').val(val);
                if (addressType == 'country')
                    $('#country').val(val);
            }
        }

        var address = $('#address_line_1').val();
        var latitude = place.geometry.location.lat();
        var longitude = place.geometry.location.lng();

        /*if($('#address_line_1').val() == '')
         $('#address_line_1').val($('#city').val());*/

        if ($('#address_line_1').val() == '')
            $('#js-next-btn').prop('disabled', true);
        else
            $('#js-next-btn').prop('disabled', false);

        $scope.latitude = latitude;
        $scope.longitude = longitude;
    }

    $(document).on('change', '[name="amenities"], [name="religious_amenities"], [id^="religious_amenities_extra_data_"]', function ()
    {
        var value = '';
        var religious_value = '';
        var religious_extra_value = {};
        if ($(this).val() == 0)
        {
            $('[name="amenities"]').prop('checked', false);
            $('[name="religious_amenities"]').prop('checked', false);
            $('.religious_amenity_extra_block').addClass('hide');
            $(this).prop('checked', true);
        }
        else
        {
            $('[name="amenities"][value="0"]').prop('checked', false);
        }
        if ($(this).attr('data-extra') == 'Yes ')
        {
            if ($(this).prop('checked'))
            {
                $(this).parent().parent().find('#religious_amenity_extra_' + $(this).val()).removeClass('hide');
            }
            else
            {
                $(this).parent().parent().find('#religious_amenity_extra_' + $(this).val()).addClass('hide');
            }
        }
        $('[name="amenities"]').each(function ()
        {
            if ($(this).prop('checked') == true)
            {
                value = value + $(this).val() + ',';
            }
        });

        $('[name="religious_amenities"]').each(function ()
        {
            if ($(this).prop('checked') == true)
            {
                religious_value = religious_value + $(this).val() + ',';
            }
        });

        $('[id^="religious_amenities_extra_data_"]').each(function ()
        {
            religious_extra_value[$(this).attr('name')] = $(this).val();
        });

        var saving_class = $(this).attr('data-saving');

        $('.' + saving_class + ' h5').text('Saving...');
        $('.' + saving_class).fadeIn();

        $http.post('update_amenities', {
            data: value,
            religious_data: religious_value,
            religious_extra_data: JSON.stringify(religious_extra_value)
        }).then(function (response)
        {
            if (response.data.success == 'true')
            {
                $('.' + saving_class + ' h5').text('Saved!');
                $('.' + saving_class).fadeOut();

                $('#steps_count').text(response.data.steps_count);
                $scope.steps_count = response.data.steps_count;

            }
        });
        $scope.amenities_status = $('[name="amenities"]:checked').length - 0 + $('[name="religious_amenities"]:checked').length;
        if ($scope.amenities_status != 0 && $scope.amenities_status != null && $scope.amenities_status != '' && $scope.amenities_status != '0')
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

    $(document).on('click', '#photo-uploader', function ()
    {
        $('#upload_photos').trigger('click');
    });

    $(document).on('click', '#js-photo-grid-placeholder', function ()
    {
        $('#upload_photos2').trigger('click');
    });

    function upload()
    {
        upload2();
        $(document).on("change", '#upload_photos', function ()
        {
            jQuery.ajaxFileUpload({
                url: "../../add_photos/" + $('#room_id').val(),
                secureuri: false,
                fileElementId: "upload_photos",
                dataType: "json",
                async: false,
                success: function (response)
                {
                    if (response.error_title)
                    {
                        $('#js-error .panel-header').text(response.error_title);
                        $('#js-error .panel-body').text(response.error_description);
                        $('.js-delete-photo-confirm').addClass('hide');
                        $('#js-error').attr('aria-hidden', false);
                    }
                    else
                    {
                        $scope.$apply(function ()
                        {
                            $scope.photos_list = response;
                            $('#photo_count').css('display', 'block');
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
        $(document).on("change", '#upload_photos2', function ()
        {
            jQuery.ajaxFileUpload({
                url: "../../add_photos/" + $('#room_id').val(),
                secureuri: false,
                fileElementId: "upload_photos2",
                dataType: "json",
                async: false,
                success: function (response)
                {
                    if (response.error_title)
                    {
                        $('#js-error .panel-header').text(response.error_title);
                        $('#js-error .panel-body').text(response.error_description);
                        $('.js-delete-photo-confirm').addClass('hide');
                        $('#js-error').attr('aria-hidden', false);
                    }
                    else
                    {
                        $scope.$apply(function ()
                        {
                            $scope.photos_list = response;
                            $('#photo_count').css('display', 'block');
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
        $http.get('photos_list', {}).then(function (response)
        {
            $scope.photos_list = response.data;
            if (response.data.length > 0)
            {
                $('#photo_count').css('display', 'block');
            }
        });
    }

    upload();

    /* ajaxfileupload */
    jQuery.extend({
        handleError: function (s, xhr, status, e)
        {
            if (s.error) s.error(xhr, status, e);
            else if (xhr.responseText) console.log(xhr.responseText);
        }
    });
    jQuery.extend({
        createUploadIframe: function (e, t)
        {
            var r = "jUploadFrame" + e;
            if (window.ActiveXObject)
            {
                var n = document.createElement("iframe");
                n.id = n.name = r, "boolean" == typeof t ? n.src = "javascript:false" : "string" == typeof t && (n.src = t)
            }
            else
            {
                var n = document.createElement("iframe");
                n.id = r, n.name = r
            }
            return n.style.position = "absolute", n.style.top = "-1000px", n.style.left = "-1000px", document.body.appendChild(n), n
        }, createUploadForm: function (e, t)
        {
            var r = "jUploadForm" + e, n = "jUploadFile" + e, o = jQuery('<form  action="" method="POST" name="' + r + '" id="' + r + '" enctype="multipart/form-data"></form>'), a = jQuery("#" + t), u = jQuery(a).clone();
            return jQuery(a).attr("id", n), jQuery(a).before(u), jQuery(a).appendTo(o), jQuery(o).css("position", "absolute"), jQuery(o).css("top", "-1200px"), jQuery(o).css("left", "-1200px"), jQuery(o).appendTo("body"), o
        }, ajaxFileUpload: function (e)
        {
            e = jQuery.extend({}, jQuery.ajaxSettings, e);
            var t = (new Date).getTime(), r = jQuery.createUploadForm(t, e.fileElementId), n = (jQuery.createUploadIframe(t, e.secureuri), "jUploadFrame" + t), o = "jUploadForm" + t;
            e.global && !jQuery.active++ && jQuery.event.trigger("ajaxStart");
            var a = !1, u = {};
            e.global && jQuery.event.trigger("ajaxSend", [u, e]);
            var c = function (t)
            {
                var o = document.getElementById(n);
                try
                {
                    o.contentWindow ? (u.responseText = o.contentWindow.document.body ? o.contentWindow.document.body.innerHTML : null, u.responseXML = o.contentWindow.document.XMLDocument ? o.contentWindow.document.XMLDocument : o.contentWindow.document) : o.contentDocument && (u.responseText = o.contentDocument.document.body ? o.contentDocument.document.body.innerHTML : null, u.responseXML = o.contentDocument.document.XMLDocument ? o.contentDocument.document.XMLDocument : o.contentDocument.document)
                } catch (c)
                {
                    jQuery.handleError(e, u, null, c)
                }
                if (u || "timeout" == t)
                {
                    a = !0;
                    var d;
                    try
                    {
                        if (d = "timeout" != t ? "success" : "error", "error" != d)
                        {
                            var l = jQuery.uploadHttpData(u, e.dataType);
                            e.success && e.success(l, d), e.global && jQuery.event.trigger("ajaxSuccess", [u, e])
                        }
                        else jQuery.handleError(e, u, d)
                    } catch (c)
                    {
                        d = "error", jQuery.handleError(e, u, d, c)
                    }
                    e.global && jQuery.event.trigger("ajaxComplete", [u, e]), e.global && !--jQuery.active && jQuery.event.trigger("ajaxStop"), e.complete && e.complete(u, d), jQuery(o).unbind(), setTimeout(function ()
                    {
                        try
                        {
                            jQuery(o).remove(), jQuery(r).remove()
                        } catch (t)
                        {
                            jQuery.handleError(e, u, null, t)
                        }
                    }, 100), u = null
                }
            };
            e.timeout > 0 && setTimeout(function ()
            {
                a || c("timeout")
            }, e.timeout);
            try
            {
                var r = jQuery("#" + o);
                jQuery(r).attr("action", e.url), jQuery(r).attr("method", "POST"), jQuery(r).attr("target", n), r.encoding ? r.encoding = "multipart/form-data" : r.enctype = "multipart/form-data", jQuery(r).submit()
            } catch (d)
            {
                jQuery.handleError(e, u, null, d)
            }
            return window.attachEvent ? document.getElementById(n).attachEvent("onload", c) : document.getElementById(n).addEventListener("load", c, !1), {
                abort: function ()
                {
                }
            }
        }, uploadHttpData: function (r, type)
        {
            var data = !type;
            return data = "xml" == type || data ? r.responseXML : r.responseText, "script" == type && jQuery.globalEval(data), "json" == type && eval("data = " + data), "html" == type && jQuery("<div>").html(data).evalScripts(), data
        }
    });

    $scope.delete_photo = function (item, id)
    {
        $('#js-error .panel-header').text("Delete Photo");
        $('#js-error .panel-body').text("Are you sure you wish to delete this photo? It's a nice one!");
        $('.js-delete-photo-confirm').removeClass('hide');
        $('#js-error').attr('aria-hidden', false);
        $('.js-delete-photo-confirm').attr('data-id', id);
        var index = $scope.photos_list.indexOf(item);
        $('.js-delete-photo-confirm').attr('data-index', index);
    };

    $(document).on('click', '.js-delete-photo-confirm', function ()
    {
        var index = $(this).attr('data-index');
        $http.post('delete_photo', {photo_id: $(this).attr('data-id')}).then(function (response)
        {
            if (response.data.success == 'true')
            {
                $scope.photos_list.splice(index, 1);
                $('#js-error').attr('aria-hidden', true);
                // photos_list();
                $('#steps_count').text(response.data.steps_count);
                $scope.steps_count = response.data.steps_count;

            }
        });
    });

    $scope.$watchCollection('photos_list', function (newCol, oldCol, scope)
    {

        if ($scope.photos_list != undefined)
        {
            if ($scope.photos_list.length != 0)
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
    $(document).ready(function ()
    {
        var pathname = document.getElementById("href_calendar").href;
        if ($scope.rooms_status_value == 'Listed' && $(location).attr('href') == pathname)
        {
            $('#address-flow-view').html('');
            console.log('listed');
            /*$('.manage-listing-row-container').removeClass('page-container');
             $('#js-manage-listing-nav').removeClass('pos-fix');
             $('#ajax_container').removeClass('mar-left-cont');*/
        }
    });

    $scope.$watch('steps_count', function (value)
    {

        if ($scope.steps_count != undefined)
        {
            if ($scope.steps_count == 10)
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

    $scope.over_first_photo = function (index)
    {
        if (index == 0)
            $('#js-first-photo-text').removeClass('invisible');
    };

    $scope.out_first_photo = function (index)
    {
        if (index == 0)
            $('#js-first-photo-text').addClass('invisible');
    };

    $scope.keyup_highlights = function (id, value)
    {
        $http.post('photo_highlights', {photo_id: id, data: value}).then(function (response)
        {

        });
    };

    $(document).on('change', '[id^="price-select-"]', function ()
    {
        var data_params = {};

        data_params[$(this).attr('name')] = $(this).val();
        data_params['night'] = $('#price-night').val();

        var data = JSON.stringify(data_params);

        var saving_class = $(this).attr('data-saving');

        $('.' + saving_class + ' h5').text('Saving...');
        $('.' + saving_class).fadeIn();

        $http.post('update_price', {data: data}).then(function (response)
        {
            if (response.data.success == 'true')
            {
                $('[data-error="price"]').text('');
                $('.input-prefix').html(response.data.currency_symbol);
                $('.' + saving_class + ' h5').text('Saved!');
                $('.' + saving_class).fadeOut();
                $('#steps_count').text(response.data.steps_count);
                $scope.steps_count = response.data.steps_count;
                $('html, body').animate({scrollTop: $('#js-list-space-button').offset().top + 1000}, 'slow');
            }
            else
            {
                $('[data-error="price"]').html(response.data.msg);
                $('.' + saving_class).fadeOut();
            }
        });
    });
// $(document).on('keypress', '.autosubmit-text[id^="price-"]', function()
// {
//  setTimeout(function(){
// 	 $('.autosubmit-text[id^="price-"]').trigger('blur');
// },300);
// });
    $(document).on('blur', '.autosubmit-text[id^="price-"]', function ()
    {

        var data_params = {};

        data_params[$(this).attr('name')] = $(this).val();

        data_params['currency_code'] = $('#price-select-currency_code').val();

        var data = JSON.stringify(data_params);

        var saving_class = $(this).attr('data-saving');

        $('.' + saving_class + ' h5').text('Saving...');

        if ($('#price-night').val() != 0)
        {
            $('.' + saving_class).fadeIn();
            $http.post('update_price', {data: data}).then(function (response)
            {
                if (response.data.success == 'true')
                {
                    $('[data-error="price"]').text('');
                    $('.input-prefix').html(response.data.currency_symbol);
                    $('.' + saving_class + ' h5').text('Saved!');
                    $('.' + saving_class).fadeOut();
                    $('#steps_count').text(response.data.steps_count);
                    $scope.steps_count = response.data.steps_count;

                }
                else
                {
                    $('[data-error="price"]').html(response.data.msg);
                    $('.' + saving_class).fadeOut();
                }
                if ($('#price-night').val() != 0)
                {
                    $('#price-night-old').val($('#price-night').val());
                    $('[data-track="pricing"] a div div .transition').removeClass('visible');
                    $('[data-track="pricing"] a div div .transition').addClass('hide');
                    $('[data-track="pricing"] a div div div .icon-ok-alt').removeClass('hide');
                    $('html, body').animate({scrollTop: $('#js-list-space-button').offset().top + 1000}, 'slow');
                }

            });
        }
        else
        {
            if ($('#price-night-old').val() == 0)
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

    $(document).on('change', '[id$="_checkbox"]', function ()
    {
        if ($(this).prop('checked') == false)
        {
            var data_params = {};

            var id = $(this).attr('id');
            var selector = '[data-checkbox-id="' + id + '"] > div > div > div > input';

            $(selector).val('');

            if (id == 'price_for_extra_person_checkbox')
            {
                $('[data-checkbox-id="' + id + '"] > div > div > #guests-included-select > div > select').val(1);

                data_params[$('[data-checkbox-id="' + id + '"] > div > div > #guests-included-select > div > select').attr('name')] = 1;
            }

            data_params[$(selector).attr('name')] = $(selector).val();

            var data = JSON.stringify(data_params);

            var saving_class = $(selector).attr('data-saving');

            $('.' + saving_class + ' h5').text('Saving...');
            $('.' + saving_class).fadeIn();

            $http.post('update_price', {data: data}).then(function (response)
            {
                if (response.data.success == 'true')
                {
                    $('.input-prefix').html(response.data.currency_symbol);
                    $('.' + saving_class + ' h5').text('Saved!');
                    $('.' + saving_class).fadeOut();
                    $('#steps_count').text(response.data.steps_count);
                    $scope.steps_count = response.data.steps_count;
                    $('html, body').animate({scrollTop: $('#js-list-space-button').offset().top + 1000}, 'slow');
                }
            });
        }
    });

    $(document).on('click', '[id^="available-"]', function ()
    {
        var data_params = {};

        var value = $(this).attr('data-slug');

        data_params['calendar_type'] = value.charAt(0).toUpperCase() + value.slice(1);
        ;

        var data = JSON.stringify(data_params);

        $('.saving-progress h5').text('Saving...');

        $('.saving-progress').fadeIn();

        $http.post('update_rooms', {data: data}).then(function (response)
        {
            if (response.data.success == 'true')
            {
                $scope.selected_calendar = value;
                $('.selected_calendar_type').removeClass('selected_calendar_type');
                $('[data-slug="' + value + '"]').addClass('selected_calendar_type');
                $('.saving-progress h5').text('Saved!');
                $('.saving-progress').fadeOut();
                $('#steps_count').text(response.data.steps_count);
                $scope.steps_count = response.data.steps_count;
            }

            if ($scope.selected_calendar == 'always')
            {
                //$('#calendar-edit-container').addClass('hide');
                $('.calendar-modal').attr('aria-hidden', 'true');
                $('.calendar-modal').addClass('hide');
            }
            else
            {
                //$('#calendar-edit-container').removeClass('hide');
                //$('html, body').animate({ scrollTop: $('#js-list-space-button').offset().top+1000 }, 'slow');
                $('.calendar-modal').attr('aria-hidden', 'false');
                $('.calendar-modal').removeClass('hide');
                $('.calendar-modal').show();
            }
            $('[data-track="calendar"] a div div .transition').removeClass('visible');
            $('[data-track="calendar"] a div div .transition').addClass('hide');
            $('[data-track="calendar"] a div div .pull-right .nav-icon').removeClass('hide');
        });
    });

    $(document).on('mouseover', '[id^="available-"]', function ()
    {
        $('[id^="available-"]').removeClass('selected_calendar_type');
    });

    $(document).on('mouseout', '[id^="available-"]', function ()
    {
        $('[id="available-' + $scope.selected_calendar + '"]').addClass('selected_calendar_type');
    });

    var ex_pathname = (window.location.href).split('/');
    $scope.step = $(ex_pathname).get(-1);
    photos_list();

    $(document).on('click', '#finish_step', function ()
    {
        $http.get('rooms_steps_status', {}).then(function (response)
        {
            for (var key in response.data)
            {
                if (response.data[key] == '0')
                {
                    $('#href_' + key).trigger('click');
                    return false;
                }
            }
        });
    });

    $(document).on('click', '#js-list-space-button', function ()
    {
        var data_params = {};

        $('.load_show').removeClass('hide');

        data_params['status'] = 'Listed';

        var data = JSON.stringify(data_params);

        $http.post('update_rooms', {data: data}).then(function (response)
        {
            $http.get('rooms_data', {}).then(function (response)
            {
                $('#symbol_finish').html(response.data.symbol);
                $scope.popup_photo_name = response.data.photo_name;
                $scope.popup_night = response.data.night;
                $scope.popup_room_name = response.data.name;
                $scope.popup_room_type_name = response.data.room_type_name;
                $scope.popup_property_type_name = response.data.property_type_name;
                $scope.popup_state = response.data.state;
                $scope.popup_country = response.data.country_name;
                $('.finish-modal').attr('aria-hidden', 'false');
                $('.finish-modal').removeClass('hide');
                $('#js-list-space-button').remove();
                $('.load_show').addClass('hide');
            });
        });
    });

    $(document).on('blur', '[id^="help-panel"] > textarea', function ()
    {
        var data_params = {};

        var input_name = $(this).attr('name');

        data_params[input_name] = $(this).val();

        var data = JSON.stringify(data_params);

        $('.saving-progress h5').text('Saving...');

        if (input_name != 'neighborhood_overview' && input_name != 'transit')
            $('.help-panel-saving').fadeIn();
        else
            $('.help-panel-neigh-saving').fadeIn();

        $http.post('update_description', {data: data}).then(function (response)
        {
            if (response.data.success == 'true')
            {
                $scope.steps_count = response.data.steps_count;
                $('.saving-progress h5').text('Saved!');

                if (input_name != 'neighborhood_overview' && input_name != 'transit')
                    $('.help-panel-saving').fadeOut();
                else
                    $('.help-panel-neigh-saving').fadeOut();
            }
            if ($scope.space != "" || $scope.access != "" || $scope.interaction != "" || $scope.notes != "" || $scope.house_rules != "" || $scope.neighborhood_overview != "" || $scope.transit != "")
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

    $(document).on('click', '[id^="help-panel"] > input[type="checkbox"]', function ()
    {
        var data_params = {};

        var input_name = $(this).attr('name');

        if ($(this).is(':checked'))
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

        if (input_name != 'neighborhood_overview' && input_name != 'transit')
            $('.help-panel-saving').fadeIn();
        else
            $('.help-panel-neigh-saving').fadeIn();

        $http.post('update_description', {data: data}).then(function (response)
        {
            if (response.data.success == 'true')
            {
                $scope.steps_count = response.data.steps_count;
                $('.saving-progress h5').text('Saved!');

                if (input_name != 'neighborhood_overview' && input_name != 'transit')
                    $('.help-panel-saving').fadeOut();
                else
                    $('.help-panel-neigh-saving').fadeOut();
            }
        });
    });


    $(document).on('click', '#collapsed-nav', function ()
    {
        if ($('#js-manage-listing-nav').hasClass('collapsed'))
            $('#js-manage-listing-nav').removeClass('collapsed');
        else
            $('#js-manage-listing-nav').addClass('collapsed');
    });

    $(document).on('click', '.month-nav', function ()
    {
        var month = $(this).attr('data-month');
        var year = $(this).attr('data-year');

        var data_params = {};

        data_params['month'] = month;
        data_params['year'] = year;

        var data = JSON.stringify(data_params);

        $('.ui-datepicker-backdrop').removeClass('hide');
        $('.spinner-next-to-month-nav').addClass('loading');

        $http.post($(this).attr('href').replace('manage-listing', 'ajax-manage-listing'), {data: data}).then(function (response)
        {
            $("#calendar-edit-container").html($compile($(response.data).find('#calendar-edit-container').html())($scope));
            $('.spinner-next-to-month-nav').removeClass('loading');
            $('.ui-datepicker-backdrop').addClass('hide');
        });
        return false;
    });

    $(document).on('change', '#calendar_dropdown', function ()
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

        $http.post($(this).attr('data-href').replace('manage-listing', 'ajax-manage-listing'), {data: data}).then(function (response)
        {
            $('.ui-datepicker-backdrop').addClass('hide');
            $('.spinner-next-to-month-nav').removeClass('loading');
            //$( "#js-address-container" ).html( $compile(response.data)($scope) );
            $("#calendar-edit-container").html($compile($(response.data).find('#calendar-edit-container').html())($scope));
        });
        return false;
    });

    /*Start - Calendar Date Selection*/

    $(document).on('click', '.tile', function ()
    {
        if (!$(this).hasClass('other-day-selected') && !$(this).hasClass('selected') && !$(this).hasClass('tile-previous'))
        {
            var current_tile = $(this).attr('id');
            $('#' + current_tile).addClass('first-day-selected last-day-selected selected');
            $('<div class="tile-selection-container"><div class="tile-selection"></div></div>').insertBefore('#' + current_tile + '> .date');

            var clicked_li = $(this).index();

            var start_top = $(this).position().top + 50, start_left = $(this).position().left, end_top = start_top, end_left = start_left + 125;

            $('.days-container').append('<div><div style="left:' + start_left + 'px;top:' + start_top + 'px;" class="tile-selection-handle tile-handle-left"><div class="tile-selection-handle__inner"><span class="tile-selection-handle__ridge"></span><span class="tile-selection-handle__ridge"></span><span class="tile-selection-handle__ridge"></span></div></div></div><div><div style="left: ' + end_left + 'px; top: ' + end_top + 'px;" class="tile-selection-handle tile-handle-right"><div class="tile-selection-handle__inner"><span class="tile-selection-handle__ridge"></span><span class="tile-selection-handle__ridge"></span><span class="tile-selection-handle__ridge"></span></div></div></div>');

            $('.tile').each(function ()
            {
                if (current_tile != $(this).attr('id'))
                    $(this).addClass('other-day-selected');
            });

            calendar_edit_form();
        }
    });

    var selected_li_status = 0;
    var direction = '';

    $(document).on('mousedown', '.tile-selection-handle', function ()
    {
        selected_li_status = 1;
        if ($(this).hasClass('tile-handle-left'))
            direction = 'left';
        else
            direction = 'right';
    });

    var oldx = 0;
    var oldy = 0;

    $(document).on('mouseover', '.tile', function (e)
    {
        if (e.pageX > oldx && direction == 'right')
        {
            if (selected_li_status == 1 && !$(this).hasClass('tile-previous'))
            {
                var id = $(this).attr('id');
                $('#' + id).removeClass('other-day-selected');
                $('#' + id).addClass('selected');

                if (!$('#' + $(this).attr('id') + ' > div').hasClass('tile-selection-container'))
                {
                    $('<div class="tile-selection-container"><div class="tile-selection"></div></div>').insertBefore('#' + $(this).attr('id') + '> .date');
                }

                var last_index = $('.selected').last().index();
                var first_index = $('.selected').first().index();

                $('*').removeClass('first-day-selected last-day-selected');
                $('.selected').first().addClass('first-day-selected');
                $('.selected').last().addClass('last-day-selected');

                for (var i = (first_index + 1); i <= last_index; i++)
                {
                    var id = $(".days-container > .list-unstyled > li:nth-child(" + i + ")").attr('id');
                    $('#' + id).addClass('selected');
                    $('#' + id).removeClass('other-day-selected');

                    if (!$('#' + id + ' > div').hasClass('tile-selection-container'))
                    {
                        $('<div class="tile-selection-container"><div class="tile-selection"></div></div>').insertBefore('#' + id + '> .date');
                    }
                }
            }
        }
        else if (e.pageX < oldx && direction == 'right')
        {
            if (selected_li_status == 1 && !$(this).hasClass('tile-previous'))
            {
                var id = $(".days-container > .list-unstyled > li:nth-child(" + $(this).index() + ")").next().next().attr('id');

                var id2 = $(".days-container > .list-unstyled > li:nth-child(" + $(this).index() + ")").next().attr('id');

                $('*').removeClass('first-day-selected last-day-selected');
                $('.selected').first().addClass('first-day-selected');
                $('.selected').last().addClass('last-day-selected');

                $('#' + id).removeClass('selected');
                $('#' + id).addClass('other-day-selected');
                $(this).removeClass('selected');
                $(this).addClass('other-day-selected');
                $('#' + id2 + ' > div.tile-selection-container').remove();
            }

            if ($('.selected').length == 0)
            {
                selected_li_status = 0;
                $('.tile').each(function ()
                {
                    $(this).removeClass('other-day-selected last-day-selected first-day-selected');
                    $('.tile-selection-container').remove();
                    $('.tile-selection-handle').remove();
                });
            }
        }

        if (e.pageX > oldx && direction == 'left')
        {
            if (selected_li_status == 1 && !$(this).hasClass('tile-previous'))
            {
                var id = $(".days-container > .list-unstyled > li:nth-child(" + $(this).index() + ")").attr('id');

                var id2 = $(".days-container > .list-unstyled > li:nth-child(" + $(this).index() + ")").attr('id');

                $('*').removeClass('first-day-selected last-day-selected');
                $('.selected').first().addClass('first-day-selected');
                $('.selected').last().addClass('last-day-selected');

                $('#' + id).removeClass('selected');
                $('#' + id).addClass('other-day-selected');
                $(this).removeClass('selected');
                $(this).addClass('other-day-selected');
                $('#' + id2 + ' > div.tile-selection-container').remove();
            }
        }
        else if (e.pageX < oldx && direction == 'left')
        {
            if (selected_li_status == 1 && !$(this).hasClass('tile-previous'))
            {
                var id = $(".days-container > .list-unstyled > li:nth-child(" + $(this).index() + ")").next().next().attr('id');

                var id2 = $(".days-container > .list-unstyled > li:nth-child(" + $(this).index() + ")").next().attr('id');

                $('#' + id).addClass('selected');
                $('#' + id).removeClass('other-day-selected');
                $(this).addClass('selected');
                $(this).removeClass('other-day-selected');

                var last_index = $('.selected').last().index();
                var first_index = $('.selected').first().index();

                for (var i = (first_index + 1); i <= last_index; i++)
                {
                    var id = $(".days-container > .list-unstyled > li:nth-child(" + i + ")").attr('id');
                    $('#' + id).addClass('selected');
                    $('#' + id).removeClass('other-day-selected');

                    if (!$('#' + id + ' > div').hasClass('tile-selection-container'))
                    {
                        $('<div class="tile-selection-container"><div class="tile-selection"></div></div>').insertBefore('#' + id + '> .date');
                    }
                    $('#' + id).removeClass('first-day-selected last-day-selected');
                }

                $('*').removeClass('first-day-selected last-day-selected');
                $('.selected').first().addClass('first-day-selected');
                $('.selected').last().addClass('last-day-selected');

                if (!$('#' + id + ' > div').hasClass('tile-selection-container'))
                {
                    $('<div class="tile-selection-container"><div class="tile-selection"></div></div>').insertBefore('#' + id + '> .date');
                }
            }

            if ($('.selected').length == 0)
            {
                selected_li_status = 0;
                $('.tile').each(function ()
                {
                    $(this).removeClass('other-day-selected last-day-selected first-day-selected');
                    $('.tile-selection-container').remove();
                    $('.tile-selection-handle').remove();
                });
            }
        }

        if (e.pageY > oldy && direction == 'right')
        {
            if (selected_li_status == 1 && !$(this).hasClass('tile-previous'))
            {
                var id = $(this).attr('id');
                $('#' + id).removeClass('other-day-selected');
                $('#' + id).addClass('selected');

                if (!$('#' + $(this).attr('id') + ' > div').hasClass('tile-selection-container'))
                {
                    $('<div class="tile-selection-container"><div class="tile-selection"></div></div>').insertBefore('#' + $(this).attr('id') + '> .date');
                }

                var last_index = $('.selected').last().index();
                var first_index = $('.selected').first().index();

                $('*').removeClass('first-day-selected last-day-selected');
                $('.selected').first().addClass('first-day-selected');
                $('.selected').last().addClass('last-day-selected');

                for (var i = (first_index + 1); i <= last_index; i++)
                {
                    var id = $(".days-container > .list-unstyled > li:nth-child(" + i + ")").attr('id');
                    $('#' + id).addClass('selected');
                    $('#' + id).removeClass('other-day-selected');

                    if (!$('#' + id + ' > div').hasClass('tile-selection-container'))
                    {
                        $('<div class="tile-selection-container"><div class="tile-selection"></div></div>').insertBefore('#' + id + '> .date');
                    }
                }
            }
        }

        if (e.pageY < oldy && direction == 'right')
        {
            if (selected_li_status == 1 && !$(this).hasClass('tile-previous'))
            {
                if (!$(this).hasClass('selected'))
                {
                    var id = $(this).attr('id');
                    var last_index = $(this).index();
                    var first_index = $('.selected').first().index();

                    $('.selected').addClass('other-day-selected');
                    $('.selected').removeClass('selected');

                    for (var i = (first_index + 1); i <= (last_index + 1); i++)
                    {
                        var id = $(".days-container > .list-unstyled > li:nth-child(" + i + ")").attr('id');
                        $('#' + id).addClass('selected');
                        $('#' + id).removeClass('other-day-selected');

                        if (!$('#' + id + ' > div').hasClass('tile-selection-container'))
                        {
                            $('<div class="tile-selection-container"><div class="tile-selection"></div></div>').insertBefore('#' + id + '> .date');
                        }
                    }
                    $('*').removeClass('first-day-selected last-day-selected');
                    $('.selected').first().addClass('first-day-selected');
                    $('.selected').last().addClass('last-day-selected');
                }
            }
        }

        if (e.pageY < oldy && direction == 'left')
        {
            if (selected_li_status == 1 && !$(this).hasClass('tile-previous'))
            {
                var id = $(this).attr('id');
                $('#' + id).removeClass('other-day-selected');
                $('#' + id).addClass('selected');

                if (!$('#' + $(this).attr('id') + ' > div').hasClass('tile-selection-container'))
                {
                    $('<div class="tile-selection-container"><div class="tile-selection"></div></div>').insertBefore('#' + $(this).attr('id') + '> .date');
                }

                var last_index = $('.selected').last().index();
                var first_index = $('.selected').first().index();

                $('*').removeClass('first-day-selected last-day-selected');
                $('.selected').first().addClass('first-day-selected');
                $('.selected').last().addClass('last-day-selected');

                for (var i = (first_index + 1); i <= last_index; i++)
                {
                    var id = $(".days-container > .list-unstyled > li:nth-child(" + i + ")").attr('id');
                    $('#' + id).addClass('selected');
                    $('#' + id).removeClass('other-day-selected');

                    if (!$('#' + id + ' > div').hasClass('tile-selection-container'))
                    {
                        $('<div class="tile-selection-container"><div class="tile-selection"></div></div>').insertBefore('#' + id + '> .date');
                    }
                }
            }
        }

        if (e.pageY > oldy && direction == 'left')
        {
            if (selected_li_status == 1 && !$(this).hasClass('tile-previous'))
            {
                if (!$(this).hasClass('selected'))
                {
                    var id = $(this).attr('id');
                    var first_index = $(this).index();
                    var last_index = $('.selected').last().index();

                    $('.selected').addClass('other-day-selected');
                    $('.selected').removeClass('selected');

                    for (var i = (first_index + 1); i <= (last_index + 1); i++)
                    {
                        var id = $(".days-container > .list-unstyled > li:nth-child(" + i + ")").attr('id');
                        $('#' + id).addClass('selected');
                        $('#' + id).removeClass('other-day-selected');

                        if (!$('#' + id + ' > div').hasClass('tile-selection-container'))
                        {
                            $('<div class="tile-selection-container"><div class="tile-selection"></div></div>').insertBefore('#' + id + '> .date');
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

    $(document).on('mouseup', '.tile-selection-handle, .tile', function ()
    {
        selected_li_status = 0;

        var last_id = $('.selected').last().attr('id');
        var first_id = $('.selected').first().attr('id');

        $('*').removeClass('first-day-selected last-day-selected');
        $('.selected').first().addClass('first-day-selected');
        $('.selected').last().addClass('last-day-selected');

        var position = $('#' + last_id).position();
        var first_position = $('#' + first_id).position();

        var start_top = first_position.top + 50, start_left = first_position.left, end_top = position.top + 50, end_left = position.left + 125;

        $('.days-container > div > .tile-selection-handle:last').remove();
        $('.days-container > div > .tile-selection-handle:first').remove();

        $('.days-container').append('<div><div style="left:' + start_left + 'px;top:' + start_top + 'px;" class="tile-selection-handle tile-handle-left"><div class="tile-selection-handle__inner"><span class="tile-selection-handle__ridge"></span><span class="tile-selection-handle__ridge"></span><span class="tile-selection-handle__ridge"></span></div></div></div><div><div style="left: ' + end_left + 'px; top: ' + end_top + 'px;" class="tile-selection-handle tile-handle-right"><div class="tile-selection-handle__inner"><span class="tile-selection-handle__ridge"></span><span class="tile-selection-handle__ridge"></span><span class="tile-selection-handle__ridge"></span></div></div></div>');

        calendar_edit_form();
    });

    function calendar_edit_form()
    {
        //$('.calendar-edit-form').removeClass('hide');

        $('.calendar-sub-modal').attr('aria-hidden', 'false');
        $('.calendar-sub-modal').removeClass('hide');

        if ($('.selected').length > 1)
        {
            $('.calendar-edit-form > form > .panel-body').first().show();
        }
        else
        {
            $('.calendar-edit-form > form > .panel-body').first().show();
        }

        if ($('.selected').hasClass('status-b'))
            $scope.segment_status = 'not available';
        else
            $scope.segment_status = 'available';

        var start_date = $('.first-day-selected').first().attr('id');
        var end_date = $('.last-day-selected').last().attr('id');

        $scope.calendar_edit_price = $('#' + start_date).find('.price > span:last').text();
        $('.sidebar-price').val($scope.calendar_edit_price);
        $scope.notes = $('#' + start_date).find('.tile-notes-text').text();
        $scope.isAddNote = ($scope.notes != '') ? true : false;

        $("#calendar-edit-start").datepicker({
            dateFormat: "dd-mm-yy",
            minDate: 0,
            onSelect: function (date)
            {
                var checkout = $("#calendar-edit-start").datepicker('getDate');
                $('#calendar-edit-end').datepicker('option', 'minDate', checkout);
                $('#calendar-edit-start').datepicker('option', 'maxDate', checkout);
                setTimeout(function ()
                {
                    $('#calendar-edit-end').datepicker("show");
                }, 20);
            }
        });

        $('#calendar-edit-end').datepicker({
            dateFormat: "dd-mm-yy",
            minDate: 1,
            onClose: function ()
            {
                var checkin = $("#calendar-edit-start").datepicker('getDate');
                var checkout = $('#calendar-edit-end').datepicker('getDate');
                $('#calendar-edit-end').datepicker('option', 'minDate', checkout);
                $('#calendar-edit-start').datepicker('option', 'maxDate', checkout);
                if (checkout <= checkin)
                {
                    var minDate = $('#calendar-edit-end').datepicker('option', 'minDate');
                    $('#calendar-edit-end').datepicker('setDate', minDate);
                }
            }
        });

        $('#calendar-edit-start').datepicker('setDate', change_format(start_date));
        $('#calendar-edit-end').datepicker('setDate', change_format(end_date));

        $('#calendar-edit-start').datepicker('option', 'maxDate', change_format(start_date));
        $('#calendar-edit-end').datepicker('option', 'minDate', change_format(end_date));
    }

    function change_format(date)
    {
        var split_date = date.split('-');
        return split_date[2] + '-' + split_date[1] + '-' + split_date[0];
    }

    $scope.calendar_edit_submit = function (href)
    {
        $http.post('calendar_edit', {
            status: $scope.segment_status,
            start_date: $('#calendar-edit-start').val(),
            end_date: $('#calendar-edit-end').val(),
            price: $scope.calendar_edit_price,
            notes: $scope.notes
        }).then(function (response)
        {
            var year_month = $('#calendar_dropdown').val();
            var year = year_month.split('-')[0];
            var month = year_month.split('-')[1];

            var data_params = {};

            data_params['month'] = month;
            data_params['year'] = year;

            var data = JSON.stringify(data_params);

            // $('.ui-datepicker-backdrop').removeClass('hide');
            // $('.spinner-next-to-month-nav').addClass('loading');
            $('.load_show').removeClass('hide');

            $http.post(href.replace('manage-listing', 'ajax-manage-listing'), {data: data}).then(function (response)
            {
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
                $("#calendar-edit-container").html($compile($(response.data).find('#calendar-edit-container').html())($scope));
                $('.calendar-sub-modal').attr('aria-hidden', 'true');
                $('.calendar-sub-modal').addClass('hide');
            });
            return false;
        });
    };

    /*End - Calendar Date Selection*/

    $(document).on('change', '#availability-dropdown > div > select', function ()
    {
        var data_params = {};

        data_params['status'] = $(this).val();

        var data = JSON.stringify(data_params);

        $http.post('update_rooms', {data: data}).then(function (response)
        {
            if (data_params['status'] == 'Unlisted')
            {
                $('#availability-dropdown > i').addClass('dot-danger');
                $('#availability-dropdown > i').removeClass('dot-success');
            }
            else if (data_params['status'] == 'Listed')
            {
                $('#availability-dropdown > i').removeClass('dot-danger');
                $('#availability-dropdown > i').addClass('dot-success');
            }
        });
    });

    $(document).on('click', '#export_button', function ()
    {
        $('#export_popup').attr('aria-hidden', 'false');
    });

    $(document).on('click', '#import_button', function ()
    {
        $('#import_popup').attr('aria-hidden', 'false');
    });

    $scope.booking_select = function (value)
    {
        var data_params = {};

        data_params['booking_type'] = value;

        var data = JSON.stringify(data_params);

        $http.post('update_rooms', {data: data}).then(function (response)
        {
            if (response.data.success == 'true')
            {
                // $('#before_select').addClass('hide');
                // $('#'+value).removeClass('hide');
                $('[data-track="how-guests-book"] a div div .transition').removeClass('visible');
                $('[data-track="how-guests-book"] a div div .transition').addClass('hide');
                $('[data-track="how-guests-book"] a div div div .icon-ok-alt').removeClass('hide');
            }

        });
    }

    $scope.booking_change = function (value)
    {
        var data_params = {};

        data_params['booking_type'] = '';

        var data = JSON.stringify(data_params);

        $http.post('update_rooms', {data: data}).then(function (response)
        {
            if (response.data.success == 'true')
            {
                $('#before_select').removeClass('hide');
                $('#' + value).addClass('hide');
            }
        });
    }

}]);

$('.calendar-popup').click(function ()
{
    //    $('.manage-listing-row-container').removeClass('page-container');
    //    $('#js-manage-listing-nav').removeClass('pos-fix');
    //    $('#ajax_container').removeClass('mar-left-cont');
    //    if($('#js-manage-listing-nav').hasClass('collapsed') === true){
    // $('#js-manage-listing-nav').removeClass('collapsed');
    //    }

});

$(document).on('click', '.nav-min', function ()
{
    $('#js-manage-listing-nav').removeClass('pos-fix');
    $('#js-manage-listing-nav').addClass('listing-nav-sm')

});
$(document).on('click', '.list-nav-link a', function ()
{
    $('.listing-nav-sm').removeClass('collapsed');
});
/*var pathname = document.getElementById("href_calendar").href; 
 if($(location).attr('href')== pathname){
 $('.manage-listing-row-container').removeClass('page-container');
 $('#js-manage-listing-nav').removeClass('pos-fix');
 }*/
$(window).resize(function ()
{
    if ($(window).width() < 760)
    {
        $('#js-manage-listing-nav').removeClass('pos-fix');
        $('#js-manage-listing-nav').addClass('listing-nav-sm')
    }
    else
    {
        $('#js-manage-listing-nav').removeClass('pos-fix');
        $('#js-manage-listing-nav').addClass('listing-nav-sm')
    }
});

$(document).on('click', '#href_pricing', function ()
{
    $('#js-manage-listing-nav').addClass('pos-fix');
    $('.manage-listing-row-container').addClass('page-container');
    $('#ajax_container').addClass('mar-left-cont');
});
$(document).on('click', '#href_terms', function ()
{
    $('#js-manage-listing-nav').addClass('pos-fix');
    $('.manage-listing-row-container').addClass('page-container');
    $('#ajax_container').addClass('mar-left-cont');
});
$(document).on('click', '#remove-manage', function ()
{
    $('#js-manage-listing-nav').addClass('pos-fix');
    $('.manage-listing-row-container').addClass('page-container');
    $('#ajax_container').addClass('mar-left-cont');
});
$(document).on('click', '#href_booking', function ()
{
    $('#js-manage-listing-nav').addClass('pos-fix');
    $('.manage-listing-row-container').addClass('page-container');
    $('#ajax_container').addClass('mar-left-cont');
});
$(document).on('click', '#href_basics', function ()
{
    $('#js-manage-listing-nav').addClass('pos-fix');
    $('.manage-listing-row-container').addClass('page-container');
    $('#ajax_container').addClass('mar-left-cont');
});
$(document).on('click', '#href_description', function ()
{
    $('#js-manage-listing-nav').addClass('pos-fix');
    $('.manage-listing-row-container').addClass('page-container');
    $('#ajax_container').addClass('mar-left-cont');
});
$(document).on('click', '#href_location', function ()
{
    $('#js-manage-listing-nav').addClass('pos-fix');
    $('.manage-listing-row-container').addClass('page-container');
    $('#ajax_container').addClass('mar-left-cont');
});
$(document).on('click', '#href_amenities', function ()
{
    $('#js-manage-listing-nav').addClass('pos-fix');
    $('.manage-listing-row-container').addClass('page-container');
    $('#ajax_container').addClass('mar-left-cont');
});
$(document).on('click', '#href_photos', function ()
{

    $('#js-manage-listing-nav').addClass('pos-fix');
    $('.manage-listing-row-container').addClass('page-container');
    $('#ajax_container').addClass('mar-left-cont');
});
$(document).on('click', '#href_details', function ()
{
    $('#js-manage-listing-nav').addClass('pos-fix');
    $('.manage-listing-row-container').addClass('page-container');
    $('#ajax_container').addClass('mar-left-cont');
});
$(document).on('click', '#href_guidebook', function ()
{
    $('#js-manage-listing-nav').addClass('pos-fix');
    $('.manage-listing-row-container').addClass('page-container');
    $('#ajax_container').addClass('mar-left-cont');
});
