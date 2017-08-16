<div id="js-manage-listing-content-container" class="manage-listing-content-container">
   <div class="manage-listing-content-wrapper">
      <div id="js-manage-listing-content" class="manage-listing-content">
        <div class="list_frame">
               <div class="list_frame_label">
                  {{ trans('messages.lys.photos') }}
               </div>
               <div class="js-section list_inner_frame">
                  <div id="photos">
                     <div class="col-md-12" style="margin-bottom: 10px;">
                        <div id="js-first-photo-text" class="row-space-top-2 h5 invisible">
                           {{ trans('messages.lys.first_photo_appears_search_results') }}!
                        </div>
                     </div>


                     <ul id="js-photo-grid" class="row list-unstyled sortable">
                        <li ng-repeat="item in photos_list" class="col-4 row-space-4" data-id="@{{ item.id }}" data-index="0" draggable="true" style="display: list-item;" ng-mouseover="over_first_photo($index)" ng-mouseout="out_first_photo($index)">
                           <div class="panel photo-item">
                              <div class="first-photo-ribbon"><i class="icon icon-star text-center"></i></div>
                              <div class="photo-size photo-drag-target js-photo-link" id="photo-@{{ item.id }}"></div>
                              <a class="media-photo media-photo-block text-center photo-size" href="#">
                              {!! Html::image('images/rooms/@{{ item.room_id }}/@{{ item.name }}', '', ['class' => 'img-responsive-height']) !!}
                              </a>
                              <button data-photo-id="@{{ item.id }}" ng-click="delete_photo(item,item.id)" class="delete-photo-btn overlay-btn js-delete-photo-btn">
                              <i class="icon icon-trash"></i>
                              </button>
                              <div class="panel-body panel-condensed">
                                 <textarea id="text_heighlight_@{{$index}}" name="@{{ item.id }}" ng-model="item.highlights" ng-keyup="keyup_highlights(item.id, item.highlights)" rows="3" placeholder="{{ trans('messages.lys.highlights_photo') }}" class="input-large highlights" tabindex="1"></textarea>
                              </div>
                           </div>
                        </li>
                        <li>
                          <div class="js-saving-progress saving-progress basics1" style="display: none;">
                            <h5>{{ trans('messages.lys.saving') }}...</h5>
                          </div>
                        </li>


                        <li id="js-photo-grid-placeholder" class="col-4 row-space-4" draggable="true" >
                           <div id="photo-placeholder" class="panel photo-item add-photos empty-photo-frame">
                              <div class="empty-photo-image">
                                 <i class="icon icon-picture icon-light-gray icon-size-3"></i>
                              </div>
                              <p class="row-space-top-4 row-space-4 text-center text-rausch">
                                 <a data-prevent-default="" id="add-photo-text" href="#" style="text-decoration: none;" class="a_text">+{{ trans_choice('messages.lys.add_photo',1) }}</a>
                              </p>
                           </div>
                        </li>
                        <input type="file" class="hide" name="photos[]" multiple="true" id="upload_photos2" accept="image/*">
                     </ul>

                  </div>
               </div>
            </div>

            <div class="not-post-listed row row-space-top-6 progress-buttons">
                        <div class="col-12">
                           <div class="separator"></div>
                        </div>
                        <div class="row">
                           <div id="js-publish-button" style="float:right;">
                              <div class="not-post-listed text-center">
                                 <!-- <div  class="animated text-lead text-muted steps-remaining js-steps-remaining {{ ($result->steps_count != 0) ? 'show' : 'show' }}" style="opacity: 1;"><strong class="text-highlight"> <span id="steps_count">{{ 7-$result->steps_count }}</span> / 6 </strong>{{ trans('messages.lys.steps') }} completed</div> -->
                                 <div class="animated text-lead text-muted steps-remaining js-steps-remaining show" style="opacity: 1;">
                                    <div class="col-3 row-space-top-1 next_step">
                                       <a class="back-section-button" href="{{ url('manage-listing/'.$room_id.'/basics') }}" data-prevent-default="">{{ trans('messages.lys.back') }}</a>
                                    </div>
                                    @if($result->status == NULL)
                                    <button data-href="complete" class="animated btn btn-large btn-host btn-primary {{ ($result->steps_count == 0) ? '' : 'hide'}}" id="js-list-space-button" data-track="list_space_button_left_nav" style="">
                                    {{ trans('messages.lys.list_your_space') }}
                                    </button>
                                    <div class="col-3 text-right next_step">
                                       <a data-prevent-default="" href="{{ url('manage-listing/'.$room_id.'/location') }}" class="btn btn-large btn-primary next-section-button {{ ($result->steps_count != 0) ? '' : 'hide'}}">
                                       {{ trans('messages.lys.next') }}
                                       </a>
                                    </div>
                                    @endif @if($result->status != NULL)
                                    <div class="col-3 text-right next_step">
                                       <a data-prevent-default="" href="{{ url('manage-listing/'.$room_id.'/location') }}" class="btn btn-large btn-primary next-section-button">
                                       {{ trans('messages.lys.next') }}
                                       </a>
                                    </div>
                                 </div>
                                 @endif
                              </div>
                           </div>
                        </div>
                     </div>







   </div>
