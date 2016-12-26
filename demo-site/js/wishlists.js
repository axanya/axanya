app.controller('wishlists', ['$scope', '$http', '$filter', function ($scope, $http, $filter)
{

    $('.create').click(function ()
    {
        $('.modal-transitions').removeClass('hide');
    });

    $('.cancel').click(function (event)
    {
        event.preventDefault();
        $('.modal-transitions').addClass('hide');
        event.preventDefault();
    });

    $('#map').click(function ()
    {
        $('.results-map').show();
        $('.results-list').hide();
        $('#map').prop('disabled', true);
        $('#list').prop('disabled', false);
        initialize();
    });

    $('#list').click(function ()
    {
        $('.results-list').show();
        $('.results-map').hide();
        $('#list').prop('disabled', true);
        $('#map').prop('disabled', false);
    });

    function initialize()
    {
        var myOptions = {
            zoom: 10,
            scrollwheel: false,
            mapTypeId: google.maps.MapTypeId.ROADMAP
        };
        map = new google.maps.Map(document.getElementById("results_map"), myOptions);
        setMarkers(map, locations);
    }

    function setMarkers(map, locations)
    {

        var marker, i;
        var bounds = new google.maps.LatLngBounds();
        var infowindow = new google.maps.InfoWindow();

        for (i = 0; i < locations.length; i++)
        {
            var title = locations[i]['rooms']['name'];
            var lat = locations[i]['rooms']['rooms_address']['latitude'];
            var long = locations[i]['rooms']['rooms_address']['longitude'];
            var address = locations[i]['rooms']['rooms_address']['city'];
            var image = APP_URL + '/images/' + locations[i]['rooms']['photo_name'];
            var list_id = locations[i]['room_id'];
            var user_id = locations[i]['rooms']['user_id'];
            var price = locations[i]['rooms_price']['night'];
            var currency_code = locations[i]['rooms_price']['currency_code'];
            var currency_symbol = locations[i]['rooms_price']['currency']['symbol'];
            var wishlist_img = APP_URL + '/images/' + locations[i]['rooms']['photo_name'];
            var booking_type = locations[i]['rooms']['booking_type'];

            latlngset = new google.maps.LatLng(lat, long);

            var content = '<div id="info_window_' + list_id + '" class="listing listing-map-popover" data-price="' + currency_symbol + '" data-id="' + list_id + '" data-user="' + user_id + '" data-url="/rooms/' + list_id + '" data-name="' + title + '" data-lng="' + long + '" data-lat="' + lat + '"><div class="panel-image listing-img">';
            content += '<a class="media-photo media-cover" target="listing_' + list_id + '" href="' + APP_URL + '/rooms/' + list_id + '"><div class="listing-img-container media-cover text-center"><img id="marker_image_' + list_id + '" rooms_image = "" alt="' + title + '" class="img-responsive-height" data-current="0" src="' + image + '"></div></a>';
            content += '<div class="target-prev target-control block-link marker_slider"  data-room_id="' + list_id + '"><i class="icon icon-chevron-left icon-size-2 icon-white"></i></div><a class="link-reset panel-overlay-bottom-left panel-overlay-label panel-overlay-listing-label" target="listing_' + list_id + '" href="' + APP_URL + 'rooms/' + list_id + '"><div>';

            instant_book = '';

            if (booking_type == 'instant_book')
                instant_book = '<span aria-label="Book Instantly" data-behavior="tooltip" class="h3 icon-beach"><i class="icon icon-instant-book icon-flush-sides"></i></span>';

            content += '<sup class="h6 text-contrast">' + currency_symbol + '</sup><span class="h3 text-contrast price-amount">' + price + '</span><sup class="h6 text-contrast"></sup>' + instant_book + '</div></a><div class="target-next target-control marker_slider block-link" data-room_id="' + list_id + '"><i class="icon icon-chevron-right icon-size-2 icon-white"></i></div></div>';
            content += '<div class="panel-body panel-card-section"><div class="media"><h3 class="h5 listing-name text-truncate row-space-top-1" itemprop="name" title="' + title + '">' + title + '</a></h3>';

            var marker = new google.maps.Marker({
                map: map,
                title: content,
                position: latlngset,
                icon: getMarkerImage('normal'),
            });

            bounds.extend(marker.position);

            google.maps.event.addListener(marker, 'click', function ()
            {
                infowindow.setContent(this.title);
                infowindow.open(map, this);
            });
        }

        map.fitBounds(bounds);

        var listener = google.maps.event.addListener(map, "idle", function ()
        {
            map.setZoom(3);
            google.maps.event.removeListener(listener);
        });

    }

    function getMarkerImage(type)
    {
        var image = 'map-pin-set-3460214b477748232858bedae3955d81.png';

        if (type == 'hover')
            image = 'hover-map-pin-set-3460214b477748232858bedae3955d81.png';

        var gicons = new google.maps.MarkerImage(APP_URL + "/images/" + image,
            new google.maps.Size(50, 50),
            new google.maps.Point(0, 0),
            new google.maps.Point(9, 20));

        return gicons;

    }

    $(document).on('click', '.marker_slider', function ()
    {
        var rooms_id = $(this).attr("data-room_id");
        var dataurl = $("#marker_image_" + rooms_id + ",#wishlist_image_" + rooms_id).attr("rooms_image");
        var img_url = $("#marker_image_" + rooms_id + ",#wishlist_image_" + rooms_id).attr("src");
        if ($.trim(dataurl) == '')
        {
            $(this).parent().addClass("loading");
            $http.post(APP_URL + '/rooms_photos', {rooms_id: rooms_id})
                .then(function (response)
                {
                    angular.forEach(response.data, function (obj)
                    {
                        if ($.trim(dataurl) == '')
                        {
                            dataurl = obj['name'];
                        }
                        else
                            dataurl = dataurl + ',' + obj['name'];
                    });

                    $("#marker_image_" + rooms_id + ",#wishlist_image_" + rooms_id).attr("rooms_image", dataurl);
                    var img_explode = img_url.split('rooms/' + rooms_id + '/');

                    var all_image = dataurl.split(',');
                    var rooms_img_count = all_image.length;
                    var i = 0;
                    var set_img_no = '';
                    angular.forEach(all_image, function (img)
                    {
                        if ($.trim(img) == $.trim(img_explode[1]))
                        {
                            set_img_no = i;
                        }
                        i++;
                    });
                    if ($(this).is(".target-prev") == true)
                    {
                        var cur_img = set_img_no - 1;
                        var count = rooms_img_count - 1;
                    }
                    else
                    {
                        var cur_img = set_img_no + 1;
                        var count = 0;
                    }

                    if (typeof (all_image[cur_img]) != 'undefined' && $.trim(all_image[cur_img]) != "null")
                    {
                        var img = all_image[cur_img];
                    }
                    else
                    {

                        var img = all_image[count];
                    }

                    var set_img_url = img_explode[0] + 'rooms/' + rooms_id + '/' + img;

                    $(".panel-image").removeClass("loading");
                    $('.listing_slideshow_thumb_view').removeClass("loading");
                    $("#marker_image_" + rooms_id + ",#wishlist_image_" + rooms_id).attr("src", set_img_url);
                });
        }
        else
        {
            $(this).parent().addClass("loading");
            var img_explode = img_url.split('rooms/' + rooms_id + '/');

            var all_image = dataurl.split(',');
            var rooms_img_count = all_image.length;
            var i = 0;
            var set_img_no = '';
            angular.forEach(all_image, function (img)
            {
                if ($.trim(img) == $.trim(img_explode[1]))
                {
                    set_img_no = i;
                }
                i++;
            });
            if ($(this).is(".target-prev") == true)
            {
                var cur_img = set_img_no - 1;
                var count = rooms_img_count - 1;
            }
            else
            {
                var cur_img = set_img_no + 1;
                var count = 0;
            }

            if (typeof (all_image[cur_img]) != 'undefined' && $.trim(all_image[cur_img]) != "null")
            {
                var img = all_image[cur_img];
            }
            else
            {
                var img = all_image[count];
            }
            var set_img_url = img_explode[0] + 'rooms/' + rooms_id + '/' + img;

            $(".panel-image").removeClass("loading");
            $('.listing_slideshow_thumb_view').removeClass("loading");
            $("#marker_image_" + rooms_id + ",#wishlist_image_" + rooms_id).attr("src", set_img_url);

        }

    });

    $('.edit-button').click(function ()
    {
        $('.show_view').hide();
        $('.edit_view').show();
    });

    $('.edit_view .cancel').click(function ()
    {
        $('.show_view').show();
        $('.edit_view').hide();
    });

    $('.edit_view .delete').click(function ()
    {
        if (confirm("Are you sure?"))
        {
            window.location.href = APP_URL + "/delete_wishlist/" + wishlist_id;
        }
    });

    $('.remove_listing_button').click(function ()
    {
        $('.wishlist-loading-indicator').show();
        var room_id = $(this).data('room_id');
        $http.post(APP_URL + '/remove_saved_wishlist/' + wishlist_id, {room_id: $(this).data('room_id')}).then(function (response)
        {
            $('.wishlist-loading-indicator').hide();
            $('#li_' + room_id).slideUp();
            $('#wishlist_count').text(response.data);
        });
    });

    $('.note-container').submit(function (event)
    {
        event.preventDefault();
        $('.wishlist-loading-indicator').show();
        $http.post(APP_URL + '/add_note_wishlist/' + wishlist_id, {
            room_id: $(this).data('room_id'),
            note: $('#note_' + $(this).data('room_id')).val()
        }).then(function (response)
        {
            $('.wishlist-loading-indicator').hide();
        });
    });

    $('.share-envelope-btn').click(function ()
    {
        $('.modal-container').show();
    });

    $('.modal-close').click(function ()
    {
        $('.modal-container').hide();
    });

// $(document).on('click', '[id^="wishlist-widget-icon-"]', function() {
    $('[id^="wishlist-widget-icon-"]').click(function ()
    {
        if (typeof USER_ID == 'object')
        {
            window.location.href = APP_URL + '/login';
            return false;
        }
        var name = $(this).data('name');
        var img = $(this).data('img');
        var address = $(this).data('address');
        var host_img = $(this).data('host_img');
        $scope.room_id = $(this).data('room_id');

        $('.background-listing-img').css('background-image', 'url(' + img + ')');
        $('.host-profile-img').attr('src', host_img);
        $('.wl-modal-listing__name').text(name);
        $('.wl-modal-listing__address').text(address);
        $('.wl-modal-footer__input').val(address);

        $('.wl-modal__modal').removeClass('hide');
        $('.wl-modal__col:nth-child(2)').addClass('hide');
        $('.row-margin-zero').append('<div id="wish-list-signup-container" style="overflow-y:auto;" class="col-lg-5 wl-modal__col-collapsible"> <div class="loading wl-modal__col"> </div> </div>');
        $http.get(APP_URL + "/wishlist_list?id=" + $(this).data('room_id'), {}).then(function (response)
        {
            $('#wish-list-signup-container').remove();
            $('.wl-modal__col:nth-child(2)').removeClass('hide');
            $scope.wishlist_list = response.data;
        });
    });

    $scope.wishlist_row_select = function (index)
    {

        $http.post(APP_URL + "/save_wishlist", {
            data: $scope.room_id,
            wishlist_id: $scope.wishlist_list[index].id,
            saved_id: $scope.wishlist_list[index].saved_id
        }).then(function (response)
        {
            if (response.data == 'null')
                $scope.wishlist_list[index].saved_id = null;
            else
                $scope.wishlist_list[index].saved_id = response.data;
        });

        if ($('#wishlist_row_' + index).hasClass('text-dark-gray'))
            $scope.wishlist_list[index].saved_id = null;
        else
            $scope.wishlist_list[index].saved_id = 1;
    };

    $(document).on('submit', '.wl-modal-footer__form', function (event)
    {
        event.preventDefault();
        $('.wl-modal__col:nth-child(2)').addClass('hide');
        $('.row-margin-zero').append('<div id="wish-list-signup-container" style="overflow-y:auto;" class="col-lg-5 wl-modal__col-collapsible"> <div class="loading wl-modal__col"> </div> </div>');
        $http.post(APP_URL + "/wishlist_create", {
            data: $('.wl-modal-footer__input').val(),
            id: $scope.room_id
        }).then(function (response)
        {
            $('.wl-modal-footer__form').addClass('hide');
            $('#wish-list-signup-container').remove();
            $('.wl-modal__col:nth-child(2)').removeClass('hide');
            $scope.wishlist_list = response.data;
            event.preventDefault();
        });
        event.preventDefault();
    });

    $('.wl-modal__modal-close').click(function ()
    {
        var null_count = $filter('filter')($scope.wishlist_list, {saved_id: null});

        if (null_count.length == $scope.wishlist_list.length)
            $('#wishlist-widget-' + $scope.room_id).prop('checked', false);
        else
            $('#wishlist-widget-' + $scope.room_id).prop('checked', true);

        $('.wl-modal__modal').addClass('hide');
        $('.wl-modal__modal').show();
    });

}]);