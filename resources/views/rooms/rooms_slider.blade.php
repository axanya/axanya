<!DOCTYPE html>
<!--[if gt IE 8]><!--> <html class="no-js"> <!--<![endif]-->
    <head>

         {!! Html::style('css/common.css') !!}
      {!! Html::style('css/slider/default.css') !!}
      {!! Html::style('css/slider/jquery.ad-gallery.css') !!}
      {!! Html::style('css/slider/main.css') !!}

    </head>

    <body style="background:rgba(0,0,0,0)">


    <div id="gallery" class="ad-gallery">
      <div class="ad-image-wrapper">
      </div>
      <div class="ad-nav">
        <div class="ad-controls">
        </div>

        <div class="ad-thumbs">
          <ul class="ad-thumb-list">
          @foreach($rooms_photos as $row_photos)
            <li>
              <a href="{{ url('images/rooms/'.$room_id.'/'.$row_photos->name) }}" class="image0">
                
              <img src="{{ url('images/rooms/'.$room_id.'/'.$row_photos->name) }}" title="{{ $row_photos->highlights }}" >
              </a>
            </li>
            @endforeach
          </ul>
        </div>
      </div>
    </div>       

    </body>
</html>
<script type="text/javascript" src="https://maps.googleapis.com/maps/api/js?key={{ $map_key }}&sensor=false&libraries=places"></script>
{!! Html::script('js/jquery-1.11.3.js') !!}

{!! Html::script('js/angular.js') !!}
 <script> 
    var app = angular.module('App', [], function($interpolateProvider) {
        /*$interpolateProvider.startSymbol('<%');
        $interpolateProvider.endSymbol('%>');*/
    });
    </script>
{!! Html::script('js/rooms.js') !!}