@extends('template')
   
@section('main')
	
<main role="main" id="site-content">

<div class="page-container-responsive">
  <div class="row-space-top-6 row-space-16 text-wrap">
    {!! $content !!}
  </div>
</div>

</main>
   
@stop