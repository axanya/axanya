app.directive('postsPagination', function ()
{
    return {
        restrict: 'E',
        template: '<ul class="pagination">' +
        '<li ng-show="currentPage != 1"><a href="javascript:void(0)" ng-click="search_result(1)">&laquo;</a></li>' +
        '<li ng-show="currentPage != 1"><a href="javascript:void(0)" ng-click="search_result(currentPage-1)">&lsaquo; Prev</a></li>' +
        '<li ng-repeat="i in range" ng-class="{active : currentPage == i}">' +
        '<a href="javascript:void(0)" ng-click="search_result(i)">{{i}}</a>' +
        '</li>' +
        '<li ng-show="currentPage != totalPages"><a href="javascript:void(0)" ng-click="search_result(currentPage+1)">Next &rsaquo;</a></li>' +
        '<li ng-show="currentPage != totalPages"><a href="javascript:void(0)" ng-click="search_result(totalPages)">&raquo;</a></li>' +
        '</ul>'
    };
}).controller('search-page', ['$scope', '$http', '$compile', '$filter', function ($scope, $http, $compile, $filter)
{

    $scope.current_date = new Date();

    $scope.totalPages = 0;
    $scope.currentPage = 1;
    $scope.range = [];
    $scope.places_info = [];

    $(document).on('click', '[id^="wishlist-widget-icon-"]', function ()
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
    });

    $(document).ready(function ()
    {
        localStorage.removeItem("map_lat_long");
        var room_type = [];
        $('.room-type:checked').each(function (i)
        {
            room_type[i] = $(this).val();
        });

        var property_type = [];
        $('.property_type:checked').each(function (i)
        {
            property_type[i] = $(this).val();
        });

        var amenities = [];
        $('.amenities:checked').each(function (i)
        {
            amenities[i] = $(this).val();
        });
        $('.search_tag').addClass('hide');

        if (room_type != '')
        {
            $('.room-type_tag').removeClass('hide');
        }
        if (amenities != '')
        {
            $('.amenities_tag').removeClass('hide');
        }
        if (property_type != '')
        {
            $('.property_type_tag').removeClass('hide');
        }

        var location_val = $("#location").val();
        $("#header-search-form").val(location_val);
        $("#slider-3").slider({
            range: true,
            min: min_slider_price,
            max: max_slider_price,
            values: [min_slider_price_value, max_slider_price_value],
            slide: function (event, ui)
            {
                $("#min_value").val(ui.values[0]);
                $("#min_text").val(ui.values[0]);

                if (max_slider_price == ui.values[1])
                {
                    $("#max_text").html(ui.values[1] + '+');
                }
                else
                {
                    $("#max_text").html(ui.values[1]);
                }
                $("#max_value").val(ui.values[1]);
            },
            stop: function (event, ui)
            {
                $("#min_value").val(ui.values[0]);
                $("#min_text").html(ui.values[0]);
                if (max_slider_price == ui.values[1])
                {
                    $("#max_text").html(ui.values[1] + '+');
                }
                else
                {
                    $("#max_text").html(ui.values[1]);
                }
                $("#max_value").val(ui.values[1]);
                $scope.search_result();
            }
        }).find(".ui-slider-handle").removeClass("ui-slider-handle").removeClass("ui-state-default").removeClass("ui-corner-all").addClass("airslide-handle");

        $('#slider-3').removeClass('ui-slider').removeClass('ui-slider-horizontal').removeClass('ui-widget').removeClass('ui-widget-content').removeClass('ui-corner-all');

        $('#slider-3').find('.ui-slider-range').removeClass('ui-slider-range').removeClass('ui-widget-header').removeClass('ui-corner-all').addClass('airslide-progress');

        $('#slider-3').append('<div class="airslide-background"></div>');

        $('.airslide-progress').css('z-index', '1');

        $('.show-more').click(function ()
        {
            $(this).children('span').toggleClass('hide');
            $(this).parent().parent().children('div').children().toggleClass('filters-more');
        });


        $("#more_filters").click(function ()
        {

            $(".toggle-group").css("display", "block");
            $(".toggle-hide").css("display", "none");
        });
    });

    var location1 = getParameterByName('location');

    var current_url = (window.location.href).replace('/s', '/searchResult');
    var current_url_places = (window.location.href).replace('/s', '/places');

    pageNumber = 1;

    if (pageNumber === undefined)
    {
        pageNumber = '1';
    }

    //$('.search-results').addClass('loading');
    $http.get(current_url).then(function (response)
    {
        // $scope.room_result = response;
        //$('.search-results').removeClass('loading');
        $scope.room_result = response.data;
        $scope.totalPages = response.data.last_page;
        $scope.currentPage = response.data.current_page;
        // Pagination Range
        var pages = [];

        for (var i = 1; i <= response.data.last_page; i++)
        {
            pages.push(i);
        }

        $scope.range = pages;
        $http.get(current_url_places).then(function (response)
        {

            $scope.place_result = response.data;

            // marker_places(response.data);
            marker($scope.room_result);
        });
        $('[data-toggle="tooltip"]').tooltip();
        // marker(response.data);
    });
    // $http.get(current_url_places).then(function(response) {

    //       $scope.place_result = response.data;

    //        marker_places(response.data);
    //     });

    $scope.on_mouse = function (index)
    {
        //markers[index].setIcon(getMarkerImage('hover'));
        if (markers[index] != undefined)
        {
            mark = markers[index].div_;
            $(mark).addClass('hover');
        }
    };
    $scope.out_mouse = function (index)
    {
        //markers[index].setIcon(getMarkerImage('normal'));
        if (markers[index] != undefined)
        {
            mark = markers[index].div_;
            $(mark).removeClass('hover');
        }
    };

    $scope.search_result = function (pageNumber)
    {

        if (pageNumber === undefined)
        {
            pageNumber = '1';
        }

        var max_price = $("#max_value").val();
        var min_price = $("#min_value").val();

        var room_type = [];
        $('.room-type:checked').each(function (i)
        {
            room_type[i] = $(this).val();
        });
        //alert(room_type);
        if (room_type == '')
        {
            $('.room-type_tag').addClass('hide');
        }

        var property_type = [];
        $('.property_type:checked').each(function (i)
        {
            property_type[i] = $(this).val();
        });
        if (property_type == '')
        {
            $('.property_type_tag').addClass('hide');
        }
        var amenities = [];
        $('.amenities:checked').each(function (i)
        {
            amenities[i] = $(this).val();
        });
        if (amenities == '')
        {
            $('.amenities_tag').addClass('hide');
        }
        var checkin = $('#checkin').val();
        var checkout = $('#checkout').val();

        var min_beds = $("#map-search-min-beds").val();
        var min_bathrooms = $("#map-search-min-bathrooms").val();
        var min_bedrooms = $("#map-search-min-bedrooms").val();
        var guest_select = $("#guest-select").val();

        if ($.trim(localStorage.getItem("map_lat_long")) != 'null')
        {
            var map_details = localStorage.getItem("map_lat_long");
        }
        else
        {
            var map_details = "";
        }

        setGetParameter('room_type', room_type);
        setGetParameter('property_type', property_type);
        setGetParameter('amenities', amenities);
        setGetParameter('checkin', checkin);
        setGetParameter('checkout', checkout);
        setGetParameter('guest', guest_select);
        setGetParameter('beds', min_beds);
        setGetParameter('bathrooms', min_bathrooms);
        setGetParameter('bedrooms', min_bedrooms);
        setGetParameter('min_price', min_price);
        setGetParameter('max_price', max_price);
        setGetParameter('page', pageNumber);
        $('.search_tag').addClass('hide');

        if (room_type != '')
        {
            $('.room-type_tag').removeClass('hide');
        }
        if (amenities != '')
        {
            $('.amenities_tag').removeClass('hide');
        }
        if (property_type != '')
        {
            $('.property_type_tag').removeClass('hide');
        }

        var location1 = getParameterByName('location');

        //$('.search-results').addClass('loading');
        $http.post('searchResult?page=' + pageNumber, {
            location: location1,
            min_price: min_price,
            max_price: max_price,
            amenities: amenities,
            property_type: property_type,
            room_type: room_type,
            beds: min_beds,
            bathrooms: min_bathrooms,
            bedrooms: min_bedrooms,
            checkin: checkin,
            checkout: checkout,
            guest: guest_select,
            map_details: map_details
        })
            .then(function (response)
            {

                //  $scope.room_result = response;
                // alert(response.data[0].rooms_address.city);
                $scope.room_result = response.data;
                $scope.checkin = checkin;
                $scope.checkout = checkout;
                $scope.totalPages = response.data.last_page;
                $scope.currentPage = response.data.current_page;
                // Pagination Range
                var pages = [];

                for (var i = 1; i <= response.data.last_page; i++)
                {
                    pages.push(i);
                }

                $scope.range = pages;

                var place_types = '';
                $('.place_types:checked').each(function ()
                {
                    place_types += $(this).val() + ',';
                });

                //$('.search-results').removeClass('loading');
                $('.load_show').removeClass('hide');
                $http.post('places', {
                    location: location1,
                    map_details: map_details,
                    types: (place_types) ? place_types : 'empty'
                })
                    .then(function (response)
                    {

                        $scope.place_result = response.data;

                        // marker_places(response.data);
                        marker($scope.room_result);
                        $('.load_show').addClass('hide');
                    });
                // marker(response.data);
            });

        // $http.post('places', { location: location1, map_details: map_details })
        //    .then(function(response) {

        //      $scope.place_result = response.data;

        //       marker_places(response.data);
        //    });
    };

    $scope.apply_filter = function ()
    {
        $(".toggle-hide").css("display", "block");
        $(".toggle-group").css("display", "none");

        $scope.search_result();
    };

    $(document).on('click', '#restaurant, #mikvahs, #synagogues, #kosher_vendor', function ()
    {
        var place_types = '';
        $('.place_types:checked').each(function ()
        {
            place_types += $(this).val() + ',';
        });

        if ($.trim(localStorage.getItem("map_lat_long")) != 'null')
            var map_details = localStorage.getItem("map_lat_long");
        else
            var map_details = "";

        $('#map_canvas').addClass('loading');
        $http.post('places', {
            location: location1,
            map_details: map_details,
            types: (place_types) ? place_types : 'empty'
        })
            .then(function (response)
            {
                $('#map_canvas').removeClass('loading');
                $scope.place_result = response.data;
                marker($scope.room_result);
            });

    });

    $scope.remove_filter = function (parameter)
    {
        $('.' + parameter).removeAttr('checked');
        var paramName = parameter.replace('-', '_');
        var paramValue = '';
        setGetParameter(paramName, paramValue)
        $('.' + parameter + '_tag').addClass('hide');

        $scope.search_result();
    };
    function setGetParameter(paramName, paramValue)
    {
        var url = window.location.href;

        if (url.indexOf(paramName + "=") >= 0)
        {
            var prefix = url.substring(0, url.indexOf(paramName));
            var suffix = url.substring(url.indexOf(paramName));
            suffix = suffix.substring(suffix.indexOf("=") + 1);
            suffix = (suffix.indexOf("&") >= 0) ? suffix.substring(suffix.indexOf("&")) : "";
            url = prefix + paramName + "=" + paramValue + suffix;
        }
        else
        {
            if (url.indexOf("?") < 0)
                url += "?" + paramName + "=" + paramValue;
            else
                url += "&" + paramName + "=" + paramValue;
        }
        history.pushState(null, null, url);
    }

    function getParameterByName(name)
    {
        name = name.replace(/[\[]/, "\\[").replace(/[\]]/, "\\]");
        var regex = new RegExp("[\\?&]" + name + "=([^&#]*)"),
            results = regex.exec(location.search);
        return results === null ? "" : decodeURIComponent(results[1].replace(/\+/g, " "));
    }

    initializeMap();
    function initializeMap()
    {
        // Create the autocomplete object, restricting the search
        // to geographical location types.
        autocomplete = new google.maps.places.Autocomplete(
            /** @type {HTMLInputElement} */(document.getElementById('header-search-form')),
            {types: ['geocode']});
        google.maps.event.addListener(autocomplete, 'place_changed', function ()
        {
            var location = $('#header-search-form').val();
            var locations = location.replace(" ", "+");
            setGetParameter('location', locations);
            var place = autocomplete.getPlace();
            var latitude = place.geometry.location.lat();
            var longitude = place.geometry.location.lng();
            $scope.cLat = latitude;
            $scope.cLong = longitude;
            $scope.search_result();
            initialize();
            // window.location.href = window.location.href;
        });
    }


    $scope.zoom = '';
    $scope.cLat = '';
    $scope.cLong = '';
    var html = '';
    var markers = [];
    var map;
    var infowindow = new google.maps.InfoWindow(
        {
            content: html
        });

    initialize();

    function initialize()
    {

        if ($scope.zoom == '')
        {
            var zoom_set = 10;
        }
        else
        {
            var zoom_set = $scope.zoom;
        }
        if ($("#lat").val() == 0)
        {
            var zoom_set = 1;
        }
        if ($scope.cLat == '' && $scope.cLong == '')
        {
            var latitude = $("#lat").val();
            var longitude = $("#long").val();
        }
        else
        {
            var latitude = $scope.cLat;
            var longitude = $scope.cLong;
        }

        var myCenter = new google.maps.LatLng(latitude, longitude);

        var mapProp = {
            scrollwheel: false,
            center: myCenter,
            zoom: zoom_set,
            zoomControl: true,
            zoomControlOptions: {
                position: google.maps.ControlPosition.LEFT_TOP,
                style: google.maps.ZoomControlStyle.SMALL
            },
            mapTypeControl: false,
            streetViewControl: false,
            navigationControl: false,

        }
        map = new google.maps.Map(document.getElementById("map_canvas"), mapProp);
        google.maps.event.addListener(map, 'click', function ()
        {
            if (infoBubble != undefined)
            {
                if (infoBubble.isOpen())
                {
                    infoBubble.close();
                    infoBubble = new InfoBubble({
                        maxWidth: 3000
                    });
                }
            }
        });
        var homeControlDiv = document.createElement('div');
        var homeControl = new HomeControl(homeControlDiv, map);
//  homeControlDiv.index = 1;
        map.controls[google.maps.ControlPosition.LEFT_TOP].push(homeControlDiv);

        google.maps.event.addListener(map, 'dragend', function ()
        {
            if (infoBubble != undefined)
            {
                if (infoBubble.isOpen())
                {
                    infoBubble.close();
                    infoBubble = new InfoBubble({
                        maxWidth: 3000
                    });
                }
            }
            $scope.zoom = map.getZoom();

            var zoom = map.getZoom();
            var bounds = map.getBounds();
            var minLat = bounds.getSouthWest().lat();
            var minLong = bounds.getSouthWest().lng();
            var maxLat = bounds.getNorthEast().lat();
            var maxLong = bounds.getNorthEast().lng();
            var cLat = bounds.getCenter().lat();
            var cLong = bounds.getCenter().lng();

            $scope.cLat = bounds.getCenter().lat();
            $scope.cLong = bounds.getCenter().lng();

            var map_lat_long = zoom + '~' + bounds + '~' + minLat + '~' + minLong + '~' + maxLat + '~' + maxLong + '~' + cLat + '~' + cLong;

            localStorage.setItem("map_lat_long", map_lat_long);
            var redo_search = '';
            // $('#redo_search:checked').each(function(i){
            redo_search = $('#redo_search:checked').val();
            // });
            //alert(redo_search);
            if (redo_search == 'true')
            {
                $scope.search_result();
            }
            else
            {
                $(".map-auto-refresh").addClass('hide');
                $(".map-manual-refresh").removeClass('hide');
            }
        });

        google.maps.event.addListener(map, 'click', function (e)
        {
            if (infoBubble.isOpen())
            {
                infoBubble.close();
                infoBubble = new InfoBubble({
                    maxWidth: 3000
                });
            }
        });

        google.maps.event.addListener(map, 'zoom_changed', function ()
        {
            if (infoBubble != undefined)
            {
                if (infoBubble.isOpen())
                {
                    infoBubble.close();
                    infoBubble = new InfoBubble({
                        maxWidth: 3000
                    });
                }
            }
            $scope.zoom = map.getZoom();

            var zoom = map.getZoom();
            var bounds = map.getBounds();
            var minLat = bounds.getSouthWest().lat();
            var minLong = bounds.getSouthWest().lng();
            var maxLat = bounds.getNorthEast().lat();
            var maxLong = bounds.getNorthEast().lng();
            var cLat = bounds.getCenter().lat();
            var cLong = bounds.getCenter().lng();
            $scope.cLat = bounds.getCenter().lat();
            $scope.cLong = bounds.getCenter().lng();
            var map_lat_long = zoom + '~' + bounds + '~' + minLat + '~' + minLong + '~' + maxLat + '~' + maxLong + '~' + cLat + '~' + cLong;
            localStorage.setItem("map_lat_long", map_lat_long);
            var redo_search = '';
            // $('#redo_search:checked').each(function(i){
            redo_search = $('#redo_search:checked').val();
            // });
            //  alert(redo_search);
            if (redo_search == 'true')
            {
                $scope.search_result();
            }
            else
            {
                $(".map-auto-refresh").addClass('hide');
                $(".map-manual-refresh").removeClass('hide');
            }
        });


//marker(response);
    }

    function HomeControl(controlDiv, map)
    {
        var controlText = document.createElement('div');
        controlText.style.position = 'relative';
        controlText.style.padding = '5px';
        controlText.style.margin = '-65px 338px 0px 50px';
        controlText.style.fontSize = '14px';
        controlText.style.width = '230px';
        controlText.innerHTML = '<div class="map-refresh-controls google"><a class="map-manual-refresh btn btn-primary hide" style="background-color:#ff5a5f;color: #ffffff;">Redo Search Here<i class="icon icon-refresh icon-space-left"></i></a><div class="panel map-auto-refresh"><label class="checkbox"><input type="checkbox" checked="checked" name="redo_search" value="true" class="map-auto-refresh-checkbox" id="redo_search"><small>Search as I move the map</small></label></div></div>';

        controlDiv.appendChild(controlText);

        var controlText = document.createElement('div');
        controlText.style.position = 'relative';
        controlText.style.padding = '5px';
        controlText.style.margin = '0px 0px 0px 50px';
        controlText.style.fontSize = '14px';
        controlText.innerHTML = '<div class="map-refresh-controls google"><div class="panel map-auto-refresh display-icon" style="padding-top:6px; padding-bottom:6px;">Display: <label class="checkbox" style="display:inline;"><input type="checkbox" checked="checked" name="restaurant" value="Restaurants" class="map-auto-refresh-checkbox place_types" id="restaurant"><small>Restaurants</small></label> <label class="checkbox" style="display:inline;"><input type="checkbox" checked="checked" name="kosher_vendor" id="kosher_vendor" value="Kosher Vendor" class="map-auto-refresh-checkbox place_types"><small>Kosher Vendor</small></label> <label class="checkbox" style="display:inline;"><input type="checkbox" checked="checked" name="synagogues" value="Synagogues" class="map-auto-refresh-checkbox place_types" id="synagogues"><small>Synagogues</small></label> <label class="checkbox" style="display:inline;"><input type="checkbox" checked="checked" name="mikvahs" value="Mikvahs" id="mikvahs" class="map-auto-refresh-checkbox place_types"><small>Mikvahs</small></label></div></div>';

        controlDiv.appendChild(controlText);

        // Setup click-event listener: simply set the map to London
        google.maps.event.addDomListener(controlText, 'click', function ()
        {
        });
    }

    /*Overlay Script*/
    function TxtOverlay(pos, txt, cls, map)
    {

        // Now initialize all properties.
        this.pos = pos;
        this.txt_ = txt;
        this.cls_ = cls;
        this.map_ = map;

        // We define a property to hold the image's
        // div. We'll actually create this div
        // upon receipt of the add() method so we'll
        // leave it null for now.
        this.div_ = null;

        // Explicitly call setMap() on this overlay
        this.setMap(map);
    }

    TxtOverlay.prototype = new google.maps.OverlayView();


    TxtOverlay.prototype.onAdd = function ()
    {

        // Note: an overlay's receipt of onAdd() indicates that
        // the map's panes are now available for attaching
        // the overlay to the map via the DOM.

        // Create the DIV and set some basic attributes.
        var div = document.createElement('DIV');
        div.className = this.cls_;

        div.innerHTML = this.txt_;

        // Set the overlay's div_ property to this DIV
        this.div_ = div;
        var overlayProjection = this.getProjection();
        var position = overlayProjection.fromLatLngToDivPixel(this.pos);
        div.style.left = position.x + 'px';
        div.style.top = position.y + 'px';
        // We add an overlay to a map via one of the map's panes.

        var panes = this.getPanes();
        panes.overlayMouseTarget.appendChild(div);

        var me = this;
        google.maps.event.addDomListener(div, 'click', function (event)
        {
            google.maps.event.trigger(me, 'click');
            event.stopPropagation();
        });
        google.maps.event.addDomListener(div, 'dblclick', function (event)
        {
            event.stopPropagation();
        });

    }
    TxtOverlay.prototype.draw = function ()
    {


        var overlayProjection = this.getProjection();

        // Retrieve the southwest and northeast coordinates of this overlay
        // in latlngs and convert them to pixels coordinates.
        // We'll use these coordinates to resize the DIV.
        var position = overlayProjection.fromLatLngToDivPixel(this.pos);

        var div = this.div_;
        //todo fix this
        //the box is approx. 40px wide, we need a center of it
        //not sure how to calculate it unless we add this box to the DOM
        div.style.left = (position.x - 20) + 'px';
        div.style.top = (position.y - 23) + 'px';
        div.style.position = 'absolute';
        //div.style.maxWidth='100px';
        div.style.cursor = 'pointer';


    }
    //Optional: helper methods for removing and toggling the text overlay.
    TxtOverlay.prototype.onRemove = function ()
    {
        this.div_.parentNode.removeChild(this.div_);
        this.div_ = null;
    }
    TxtOverlay.prototype.hide = function ()
    {
        if (this.div_)
        {
            this.div_.style.visibility = "hidden";
        }
    }

    TxtOverlay.prototype.show = function ()
    {
        if (this.div_)
        {
            this.div_.style.visibility = "visible";
        }
    }

    TxtOverlay.prototype.toggle = function ()
    {
        if (this.div_)
        {
            if (this.div_.style.visibility == "hidden")
            {
                this.show();
            }
            else
            {
                this.hide();
            }
        }
    }

    TxtOverlay.prototype.toggleDOM = function ()
    {
        if (this.getMap())
        {
            this.setMap(null);
        }
        else
        {
            this.setMap(this.map_);
        }
    }
    /*Overlay Script*/

    function marker(response)
    {
        var checkout = $scope.checkout;
        var checkin = $scope.checkin;
        setAllMap(null);
        markers = [];
        $scope.place_info = [];
        angular.forEach(response.data, function (obj)
        {

            if (obj["koshire"] == '1')
            {
                var koshire = '<span title="This house is Kosher" class="h3 test hastooltip icon-k">K</span>';

            }

            var html = '<div id="info_window_' + obj["id"] + '" class="listing listing-map-popover" data-price="' + obj["rooms_price"]["currency"]["symbol"] + '" data-id="' + obj["id"] + '" data-user="' + obj["user_id"] + '" data-url="/rooms/' + obj["id"] + '" data-name="' + obj["name"] + '" data-lng="' + obj['rooms_address']["longitude"] + '" data-lat="' + obj['rooms_address']["latitude"] + '"><div class="panel-image listing-img">';
            html += '<a class="media-photo media-cover" target="listing_' + obj["id"] + '" href="' + APP_URL + '/rooms/' + obj["id"] + '?checkin=' + checkin + '&checkout=' + checkout + '"><div class="listing-img-container media-cover text-center"><img id="marker_image_' + obj["id"] + '" rooms_image = "" alt="' + obj["name"] + '" class="img-responsive-height" data-current="0" src="' + APP_URL + '/images/' + obj["photo_name"] + '"></div></a>';
            html += '<div class="target-prev target-control block-link marker_slider" ng-click="marker_slider($event)"  data-room_id="' + obj["id"] + '"><i class="icon icon-chevron-left icon-size-2 icon-white"></i></div><a class="link-reset panel-overlay-bottom-left panel-overlay-label panel-overlay-listing-label" target="listing_' + obj["id"] + '" href="' + APP_URL + 'rooms/' + obj["id"] + '?checkin=' + checkin + '&checkout=' + checkout + '"><div>';

            var instant_book = '';

            if (obj["booking_type"] == 'instant_book')
                instant_book = '<span aria-label="Book Instantly" data-behavior="tooltip" class="h3 icon-beach"><i class="icon icon-instant-book icon-flush-sides"></i></span>';

            html += '<sup class="h6 text-contrast">' + obj["rooms_price"]["currency"]["symbol"] + '</sup><span class="h3 text-contrast price-amount">' + obj["rooms_price"]["night"] + '</span><sup class="h6 text-contrast"></sup>' + instant_book + '</div></a><div class="target-next target-control marker_slider block-link" ng-click="marker_slider($event)" data-room_id="' + obj["id"] + '"><i class="icon icon-chevron-right icon-size-2 icon-white"></i></div>' + koshire + '</div>';
            html += '<div class="panel-body panel-card-section"><div class="media"><h3 class="h5 listing-name text-truncate row-space-top-1" itemprop="name" title="' + obj["name"] + '">' + obj["name"] + '</a></h3>';

            var star_rating = '';

            if (obj['overall_star_rating'] != '')
                star_rating = ' · ' + obj['overall_star_rating'];

            var reviews_count = '';
            var review_plural = (obj['reviews_count'] > 1) ? 's' : '';

            if (obj['reviews_count'] != 0)
                reviews_count = ' · ' + obj['reviews_count'] + ' review' + review_plural;

            html += '<div class="text-muted listing-location text-truncate" itemprop="description"><a class="text-normal link-reset" href="' + APP_URL + '/rooms/' + obj["id"] + '">' + obj["room_type_name"] + star_rating + reviews_count + '</a></div></div></div></div>';
            var lat = obj["rooms_address"]["latitude"];
            var lng = obj["rooms_address"]["longitude"];
            var point = new google.maps.LatLng(lat, lng);
            var name = obj["name"];
            var currency_symbol = obj["rooms_price"]["currency"]["symbol"];
            var currency_value = obj["rooms_price"]["night"];
            var marker = new google.maps.Marker({
                position: point,
                map: map,
                icon: getMarkerImage('normal'),
                title: name,
                zIndex: 1
            });
            customTxt = currency_symbol + ' ' + currency_value;
            txt = new TxtOverlay(point, customTxt, "customBox", map);
            //console.log(txt);
            markers.push(txt);
            google.maps.event.addListener(marker, "mouseover", function ()
            {
                marker.setIcon(getMarkerImage('hover'));
            });
            google.maps.event.addListener(marker, "mouseout", function ()
            {
                marker.setIcon(getMarkerImage('normal'));
            });
            createInfoWindow(txt, html);
            $(document).on('mouseenter', '.hastooltip', function ()
            {
                var tool_title = $(this).attr('title');
                $(this).prepend('<div class="tooltip fade top in" role="tooltip" style="position:absolute; top: -50px; width: 200px; left: 50%; transform: translateX(-50%);"><div class="tooltip-arrow"></div><div class="tooltip-inner">' + tool_title + '</div></div>');
            }).on('mouseleave', '.hastooltip', function ()
            {
                $(this).find(".tooltip").remove();
            });

        });

//console.log($scope.place_result);
        angular.forEach($scope.place_result, function (obj)
        {
            var lat = obj["latitude"];
            var lng = obj["longitude"];
            var point = new google.maps.LatLng(lat, lng);

            /*var marker = new google.maps.Marker({
             position: point,
             map: map,
             icon: getMarkerImage(obj['type'])
             });*/

            var marker = new Marker({
                map: map,
                position: point,
                /*icon: {
                 path: MAP_PIN,
                 fillColor: '#ff5a5f',
                 fillOpacity: 1,
                 strokeColor: '',
                 strokeWeight: 0
                 },*/
                icon: ' ',
                map_icon_label: getMarkerLabel(obj['type'])
            });
            markers.push(marker);

            place_info = '<p>' + obj['name'] + '</p><p>' + obj['address_line_1'] + ' ' + obj['address_line_2'] + ', ' + obj['city'] + '</p><p>' + obj['state'] + ', ' + obj['country'] + '</p>';
            $scope.places_info.push(place_info);

            html = '<div style="font-size:16px"><h3 style="margin:0px; color:#000;">' + obj['name'] + '</h3><div class="popup-review">' + obj['reviews_star_rating_div'] + '</div><hr><div class="address-align">' + obj['address_line_1'] + '</div><div class="address-align">' + obj['address_line_2'] + '</div><div class="address-align">' + obj['city'] + '</div><div class="address-align">' + obj['state'] + '</div><div class="address-align">' + obj['country_name'] + '</div><div class="address-align">' + obj['postal_code'] + '</div>';
            // html += '<br><br><a class="review-btn-pop" href="'+APP_URL+'/add_place_reviews/place/'+obj['id']+'" target="_blank" >Review</a>';
            html += '<div class="review-search-popup"><div onclick="reviews_popup(event, this)" class="close" >close</div>';
            angular.forEach(obj['reviews'], function (review)
            {
                html += '<div class="review-content flt-left">';
                html += '<div class="left-blk review-content-blk" ><img width="40" height="40" src="' + review.users_from.profile_picture.src + '" class="flt-left img-rnd" ></div>';
                html += '<div class="right-blk review-content-blk" ><div class="place_comments">' + review.place_comments + '</div><div class="place_stars" >' + review.place_review_stars_div + '</div></div>';
                html += '</div>';
            });
            html += '</div></div></div></div>';
            createPlaceInfoWindow(marker, html);

        });
    }

    /*$(document).on('click', 'div.reviews-count', function(e) {
     $(this).parent().find('.review-search-popup').toggle('display');
     e.stopPropagation();
     });*/

    function marker_places(response)
    {
// markers = [];
//   setAllMap(null);
//   angular.forEach(response, function(obj) {
//     var lat = obj["latitude"];
//     var lng = obj["longitude"];
//     var point = new google.maps.LatLng(lat,lng);

//     var marker = new google.maps.Marker({
//         position: point,
//          map: map,
//          icon: getMarkerImage('Restaurant')
//     });
//     markers.push(marker);
//   });
    }

    function createInfoWindow(marker, popupContent)
    {
        infoBubble = new InfoBubble({
            maxWidth: 3000
        });
//console.log(marker.pos);
        var contentString = $compile(popupContent)($scope);
        google.maps.event.addDomListener(marker, 'click', function ()
        {

            if (infoBubble.isOpen())
            {
                infoBubble.close();
                infoBubble = new InfoBubble({
                    maxWidth: 3000
                });
            }

            infoBubble.addTab('', contentString[0]);

            var borderRadius = 0;
            infoBubble.setBorderRadius(borderRadius);
            var maxWidth = 300;
            infoBubble.setMaxWidth(maxWidth);

            var maxHeight = 300;
            infoBubble.setMaxHeight(maxHeight);
            var minWidth = 282;
            infoBubble.setMinWidth(minWidth);

            var minHeight = 245;
            infoBubble.setMinHeight(minHeight);
            infoBubble.setPosition(marker.pos);
            infoBubble.open(map);
            //console.log(infoBubble);
        });
    }

    function createPlaceInfoWindow(marker, popupContent)
    {
        infoBubble = new InfoBubble({
            maxWidth: 1500
        });

        var contentString = popupContent;
        google.maps.event.addListener(marker, 'click', function ()
        {

            if (infoBubble.isOpen())
            {
                infoBubble.close();
                infoBubble = new InfoBubble({
                    maxWidth: 1500
                });
            }

            infoBubble.addTab('', contentString);

            var borderRadius = 0;
            infoBubble.setBorderRadius(borderRadius);
            var maxWidth = 300;
            infoBubble.setMaxWidth(maxWidth);

            var maxHeight = 250;
            infoBubble.setMaxHeight(maxHeight);
            var minWidth = 300;
            infoBubble.setMinWidth(minWidth);

            var minHeight = 150;
            infoBubble.setMinHeight(minHeight);

            infoBubble.open(map, marker);
        });

    }

    function getMarkerImage(type)
    {
        var image = 'no-map-pin-set-3460214b477748232858bedae3955d81.png';

        if (type == 'hover')
            image = 'hover-no-map-pin-set-3460214b477748232858bedae3955d81.png';
        else if (type == 'Restaurants')
            image = 'pin-restaurant.png';
        /*'<span class="map-icon map-icon-restaurant"></span>'*/


        else if (type == 'Synagogues')
            image = 'pin-synagogues.png';

        /*'<span class="map-icon map-icon-restaurant"></span>'*/

        /*var point = new google.maps.LatLng(50,50),


         myOptions = {
         zoom: 4,
         center: point,
         mapTypeId: google.maps.MapTypeId.ROADMAP
         },

         map = new google.maps.Map( document.getElementById( 'map_canvas' ), myOptions ),

         marker = new MarkerWithLabel({
         position: point,
         draggable: true,
         raiseOnDrag: true,
         icon: ' ',
         map: map,
         labelContent: '<span class="map-icon map-icon-synagogue"></span>',
         labelAnchor: new google.maps.Point(22, 50),
         labelClass: "labels" // the CSS class for the label
         });

         marker.setMap( map );
         }*/
        else if (type == 'Mikvahs')
            image = 'pin-mikvahs.png';
        /*'<span class="map-icon map-icon-florist"></span>'*/
        else if (type == 'Kosher Vendor')
            image = 'pin-kosher_vendor.png';
        /*'<span class="map-icon map-icon-grocery-or-supermarket"></span>'*/

        var gicons = new google.maps.MarkerImage("images/" + image,
            new google.maps.Size(50, 50),
            new google.maps.Point(0, 0),
            new google.maps.Point(9, 20));

        return gicons;

    }

    function getMarkerLabel(type)
    {
        var label = '';
        if (type == 'Restaurants')
            label = '<span class="map-icon map-icon-restaurant"></span>';
        else if (type == 'Synagogues')
            label = '<span class="map-icon map-icon-synagogue"></span>';
        else if (type == 'Mikvahs')
            label = '<span class="map-icon map-icon-florist"></span>';
        else if (type == 'Kosher Vendor')
            label = '<span class="map-icon map-icon-grocery-or-supermarket"></span>';

        return label;

    }

    function setAllMap(map)
    {
        if (infoBubble != undefined)
        {
            if (infoBubble.isOpen())
            {
                infoBubble.close();
                infoBubble = new InfoBubble({
                    maxWidth: 3000
                });
            }
        }
        for (var i = 0; i < markers.length; i++)
        {
            markers[i].setMap(map);
        }
    }

    var infoBubble;

    $('.footer-toggle').click(function ()
    {
        $(".footer-container").slideToggle("fast", function ()
        {
            if ($(".footer-container").is(":visible"))
            {
                $('.open-content').hide();
                $('.close-content').show();
            }
            else
            {
                $('.open-content').show();
                $('.close-content').hide();
            }
        });
    });


    $(document).on('click', '.map-manual-refresh', function ()
    {
        $(".map-manual-refresh").addClass('hide');
        $(".map-auto-refresh").removeClass('hide');
        $scope.search_result();
    });
    $(document).on('click', '.rooms-slider', function ()
    {

        var rooms_id = $(this).attr("data-room_id");
        var dataurl = $("#rooms_image_" + rooms_id).attr("rooms_image");
        var img_url = $("#rooms_image_" + rooms_id).attr("src");
        if ($.trim(dataurl) == '')
        {
            $(this).parent().addClass("loading");
            $http.post('rooms_photos', {rooms_id: rooms_id})
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

                    $("#rooms_image_" + rooms_id).attr("rooms_image", dataurl);
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
                    $("#rooms_image_" + rooms_id).attr("src", set_img_url);
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
            $("#rooms_image_" + rooms_id).attr("src", set_img_url);

        }

    });
    $scope.marker_slider = function ($event)
    {

        $event.stopPropagation();
        var this_elm = angular.element($event.currentTarget);

        var rooms_id = $($event.currentTarget).attr("data-room_id");
        var dataurl = $("#marker_image_" + rooms_id).attr("rooms_image");
        var img_url = $("#marker_image_" + rooms_id).attr("src");
        if ($.trim(dataurl) == '')
        {
            $($event.currentTarget).parent().addClass("loading");
            $http.post('rooms_photos', {rooms_id: rooms_id})
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

                    $("#marker_image_" + rooms_id).attr("rooms_image", dataurl);
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
                    if ($($event.currentTarget).is(".target-prev") == true)
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
                    $("#marker_image_" + rooms_id).attr("src", set_img_url);
                });
        }
        else
        {
            $($event.currentTarget).parent().addClass("loading");
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
            if ($($event.currentTarget).is(".target-prev") == true)
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
            $("#marker_image_" + rooms_id).attr("src", set_img_url);

        }

    }

    $(document).on('click', '.marker_slider', function ()
    {

        var rooms_id = $(this).attr("data-room_id");
        var dataurl = $("#marker_image_" + rooms_id).attr("rooms_image");
        var img_url = $("#marker_image_" + rooms_id).attr("src");
        if ($.trim(dataurl) == '')
        {
            $(this).parent().addClass("loading");
            $http.post('rooms_photos', {rooms_id: rooms_id})
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

                    $("#marker_image_" + rooms_id).attr("rooms_image", dataurl);
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
                    $("#marker_image_" + rooms_id).attr("src", set_img_url);
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
            $("#marker_image_" + rooms_id).attr("src", set_img_url);

        }

    });
    $(document).on('change', '[id^="map-search"]', function ()
    {
        var i = 0;
        $('[id^="map-search"]').each(function ()
        {
            if ($(this).is(':checkbox'))
            {
                if ($(this).is(':checked'))
                {
                    i++;
                }
            }
            else if ($(this).val() != '')
            {
                i++
            }
        });

        if (i == 0)
        {
            $('#more_filter_submit').attr('disabled', 'disabled');
        }
        else
        {
            $('#more_filter_submit').removeAttr('disabled');
        }
    });

    $(document).on('click', '#cancel-filter', function ()
    {
        $('[id^="map-search"]').each(function ()
        {
            if ($(this).is(':checkbox'))
            {
                $(this).attr('checked', false);
            }
            else
            {
                $(this).val('');
            }
        });

        $('#more_filter_submit').attr('disabled', 'disabled');

        $(".toggle-hide").css("display", "block");
        $(".toggle-group").css("display", "none");

        $scope.search_result();
    });

}]);
function reviews_popup(event, that)
{
    $(that).parent().parent().find('.review-search-popup').toggle('display');
    event.stopPropagation();
}
