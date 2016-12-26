
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
      @endif

      @if (Route::current()->uri() == 's')
        {!! Html::script('js/search.js') !!}
		{!! Html::script('js/infobubble.js') !!}
      @endif

      @if (Route::current()->uri() == 'rooms/{id}')
        {!! Html::script('js/rooms.js') !!}
        {!! Html::script('js/jquery.bxslider.min.js') !!}
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

    @endif

    @stack('scripts')
 
<!-- ver. 87c23752f8dfbd60bf83837d2c8b2dcd0ec660a9 -->
<div class="tooltip tooltip-bottom-middle" role="tooltip" aria-hidden="true">  
	<p class="panel-body">To sign up, you must be 18 or older. Other people won’t see your birthday.</p>
	</div></body></html>