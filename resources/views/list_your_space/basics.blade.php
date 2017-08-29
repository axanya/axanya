<!-- Center Part Starting  -->
<script type="text/javascript">
window.trans_lys = <?php echo json_encode( \Lang::get('messages.lys') ); ?>
</script>
<div class="manage-listing-content-container" id="js-manage-listing-content-container">
   <div class="manage-listing-content-wrapper">
      <div class="manage-listing-content" id="js-manage-listing-content">
         <div>
            <div class="list_frame">
               <div class="list_frame_label">
                  {{ trans('messages.lys.property_room_type') }}
               </div>
               <form name="overview" class="list_inner_frames">
               <div class="js-section list_inner_frame">
                  <div class="js-saving-progress saving-progress basics2" style="display: none;">
                     <h5>{{ trans('messages.lys.select') }}...</h5>
                  </div>
                  <!-- HTML for listing info subsection -->
                  <div class="row row-space-2">
                     <div class="col-4">
                        <label class="label-large">{{ trans('messages.lys.property_type') }}</label>
                        <div id="property-type-select">
                           <div class="select
                              select-large
                              select-block">
                              {!! Form::select('property_type',$property_type, $result->property_type, ['id' => 'basics-select-property_type', 'data-saving' => 'basics2']) !!}
                           </div>
                        </div>
                     </div>
                     <div class="col-4">
                        <label class="label-large">{{ trans('messages.lys.room_type') }}</label>
                        <div id="room-type-select">
                           <div class="select
                              select-large
                              select-block">
                              {!! Form::select('room_type',$room_type, $result->room_type, ['id' => 'basics-select-room_type', 'data-saving' => 'basics2']) !!}
                           </div>
                        </div>
                     </div>
                     <div class="col-4">
                        <label class="label-large">{{ trans('messages.lys.accommodates') }}</label>
                        <div id="person-capacity-select">
                           <div class="select
                              select-large
                              select-block">
                              <select name="accommodates" id="basics-select-accommodates"
                                 data-saving="basics2">
                              @for($i=1;$i<=16;$i++)
                              <option class="accommodates"
                              value="{{ $i }}" {{ ($i == $result->accommodates) ? 'selected' : '' }}>
                              {{ ($i == '16') ? $i.'+' : $i }}
                              </option>
                              @endfor
                              </select>
                           </div>
                        </div>
                     </div>
                  </div>
                  {{--///+++--}}
                  <div class="row row-space-2">
                     <div class="col-4">
                        <label class="label-large">{{ trans('messages.lys.guest_gender') }}</label>
                        <div id="guest-gender-select">
                           <div class="select
                              select-large
                              select-block">
                              {!! Form::select('guest_gender',$guest_gender, $result->guest_gender, ['id' => 'basics-select-guest_gender', 'data-saving' => 'basics2']) !!}
                           </div>
                        </div>
                     </div>
                  </div>
                  {{--///+++end--}}
               </div>
            </div>
            {{--///+++ list_frame_end--}}
            {{--
            <hr class="row-space-top-6 row-space-5">
            --}}
            <div class="js-section"ng-init="bedrooms='{{ $result->bedrooms }}';beds='{{ $result->beds }}';bathrooms='{{ $result->bathrooms }}';bed_type='{{ $result->bed_type }}';basics='{{ $rooms_status->basics }}'" style = "margin-top: 30px">
               {{--
               <h4>{{ trans('messages.lys.rooms_beds') }}</h4>
               --}}
               <div class="list_frame">
                  <div class="list_frame_label">
                     {{ trans('messages.lys.bedrooms') }}
                  </div>
                  <div class="list_inner_frame">
                     <div class="js-saving-progress saving-progress basics1" style="display: none;">
                        <h5>{{ trans('messages.lys.saving') }}...</h5>
                     </div>
                     <!-- HTML for rooms and beds subsection -->
                     <div class="row row-space-2">
                        <div class="col-4">
                           <label class="label-large">{{ trans('messages.lys.how_many_bedrooms') }}</label>
                           <div id="bedrooms-select">
                              <div class="select
                                 select-large
                                 select-block" data-behavior="tooltip" data-position="right"
                                 aria-label="Number of bedrooms can only be set if the room type is &lt;strong&gt;Entire home/apt&lt;/strong&gt;.">
                                 <select name="bedrooms" id="basics-select-bedrooms" data-saving="basics1"
                                    ng-model="bedrooms">
                                    <option disabled="" selected="" value="">{{ trans('messages.lys.select') }}...</option>
                                    <!-- <option value="0" {{ ('0' == $result->bedrooms) ? 'selected' : '' }}>{{ trans('messages.lys.studio') }}</option> -->
                                    @for($i=1;$i<=14;$i++)
                                    <option value="{{ $i }}" {{ ($i == $result->bedrooms) ? 'selected' : '' }}>
                                    {{ $i}}
                                    </option>
                                    @endfor
                                 </select>
                              </div>
                           </div>
                        </div>
                     </div>
                     <!--            ///+++working-->
                     <div class = "row row-space-2 bedroom_parent" id = "bedroom_parent">
                        <h4 class="text-center">{{ trans('messages.lys.loading') }}</h4>
                     </div>
                     <div class="row row-space-2" id="beds_show" style="display:none">
                        <div class="col-4">
                           <label class="label-large">{{ trans('messages.lys.bed_type') }}</label>
                           <div id="bedtype-select">
                              <div class="select
                                 select-large
                                 select-block">
                                 <select id="basics-select-bed_type" name="bed_type" data-saving="basics1"
                                    ng-model="bed_type">
                                    <option value="" selected="" disabled="">{{ trans('messages.lys.select') }}...</option>
                                    @foreach($bed_type as $row_bed_type)
                                    <option value="{{ $row_bed_type->id }}" {{ ($row_bed_type->id == $result->bed_type) ? 'selected' : '' }}>{{ $row_bed_type->name }}</option>
                                    @endforeach
                                 </select>
                              </div>
                           </div>
                        </div>
                     </div>
                  </div>
               </div>
               <div class="row-space-top-6 row-space-5"></div>
               <div class="list_frame">
                  <div class="list_frame_label">
                     {{ trans('messages.lys.bathrooms') }}
                  </div>
                  <div class="list_inner_frame">
                     <div class="js-saving-progress saving-progress basics3" style="display: none;">
                        <h5>{{ trans('messages.lys.select') }}...</h5>
                     </div>
                     <!-- HTML for rooms and beds subsection -->
                     <div class="row row-space-2">
                        <div class="col-4">
                           <label class="label-large">{{ trans('messages.lys.how_many_bathrooms') }}</label>
                           <div id="bathrooms-select">
                              <div class="select
                                 select-large
                                 select-block" data-behavior="tooltip" data-position="right"
                                 aria-label="Number of bathrooms can only be set if the room type is &lt;strong&gt;Entire home/apt&lt;/strong&gt;.">
                                 <select name="bathrooms" id="basics-select-bathrooms" data-saving="basics3"
                                    ng-model="bathrooms">
                                    <option disabled="" selected="" value="">{{ trans('messages.lys.select') }}...</option>
                                    <!-- <option value="0" {{ ('0' == $result->bedrooms) ? 'selected' : '' }}>{{ trans('messages.lys.studio') }}</option> -->
                                    @for($i=1;$i<=14;$i++)
                                    <option value="{{ $i }}" {{ ($i == $result->bathrooms) ? 'selected' : '' }}>
                                    {{ $i}}
                                    </option>
                                    @endfor
                                 </select>
                              </div>
                           </div>
                        </div>
                     </div>
                     <!--            ///+++working-->
                     <div class = "row-space-2 bathroom_parent" id = "bathroom_parent">
                        <h4 class="text-center">{{ trans('messages.lys.loading') }}</h4>

                     </div>
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
                        <div  class="animated text-lead text-muted steps-remaining js-steps-remaining show" style="opacity: 1;">
                           <div class="col-3 row-space-top-1 next_step">
                              <a class="back-section-button" href="{{ url('manage-listing/'.$room_id.'/description') }}" data-prevent-default="">{{ trans('messages.lys.back') }}</a>
                           </div>
                           @if($result->status == NULL)
                           <button data-href="complete" class="animated btn btn-large btn-host btn-primary {{ ($result->steps_count == 0) ? '' : 'hide'}}" id="js-list-space-button" data-track="list_space_button_left_nav" style="">
                           {{ trans('messages.lys.list_your_space') }}
                           </button>
                           <div class="col-3 text-right next_step">
                              <a data-prevent-default="" href="{{ url('manage-listing/'.$room_id.'/photos') }}" class="btn btn-large btn-primary next-section-button {{ ($result->steps_count != 0) ? '' : 'hide'}}">
                              {{ trans('messages.lys.next') }}
                              </a>
                           </div>
                           @endif
                           @if($result->status != NULL)
                           <div class="col-3 text-right next_step">
                              <a data-prevent-default="" href="{{ url('manage-listing/'.$room_id.'/photos') }}" class="btn btn-large btn-primary next-section-button">
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
      <div class="manage-listing-help hide" id="js-manage-listing-help"></div>
   </div>
   <div class="manage-listing-content-background"></div>
</div>
<!-- Center Part Ending -->
<link rel="stylesheet" href="//code.jquery.com/ui/1.12.1/themes/base/jquery-ui.css">
<script src="https://code.jquery.com/jquery-1.12.4.min.js"></script>
<script src="https://code.jquery.com/ui/1.12.1/jquery-ui.js"></script>

<div id="bedroom-flow-view"></div>
<div id="dialog" title="Basic dialog" style="display:none" class = "ui-widget">
   <p>This is the default dialog which is useful for displaying information. The dialog window can be moved, resized and closed with the 'x' icon.</p>
</div>
<script>
   get_bedroom_details();
   function get_bedroom_details()
   {
       var url = window.location.href.replace('manage-listing','get_bedroom_details');
       $.getJSON(url, function(response, status){
           if(response.basics == 1){

               var track = 'basics';//saving_class.substring(0, saving_class.length - 1);
               $('[data-track="'+track+'"] a div div .transition').removeClass('visible');
               $('[data-track="'+track+'"] a div div .transition').addClass('hide');
               $('[data-track="'+track+'"] a div div .pull-right .nav-icon').removeClass('hide');

           }
           $("#bedroom_parent").empty();
           var total_bedrooms = response.total_bedrooms;
           for( var i = 1 ; i <= total_bedrooms.length ; i++ ) {
                   var bed_option = '';
                   if(total_bedrooms[i-1].bed_options != null){
                       bed_option = total_bedrooms[i-1].bed_options;
                   }
               var html = '<div class = "row bedroom_child" id = "bedroom_child">'
                   + '<div class="col-md-3 mobile-block">'
                       + '<label class="label-large" id = "bedroom_child_label">{{ trans("messages.lys.bedroom") }} ' + i + '</label>'
                   + '</div>';
                   if(bed_option != ''){
                       html +=  '<div class="col-md-5 label-large rtl-right mobile-block">'
                               + '<span class ="">' + bed_option + '</span>'
                               + '</div>'
                               +'<div class="col-md-4 label-large mobile-block">'
                               + '<a id = "bedroom_child_add_beds" class ="a_text bedroom_child_add_beds">{{ trans("messages.lys.modify") }}<span style="display:none" class="data_index" data_index=' + i + ' data_id=' + total_bedrooms[i-1].bedroom_id + ' ></span>\n\
                                   <span style="display:none" class="bedroom_type" bedroom_type=' + "Bedroom" + '></span></a>'
                               + '</div>';
                   }else{
                       html +=  '<div class="col-md-4 label-large mobile-block">'
                               + '<a id = "bedroom_child_add_beds" class ="a_text bedroom_child_add_beds">{{ trans("messages.lys.add_beds") }}<span style="display:none" class="data_index" data_index=' + i + ' data_id=' + total_bedrooms[i-1].bedroom_id + ' ></span>\n\
                                   <span style="display:none" class="bedroom_type" bedroom_type=' + "Bedroom" + '></span></a>'
                               + '</div>';
                   }


               html += '</div>';

               $('#bedroom_parent').append(html);
           }

           var total_bathrooms = response.total_bathrooms;
           $('#bathroom_parent').html('');
           if(total_bathrooms.length > 0){
               var html1 = '<div class="row">'
                              +'<div class="col-4"></div>'
                               +'<div class="col-4"><label class="label-large">{{ trans("messages.account.type") }}</label></div>'
                               +'<div class="col-4"><label class="label-large">{{ trans("messages.lys.bathroom_details") }}</label></div>'
                           +'</div>';
               $('#bathroom_parent').append(html1);
           }
           for( var i = 1 ; i <= total_bathrooms.length ; i++ ) {


           var html = '<div class="row row-space-top-2 bathroom_child" id="bathroom_child">'
                   +'<div class="col-4"><label class="label-large" id="bathroom_child_label">{{ trans("messages.lys.bathroom") }} '+i+'</label></div>'
                   +'<div class="col-md-4 col-sm-12"><div class="select select-block">'
                   +'<select name="bathrooms_details" id="basics-select-bathrooms-details-'+i+'" data-id="'+total_bathrooms[i-1].id+'" data-saving="basics3"><option disabled="" selected="" value="">{{ trans("messages.lys.select") }}...</option><option value="private">{{ trans("messages.lys.bathroom_private") }}</option><option value="shared">{{ trans("messages.lys.bathroom_shared") }}</option></select>'
                   +'</div></div>'
                   +'<div class="col-md-4 col-sm-12"><div class="select select-block">'
                   +'<select name="bathrooms_type" id="basics-select-bathrooms-type-'+i+'" data-id="'+total_bathrooms[i-1].id+'"  data-saving="basics3"><option disabled="" selected="" value="">{{ trans("messages.lys.select") }}...</option><option value="toilet_shower">{{ trans("messages.lys.bathroom_toilet_shower") }}</option><option value="toilet_only">{{ trans("messages.lys.bathroom_toilet_only") }}</option><option value="shower_only">{{ trans("messages.lys.bathroom_shower_only") }}</option></select>'
                   +'</div></div>'
                   +'</div>';


               $('#bathroom_parent').append(html);
               console.log(total_bathrooms[i-1].bathroom_details);
               $('#basics-select-bathrooms-details-'+i).val(total_bathrooms[i-1].bathroom_details);
               $('#basics-select-bathrooms-type-'+i).val(total_bathrooms[i-1].type);
           }

       });
   }

       $( "#basics-select-bedrooms" ).change(function() {
         //alert( "Handler for .change() called." );
   var bedrooms_num = $('#basics-select-bedrooms').val();
           //alert('bedrooms=' + bedrooms_num);
           if (bedrooms_num < 1) {
               //$('#bedroom_parent').hide();
               //$('#bedroom_child').hide();
               $("#bedroom_parent").empty();
           } else {
               $("#bedroom_parent").empty();

               for( var i = 1 ; i <= bedrooms_num ; i++ ) {

                   var html = '<div class = "row bedroom_child" id = "bedroom_child">'
                       + '<div class="col-3">'
                           + '<label class="label-large" id = "bedroom_child_label">{{ trans("messages.lys.bedroom") }} ' + i + '</label>'
                       + '</div>'
                       + '<div class="col-2 label-large">'
                           + '<a id = "bedroom_child_add_beds" class ="a_text bedroom_child_add_beds">Add beds<span style="display:none" class="data_index" data_index=' + i + '>\n\
   <span style="display:none" class="bedroom_type" bedroom_type=' + "Bedroom" + '></a>'
                       + '</div>'
                       + '<div class="col-5">'
                           + '<label class="label-large bedroom_child_content_label" id="bedroom_child_content_label" style = ""></label>'
                       + '</div>'
                       + '<div class="col-2 label-large">'
                           + '<a id = "bedroom_child_modify" class = "a_text bedroom_child_modify" style = "display:none;">Modify</a>'
                       + '</div>'
                   + '</div>';

                  // $('#bedroom_parent').append(html);
               }
           }
       });

       function show_popup(i, is_add_beds){
           if (is_add_beds){
               alert(i + "Add_beds");
               $( "#dialog" ).dialog();
           }
           else
               alert(i + "Modify");
       }



</script>
