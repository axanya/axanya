@extends('template')

@section('main')
    <main id="site-content" role="main">

        <div class="hero shift-with-hiw js-hero">
            <div class="hero__content page-container">
                <div class="textHeaderContainer_1sy11fq text-center">
                    <h1 class="textHeader_1o0y24x">
                        <img src="images/top_homepage.png" style="display:block;">

                        <span>{{ trans('messages.home.text1') }}<br></span>
                        <span>{{ trans('messages.home.text2') }}</span>
                    </h1>
                </div>
                <div class="col-sm-12 hide show-sm">
                    <div tabindex="0">
                        <div class="container_9c682r  search-modal-trigger">
                            <div class="inputContainer_2ci0p">
                                <span class="fakeInput_exzbqf">{{ trans('messages.home.search') }}</span>
                            </div>
                            <button class="button_1hueyqh">
                                <svg viewBox="0 0 24 24" style="fill:#000000;height:24px;width:24px;display:block;"
                                     data-reactid=".1w6f1a8jbb4.1.0.2.0.0.1.0">
                                    <path d="M23.53 22.47l-6.807-6.808A9.455 9.455 0 0 0 19 9.5 9.5 9.5 0 1 0 9.5 19c2.353 0 4.502-.86 6.162-2.277l6.808 6.807a.75.75 0 0 0 1.06-1.06zM9.5 17.5a8 8 0 1 1 0-16 8 8 0 0 1 0 16z"
                                          data-reactid=".1w6f1a8jbb4.1.0.2.0.0.1.0.0"></path>
                                </svg>
                            </button>
                        </div>
                    </div>
                </div>
                <div class="hero__content-footer hide-sm SearchForm">
                    <div class="col-sm-12">
                        <div id="searchbar">
                            <div class="searchbar" data-reactid=".1">
                                <form action="{{ url('s') }}" class="simple-search" method="get" id="searchbar-form"
                                      name="simple-search" autocomplete="off">

                                    <div class="SearchForm__inputs-wrapper col-md-12">
                                        <div class="SearchForm__row row">
                                            <div class="SearchForm__location col-lg-4 col-md-3 SearchForm__location_infants">
                                                <label for="search-location"
                                                       class="SearchForm__label"><span>{{ trans('messages.home.where') }}</span></label>
                                                <div>
                                                    <div>
                                                        <label class="input-placeholder-group searchbar__location">
                                                            <span class="input-placeholder-label  screen-reader-only">{{ trans('messages.home.where_do_you_go') }}</span>
                                                            <input class="menu-autocomplete-input form-inline location input-large input-contrast"
                                                                   placeholder="{{ trans('messages.home.where_do_you_go') }}"
                                                                   type="text" name="location" id="location"
                                                                   aria-autocomplete="both" autocomplete="off" value="">
                                                        </label>
                                                        <div class="searchbar__location-error hide">{{ trans('messages.home.search_validation') }}</div>
                                                    </div>
                                                    <div class="DropDownWrapper col-md-12"></div>
                                                </div>
                                            </div>
                                            <div class="SearchForm__dates text-left col-md-4">
                                                <label for="startDate"
                                                       class="SearchForm__label"><span>{{ trans('messages.home.when') }}</span></label>
                                                <div class="DateInput">
                                                    <label class="input-placeholder-group searchbar__checkin">
                                                        <span class="input-placeholder-label  screen-reader-only">{{ trans('messages.home.checkin') }}</span>
                                                        <input type="text" id="checkin"
                                                               class="checkin input-large input-contrast ui-datepicker-target"
                                                               name="checkin"
                                                               placeholder="{{ trans('messages.home.checkin') }}">
                                                    </label>
                                                </div>
                                                <div class="DateRangePickerInput__arrow">
                                                    <svg viewBox="0 0 1000 1000">
                                                        <path d="M694.4 242.4l249.1 249.1c11 11 11 21 0 32L694.4 772.7c-5 5-10 7-16 7s-11-2-16-7c-11-11-11-21 0-32l210.1-210.1H67.1c-13 0-23-10-23-23s10-23 23-23h805.4L662.4 274.5c-21-21.1 11-53.1 32-32.1z"
                                                              data-reactid=".easj7z7gu8.1.0.1.0.0.0.0.1.1.0.1.0.0"></path>
                                                    </svg>
                                                </div>
                                                <div class="DateInput DateInput--with-caret">
                                                    <label class="input-placeholder-group searchbar__checkout">
                                                        <span class="input-placeholder-label screen-reader-only"> {{ trans('messages.home.checkout') }}</span>
                                                        <input type="text" id="checkout"
                                                               class="checkout input-large input-contrast ui-datepicker-target"
                                                               name="checkout"
                                                               placeholder=" {{ trans('messages.home.checkout') }}">
                                                    </label>
                                                </div>
                                            </div>
                                            <div class="SearchForm__guests text-left SearchForm__guests_infants col-md-3 col-lg-2">
                                                <label for="how-many-guests"
                                                       class="SearchForm__label"><span>{{ trans_choice('messages.home.guest',2) }}</span></label>
                                                <label class="searchbar__guests">
                                                    <span class="screen-reader-only">{{ trans('messages.home.no_of_guests') }}</span>
                                                    <div class="select select-large">
                                                        <select id="guests" name="guests">
                                                            @for($i=1;$i<=16;$i++)
                                                                <option value="{{ $i }}"> {{ ($i == '16') ? $i.'+ '.trans_choice('messages.home.guest',$i) : $i.' '.trans_choice('messages.home.guest',$i) }} </option>
                                                            @endfor
                                                        </select>
                                                    </div>
                                                </label>
                                            </div>
                                            <div class="SearchForm__submit col-lg-2 col-md-2">
                                                <input type="hidden" name="source" value="bb">
                                                <button id="submit_location" type="submit"
                                                        class="searchbar__submit btn btn-primary btn-large">{{ trans('messages.home.search') }}</button>
                                            </div>
                                        </div>
                                        <div id="autocomplete-menu-sbea76915" aria-expanded="false" class="menu hide"
                                             aria-role="listbox">
                                            <div class="menu-section">
                                            </div>
                                        </div>
                                    </div>
                                {!! Form::close() !!}

                            </div>
                        </div>

                    </div>
                </div>
            </div>
            <div class="hero__background page-container">
                <div class="hero__content1 page-container">
                    <div class="col-lg-8 why-host-div">
                        <h2 class="headerText_17uhuaw">{{ trans('messages.home.why_host') }}</h2>
                        <p class="subtitle_1eiruqk">{{ trans('messages.home.why_host_text') }}</p>
                        <a href="{{ url('why_host') }}" class="host-button">{{ trans('messages.home.learn_more') }}</a>
                    </div>
                </div>
            </div>


            <div class="hero__content page-container">

                <div class="hide show-md show-lg">

                    <section class="how-it-works how-it-works--overlay js-how-it-works" aria-hidden="true"
                             style="top: 0px;display:none;height:361px;">
                        <a href="javascript:void(0);" class="how-it-works__close panel-close js-close-how-it-works">
      <span class="screen-reader-only">
        {{ trans('messages.home.close') }} {{ trans('messages.home.how_it_works') }}
      </span>
                        </a>

                        <div class="page-container-responsive panel-contrast text-contrast">

                            <h2 class="screen-reader-only">
                                {{ trans('messages.home.how_it_works') }}
                            </h2>

                            <div class="row space-top-8 text-center">

                                <div class="how-it-works__step how-it-works__step-one col-md-4">
                                    <div class="panel-body">
                                        <div class="how-it-works__image"></div>
                                        <h3>
                                            {{ trans('messages.home.discover_places') }}
                                        </h3>
                                        <p>
                                            {{ trans('messages.home.discover_places_desc') }}
                                        </p>
                                    </div>
                                </div>

                                <div class="how-it-works__step how-it-works__step-two col-md-4">
                                    <div class="panel-body">
                                        <div class="how-it-works__image"></div>
                                        <h3>
                                            {{ trans('messages.home.book_stay') }}
                                        </h3>
                                        <p>
                                            {{ trans('messages.home.book_stay_desc', ['site_name'=>$site_name]) }}
                                        </p>
                                    </div>
                                </div>

                                <div class="how-it-works__step how-it-works__step-three col-md-4">
                                    <div class="panel-body">
                                        <div class="how-it-works__image"></div>
                                        <h3>
                                            {{ trans('messages.home.travel') }}
                                        </h3>
                                        <p>
                                            {{ trans('messages.home.travel_desc') }}
                                        </p>
                                    </div>
                                </div>

                            </div>

                        </div>
                    </section>

                </div>


                <div class="panel">
                    <div id="discovery-container">
                        <div class="discovery-section page-container-responsive page-container-no-padding row-space-6"
                             id="discover-recommendations">
                            <div class="section-intro text-center row-space row-space-top">
                                <h2 class="row-space-1 rowHeader_e3x0j">
                                    {{ trans('messages.home.explore_world') }}
                                </h2>
                            </div>
                            <div class="discovery-tiles">

                                <div class="row">
                                    @for($i=0;$i<= $city_count-1;$i++)

                                        @if($i ==0 || $i%10 ==0 || $i ==6)

                                            <div class="col-lg-6 col-md-12 rm-padding-sm">

                                                @else

                                                    {{--*/ $j = 6 /*--}}

                                                    {{--*/ $x = 8 /*--}}

                                                    {{--*/ $z = 9 /*--}}

                                                    @if($i == $j || $i == $j+10 || $i==$x || $i==$x+10)

                                                        <div class="col-lg-3 col-md-12 rm-padding-sm hide-sm half">

                                                            @else
                                                                @if($i == $z )
                                                                    <div class="col-lg-3  col-md-6 col-sm-12 rm-padding-sm half last">
                                                                        @else
                                                                            <div class="col-lg-3 col-md-6 col-sm-12 rm-padding-sm half">
                                                                                @endif
                                                                                @endif
                                                                                @endif

                                                                                <div class="discovery-card rm-padding-sm row-space-4 darken-on-hover "
                                                                                     style="background-image:url({{ $home_city[$i]->image_url }});">
                                                                                    <a href="{{URL::to('/')}}/s?location={{$home_city[$i]->name}}&source=ds"
                                                                                       class="link-reset"
                                                                                       data-hook="discovery-card">
                                                                                        <div class="va-container va-container-v va-container-h">
                                                                                            <div class="titleContainer_1gmn22x">
                                                                                                <strong>
                                                                                                    {{$home_city[$i]->name}}
                                                                                                </strong>
                                                                                            </div>
                                                                                        </div>
                                                                                    </a>
                                                                                </div>

                                                                            </div>

                                                                            @endfor
                                                                    </div>
                                                        </div>
                                                        <div class="col-lg-12 nopad text-center hide">
                                                            <h2 class="row-space-1">
                                                                <strong>Our Community</strong>
                                                            </h2>
                                                            <div class="col-lg-4 pos-rel com-img pad-left col-md-4">
                                                                <a class="com-link-img" href="">
                                                                    <img src="images/community-1.jpg" width="100%">
                                                                </a>
                                                                <div class="com-header">
                                                                    <a>Travelling</a></div>
                                                                <div class="com-sub">
                                                                    <h2>Garry & Lianne</h2>
                                                                    <p>Across an ocean or across town, garry & Lianne
                                                                        are always in search in local experiences</p>
                                                                    <a href="#" class="com-sub-link">Learn more about
                                                                        travel on Axanya</a>
                                                                </div>
                                                            </div>
                                                            <div class="col-lg-4 pos-rel com-img col-md-4">
                                                                <a class="com-link-img" href="">
                                                                    <img src="images/community-2.jpg" width="100%"></a>
                                                                <div class="com-header">
                                                                    <a class="com-header-2">Business Travel</a></div>
                                                                <div class="com-sub com-sub-2">
                                                                    <h2>Axanya for Business</h2>
                                                                    <p>Feel at home. wherever your work takes you </p>
                                                                    <a href="#" class="com-sub-link">Get your team on
                                                                        Axanya</a>
                                                                </div>
                                                            </div>
                                                            <div class="col-lg-4 pos-rel com-img pad-right col-md-4">
                                                                <a class="com-link-img" href="">
                                                                    <img src="images/community-3.jpg" width="100%"></a>
                                                                <div class="com-header">
                                                                    <a class="com-header-3">Hosting</a></div>
                                                                <div class="com-sub">
                                                                    <h2>Patricia</h2>
                                                                    <p>A professional photographer, patricia loves
                                                                        helping guests explore Shanghai's arts
                                                                        scene. </p>
                                                                    <a href="#" class="com-sub-link">Learn more about
                                                                        hosting on Axanya</a>
                                                                </div>
                                                            </div>
                                                        </div>
                                            </div>


                                </div>

                            </div>

                            <!--[if (gt IE 8)|!(IE)]><!-->
                        <!--<div id="belong-video-player" class="fullscreen-video-player fullscreen-video-player--hidden" aria-hidden="true">
  <div class="row row-table row-full-height">
    <div class="col-sm-12 col-middle text-center">
      <video preload="none">
  <source src="{{ $home_video }}" type="video/mp4">
</video>

      <i id="play-button-belong" class="fullscreen-video-player__icon icon icon-video-play icon-white hide"></i>
      <a id="close-fullscreen-belong" class="fullscreen-video-player__close link-reset" href="{{URL::to('/')}}/#">
        <i class="icon icon-remove"></i>
        <span class="screen-reader-only">
          {{ trans('messages.home.exit_full_screen') }}
                                </span>
                              </a>
                            </div>
                          </div>
                        </div>
                        <div id="belo-video-player" class="fullscreen-video-player fullscreen-video-player--hidden" aria-hidden="true">
                          <div class="row row-table row-full-height">
                            <div class="col-sm-12 col-middle text-center">
                              <video preload="none">
                          <source src="{{ $home_video }}" type="video/mp4">
</video>

      <i id="play-button-belo" class="fullscreen-video-player__icon icon icon-video-play icon-white hide"></i>
      <a id="close-fullscreen-belo" class="fullscreen-video-player__close link-reset" href="{{URL::to('/')}}/#">
        <i class="icon icon-remove"></i>
        <span class="screen-reader-only">
          {{ trans('messages.home.exit_full_screen') }}
                                </span>
                              </a>
                            </div>
                          </div>
                        </div>-->
                            <!--<![endif]-->
                        </div>
                    </div>
                </div>


            </div>

        </div>
    </main>

    <div id="welcomepopup" class="modal-box" style="display:none;">
        <a href="#" class="js-modal-close close">×</a>
        <!-- <a href="/rooms/new"><img src="images/ax_disaster_desktop.png" style="display:block;" class="hide-sm show-md show-lg"><img src="images/axanya_disaster_mobile.png" style="display:block;" class="hide-md hide-md show-sm"></a> -->
        <div class="datareduce1">
            <div class="axanya_poplt">
                <img src="images/ax1.png" alt="">
            </div>
            <div class="axanya_poprt">
                <img src="images/ax2.png" alt="">
                <h1>{{ trans('messages.home.disaster_relief') }}</h1>
                <p>{{ trans('messages.home.popup_text') }}<br>{{ trans('messages.home.popup_text1') }}</p>
                <div class="button_axy">
                    <a href="/rooms/new">{{ trans('messages.home.open_your_home') }}</a>
                </div>
            </div>
        </div>

    </div>
    <a class="js-open-modal" href="#" data-modal-id="welcomepopup"></a>
@stop