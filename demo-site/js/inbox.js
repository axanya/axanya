app.directive('postsPagination', function ()
{
    return {
        restrict: 'E',
        template: '<ul class="pagination">' +
        '<li ng-show="currentPage != 1"><a href="javascript:void(0)" ng-click="messages_result(1)">&laquo;</a></li>' +
        '<li ng-show="currentPage != 1"><a href="javascript:void(0)" ng-click="messages_result(currentPage-1)">&lsaquo; Prev</a></li>' +
        '<li ng-repeat="i in range" ng-class="{active : currentPage == i}">' +
        '<a href="javascript:void(0)" ng-click="messages_result(i)">{{i}}</a>' +
        '</li>' +
        '<li ng-show="currentPage != totalPages"><a href="javascript:void(0)" ng-click="messages_result(currentPage+1)">Next &rsaquo;</a></li>' +
        '<li ng-show="currentPage != totalPages"><a href="javascript:void(0)" ng-click="messages_result(totalPages)">&raquo;</a></li>' +
        '</ul>'
    };
}).controller('inbox', ['$scope', '$http', function ($scope, $http)
{

    setTimeout(function ()
    {

        $scope.totalPages = 0;
        $scope.currentPage = 1;
        $scope.range = [];

        pageNumber = 1;

        if (pageNumber === undefined)
        {
            pageNumber = '1';
        }

        var type = $('#inbox_filter_select').val();

        var data = $scope.user_id;

        $http.post('inbox/message_by_type', {data: data, type: type}).then(function (response)
        {
            $scope.message_result = response.data;

            $scope.totalPages = response.data.last_page;
            $scope.currentPage = response.data.current_page;
            // Pagination Range
            var pages = [];

            for (var i = 1; i <= response.data.last_page; i++)
            {
                pages.push(i);
            }

            $scope.range = pages;


        });

        $http.post('inbox/message_count', {data: data, type: type}).then(function (response)
        {
            $scope.message_count = response.data;

        });

        $scope.messages_result = function (pageNumber)
        {

            if (pageNumber === undefined)
            {
                pageNumber = '1';
            }

            var type = $('#inbox_filter_select').val();

            var data = $scope.user_id;


            // setGetParameter('page', pageNumber);


            $http.post('inbox/message_by_type?page=' + pageNumber, {data: data, type: type})
                .then(function (response)
                {


                    $scope.message_result = response.data;

                    $scope.totalPages = response.data.last_page;
                    $scope.currentPage = response.data.current_page;
                    // Pagination Range
                    var pages = [];

                    for (var i = 1; i <= response.data.last_page; i++)
                    {
                        pages.push(i);
                    }

                    $scope.range = pages;


                });
        };


        $scope.archive = function (index, id, msg_id, type)
        {


            $http.post('inbox/archive', {id: id, msg_id: msg_id, type: type}).then(function (response)
            {
                if (type == "Archive")
                    $scope.message_result.data[index].archive = 1;

                if (type == "Unarchive")
                    $scope.message_result.data[index].archive = 0;

                $http.post('inbox/message_count', {data: data, type: type}).then(function (response)
                {
                    $scope.message_count = response.data;
                });
            });

        };

        $scope.star = function (index, id, msg_id, type)
        {


            $http.post('inbox/star', {id: id, msg_id: msg_id, type: type}).then(function (response)
            {
                if (type == "Star")
                    $scope.message_result.data[index].star = 1;

                if (type == "Unstar")
                    $scope.message_result.data[index].star = 0;

                $http.post('inbox/message_count', {data: data, type: type}).then(function (response)
                {
                    $scope.message_count = response.data;
                });
            });
        };

        $("#inbox_filter_select").change(function ()
        {

            var type = this.value;

            var data = $scope.user_id;

            $http.post('inbox/message_by_type', {data: data, type: type}).then(function (response)
            {
                $scope.message_result = response.data;

                $scope.totalPages = response.data.last_page;
                $scope.currentPage = response.data.current_page;
                // Pagination Range
                var pages = [];

                for (var i = 1; i <= response.data.last_page; i++)
                {
                    pages.push(i);
                }

                $scope.range = pages;


                $http.post('inbox/message_count', {data: data, type: type}).then(function (response)
                {
                    $scope.message_count = response.data;

                });

            });
        });

    }, 10);

}]);

