<?php

/*
|--------------------------------------------------------------------------
| Application Routes
|--------------------------------------------------------------------------
|
| Here is where you can register all of the routes for an application.
| It's a breeze. Simply tell Laravel the URIs it should respond to
| and give it the controller to call when that URI is requested.
|
 */

Route::group(['middleware' => ['install', 'locale']], function () {
	Route::get('/', 'HomeController@index');
});

Route::get('phpinfo', 'HomeController@phpinfo');

Route::get('pcss', 'PcssController@index');

// Before Login Routes

Route::group(['before' => 'no_auth', 'middleware' => 'locale'], function () {

	Route::get('login', 'HomeController@login');

	Route::get('auth/login', function () {
		return Redirect::to('login');
	});

	Route::get('signup_login', 'HomeController@signup_login');

	Route::post('create', 'UserController@create');

	Route::post('authenticate', 'UserController@authenticate');

	Route::get('facebookAuthenticate', 'UserController@facebookAuthenticate');

	Route::get('googleAuthenticate', 'UserController@googleAuthenticate');

	Route::get('googleLogin', 'UserController@googleLogin');

	Route::match(array('GET', 'POST'), 'forgot_password', 'UserController@forgot_password');

	Route::get('users/set_password/{secret?}', 'UserController@set_password');

	Route::post('users/set_password', 'UserController@set_password');

	Route::get('c/{username}', 'ReferralsController@invite_referral');

});

Route::group(['middleware' => 'locale'], function () {

	Route::post('set_session', 'HomeController@set_session');

	Route::get('s', 'SearchController@index');

	Route::match(['get', 'post'], 'searchResult', 'SearchController@searchResult');

	Route::match(['get', 'post'], 'places', 'SearchController@places');

	Route::post('rooms_photos', 'SearchController@rooms_photos');

	Route::get('s/{id}', 'SearchController@index');

	Route::get('currency_cron', 'CronController@currency');

	Route::get('cron/ical_sync', 'CronController@ical_sync');

	Route::get('cron/expire', 'CronController@expire');

	Route::get('cron/travel_credit', 'CronController@travel_credit');

	Route::get('users/show/{id}', 'UserController@show');

	Route::get('home/cancellation_policies', 'HomeController@cancellation_policies');

	Route::get('help', 'HomeController@help');

	Route::get('help/topic/{id}/{category}', 'HomeController@help');

	Route::get('help/article/{id}/{question}', 'HomeController@help');
	Route::get('ajax_help_search', 'HomeController@ajax_help_search');

	Route::get('wishlist_list', 'WishlistController@wishlist_list');
	Route::get('wishlists/{id}', 'WishlistController@wishlist_details')->where('id', '[0-9]+');
	Route::get('users/{id}/wishlists', 'WishlistController@my_wishlists');

	Route::get('invite', 'ReferralsController@invite');

	Route::get('invites', 'ReferralsController@invites');

	Route::get('wishlists/popular', 'WishlistController@popular');
	Route::get('wishlists/picks', 'WishlistController@picks');

/* CONTACT US PAGE */
//Route::get('contact', 'HomeController@contact_us');

	Route::get('contact',
		['as' => 'contact', 'uses' => 'HomeController@contact_us']);
	/*Route::post('contact',
		['as' => 'contact_store', 'uses' => 'HomeController@store']);*/
	Route::post('contact_store', 'HomeController@store');

});

// After Login Routes

