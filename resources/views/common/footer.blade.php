 <div id="footer" class="container-brand-light footer-surround footer-container">
 <footer class="page-container-responsive" ng-controller="footer">
  <div class="row row-condensed">

    <div class="col-md-2">
      <div class="language-curr-picker clearfix">
        <div class="select select-large select-block row-space-2">
  <label id="language-selector-label" class="screen-reader-only">
    {{ trans('messages.footer.choose_language') }}
  </label>
 {!! Form::select('language',$language, (Session::get('language')) ? Session::get('language') : $default_language[0]->value, ['class' => 'language-selector', 'aria-labelledby' => 'language-selector-label', 'id' => 'language_footer']) !!}
</div>

          
<div class="select select-large select-block row-space-2">
  <label id="currency-selector-label" class="screen-reader-only">{{ trans('messages.footer.choose_currency') }}</label>
  {!! Form::select('currency',$currency, (Session::get('currency')) ? Session::get('currency') : $default_currency[0]->code, ['class' => 'currency-selector', 'aria-labelledby' => 'currency-selector-label', 'id' => 'currency_footer']) !!}
</div>

        <div class="cx-number"></div>
      </div>
    </div>

    <div class="col-md-2 col-md-offset-1 hide-sm">
      <h2 class="h5">{{ trans('messages.footer.company') }}</h2>
      <ul class="list-layout">
		<li><a href="{{ url('contact') }}" class="link-contrast">{{ trans('messages.footer.contact') }}</a></li>
      @foreach($company_pages as $company_page)
        <li><a href="{{ url($company_page->url) }}" class="link-contrast">{{ $company_page->name }}</a></li>		
      @endforeach
		
      </ul>
    </div>

    <div class="col-md-2 col-md-offset-1 hide-sm">
      <h2 class="h5">{{ trans('messages.footer.discover') }}</h2>
      <ul class="list-layout">
        <li><a href="{{ url('invite') }}" class="link-contrast">{{ trans('messages.footer.invite_friends') }}</a></li>
      @foreach($discover_pages as $discover_page)
        <li><a href="{{ url($discover_page->url) }}" class="link-contrast">{{ $discover_page->name }}</a></li>
      @endforeach
      </ul>
    </div>

    <div class="col-md-2 col-md-offset-1 hide-sm">
      <h2 class="h5">{{ trans('messages.footer.hosting') }}</h2>
      <ul class="list-layout">
      @foreach($hosting_pages as $hosting_page)
        <li><a href="{{ url($hosting_page->url) }}" {{$hosting_page->url == 'kosher' ?'target=_blank':''}} class="link-contrast">{{ $hosting_page->name }}</a></li>
      @endforeach         
      </ul>
    </div>
  </div>
  <div class="col-sm-12 space-4 space-top-4 hide">
    <ul class="list-layout list-inline text-center h5">
      @foreach($company_pages as $company_page)
        <li><a href="{{ url($company_page->url) }}" class="link-contrast">{{ $company_page->name }}</a></li>
      @endforeach
    </ul>
  </div>
<hr class="footer-divider space-top-8 space-4 hide-sm">
<hr class="footer-divider show-sm">

<div class="footer-table">
      <ul class="list-layout list-inline icon-list" itemscope="" itemtype="http://schema.org/Organization">
        <i class="footer-logoicon" style="{{ (!isset($exception)) ? (Route::current()->uri() == '/' ? $home_logo_style : $logo_style) : $logo_style }}"></i>
          <div class="text-muted">
            © {{ $site_name }}, Inc.
          </div>
      </ul>
      <ul class="list-layout list-inline icon-list right-list" itemscope="" itemtype="http://schema.org/Organization">
           <div class="hide-sm">
            <link itemprop="url" href="">
			  <meta itemprop="logo" content="">
			  @for($i=0; $i<count($join_us); $i++)
				   @if($join_us[$i]->value != '')
					<li>
					  <a href="{{ $join_us[$i]->value }}" class="link-contrast footer-icon-container" target="_blank">
						<span class="screen-reader-only">{{ ucfirst($join_us[$i]->name) }}</span>
						<i class="icon footer-icon icon-{{ str_replace('_','-',$join_us[$i]->name) }}"></i> 
					  </a>        
					</li>
				@endif
			  @endfor
        </div>
      </ul>
    </div>
</footer>
</div>