app.controller('conversation', ['$scope', '$http', function ($scope, $http)
{

    $scope.reply_message = function (value)
    {
        var message = $('[data-key="' + value + '"] textarea[name="message"]').val();
        var template = $('[data-key="' + value + '"] input[name="template"]').val();

        $http.post(APP_URL + '/messaging/qt_reply/' + $('#reservation_id').val(), {
            message: message,
            template: template,
            pricing_room_id: $('#pricing_room_id').val(),
            pricing_checkin: $('#pricing_start_date').val(),
            pricing_checkout: $('#pricing_end_date').val(),
            pricing_guests: $('#pricing_guests').val(),
            pricing_price: $('#pricing_price').val()
        }).then(function (response)
        {
            $('#thread-list').prepend(response.data);
            $('[data-key="' + value + '"] textarea[name="message"]').val('');
            $('.inquiry-form-fields').addClass('hide');
            $('[data-tracking-section="accept"] ul').addClass('hide');
            $('[data-tracking-section="decline"] ul').addClass('hide');
            $('[data-tracking-section="discussion"] ul').addClass('hide');
        });
    }

    $(document).on('change', '#month-dropdown', function ()
    {
        var year_month = $(this).val();
        var year = year_month.split('-')[0];
        var month = year_month.split('-')[1];

        var data_params = {};

        data_params['month'] = month;
        data_params['year'] = year;
        data_params['reservation_id'] = $('#reservation_id').val();
        data_params['room_id'] = $('#hosting').val();

        var data = JSON.stringify(data_params);

        $('#calendar-container').addClass('loading');

        $http.post(APP_URL + '/inbox/calendar', {data: data}).then(function (response)
        {
            $('#calendar-container').removeClass('loading');
            $("#calendar-container").html(response.data);
        });
        return false;
    });

    $(document).on('click', '.nextMonth, .previousMonth', function ()
    {
        var month = $(this).attr('data-month');
        var year = $(this).attr('data-year');

        var data_params = {};

        data_params['month'] = month;
        data_params['year'] = year;
        data_params['reservation_id'] = $('#reservation_id').val();
        data_params['room_id'] = $('#hosting').val();

        var data = JSON.stringify(data_params);

        $('#calendar-container').addClass('loading');

        $http.post(APP_URL + '/inbox/calendar', {data: data}).then(function (response)
        {
            $('#calendar-container').removeClass('loading');
            $("#calendar-container").html(response.data);
        });
        return false;
    });

    $(document).on('click', '#hosting', function ()
    {
        var data_params = {};

        var year_month = $('#month-dropdown').val();
        var year = year_month.split('-')[0];
        var month = year_month.split('-')[1];

        data_params['month'] = month;
        data_params['year'] = year;
        data_params['reservation_id'] = $('#reservation_id').val();
        data_params['room_id'] = $(this).val();

        var data = JSON.stringify(data_params);

        $('#calendar-container').addClass('loading');

        $http.post(APP_URL + '/inbox/calendar', {data: data}).then(function (response)
        {
            $('#calendar-container').removeClass('loading');
            $("#calendar-container").html(response.data);
        });
        $('#edit_calendar_url').attr('href', APP_URL + '/manage-listing/' + $(this).val() + '/calendar');
        return false;
    });

    $('#month-dropdown').val($('#month-dropdown_value').val());
    $('#hosting').val($('#room_id').val());

    $(document).on('click', '.offer_attach', function ()
    {
        $('.inquiry-form-fields').removeClass('hide');
        $('[data-tracking-section="accept"] ul').removeClass('hide');

        $('[data-tracking-section="accept"] input[name="template"][value=2]').prop('checked', true);

        var key = $('[data-tracking-section="accept"] input[name="template"]:checked').parent().parent().parent().parent().data('key');
        $('[data-key="' + key + '"] .drawer').removeClass('hide');
        /*$('[data-key="pre-approve"]').addClass('hide');
         $('[data-tracking-section="decline"]').addClass('hide');
         $('[data-tracking-section="discussion"]').addClass('hide');*/
    });

    $(document).on('click', '.pre_approve', function ()
    {
        $('.inquiry-form-fields').removeClass('hide');
        $('[data-tracking-section="accept"] ul').removeClass('hide');

        $('[data-tracking-section="accept"] input[name="template"][value=1]').prop('checked', true);

        var key = $('[data-tracking-section="accept"] input[name="template"]:checked').parent().parent().parent().parent().data('key');
        $('[data-key="' + key + '"] .drawer').removeClass('hide');
        /*$('[data-key="pre-approve"]').addClass('hide');
         $('[data-tracking-section="decline"]').addClass('hide');
         $('[data-tracking-section="discussion"]').addClass('hide');*/
    });

    $(document).on('click', '.option-list a', function ()
    {
        var track = $(this).parent().data('tracking-section');

        $('[data-tracking-section] ul').addClass('hide');
        $('[data-tracking-section="' + track + '"] ul').removeClass('hide');

        var key = $('[data-tracking-section="' + track + '"] input[name="template"]:checked').parent().parent().parent().parent().data('key');
        $('[data-key="' + key + '"] .drawer').removeClass('hide');
    });

    $(document).on('click', 'input[name="template"]', function ()
    {
        $('[data-key] .drawer').addClass('hide');

        var key = $(this).parent().parent().parent().parent().data('key');
        $('[data-key="' + key + '"] .drawer').removeClass('hide');
    });

    setTimeout(function ()
    {

        var data = $('#room_id').val();
        var room_id = data;

        $http.post('../../rooms/rooms_calendar', {data: data}).then(function (response)
        {
            var changed_price = response.data.changed_price;
            var array = response.data.not_avilable;

            $('#pricing_start_date').datepicker({
                minDate: 0,
                dateFormat: 'dd-mm-yy',
                setDate: new Date($('#pricing_start_date').val()),
                beforeShowDay: function (date)
                {
                    var date = jQuery.datepicker.formatDate('yy-mm-dd', date);
                    if ($.inArray(date, array) != -1)
                        return [false];
                    else
                        return [true];
                },
                onSelect: function (date)
                {
                    var checkout = $('#pricing_start_date').datepicker('getDate');
                    checkout.setDate(checkout.getDate() + 1);
                    $('#pricing_end_date').datepicker('setDate', checkout);
                    $('#pricing_end_date').datepicker('option', 'minDate', checkout);

                    setTimeout(function ()
                    {
                        $("#pricing_end_date").datepicker("show");
                    }, 20);

                    var checkin = $(this).val();
                    var checkout = $("#pricing_end_date").val();
                    var guest = $("#pricing_guests").val();
                    calculation(checkout, checkin, guest, room_id);

                    if (date != new Date())
                    {
                        $('.ui-datepicker-today').removeClass('ui-datepicker-today');
                    }
                }
            });

            jQuery('#pricing_end_date').datepicker({
                minDate: 1,
                dateFormat: 'dd-mm-yy',
                setDate: new Date($('#pricing_end_date').val()),
                beforeShowDay: function (date)
                {
                    var date = jQuery.datepicker.formatDate('yy-mm-dd', date);
                    if ($.inArray(date, array) != -1)
                        return [false];
                    else
                        return [true];
                },
                onClose: function ()
                {
                    var checkin = $('#pricing_start_date').datepicker('getDate');
                    var checkout = $('#pricing_end_date').datepicker('getDate');

                    if (checkout <= checkin)
                    {
                        var minDate = $('#pricing_end_date').datepicker('option', 'minDate');
                        $('#pricing_end_date').datepicker('setDate', minDate);
                    }

                    var checkout = $(this).val();
                    var checkin = $("#pricing_start_date").val();
                    var guest = $("#pricing_guests").val();

                    if (checkin != '')
                    {
                        calculation(checkout, checkin, guest, room_id);
                    }
                }
            });

        });

    }, 10);

    $('#pricing_hosting_id').change(function ()
    {
        var checkout = $("#pricing_end_date").val();
        var checkin = $("#pricing_start_date").val();
        var guest = $("#pricing_guests").val();
        var room_id = $(this).val();
        calculation(checkout, checkin, guest, room_id);
    });

    function calculation(checkout, checkin, guest, room_id)
    {
        $('.special-offer-date-fields').addClass('loading');
        $http.post('../../rooms/price_calculation', {
            checkin: checkin,
            checkout: checkout,
            guest_count: guest,
            room_id: room_id
        }).then(function (response)
        {
            $('.special-offer-date-fields').removeClass('loading');
            $('#pricing_price').val(response.data.subtotal);
        });
    }

}]);