Route::group(['before' => 'guest', 'middleware' => 'locale'], function () {

	Route::get('verification', 'UserController@mobile_verification');

	Route::post('mobile_verification', 'UserController@post_mobile_verification');

	Route::get('dashboard', 'UserController@dashboard');

	Route::get('users/edit', 'UserController@edit');

	Route::get('users/edit/media', 'UserController@media');

	Route::get('users/phone_list', 'UserController@phone_list');

	Route::get('users/references', 'UserController@references');
	Route::get('users/delete_reference', 'UserController@delete_reference');
	Route::post('users/add_reference', 'UserController@add_reference');

	Route::get('users/edit_verification', 'UserController@verification');

	Route::get('users/delete_phone', 'UserController@delete_phone');

	Route::get('facebookConnect', 'UserController@facebookConnect');

	Route::get('facebookDisconnect', 'UserController@facebookDisconnect');

	Route::get('googleLoginVerification', 'UserController@googleLoginVerification');

	Route::get('googleConnect/{id}', 'UserController@googleConnect');

	Route::get('googleDisconnect', 'UserController@googleDisconnect');

	Route::get('linkedinLoginVerification', 'UserController@linkedinLoginVerification');

	Route::get('linkedinConnect', 'UserController@linkedinConnect');

	Route::get('linkedinDisconnect', 'UserController@linkedinDisconnect');

	Route::post('users/image_upload', 'UserController@image_upload');

	Route::get('users/reviews', 'UserController@reviews');

	Route::match(['get', 'post'], 'reviews/edit/{id}', 'UserController@reviews_edit');

	Route::match(['get', 'post'], 'add_place_reviews/{mode}/{id}', 'UserController@add_place_reviews');

	Route::post('users/update/{id}', 'UserController@update');

	Route::get('users/confirm_email/{code?}', 'UserController@confirm_email');

	Route::get('users/request_new_confirm_email', 'UserController@request_new_confirm_email');

	Route::get('users/security', 'UserController@security');

	Route::post('wishlist_create', 'WishlistController@create');
	Route::post('create_new_wishlist', 'WishlistController@create_new_wishlist');
	Route::post('edit_wishlist/{id}', 'WishlistController@edit_wishlist');
	Route::get('delete_wishlist/{id}', 'WishlistController@delete_wishlist');
	Route::post('remove_saved_wishlist/{id}', 'WishlistController@remove_saved_wishlist');
	Route::post('add_note_wishlist/{id}', 'WishlistController@add_note_wishlist');
	Route::post('save_wishlist', 'WishlistController@save_wishlist');
	Route::get('wishlists/my', 'WishlistController@my_wishlists');
	Route::post('share_email/{id}', 'WishlistController@share_email');

	Route::match(['get', 'post'], 'users/payout_preferences/{id}', 'UserController@payout_preferences');
	Route::get('users/payout_delete/{id}', 'UserController@payout_delete');
	Route::get('users/payout_default/{id}', 'UserController@payout_default');

	Route::get('users/transaction_history', 'UserController@transaction_history');
	Route::post('users/result_transaction_history', 'UserController@result_transaction_history');
	Route::get('transaction_history/csv/{id}', 'UserController@transaction_history_csv');

	Route::post('change_password', 'UserController@change_password');

	Route::get('account', function () {
		return Redirect::to('users/payout_preferences/' . Auth::user()->user()->id);
	});

	Route::get('rooms', 'RoomsController@index');

	Route::get('rooms/new', 'RoomsController@create');

	Route::post('rooms/create', 'RoomsController@create');

	Route::post('manage-listing/{id}/update_rooms', 'RoomsController@update_rooms');
	Route::post('manage-listing/{id}/update_rooms_bathroom', 'RoomsController@update_rooms_bathroom');
	Route::post('manage-listing/{id}/update_rooms_policies', 'RoomsController@update_rooms_policies');

	Route::get('get_bedroom_details/{id}/basics', 'RoomsController@get_bedroom_details');
	Route::get('get_rooms_status/{id}', 'RoomsController@get_rooms_status');

	Route::post('manage-listing/{id}/update_amenities', 'RoomsController@update_amenities');

	Route::post('add_photos/{id}', 'RoomsController@add_photos');

	Route::post('manage-listing/{id}/delete_photo', 'RoomsController@delete_photo');

	Route::get('manage-listing/{id}/photos_list', 'RoomsController@photos_list');

	Route::post('manage-listing/{id}/photo_highlights', 'RoomsController@photo_highlights');

	Route::post('manage-listing/{id}/update_price', 'RoomsController@update_price');

	Route::post('manage-listing/{id}/update_description', 'RoomsController@update_description');

	Route::post('manage-listing/{id}/update_locations', 'RoomsController@update_locations');

	Route::get('manage-listing/{id}/rooms_steps_status', 'RoomsController@rooms_steps_status');

	Route::get('manage-listing/{id}/rooms_data', 'RoomsController@rooms_data');

	Route::post('manage-listing/{id}/calendar_edit', 'RoomsController@calendar_edit');

	Route::post('manage-listing/{id}/get-calendar', 'CalendarController@get_calendar');

	Route::post('manage-listing/{id}/mobile_verification', 'RoomsController@mobile_verification');

	Route::get('calendar/ical/{id}', 'CalendarController@ical_export');

	Route::post('calendar/import/{id}', 'CalendarController@ical_import');

	Route::get('calendar/sync/{id}', 'CalendarController@ical_sync');

	Route::get('manage-listing/{id}/{page}', 'RoomsController@manage_listing')
		->where(['id' => '[0-9]+', 'page' => 'basics|description|location|amenities|photos|pricing|calendar|details|guidebook|terms|booking|verification|mobile_verification|referral']);

	Route::post('ajax-manage-listing/{id}/{page}', 'RoomsController@ajax_manage_listing')
		->where(['id' => '[0-9]+', 'page' => 'basics|description|location|amenities|photos|pricing|calendar|details|guidebook|terms|booking|verification|mobile_verification|referral']);

	Route::post('ajax-header/{id}/{page}', 'RoomsController@ajax_header')
		->where(['id' => '[0-9]+', 'page' => 'basics|description|location|amenities|photos|pricing|calendar|details|guidebook|terms|booking']);

	Route::post('enter_address/{id}/{page}', 'RoomsController@enter_address');

	Route::post('enter_bedroom/{id}/{page}', 'RoomsController@enter_bedroom');
	Route::post('enter_bed_option/{id}/{page}', 'RoomsController@enter_bed_option');

	Route::post('location_not_found/{id}/{page}', 'RoomsController@location_not_found');

	Route::post('verify_location/{id}/{page}', 'RoomsController@verify_location');

	Route::post('finish_address/{id}/{page}', 'RoomsController@finish_address');

	Route::get('inbox', 'InboxController@index');

	Route::match(['get', 'post'], 'payments/book/{id?}', 'PaymentController@index');

	Route::post('payments/apply_coupon', 'PaymentController@apply_coupon');
	Route::post('payments/remove_coupon', 'PaymentController@remove_coupon');

	Route::post('payments/create_booking', 'PaymentController@create_booking');

	Route::get('payments/success', 'PaymentController@success');
	Route::get('payments/cancel', 'PaymentController@cancel');

	Route::post('users/ask_question/{id}', 'RoomsController@contact_request');

// Message
	Route::post('inbox/archive', 'InboxController@archive');
	Route::post('inbox/star', 'InboxController@star');
	Route::post('inbox/message_by_type', 'InboxController@message_by_type');
	Route::post('inbox/all_message', 'InboxController@all_message');
	Route::get('z/q/{id}', 'InboxController@guest_conversation');
	Route::get('messaging/qt_with/{id}', 'InboxController@host_conversation');
	Route::post('messaging/qt_reply/{id}', 'InboxController@reply');
	Route::get('messaging/remove_special_offer/{id}', 'InboxController@remove_special_offer');
	Route::post('inbox/calendar', 'InboxController@calendar');

	Route::post('inbox/message_count', 'InboxController@message_count');

// Reservation
	Route::get('reservation/{id}', 'ReservationController@index')->where('id', '[0-9]+');
	Route::post('reservation/accept/{id}', 'ReservationController@accept');
	Route::post('reservation/decline/{id}', 'ReservationController@decline');
	Route::get('reservation/expire/{id}', 'ReservationController@expire');
	Route::get('my_reservations', 'ReservationController@my_reservations');
	Route::get('reservation/itinerary', 'ReservationController@print_confirmation');
	Route::get('reservation/requested', 'ReservationController@requested');
	Route::post('reservation/itinerary_friends', 'ReservationController@itinerary_friends');

// Change and cancel
	Route::match(['get', 'post'], 'reservation/change', 'ReservationController@host_cancel_change');
	Route::match(['get', 'post'], 'alterations/{code}', 'ReservationController@cancel_alterations');
	Route::match(['get', 'post'], 'reservation/reservation_alterations', 'ReservationController@reservation_alterations');

// Cancel Reservation
	Route::match(['get', 'post'], 'trips/guest_cancel_reservation', 'TripsController@guest_cancel_reservation');
	Route::match(['get', 'post'], 'reservation/host_cancel_reservation', 'ReservationController@host_cancel_reservation');
// Trips
	Route::get('trips/current', 'TripsController@current');
	Route::get('trips/previous', 'TripsController@previous');
	Route::get('reservation/receipt', 'TripsController@receipt');

	Route::post('invite/share_email', 'ReferralsController@share_email');

});

