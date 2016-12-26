﻿@extends('template')

@section('main')
  
<main id="site-content" role="main" ng-controller="help">

<div id="help-search-container-banner-id" class="media-photo media-photo-block help-search-container help-search-container-banner">
  <div class="media-cover background-cover help-search-bg"></div>
  <div class="va-container va-container-v va-container-h">
    <div class="va-middle text-center text-contrast">
      <div class="hide-sm">
        <h1>
          {{ trans('messages.help.welcome') }}
        </h1>
      </div>
      <div class="page-container-responsive">
        <div class="row">
          <form class="help-search-form col-sm-12 col-md-10 col-md-offset-1">
            <div id="help-search-container"><div class="text-left search-container"><div class="search-input-container"><div class="icon-search-container"><i class="icon-light-gray icon icon-size-2 icon-search article-link-icon"></i></div><input class="search-input" type="text" name="q" autocomplete="off" maxlength="1024" value="" placeholder="{{ trans('messages.help.search_anything') }}" id="help_search"></div></div></div>
          </form>
        </div>
      </div>
    </div>
  </div>
</div>

<div class="subnav">
  <div class="page-container page-container-responsive">
    <ul class="subnav-list">
      <li>
        <a class="subnav-item" href="{{ url('help') }}" data-node-id="0" aria-selected="{{ ((@$is_subcategory != 'no' || Route::current()->uri() != 'help/topic/{id}/{category}') && (@$is_subcategory != 'no' || Route::current()->uri() != 'help/article/{id}/{question}') && Route::current()->uri() != 'help') ? 'false' : 'true' }}">
          {{ trans('messages.help.help_center') }}
        </a>
      </li>
      @if ((@$is_subcategory != 'no' || Route::current()->uri() != 'help/topic/{id}/{category}') && (@$is_subcategory != 'no' || Route::current()->uri() != 'help/article/{id}/{question}') && Route::current()->uri() != 'help')
      <li>
        <a class="subnav-item" href="#" data-node-id="0" aria-selected="true">
          {{ @$result[0]->category_name }}
        </a>
      </li>
      @endif
    </ul>
  </div>
</div>


<div class="page-container page-container-responsive">
  <div class="row row-space-6 space-top-lg-6 space-top-md-4">
    <div class="col-md-3 left-menu">
      <div class="navtree">
        <ul class="sidenav-list navtree-list" id="navtree" style="display: block; {{ (Route::current()->uri() == 'help' || $is_subcategory == 'no') ? 'left: 0px;' : 'left: -300px;' }}">
       @for($i=0; $i<count($category); $i++) 
    <li>
    <a href="{{ (count($category[$i]->subcategory)) ? 'javascript:void(0);' : url('help/topic/'.$category[$i]->category_id.'/'.str_slug($category[$i]->category_name,'-')) }}" class="sidenav-item {{ (count($category[$i]->subcategory)) ? 'navtree-next' : '' }}" data-id="{{ $category[$i]->category_id }}" data-name="{{ $category[$i]->category->name }}" aria-selected="{{ ((Route::current()->uri() == 'help/topic/{id}/{category}' || Route::current()->uri() == 'help/article/{id}/{question}') && ($category[$i]->category->id == @$result[0]->category_id)) ? 'true' : 'false' }}"> {{ $category[$i]->category->name }}
      <span class="show-sm"><i class="icon icon-chevron-right"></i></span>
    </a>
    @if(count($category[$i]->subcategory))
      <ul class="sidenav-list navtree-list" id="navtree-{{ $category[$i]->category_id }}" style="{{ (Route::current()->uri() == 'help/topic/{id}/{category}' || Route::current()->uri() == 'help/article/{id}/{question}') ? ((@$result[0]->category_id == $category[$i]->category->id) ? 'display:block;' : '') : '' }}">
  <li>
    <a href="javascript:void(0);" class="sidenav-item navtree-back" data-id="{{ $category[$i]->category_id }}" data-name="{{ $category[$i]->category->name }}">
        <i class="icon icon-arrow-left"></i>
        {{ trans('messages.lys.back') }}
    </a>
  </li>
  @for($j=0; $j<count($category[$i]->subcategory); $j++)
  @if($category[$i]->subcategory_($category[$i]->subcategory[$j]->id)->count())
  <li>
    <a href="{{ url('help/topic/'.$category[$i]->subcategory[$j]->id.'/'.str_slug($category[$i]->subcategory[$j]->name,'-')) }}" class="sidenav-item" aria-selected="{{ (@$result[0]->subcategory_id == $category[$i]->subcategory[$j]->id && Route::current()->uri() != 'help') ? 'true' : 'false' }}">{{ $category[$i]->subcategory[$j]->name }}
      <span class="show-sm"><i class="icon icon-chevron-right"></i></span>
    </a>
  </li>
  @endif
  @endfor
</ul>
@endif
  </li>
  @endfor
</ul>

      </div>
    </div>

    @if (Route::current()->uri() == 'help/topic/{id}/{category}')
<div class="col-sm-12 col-sm-offset-0 col-md-8 col-offset-1 help-content text-copy navtree-content breadcrumbs-content">
      <div class="h3 space-md-2 space-lg-4 help-center-sm">
      {{ (@$subcategory_count == 0) ? @$result[0]->category_name : @$result[0]->subcategory_name }}
      </div>

      @foreach($result as $row)
    <a href="{{ url('help/article/'.$row->id.'/'.str_slug($row->question,'-')) }}" class="article-link link-reset article-link-panel">
      <div class="col-middle-alt article-link-left">
        <i class="icon icon-light-gray icon-size-2 icon-description article-link-icon"></i>
      </div><div class="col-middle-alt article-link-right">
        {{ str_replace('SITE_NAME', $site_name, $row->question) }}
      </div>
    </a>
      @endforeach
    </div>
@elseif (Route::current()->uri() == 'help/article/{id}/{question}')
<div class="col-sm-12 col-sm-offset-0 col-md-8 col-offset-1 help-content text-copy navtree-content breadcrumbs-content">
      
<div class="help-center-sm">
  <div class="space-8">
    <h2>{{ str_replace('SITE_NAME', $site_name, $result[0]->question) }}</h2>
      <div class="text-copy space-8">
      {!! str_replace('SITE_NAME', $site_name, $result[0]->answer) !!}
      </div>
  </div>
</div>
    </div>
@else
      <div class="col-md-8 col-offset-1 help-content text-copy navtree-content breadcrumbs-content">
      
<div class="popular-topics">
  <div class="row row-space-6">
    <div class="h2 space-md-2 space-lg-2 help-section-header">
      <h2>{{ trans('messages.help.suggested_helps') }}</h2>
    </div>
      <div class="homepage-articles-list">
      @foreach($result as $row)
      <a href="{{ url('help/article/'.$row->id.'/'.str_slug($row->question,'-')) }}" class="article-link homepage-article-link-panel link-reset">
        <div class="article-link-left col-middle-alt">
          <i class="icon icon-size-2 icon-light-gray icon-description article-link-icon"></i>
        </div><div class="article-link-right col-middle-alt">
        {{ str_replace('SITE_NAME', $site_name, $row->question) }}
      </div>
      </a>
      @endforeach
      @if($result->count() == 0)
       {{ trans('messages.help.no_suggested_helps') }}
      @endif
      </div>
  </div>
</div>
      </div>
@endif

    </div>
  </div>

</div>

@stop