</div>
<div id="js-manage-listing-help" class="manage-listing-help">
   <div class="manage-listing-help-panel-wrapper">
      <div class="panel manage-listing-help-panel" style="top: 250.867px;">
         <div class="help-header-icon-container text-center va-container-h">
            <img width="50" height="50" class="col-center" src="{{ url('images/lightbulb2x.png') }}">
         </div>
         <div class="panel-body">
            <h4 class="text-center">{{ trans('messages.lys.guests_love_photos') }}</h4>
            <p class="text-center no-margin-bottom">{{ trans('messages.lys.include_well_lit_photos') }}</p>
            <p class="text-center">{{ trans('messages.lys.phone_photos_find') }}</p>
         </div>
      </div>
   </div>
</div>
</div>
<div class="manage-listing-content-background"></div>
</div>







<style type="text/css">
  .border{
    border-color: #00d1c1 !important;
    outline: 1px solid #00d1c1 !important;
  }
</style>
<script type="text/javascript">
  /* ajaxfileupload */
jQuery.extend({ handleError: function( s, xhr, status, e ) {if ( s.error ) s.error( xhr, status, e ); else if(xhr.responseText) console.log(xhr.responseText); } });
jQuery.extend({createUploadIframe:function(e,t){var r="jUploadFrame"+e;if(window.ActiveXObject){var n=document.createElement("iframe");n.id=n.name=r,"boolean"==typeof t?n.src="javascript:false":"string"==typeof t&&(n.src=t)}else{var n=document.createElement("iframe");n.id=r,n.name=r}return n.style.position="absolute",n.style.top="-1000px",n.style.left="-1000px",document.body.appendChild(n),n},createUploadForm:function(e,t){var r="jUploadForm"+e,n="jUploadFile"+e,o=jQuery('<form  action="" method="POST" name="'+r+'" id="'+r+'" enctype="multipart/form-data"></form>'),a=jQuery("#"+t),u=jQuery(a).clone();return jQuery(a).attr("id",n),jQuery(a).before(u),jQuery(a).appendTo(o),jQuery(o).css("position","absolute"),jQuery(o).css("top","-1200px"),jQuery(o).css("left","-1200px"),jQuery(o).appendTo("body"),o},ajaxFileUpload:function(e){e=jQuery.extend({},jQuery.ajaxSettings,e);var t=(new Date).getTime(),r=jQuery.createUploadForm(t,e.fileElementId),n=(jQuery.createUploadIframe(t,e.secureuri),"jUploadFrame"+t),o="jUploadForm"+t;e.global&&!jQuery.active++&&jQuery.event.trigger("ajaxStart");var a=!1,u={};e.global&&jQuery.event.trigger("ajaxSend",[u,e]);var c=function(t){var o=document.getElementById(n);try{o.contentWindow?(u.responseText=o.contentWindow.document.body?o.contentWindow.document.body.innerHTML:null,u.responseXML=o.contentWindow.document.XMLDocument?o.contentWindow.document.XMLDocument:o.contentWindow.document):o.contentDocument&&(u.responseText=o.contentDocument.document.body?o.contentDocument.document.body.innerHTML:null,u.responseXML=o.contentDocument.document.XMLDocument?o.contentDocument.document.XMLDocument:o.contentDocument.document)}catch(c){jQuery.handleError(e,u,null,c)}if(u||"timeout"==t){a=!0;var d;try{if(d="timeout"!=t?"success":"error","error"!=d){var l=jQuery.uploadHttpData(u,e.dataType);e.success&&e.success(l,d),e.global&&jQuery.event.trigger("ajaxSuccess",[u,e])}else jQuery.handleError(e,u,d)}catch(c){d="error",jQuery.handleError(e,u,d,c)}e.global&&jQuery.event.trigger("ajaxComplete",[u,e]),e.global&&!--jQuery.active&&jQuery.event.trigger("ajaxStop"),e.complete&&e.complete(u,d),jQuery(o).unbind(),setTimeout(function(){try{jQuery(o).remove(),jQuery(r).remove()}catch(t){jQuery.handleError(e,u,null,t)}},100),u=null}};e.timeout>0&&setTimeout(function(){a||c("timeout")},e.timeout);try{var r=jQuery("#"+o);jQuery(r).attr("action",e.url),jQuery(r).attr("method","POST"),jQuery(r).attr("target",n),r.encoding?r.encoding="multipart/form-data":r.enctype="multipart/form-data",jQuery(r).submit()}catch(d){jQuery.handleError(e,u,null,d)}return window.attachEvent?document.getElementById(n).attachEvent("onload",c):document.getElementById(n).addEventListener("load",c,!1),{abort:function(){}}},uploadHttpData:function(r,type){var data=!type;return data="xml"==type||data?r.responseXML:r.responseText,"script"==type&&jQuery.globalEval(data),"json"==type&&eval("data = "+data),"html"==type&&jQuery("<div>").html(data).evalScripts(),data}});
</script>