Route::group(['middleware' => 'locale'], function () {

// Rooms details
	Route::get('rooms/{id}', 'RoomsController@rooms_detail');
	Route::get('rooms/{id}/slider', 'RoomsController@rooms_slider');
	Route::post('rooms/rooms_calendar', 'RoomsController@rooms_calendar');
	Route::post('rooms/rooms_calendar_alter', 'RoomsController@rooms_calendar_alter');
	Route::post('rooms/price_calculation', 'RoomsController@price_calculation');

});

Route::get('logout', function () {
	Auth::user()->logout();
	return Redirect::to('login');
});

Route::filter('guest', function () {
	if (Auth::user()->guest()) {
		return Redirect::guest('login');
	}
	if (Auth::user()->user()->status == 'Inactive') {
		$data['title'] = 'Disabled';
		return View::make('users.disabled');
	}
});

// Filter for check the user is logged in or not before goto login page
Route::filter('no_auth', function () {
	if (Auth::user()->check()) {
		return Redirect::to('dashboard');
	}
});

// Admin Panel Routes
Route::group(['prefix' => 'admin', 'before' => 'admin_guest'], function () {

	Route::get('/', function () {
		return Redirect::to('admin/dashboard');
	});

	Route::get('dashboard', 'Admin\AdminController@index');

	Route::get('logout', 'Admin\AdminController@logout');

	Route::get('users', 'Admin\UsersController@index');

	Route::get('reservations', 'Admin\ReservationsController@index');
	Route::get('host_penalty', 'Admin\HostPenaltyController@index');

	Route::match(['GET', 'POST'], 'reports', 'Admin\ReportsController@index');

	Route::get('reports/export/{from}/{to}/{category}', 'Admin\ReportsController@export');

	Route::get('reservation/detail/{id}', 'Admin\ReservationsController@detail');

	Route::get('reservation/need_payout_info/{id}/{type}', 'Admin\ReservationsController@need_payout_info');

	Route::post('reservation/payout', 'Admin\ReservationsController@payout');

	Route::match(array('GET', 'POST'), 'add_user', 'Admin\UsersController@add');

	Route::match(array('GET', 'POST'), 'edit_user/{id}', 'Admin\UsersController@update');

	Route::get('delete_user/{id}', 'Admin\UsersController@delete');

	Route::get('rooms', 'Admin\RoomsController@index');

	Route::match(array('GET', 'POST'), 'add_room', 'Admin\RoomsController@add');

	Route::match(array('GET', 'POST'), 'edit_room/{id}', 'Admin\RoomsController@update');

	Route::get('delete_room/{id}', 'Admin\RoomsController@delete');

	Route::get('popular_room/{id}', 'Admin\RoomsController@popular');

	Route::post('ajax_calendar/{id}', 'Admin\RoomsController@ajax_calendar');

	Route::post('delete_photo', 'Admin\RoomsController@delete_photo');

	Route::post('photo_highlights', 'Admin\RoomsController@photo_highlights');

	Route::get('rooms/users_list', 'Admin\RoomsController@users_list');

	Route::get('delete_room/{id}', 'Admin\RoomsController@delete');

// Manage Admin Permission Routes

	Route::group(['before' => 'manage_admin'], function () {

		Route::get('admin_users', 'Admin\AdminusersController@index');

		Route::match(array('GET', 'POST'), 'add_admin_user', 'Admin\AdminusersController@add');

		Route::match(array('GET', 'POST'), 'edit_admin_user/{id}', 'Admin\AdminusersController@update');

		Route::get('delete_admin_user/{id}', 'Admin\AdminusersController@delete');

		Route::get('roles', 'Admin\RolesController@index');

		Route::match(array('GET', 'POST'), 'add_role', 'Admin\RolesController@add');

		Route::match(array('GET', 'POST'), 'edit_role/{id}', 'Admin\RolesController@update');

		Route::get('delete_role/{id}', 'Admin\RolesController@delete');

		Route::get('permissions', 'Admin\PermissionsController@index');

		Route::match(array('GET', 'POST'), 'add_permission', 'Admin\PermissionsController@add');

		Route::match(array('GET', 'POST'), 'edit_permission/{id}', 'Admin\PermissionsController@update');

		Route::get('delete_permission/{id}', 'Admin\PermissionsController@delete');

	});

// Manage Amenities Routes

	Route::group(['before' => 'manage_amenities'], function () {

		Route::get('amenities', 'Admin\AmenitiesController@index');

		Route::match(array('GET', 'POST'), 'add_amenity', 'Admin\AmenitiesController@add');

		Route::match(array('GET', 'POST'), 'edit_amenity/{id}', 'Admin\AmenitiesController@update');

		Route::get('delete_amenity/{id}', 'Admin\AmenitiesController@delete');

		Route::get('amenities_type', 'Admin\AmenitiesTypeController@index');

		Route::match(array('GET', 'POST'), 'add_amenities_type', 'Admin\AmenitiesTypeController@add');

		Route::match(array('GET', 'POST'), 'edit_amenities_type/{id}', 'Admin\AmenitiesTypeController@update');

		Route::get('delete_amenities_type/{id}', 'Admin\AmenitiesTypeController@delete');

/// For Religious Amenities
		Route::get('religious_amenities', 'Admin\ReligiousAmenitiesController@index');
		Route::match(array('GET', 'POST'), 'add_religious_amenity', 'Admin\ReligiousAmenitiesController@add');
		Route::match(array('GET', 'POST'), 'edit_religious_amenity/{id}', 'Admin\ReligiousAmenitiesController@update');
		Route::get('delete_religious_amenity/{id}', 'Admin\ReligiousAmenitiesController@delete');

		Route::get('religious_amenities_type', 'Admin\ReligiousAmenitiesTypeController@index');
		Route::match(array('GET', 'POST'), 'add_religious_amenities_type', 'Admin\ReligiousAmenitiesTypeController@add');
		Route::match(array('GET', 'POST'), 'edit_religious_amenities_type/{id}', 'Admin\ReligiousAmenitiesTypeController@update');
		Route::get('delete_religious_amenities_type/{id}', 'Admin\ReligiousAmenitiesTypeController@delete');

	});

// Manage Property Type Routes

	Route::group(['before' => 'manage_property_type'], function () {

		Route::get('property_type', 'Admin\PropertyTypeController@index');

		Route::match(array('GET', 'POST'), 'add_property_type', 'Admin\PropertyTypeController@add');

		Route::match(array('GET', 'POST'), 'edit_property_type/{id}', 'Admin\PropertyTypeController@update');

		Route::get('delete_property_type/{id}', 'Admin\PropertyTypeController@delete');

	});

// Manage Home Page Cities Routes

	Route::group(['before' => 'manage_home_cities'], function () {

		Route::get('home_cities', 'Admin\HomeCitiesController@index');

		Route::match(array('GET', 'POST'), 'add_home_city', 'Admin\HomeCitiesController@add');

		Route::match(array('GET', 'POST'), 'edit_home_city/{id}', 'Admin\HomeCitiesController@update');

		Route::get('delete_home_city/{id}', 'Admin\HomeCitiesController@delete');

	});

// Manage Room Type Routes

	Route::group(['before' => 'manage_room_type'], function () {

		Route::get('room_type', 'Admin\RoomTypeController@index');

		Route::match(array('GET', 'POST'), 'add_room_type', 'Admin\RoomTypeController@add');

		Route::match(array('GET', 'POST'), 'edit_room_type/{id}', 'Admin\RoomTypeController@update');

		Route::get('delete_room_type/{id}', 'Admin\RoomTypeController@delete');

	});

// Manage Help Routes

	Route::group(['before' => 'manage_help'], function () {

		Route::get('help_category', 'Admin\HelpCategoryController@index');

		Route::match(array('GET', 'POST'), 'add_help_category', 'Admin\HelpCategoryController@add');

		Route::match(array('GET', 'POST'), 'edit_help_category/{id}', 'Admin\HelpCategoryController@update');

		Route::get('delete_help_category/{id}', 'Admin\HelpCategoryController@delete');

		Route::get('help_subcategory', 'Admin\HelpSubCategoryController@index');

		Route::match(array('GET', 'POST'), 'add_help_subcategory', 'Admin\HelpSubCategoryController@add');

		Route::match(array('GET', 'POST'), 'edit_help_subcategory/{id}', 'Admin\HelpSubCategoryController@update');

		Route::get('delete_help_subcategory/{id}', 'Admin\HelpSubCategoryController@delete');

		Route::get('help', 'Admin\HelpController@index');

		Route::match(array('GET', 'POST'), 'add_help', 'Admin\HelpController@add');

		Route::match(array('GET', 'POST'), 'edit_help/{id}', 'Admin\HelpController@update');

		Route::get('delete_help/{id}', 'Admin\HelpController@delete');

		Route::post('ajax_help_subcategory/{id}', 'Admin\HelpController@ajax_help_subcategory');

	});

// Manage Bed Type Routes

	Route::group(['before' => 'manage_bed_type'], function () {

		Route::get('bed_type', 'Admin\BedTypeController@index');

		Route::match(array('GET', 'POST'), 'add_bed_type', 'Admin\BedTypeController@add');

		Route::match(array('GET', 'POST'), 'edit_bed_type/{id}', 'Admin\BedTypeController@update');

		Route::get('delete_bed_type/{id}', 'Admin\BedTypeController@delete');

	});

// Manage Pages Routes

	Route::group(['before' => 'manage_pages'], function () {

		Route::get('pages', 'Admin\PagesController@index');

		Route::match(array('GET', 'POST'), 'add_page', 'Admin\PagesController@add');

		Route::match(array('GET', 'POST'), 'edit_page/{id}', 'Admin\PagesController@update');

		Route::get('delete_page/{id}', 'Admin\PagesController@delete');

	});

// Manage Currency Routes

	Route::group(['before' => 'manage_currency'], function () {

		Route::get('currency', 'Admin\CurrencyController@index');

		Route::match(array('GET', 'POST'), 'add_currency', 'Admin\CurrencyController@add');

		Route::match(array('GET', 'POST'), 'edit_currency/{id}', 'Admin\CurrencyController@update');

		Route::get('delete_currency/{id}', 'Admin\CurrencyController@delete');

	});

// Manage Coupon Code Routes

	Route::group(['before' => 'manage_coupon_code'], function () {

		Route::get('coupon_code', 'Admin\CouponCodeController@index');

		Route::match(array('GET', 'POST'), 'add_coupon_code', 'Admin\CouponCodeController@add');

		Route::match(array('GET', 'POST'), 'edit_coupon_code/{id}', 'Admin\CouponCodeController@update');

		Route::get('delete_coupon_code/{id}', 'Admin\CouponCodeController@delete');

	});
// Manage Language Routes

	Route::group(['before' => 'manage_language'], function () {

		Route::get('language', 'Admin\LanguageController@index');

		Route::match(array('GET', 'POST'), 'add_language', 'Admin\LanguageController@add');

		Route::match(array('GET', 'POST'), 'edit_language/{id}', 'Admin\LanguageController@update');

		Route::get('delete_language/{id}', 'Admin\LanguageController@delete');

	});

// Manage Country Routes

	Route::group(['before' => 'manage_country'], function () {

		Route::get('country', 'Admin\CountryController@index');

		Route::match(array('GET', 'POST'), 'add_country', 'Admin\CountryController@add');

		Route::match(array('GET', 'POST'), 'edit_country/{id}', 'Admin\CountryController@update');

		Route::get('delete_country/{id}', 'Admin\CountryController@delete');

	});

	Route::match(array('GET', 'POST'), 'api_credentials', 'Admin\ApiCredentialsController@index');

	Route::match(array('GET', 'POST'), 'payment_gateway', 'Admin\PaymentGatewayController@index');

	Route::match(array('GET', 'POST'), 'email_settings', 'Admin\EmailController@index');

	Route::match(array('GET', 'POST'), 'send_email', 'Admin\EmailController@send_email');

	Route::match(array('GET', 'POST'), 'site_settings', 'Admin\SiteSettingsController@index');

	Route::match(array('GET', 'POST'), 'theme_settings', 'Admin\ThemeSettingsController@index');

	Route::match(array('GET', 'POST'), 'referral_settings', 'Admin\ReferralSettingsController@index');

	Route::match(array('GET', 'POST'), 'fees', 'Admin\FeesController@index');

	Route::match(array('GET', 'POST'), 'fees/host_penalty_fees', 'Admin\FeesController@host_penalty_fees');

	Route::match(array('GET', 'POST'), 'metas', 'Admin\MetasController@index');

	Route::match(array('GET', 'POST'), 'edit_meta/{id}', 'Admin\MetasController@update');

	Route::match(array('GET', 'POST'), 'reviews', 'Admin\ReviewsController@index');

	Route::match(array('GET', 'POST'), 'wishlists', 'Admin\WishlistController@index');
	Route::match(array('GET', 'POST'), 'pick_wishlist/{id}', 'Admin\WishlistController@pick');

	Route::match(array('GET', 'POST'), 'edit_review/{id}', 'Admin\ReviewsController@update');

	Route::match(array('GET', 'POST'), 'join_us', 'Admin\JoinUsController@index');

// Admin Panel User Route Permissions

	Entrust::routeNeedsPermission('admin/users', 'users');

	Entrust::routeNeedsPermission('admin/edit_user*', 'edit_user');

	Entrust::routeNeedsPermission('admin/add_user', 'add_user');

	Entrust::routeNeedsPermission('admin/delete_user*', 'delete_user');

	Entrust::routeNeedsPermission('admin/rooms', 'rooms');

	Entrust::routeNeedsPermission('admin/edit_room*', 'edit_room');

	Entrust::routeNeedsPermission('admin/add_room', 'add_room');

	Entrust::routeNeedsPermission('admin/delete_room*', 'delete_room');

	Entrust::routeNeedsPermission('admin/reservations', 'reservations');
	Entrust::routeNeedsPermission('admin/host_penalty', 'reservations');

	Entrust::routeNeedsPermission('admin/reports', 'reports');

	Entrust::routeNeedsPermission('admin/api_credentials', 'api_credentials');

	Entrust::routeNeedsPermission('admin/payment_gateway', 'payment_gateway');

	Entrust::routeNeedsPermission('admin/email_settings', 'email_settings');

	Entrust::routeNeedsPermission('admin/send_email', 'send_email');

	Entrust::routeNeedsPermission('admin/site_settings', 'site_settings');

	Entrust::routeNeedsPermission('admin/theme_settings', 'site_settings');

	Entrust::routeNeedsPermission('admin/fees', 'manage_fees');

	Entrust::routeNeedsPermission('admin/referral_settings', 'manage_referral_settings');

	Entrust::routeNeedsPermission('admin/metas', 'manage_metas');

	Entrust::routeNeedsPermission('admin/edit_meta*', 'manage_metas');

	Entrust::routeNeedsPermission('admin/join_us', 'join_us');

	Entrust::routeNeedsPermission('admin/reviews', 'manage_reviews');

	Entrust::routeNeedsPermission('admin/edit_review/{id}', 'manage_reviews');

	Entrust::routeNeedsPermission('admin/wishlists', 'manage_wishlists');

});

