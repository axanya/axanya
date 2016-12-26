@extends('template')

@section('main')

    <main role="main" id="site-content" ng-controller="">

        <div class="page-container page-container-responsive">
            <div class="row row-space-top-4 row-space-8">

                <form id="place-review-form" method="post" action="">
                    <div class="h2 row-space-3">
                        {{ trans('messages.new.add_place_review') }}
                    </div>
                    <section class="row-space-4">
                        <div class="h3 row-space-1">
                            {{ trans('messages.new.places') }}
                        </div>
                        <div class="text-lead text-muted row-space-1">
                            {{ trans('messages.new.places_desc1') }}
                        </div>
                        <div class="text-lead text-muted row-space-2">
                            {!! Form::select('place_id' , $near_by_places, @$review->place_id, ['id' => 'select_place_id', 'class' => 'dropdown']) !!}
                        </div>
                        <span class="text-danger">{{ $errors->first('place_id') }}</span>
                        <div class="star-rating">
                            <input type="radio" value="5" name="place" id="review_place_5"
                                   class="star-rating-input needsclick" {{ (@$review->place == 5) ? 'checked="true"' : '' }}>
                            <label for="review_place_5" class="star-rating-star js-star-rating needsclick"><i
                                        class="icon icon-star icon-size-2 needsclick"></i>&nbsp;</label>
                            <input type="radio" value="4" name="place" id="review_place_4"
                                   class="star-rating-input needsclick" {{ (@$review->place == 4) ? 'checked="true"' : '' }}>
                            <label for="review_place_4" class="star-rating-star js-star-rating needsclick"><i
                                        class="icon icon-star icon-size-2 needsclick"></i>&nbsp;</label>
                            <input type="radio" value="3" name="place" id="review_place_3"
                                   class="star-rating-input needsclick" {{ (@$review->place == 3) ? 'checked="true"' : '' }}>
                            <label for="review_place_3" class="star-rating-star js-star-rating needsclick"><i
                                        class="icon icon-star icon-size-2 needsclick"></i>&nbsp;</label>
                            <input type="radio" value="2" name="place" id="review_place_2"
                                   class="star-rating-input needsclick" {{ (@$review->place == 2) ? 'checked="true"' : '' }}>
                            <label for="review_place_2" class="star-rating-star js-star-rating needsclick"><i
                                        class="icon icon-star icon-size-2 needsclick"></i>&nbsp;</label>
                            <input type="radio" value="1" name="place" id="review_place_1"
                                   class="star-rating-input needsclick" {{ (@$review->place == 1) ? 'checked="true"' : '' }}>
                            <label for="review_place_1" class="star-rating-star js-star-rating needsclick"><i
                                        class="icon icon-star icon-size-2 needsclick"></i>&nbsp;</label>
                        </div>
                        <div><span class="text-danger">{{ $errors->first('place') }}</span></div>

                        <label for="place_comments"
                               class="threshold-comment">{{ trans('messages.new.place_desc2') }}</label>
                        <textarea rows="2" placeholder="" name="place_comments" id="review_place_comments" cols="40"
                                  class="input-large row-space-top-2 threshold-comment">{{ @$review->place_comments }}</textarea>
                        <span class="text-danger">{{ $errors->first('place_comments') }}</span>
                    </section>
                    <input type="hidden" name="user_from" value="{{$user_id}}"/>
                    <input type="hidden" name="review_id" value="{{@$review->id}}"/>
                    <button data-track-submit="" class="btn btn-primary btn-large" type="submit">
                        {{ trans('messages.account.submit') }}
                    </button>


                </form>
            </div>

        </div>

    </main>

@stop