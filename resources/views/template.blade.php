@include('common.head')

@include('common.header')

@yield('main')

@if (!isset($exception))
	@if (Route::current()->uri() != 'payments/book/{id?}' && Route::current()->uri() != 'reservation/receipt')
		@include('common.footer')
	@endif
@else
	@include('common.footer')
@endif

@include('common.foot')