Route::group(['prefix' => 'admin', 'before' => 'admin_no_auth'], function () {

	Route::get('login', 'Admin\AdminController@login');

});

Route::post('admin/authenticate', 'Admin\AdminController@authenticate');

Route::get('admin/create', 'Admin\AdminController@create');

Route::filter('admin_guest', function () {
	if (Auth::admin()->guest()) {
		return Redirect::to('admin/login');
	}
});

// Filter for check the admin user is logged in or not before goto login page
Route::filter('admin_no_auth', function () {
	if (Auth::admin()->check()) {
		return Redirect::to('admin/dashboard');
	}
});

Route::filter('manage_admin', function () {
	if (!Entrust::can('manage_admin')) {
		return abort(403);
	}
});

Route::filter('manage_amenities', function () {
	if (!Entrust::can('manage_amenities')) {
		return abort(403);
	}
});

Route::filter('manage_property_type', function () {
	if (!Entrust::can('manage_property_type')) {
		return abort(403);
	}
});

Route::filter('manage_room_type', function () {
	if (!Entrust::can('manage_room_type')) {
		return abort(403);
	}
});

Route::filter('manage_help', function () {
	if (!Entrust::can('manage_help')) {
		return abort(403);
	}
});

Route::filter('manage_bed_type', function () {
	if (!Entrust::can('manage_bed_type')) {
		return abort(403);
	}
});

Route::filter('manage_currency', function () {
	if (!Entrust::can('manage_currency')) {
		return abort(403);
	}
});

Route::filter('manage_coupon_code', function () {
	if (!Entrust::can('manage_coupon_code')) {
		return abort(403);
	}
});

Route::filter('manage_language', function () {
	if (!Entrust::can('manage_language')) {
		return abort(403);
	}
});

Route::filter('manage_country', function () {
	if (!Entrust::can('manage_country')) {
		return abort(403);
	}
});

Route::group(['middleware' => 'locale'], function () {
	Route::get('{name}', 'HomeController@static_pages');
});
// API WebService

Route::group(['prefix' => 'api'], function () {

	Route::post('register', 'Api\TokenAuthController@register');
	Route::post('authenticate', 'Api\TokenAuthController@authenticate');
	Route::post('token', 'Api\TokenAuthController@token');

	Route::group(['middleware' => 'jwt.auth'], function () {
		Route::post('authenticate/user', 'Api\TokenAuthController@getAuthenticatedUser');
		Route::post('user_details', 'Api\UserController@user_details');
		Route::post('home_cities', 'Api\UserController@home_cities');
	});
});
