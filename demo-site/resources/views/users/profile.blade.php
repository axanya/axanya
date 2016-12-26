@extends('template')
@section('main')
    <main id="site-content" role="main">

        <div id="recommendation_container" class="clearfix container">
        </div>

        <div class="page-container page-container-responsive row-space-top-4 row-space-8">
            <div class="row">
                <div class="col-lg-3 col-md-4 hide-sm">
                    <div id="user" class="row-space-4">
                        <div>
                            <div class="row-space-2" id="user-media-container">
                                <div id="slideshow" class="slideshow">
                                    <div class="slideshow-preload"></div>
                                    <ul class="slideshow-images">
                                        <li class="active media-photo media-photo-block">
                                            <span class="img-responsive" title="{{ $result->first_name }}"
                                                  style="height:225px; width:225px; background-image:url({{ $result->profile_picture->src }})"></span>
                                        </li>
                                        <li class="media-photo media-photo-block"></li>
                                    </ul>
                                </div>
                            </div>
                        </div>
                    </div>

                    @if($result->school || $result->work)
                        <div class="panel row-space-4">
                            <div class="panel-header">
                                {{ trans('messages.profile.about_me') }}
                            </div>
                            <div class="panel-body">
                                <dl>
                                    @if($result->school)
                                        <dt>{{ trans('messages.profile.school') }}</dt>
                                        <dd>{{ $result->school }}</dd>
                                    @endif
                                    @if($result->work)
                                        <dt>{{ trans('messages.profile.work') }}</dt>
                                        <dd>{{ $result->work }}</dd>
                                    @endif
                                </dl>
                            </div>
                        </div>
                    @endif

                    @if($result->users_verification->show())
                        <div class="panel row-space-4 verifications">
                            <div class="panel-header row">
                                <div class="pull-left">
                                    {{ trans('messages.dashboard.verifications') }}
                                </div>
                            </div>
                            <div class="panel-body">
                                <ul class="list-unstyled">
                                    @if($result->users_verification->email == 'yes')
                                        <li class="row row-condensed">
                                            <div class="media">
                                                <i class="icon icon-ok icon-lima h3 pull-left"></i>
                                                <div class="media-body">
                                                    <div>
                                                        {{ trans('messages.dashboard.email_address') }}
                                                    </div>
                                                    <div class="text-muted">
                                                        {{ trans('messages.dashboard.verified') }}
                                                    </div>
                                                </div>
                                            </div>
                                        </li>
                                    @endif
                                    @if($result->users_verification->facebook == 'yes')
                                        <li class="row row-condensed">
                                            <div class="media">
                                                <i class="icon icon-ok icon-lima h3 pull-left"></i>
                                                <div class="media-body">
                                                    <div>
                                                        Facebook
                                                    </div>
                                                    <div class="text-muted">
                                                        {{ trans('messages.dashboard.validated') }}
                                                    </div>
                                                </div>
                                            </div>
                                        </li>
                                    @endif
                                    @if($result->users_verification->google == 'yes')
                                        <li class="row row-condensed">
                                            <div class="media">
                                                <i class="icon icon-ok icon-lima h3 pull-left"></i>
                                                <div class="media-body">
                                                    <div>
                                                        Google
                                                    </div>
                                                    <div class="text-muted">
                                                        {{ trans('messages.dashboard.validated') }}
                                                    </div>
                                                </div>
                                            </div>
                                        </li>
                                    @endif
                                    @if($result->users_verification->linkedin == 'yes')
                                        <li class="row row-condensed">
                                            <div class="media">
                                                <i class="icon icon-ok icon-lima h3 pull-left"></i>
                                                <div class="media-body">
                                                    <div>
                                                        LinkedIn
                                                    </div>
                                                    <div class="text-muted">
                                                        {{ trans('messages.dashboard.validated') }}
                                                    </div>
                                                </div>
                                            </div>
                                        </li>
                                    @endif
                                </ul>
                            <!-- <a href="{{ url() }}/users/edit_verification">
      {{ trans('messages.dashboard.add_verifications') }}
                                    </a> -->
                            </div>
                        </div>
                    @endif

                </div>

                <div class="col-lg-9 col-md-8 col-sm-12">
                    <div class="row row-space-4">
                        <div class="col-sm-4 show-sm">
                            <div class="media-photo media-photo-block media-round">
                                <span class="img-responsive"
                                      style="background-image:url({{ $result->profile_picture->src }}&width=255&height=255);"
                                      title="{{ $result->first_name }}"></span>
                            </div>
                        </div>
                        <div class="col-sm-8 col-md-12 col-lg-12">
                            <h1>
                                {{ trans('messages.profile.hey_iam',['first_name'=>$result->first_name]) }}!
                            </h1>
                            <div class="h5 row-space-top-2">
                                @if($result->live)
                                    <a href="{{ url('s?location='.$result->live) }}"
                                       class="link-reset">{{ $result->live }}</a>
                                    ·
                                @endif
                                <span class="text-normal">
              {{ trans('messages.profile.member_since') }} {{ $result->since }}
            </span>
                            </div>
                            <div class="flag_controls text-muted row-space-top-2 hide"></div>
                            @if(@Auth::user()->user()->id == $result->id)
                                <div class="edit_profile_container row-space-3">
                                    <a href="{{ url('users/edit') }}">{{ trans('messages.header.edit_profile') }}</a>
                                </div>
                            @endif
                        </div>
                    </div>
                    <div class="row-space-top-2">
                        <p>{{ $result->about }}</p>
                    </div>
                    <div class="row-space-6 row-space-top-6 row row-condensed">
                        @if($reviews_count > 0)
                            <div class="col-md-3 col-sm-4">
                                <a href="#reviews" rel="nofollow" class="link-reset">
                                    <div class="text-center text-wrap">
                                        <div class="badge-pill h3">
                                            <span class="badge-pill-count">{{ $reviews_count }}</span>
                                        </div>
                                        <div class="row-space-top-1">{{ trans_choice('messages.header.review',1) }}</div>
                                    </div>
                                </a>
                                <span></span>
                            </div>
                        @endif
                    </div>
                    @if($wishlists->count())
                        <div class="social_connections_and_reviews">
                            <div class="row-space-6 hide-sm">
                                <h2 class="row-space-3">
                                    {{ trans_choice('messages.header.wishlist',$wishlists->count()) }}
                                    <small>({{ $wishlists->count() }})</small>
                                </h2>
                                <div class="row">
                                    @foreach($wishlists as $row)
                                        <div class="col-lg-4 col-md-6">
                                            <div class="panel">
                                                <a href="{{ url('wishlists/'.$row->id) }}"
                                                   class="panel-image media-photo media-link media-photo-block wishlist-unit">
                                                    <div class="media-cover media-cover-dark wishlist-bg-img"
                                                         style="background-image:url('{{ url('images/'.$row->saved_wishlists[0]->rooms->photo_name) }}');">
                                                    </div>
                                                    <div class="row row-table row-full-height">
                                                        <div class="col-12 col-middle text-center text-contrast">
                                                            <div class="panel-body">
                                                                <div class="h2"><strong>{{ $row->name }}</strong></div>
                                                            </div>
                                                            <div class="btn btn-guest">{{ $row->rooms_count }} {{ trans_choice('messages.wishlist.listing', $row->rooms_count) }}</div>
                                                        </div>
                                                    </div>
                                                </a>
                                            </div>
                                        </div>
                                    @endforeach
                                </div>
                                <div class="row-space-top-2">
                                </div>
                            </div>
                        </div>
                    @endif

                    @if($reviews_count > 0)
                        <div class="social_connections_and_reviews">
                            <div id="reviews" class="reviews row-space-4">
                                <h2>
                                    {{ trans_choice('messages.header.review',2) }}
                                    <small>({{ $reviews_count }})</small>
                                </h2>
                                <div>
                                    @if($reviews_from_hosts->count() > 0)
                                        <div class="reviews_section as_guest row-space-top-3">
                                            <h4 class="row-space-4">
                                                {{ trans('messages.profile.reviews_from_hosts') }}
                                            </h4>
                                            <div class="reviews">
                                                @foreach($reviews_from_hosts->get() as $row_host)
                                                    <div id="review-{{ $row_host->id }}" class="row text-center-sm">
                                                        <div class="col-md-2 col-sm-12">
                                                            <div class="avatar-wrapper">
                                                                <a class="text-muted"
                                                                   href="{{ url() }}/users/show/{{ $row_host->user_from }}">
                                                                    <div class="media-photo media-round row-space-1">
                                                                        <span style="display: inline; height:68px; width:68px; background-image:url({{ $row_host->users_from->profile_picture->src }})"
                                                                              title="{{ $row_host->users_from->first_name }}"
                                                                              class="lazy"></span>
                                                                    </div>
                                                                    <div class="text-center profile-name text-wrap">
                                                                        {{ $row_host->users_from->first_name }}
                                                                    </div>
                                                                </a>
                                                                <div class="text-muted date show-sm">{{ $row_host->date_fy }}</div>
                                                            </div>
                                                        </div>
                                                        <div class="col-md-10 col-sm-12">
                                                            <div class="row-space-2">
                                                                <div class="comment-container expandable expandable-trigger-more row-space-2 expanded">
                                                                    <div class="expandable-content">
                                                                        <p>{{ $row_host->comments }}</p>
                                                                        <div class="expandable-indicator"></div>
                                                                    </div>
                                                                    <a href="{{ url() }}/users/show/49483864#"
                                                                       class="expandable-trigger-more text-muted">
                                                                        <strong>+ {{ trans('messages.profile.more') }}</strong>
                                                                    </a>
                                                                </div>
                                                                <div class="text-muted date hide-sm pull-left">
                                                                    @if($row_host->users_from->live)
                                                                        {{ trans('messages.profile.from') }} <a
                                                                                class="link-reset"
                                                                                href="{{ url() }}/s?location={{ $row_host->users_from->live }}">{{ $row_host->users_from->live }}</a>
                                                                        ·
                                                                    @endif
                                                                    {{ $row_host->date_fy }}
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="row row-space-2 line-separation">
                                                        <div class="col-10 col-offset-2">
                                                            <hr>
                                                        </div>
                                                    </div>
                                                @endforeach
                                            </div>
                                        </div>
                                    @endif
                                    @if($reviews_from_guests->count() > 0)
                                        <div class="reviews_section as_guest row-space-top-3">
                                            <h4 class="row-space-4">
                                                {{ trans('messages.profile.reviews_from_guests') }}
                                            </h4>
                                            <div class="reviews">
                                                @foreach($reviews_from_guests->get() as $row_guest)
                                                    <div id="review-{{ $row_guest->id }}" class="row text-center-sm">
                                                        <div class="col-md-2 col-sm-12">
                                                            <div class="avatar-wrapper">
                                                                <a class="text-muted"
                                                                   href="{{ url() }}/users/show/{{ $row_guest->user_from }}">
                                                                    <div class="media-photo media-round row-space-1">
                                                                        <span style="display: inline; height:68px; width:68px; background-image:url({{ $row_guest->users_from->profile_picture->src }});"
                                                                              title="{{ $row_guest->users_from->first_name }}"
                                                                              class="lazy"></span>
                                                                    </div>
                                                                    <div class="text-center profile-name text-wrap">
                                                                        {{ $row_guest->users_from->first_name }}
                                                                    </div>
                                                                </a>
                                                                <div class="text-muted date show-sm">{{ $row_guest->date_fy }}</div>
                                                            </div>
                                                        </div>
                                                        <div class="col-md-10 col-sm-12">
                                                            <div class="row-space-2">
                                                                <div class="comment-container expandable expandable-trigger-more row-space-2 expanded">
                                                                    <div class="expandable-content">
                                                                        <p>{{ $row_guest->comments }}</p>
                                                                        <div class="expandable-indicator"></div>
                                                                    </div>
                                                                    <a href="{{ url() }}/users/show/49483864#"
                                                                       class="expandable-trigger-more text-muted">
                                                                        <strong>+ {{ trans('messages.profile.more') }}</strong>
                                                                    </a>
                                                                </div>
                                                                <div class="text-muted date hide-sm pull-left">
                                                                    @if($row_guest->users_from->live)
                                                                        {{ trans('messages.profile.from') }} <a
                                                                                class="link-reset"
                                                                                href="{{ url() }}/s?location={{ $row_guest->users_from->live }}">{{ $row_guest->users_from->live }}</a>
                                                                        ·
                                                                    @endif
                                                                    {{ $row_guest->date_fy }}
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="row row-space-2 line-separation">
                                                        <div class="col-10 col-offset-2">
                                                            <hr>
                                                        </div>
                                                    </div>
                                                @endforeach
                                            </div>
                                        </div>
                                    @endif
                                </div>
                            </div>
                        </div>
                    @endif
                </div>
            </div>
        </div>

        <div id="staged-photos"></div>

    </main>

@stop