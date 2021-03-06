<!-- Add the sidebar's background. This div must be placed
       immediately after the control sidebar -->
  <div class="control-sidebar-bg"></div>
</div>
<!-- ./wrapper -->

<!-- jQuery 2.1.4 -->
<script src="{{ url('admin_assets/plugins/jQuery/jQuery-2.1.4.min.js') }}"></script>
<script src="{{ url('admin_assets/plugins/jQueryUI/jquery-ui.min.js') }}"></script>

<script src="{{ url('js/angular.js') }}"></script>
<script src="{{ url('js/angular-sanitize.js') }}"></script>

<script>
var app = angular.module('App', ['ngSanitize']);
var APP_URL = {!! json_encode(url('/')) !!};
</script>

<!-- Resolve conflict in jQuery UI tooltip with Bootstrap tooltip -->
<script>
  $.widget.bridge('uibutton', $.ui.button);
</script>

<!-- Bootstrap 3.3.5 -->
<script src="{{ url('admin_assets/bootstrap/js/bootstrap.min.js') }}"></script>

@if (!isset($exception))

    @if (Route::current()->uri() == 'admin/dashboard')
    	<!-- Morris.js charts -->
      <script src="{{ url('admin_assets/plugins/morris/raphael-min.js') }}"></script>
      <script src="{{ url('admin_assets/plugins/morris/morris.min.js') }}"></script>
      <!-- datepicker -->
      <script src="{{ url('admin_assets/plugins/datepicker/bootstrap-datepicker.js') }}"></script>
      <!-- AdminLTE dashboard demo (This is only for demo purposes) -->
		  <script src="{{ url('admin_assets/dist/js/dashboard.js') }}"></script>
    @endif

     @if (Route::current()->uri() == 'admin/add_user' || Route::current()->uri() == 'admin/edit_user/{id}')
      <script src="{{ url('admin_assets/plugins/datepicker/bootstrap-datepicker.js') }}"></script>
    @endif

    @if (Route::current()->uri() == 'admin/add_coupon_code' || Route::current()->uri() == 'admin/edit_coupon_code/{id}')
      <script src="{{ url('admin_assets/plugins/datepicker/bootstrap-datepicker.js') }}"></script>
    @endif

    @if (Route::current()->uri() == 'admin/religious_amenities' || Route::current()->uri() == 'admin/religious_amenities_type' || Route::current()->uri() == 'admin/users' || Route::current()->uri() == 'admin/reservations' || Route::current()->uri() == 'admin/host_penalty' || Route::current()->uri() == 'admin/admin_users' || Route::current()->uri() == 'admin/roles' || Route::current()->uri() == 'admin/permissions' || Route::current()->uri() == 'admin/amenities' || Route::current()->uri() == 'admin/amenities_type' || Route::current()->uri() == 'admin/property_type' || Route::current()->uri() == 'admin/room_type' || Route::current()->uri() == 'admin/bed_type' || Route::current()->uri() == 'admin/currency' || Route::current()->uri() == 'admin/language' || Route::current()->uri() == 'admin/country' || Route::current()->uri() == 'admin/api_credentials' || Route::current()->uri() == 'admin/payment_gateway' || Route::current()->uri() == 'admin/site_settings' || Route::current()->uri() == 'admin/rooms' || Route::current()->uri() == 'admin/pages' || Route::current()->uri() == 'admin/metas' || Route::current()->uri() == 'admin/home_cities' || Route::current()->uri() == 'admin/reviews' || Route::current()->uri() == 'admin/help_category' || Route::current()->uri() == 'admin/help_subcategory' || Route::current()->uri() == 'admin/help' || Route::current()->uri() == 'admin/coupon_code' || Route::current()->uri() == 'admin/wishlists')
      <script src="{{ url('admin_assets/plugins/datatables/jquery.dataTables.min.js') }}"></script>
      <script src="{{ url('admin_assets/plugins/datatables/dataTables.bootstrap.min.js') }}"></script>
    @endif

    @if (Route::current()->uri() == 'admin/add_room' || Route::current()->uri() == 'admin/edit_room/{id}')
      <script type="text/javascript" src="https://maps.googleapis.com/maps/api/js?sensor=false&libraries=places&key={{ SERVER_MAP_KEY }}"></script>
      <script src="{{ url('admin_assets/plugins/jQuery/jquery.validate.js') }}"></script>
      <script src="{{ url('admin_assets/dist/js/rooms.js') }}"></script>
    @endif

    @if (Route::current()->uri() == 'admin/reports')
    <script src="{{ url('admin_assets/dist/js/reports.js') }}"></script>
    @endif

    @if (Route::current()->uri() == 'admin/add_page' || Route::current()->uri() == 'admin/edit_page/{id}' || Route::current()->uri() == 'admin/send_email' || Route::current()->uri() == 'admin/add_help' || Route::current()->uri() == 'admin/edit_help/{id}')
    <script src="{{ url('admin_assets/plugins/editor/editor.js') }}"></script>
      <script type="text/javascript">
        $("[name='submit']").click(function(){
          $('#content').text($('#txtEditor').Editor("getText"));
          $('#message').text($('#txtEditor').Editor("getText"));
          $('#answer').text($('#txtEditor').Editor("getText"));
        });
      </script>
    @endif

@endif

<!-- AdminLTE App -->
<script src="{{ url('admin_assets/dist/js/app.js') }}"></script>
<script src="{{ url('admin_assets/dist/js/common.js') }}"></script>

<!-- AdminLTE for demo purposes -->
<script src="{{ url('admin_assets/dist/js/demo.js') }}"></script>

@stack('scripts')

<script type="text/javascript">
  $('#dataTableBuilder_length').addClass('dt-buttons');
  $('#dataTableBuilder_wrapper > div:not("#dataTableBuilder_length").dt-buttons').css('margin-left','20%');
</script>
{!! Html::script('js/jquery-ui.js') !!}
{!! Html::script('js/i18n/datepicker-en.js') !!}
</body>
</html>