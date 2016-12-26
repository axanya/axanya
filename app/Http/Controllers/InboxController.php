<?php

/**
 * Inbox Controller
 *
 * @package     Makent
 * @subpackage  Controller
 * @category    Inbox
 * @author      Trioangle Product Team
 * @version     0.9
 * @link        http://trioangle.com
 */

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Auth;
use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\Http\Controllers\EmailController;
use App\Models\Messages;
use App\Models\Reservation;
use App\Models\Rooms;
use App\Models\SpecialOffer;
use App\Models\Calendar;
use App\Models\Currency;
use App\Http\Start\Helpers;
use DB;

class InboxController extends Controller
{
    protected $helper; // Global variable for Helpers instance

    /**
     * Constructor to Set Helpers instance in Global variable
     *
     * @param array $helper   Instance of Helpers
     */
    public function __construct()
    {
        $this->helper = new Helpers;
        DB::enableQueryLog();
    }

    /**
     * Load Inbox View
     *
     * @return Inbox page view file
     */
    public function index()
    {
        $data['user_id']     = Auth::user()->user()->id;
        $data['all_message'] = Messages::all_messages($data['user_id']);
        return view('users.inbox', $data);
    }
    
    /**
     * Ajax function for update Archive messages
     *
     * @param array $request  Input values
     */
    public function archive(Request $request)
    {
        $id     = $request->id;
        $msg_id = $request->msg_id;
        $type   = trim($request->type);

        if($type == "Archive")
            Messages::where('user_from', $id)->where('id', $msg_id )->update(['archive' =>'1']);
        
        if($type == "Unarchive")
            Messages::where('user_from', $id)->where('id', $msg_id )->update(['archive' =>'0']);
    }
    
    /**
     * Ajax function for update Star messages
     *
     * @param array $request  Input values
     */
    public function star(Request $request)
    {
        $id     = $request->id;
        $msg_id = $request->msg_id;
        $type   = trim($request->type);

        if($type == "Star")
            Messages::where('user_from', $id)->where('id', $msg_id )->update(['star' =>'1']);
        
        if($type == "Unstar")
            Messages::where('user_from', $id)->where('id', $msg_id )->update(['star' =>'0']);
    }

    /**
     * Ajax function for Message counts
     *
     * @param array $request  Input values
     * @return json message counts
     */
    public function message_count(Request $request)
    {
        $data['user_id'] = $user_id = Auth::user()->user()->id;
        
        $all_message     = Messages::all_messages($data['user_id']);

        $all    =   Messages::whereIn('id', function($query) use($user_id)
                        {   
                            $query->select(DB::raw('max(id)'))->from('messages')->where('user_to', $user_id)->groupby('reservation_id');
                        })->count();

        $star   =   Messages::whereIn('id', function($query) use($user_id)
                        {   
                            $query->select(DB::raw('max(id)'))->from('messages')->where('user_to', $user_id)->groupby('reservation_id');
                        })->where('star', 1)->count();

        $unread  =  Messages::whereIn('id', function($query) use($user_id)
                        {   
                            $query->select(DB::raw('max(id)'))->from('messages')->where('user_to', $user_id)->groupby('reservation_id');
                        })->where('read',0)->count();

        $reserve =  Messages::whereIn('id', function($query) use($user_id)
                        {
                            $query->select(DB::raw('max(id)'))->from('messages')->where('user_to', $user_id)->where('reservation_id','!=', 0)->groupby('reservation_id');
                        })->with(['reservation'])->whereHas('reservation' ,function($query) {
                                $query->where('status','!=','');
                        })->count();

        $pending =  Messages::whereIn('id', function($query) use($user_id)
                        {
                            $query->select(DB::raw('max(id)'))->from('messages')->where('user_to', $user_id)->groupby('reservation_id');
                        })->with(['reservation'])->whereHas('reservation' ,function($query) {
                            $query->where('status','Pending');
                        })->count();

        $archive =  Messages::whereIn('id', function($query) use($user_id)
                        {
                            $query->select(DB::raw('max(id)'))->from('messages')->where('user_to', $user_id)->groupby('reservation_id');
                        })->where('archive', 1)->count();

        if(count($all_message) != 0 )
        {
            $data['all_message_count'] = $all;
            $data['stared_count']      = $star;
            $data['unread_count']      = $unread;
            $data['reservation_count'] = $reserve;
            $data['archived_count']    = $archive;
            $data['pending_count']     = $pending;
        }
        else
        {
            $data['all_message_count'] = 0;
            $data['stared_count']      = 0;
            $data['unread_count']      = 0;
            $data['reservation_count'] = 0;
            $data['archived_count']    = 0;
            $data['pending_count']     = 0;
        }

        return json_encode($data);
    }

    /**
     * Ajax function for Message Filters
     *
     * @param array $request  Input values
     * @return json message counts
     */
    public function message_by_type(Request $request)
    {
        $user_id = Auth::user()->user()->id;
        $type    = trim($request->type);

        if($type == "starred")
        {
            $result =   Messages::whereIn('id', function($query) use($user_id)
                            {   
                                $query->select(DB::raw('max(id)'))->from('messages')->where('user_to', $user_id)->groupby('reservation_id');
                            })->with(['user_details' => function($query) {
                                $query->with('profile_picture');
                            }])->with(['reservation' => function($query) {
                                $query->with('currency');
                            }])->with('rooms_address')->where('star', 1)->orderBy('id','desc');
        }
        else if($type == "all")
        {

            $result =   Messages::whereIn('id', function($query) use($user_id)
                            {   
                                $query->select(DB::raw('max(id)'))->from('messages')->where('user_to', $user_id)->groupby('reservation_id');
                            })->with(['user_details' => function($query) {
                                $query->with('profile_picture');
                            }])->with(['reservation' => function($query) {
                                $query->with('currency');
                            }])->with('rooms_address')->orderBy('id','desc');

        }
        else if($type == "hidden")
        {
            $result =   Messages::whereIn('id', function($query) use($user_id)
                            {   
                                $query->select(DB::raw('max(id)'))->from('messages')->where('user_to', $user_id)->groupby('reservation_id');
                            })->with(['user_details' => function($query) {
                                $query->with('profile_picture');
                            }])->with(['reservation' => function($query) {
                                $query->with('currency');
                            }])->with('rooms_address')->where('archive', 1)->orderBy('id','desc');
        }
        else if($type == "unread")
        {
            $result =   Messages::whereIn('id', function($query) use($user_id)
                            {   
                                $query->select(DB::raw('max(id)'))->from('messages')->where('user_to', $user_id)->groupby('reservation_id');
                            })->with(['user_details' => function($query) {
                                $query->with('profile_picture');
                            }])->with(['reservation' => function($query) {
                                $query->with('currency');
                            }])->with('rooms_address')->where('read',0)->orderBy('id','desc');
        }
        else if($type == "reservations")
        {
            $result =   Messages::whereIn('id', function($query) use($user_id)
                            {   
                                $query->select(DB::raw('max(id)'))->from('messages')->where('user_to', $user_id)->groupby('reservation_id');
                            })->with(['user_details' => function($query) {
                                $query->with('profile_picture');
                            }])->with(['reservation' => function($query) {
                                $query->with('currency');
                            }])->whereHas('reservation' ,function($query) {
                                $query->where('status','!=','');
                            })->with('rooms_address')->where('reservation_id','!=', 0)->orderBy('id','desc');
        }
        else // Pending Requests
        {
            $result =   Messages::whereIn('id', function($query) use($user_id)
                            {
                                $query->select(DB::raw('max(id)'))->from('messages')->where('user_to', $user_id)->groupby('reservation_id');
                            })->with(['user_details' => function($query) {
                                $query->with('profile_picture');
                            },'reservation'=>function($query){
                                $query->with('currency');
                            }])->whereHas('reservation' ,function($query) {
                                $query->where('status','Pending');
                            })->with('rooms_address')->orderBy('id','desc');
        }

        $result =  $result->paginate(10)->toJson();

        return $result;
    }

    /**
     * Load Guest Conversation Page with Messages
     *
     * @param array $request  Input values
     * @return view Guest Conversation View File
     */
    public function guest_conversation(Request $request)
    {
        $read_count   = Messages::where('reservation_id',$request->id)->where('user_to',Auth::user()->user()->id)->where('read',0)->count();

        if($read_count !=0)
            Messages::where('reservation_id',$request->id)->where('user_to',Auth::user()->user()->id)->update(['read' =>'1']);

        $data['messages'] = Messages::with('user_details','reservation')->where('reservation_id',$request->id)->orderBy('created_at','desc')->get();

        if($data['messages'][0]->reservation->rooms->user_id == Auth::user()->user()->id)
            abort('404');

        $data['title'] = 'Conversation ';

        return view('users.guest_conversation', $data);
    }

    /**
     * Ajax function for Conversation reply
     *
     * @param array $request  Input values
     * @return html Reply message html
     */
    public function reply(Request $request, EmailController $email_controller)
    {
        $reservation_details = Reservation::find($request->id);

        $replacement = "[removed]";

        $email_pattern = "/[^@\s]*@[^@\s]*\.[^@\s]*/";
        $url_pattern = "/[a-zA-Z]*[:\/\/]*[A-Za-z0-9\-_]+\.+[A-Za-z0-9\.\/%&=\?\-_]+/i";
        $phone_pattern = "/\+?[0-9][0-9()\s+]{4,20}[0-9]/";

        $find = array($email_pattern, $phone_pattern);
        $replace = array($replacement, $replacement);

        $message = preg_replace($find, $replace, $request->message);
        $message = preg_replace($url_pattern, $replacement, $message);

        if($reservation_details->user_id == Auth::user()->user()->id)
        {
            $messages = new Messages;

            $messages->room_id        = $reservation_details->room_id;
            $messages->reservation_id = $reservation_details->id;
            $messages->user_to        = $reservation_details->rooms->user_id;
            $messages->user_from      = Auth::user()->user()->id;
            $messages->message        = $message;
            $messages->message_type   = 5;

            $messages->save();

            echo '<div> <div> <div class="row row-condensed row-space-6 post"> <div class="col-sm-10"> <div class="panel-quote-flush panel-quote panel panel-quote-right panel-quote-dark"> <div class="panel-body panel-dark"> <div> <span class="message-text">'.$message.'</span> </div> <div class="space-top-2 text-muted"> <span class="time">'.$messages->created_time.'</span> <span class="exact-time hide">Sat, Nov 21 2015, 11:43AM CET</span> </div> </div> </div> </div> <div class="col-sm-2 text-right"> <div class="media-photo media-round"> <img width="70" height="70" src="'.Auth::user()->user()->profile_picture->src.'" class="user-profile-photo"> </div> </div> </div> </div> </div>';
        }
        else if($reservation_details->rooms->user_id == Auth::user()->user()->id)
        {
            if($request->template == 1)
            {
                $message_type = 6;

                $special_offer = new SpecialOffer;

                $special_offer->reservation_id   = $reservation_details->id;
                $special_offer->room_id          = $reservation_details->room_id;
                $special_offer->user_id          = $reservation_details->user_id;
                $special_offer->checkin          = $reservation_details->checkin;
                $special_offer->checkout         = $reservation_details->checkout;
                $special_offer->number_of_guests = $reservation_details->number_of_guests;
                $special_offer->price            = $reservation_details->subtotal;
                $special_offer->currency_code    = Currency::find(1)->session_code;
                $special_offer->type             = 'pre-approval';
                $special_offer->created_at       = date('Y-m-d H:i:s');

                $special_offer->save();

                $special_offer_id = $special_offer->id;

                $email_controller->preapproval($reservation_details->id, $message);
            }
            else if($request->template == 2)
            {
                $message_type = 7;

                $special_offer = new SpecialOffer;

                $special_offer->reservation_id   = $reservation_details->id;
                $special_offer->room_id          = $request->pricing_room_id;
                $special_offer->user_id          = $reservation_details->user_id;
                $special_offer->checkin          = $request->pricing_checkin;
                $special_offer->checkout         = $request->pricing_checkout;
                $special_offer->number_of_guests = $request->pricing_guests;
                $special_offer->price            = $request->pricing_price;
                $special_offer->currency_code    = Currency::find(1)->session_code;
                $special_offer->type             = 'special_offer';
                $special_offer->created_at       = date('Y-m-d H:i:s');

                $special_offer->save();

                $special_offer_id = $special_offer->id;

                $email_controller->preapproval($reservation_details->id, $message, 'special_offer');
            }
            else if($request->template == 'NOT_AVAILABLE')
            {
                $message_type = 8;

                $blocked_days = $this->get_days($reservation_details->checkin, $reservation_details->checkout);
                
                // Update Calendar
                for($j=0; $j<count($blocked_days)-1; $j++)
                {
                    $calendar_data = [
                            'room_id' => $reservation_details->room_id,
                            'date'    => $blocked_days[$j],
                            'status'  => 'Not available'
                            ];

                    Calendar::updateOrCreate(['room_id' => $reservation_details->room_id, 'date' => $blocked_days[$j]], $calendar_data);
                }
            }
            else if($request->template == 9)
                $message_type = 8;
            else
                $message_type = 5;
            
            $messages = new Messages;

            $messages->room_id          = $reservation_details->room_id;
            $messages->reservation_id   = $reservation_details->id;
            $messages->user_to          = $reservation_details->user_id;
            $messages->user_from        = Auth::user()->user()->id;
            $messages->message          = $message;
            $messages->message_type     = $message_type;
            $messages->special_offer_id = @$special_offer_id;

            $messages->save();

            $html = '<li class="thread-list-item" id="question2_post_'.$messages->id.'">';

            if($message_type == 6)
            {
                $html .= '<div class="row row-condensed"> <div class="col-md-10 col-md-offset-1"> <div class="panel row-space-4"> <div class="panel-body panel-dark"> <div class="h5">'.$messages->reservation->users->first_name.'is pre-approved to stay at <a href="'.url('rooms/'.$messages->special_offer->room_id).'">'.$messages->special_offer->rooms->name.'</a> </div> <p class="text-muted">'. $messages->special_offer->dates.' · '.$messages->special_offer->number_of_guests.' guest · '.$messages->special_offer->currency->symbol.$messages->special_offer->price.' '.$messages->special_offer->currency->session_code.'</p> </div> <div class="panel-body"> <a href="'.url().'/messaging/remove_special_offer/'.$messages->special_offer_id.'" class="btn" data-confirm="Are you sure?" data-method="post" rel="nofollow">Remove Pre-approval</a> </div> </div> </div> </div>';
            }
            else if($message_type == 7)
            {
                $html .= '<div class="row row-condensed"> <div class="col-md-10 col-md-offset-1"> <div class="panel row-space-4"> <div class="panel-body panel-dark"><span class="label label-info"> Special Offer </span> <div class="h5">'.$messages->reservation->users->first_name.'is pre-approved to stay at <a href="'.url('rooms/'.$messages->special_offer->room_id).'">'.$messages->special_offer->rooms->name.'</a> </div> <p class="text-muted">'. $messages->special_offer->dates.' · '.$messages->special_offer->number_of_guests.' guest <br><strong>You could earn '.$messages->special_offer->currency->symbol.$messages->special_offer->price.' '.$messages->special_offer->currency->session_code.'</strong> (will be confirmed once a reservation is made)</p> </div> <div class="panel-body"> <a href="'.url().'/messaging/remove_special_offer/'.$messages->special_offer_id.'" class="btn" data-confirm="Are you sure?" data-method="post" rel="nofollow">Remove Special Offer</a> </div> </div> </div> </div>';
            }

            echo $html.'<div class="row row-condensed"> <div class="col-sm-2 col-md-1 text-center"> <a href="'.url().'/users/show/'.Auth::user()->user()->id.'" class="media-photo media-round" data-behavior="tooltip" aria-label="Auth::user()->user()->first_name"><img width="36" height="36" alt="Auth::user()->user()->first_name" src="'.Auth::user()->user()->profile_picture->src.'" title="Auth::user()->user()->first_name"></a> </div> <div class="col-sm-10 "> <div class="row-space-4"> <div class="panel panel-quote panel-quote-flush "> <div class="panel-body"> <div class="message-text"> <p class="trans">'.$message.'</p> </div> </div> </div> <div class="time-container text-muted "> <small class="time" title="'.$messages->created_at.'">'. $messages->created_time .'</small> <small class="exact-time hide"> '.$messages->created_at.' </small> </div> </div> </div> </div> </li>';
        }
    }

    /**
     * Load Host Conversation Page with Messages
     *
     * @param array $request  Input values
     * @return view Host Conversation View File
     */
    public function host_conversation(Request $request, CalendarController $calendar)
    {
        $read_count   = Messages::where('reservation_id',$request->id)->where('user_to',Auth::user()->user()->id)->where('read',0)->count();

        if($read_count !=0)
            Messages::where('reservation_id',$request->id)->where('user_to',Auth::user()->user()->id)->update(['read' =>'1']);


        $data['messages'] = Messages::with('user_details','reservation')->where('reservation_id',$request->id)->orderBy('created_at','desc')->get();

        $data['rooms'] = Rooms::where('user_id',Auth::user()->user()->id)->lists('name','id');

        if($data['messages'][0]->reservation->user_id == Auth::user()->user()->id)
            abort('404');

        $data['calendar']       = $calendar->generate_small($data['messages'][0]->reservation->room_id, '', '', $data['messages'][0]->reservation_id);
        
        return view('users.host_conversation', $data);
    }

    /**
     * Load Small Calendar for Host Conversation Page
     *
     * @param array $request  Input values
     * @param array $calendar  Calendar Controller Instance
     * @return html Small Calendar
     */
    public function calendar(Request $request, CalendarController $calendar)
    {
        $data_calendar    = @json_decode($request['data']);
        $year             = @$data_calendar->year;
        $month            = @$data_calendar->month;
        $room_id          = @$data_calendar->room_id;
        $reservation_id   = @$data_calendar->reservation_id;

        if(!$room_id)
            $room_id      = Reservation::find($reservation_id)->room_id;
        
        $data['calendar'] = $calendar->generate_small($room_id, $year, $month, $reservation_id);

        return $data['calendar'];
    }

    /**
     * Remove Special Offer
     *
     * @param array $request  Input values
     * @return redirect to Conversation page
     */
    public function remove_special_offer(Request $request)
    {
        $id = $request->id;

        $special_offer  = SpecialOffer::find($id);
        
        $reservation_id = $special_offer->reservation_id;
        $type           = $special_offer->type;

        $special_offer->delete();

        $messages = Messages::where('special_offer_id',$id)->delete();

        $type_name = ($type=='pre-approval') ? 'Pre-Approval' : 'Special offer';

        $this->helper->flash_message('success', trans('messages.inbox.type_has_removed',['type'=>$type_name])); // Call flash message function
        return redirect('messaging/qt_with/'.$reservation_id);
    }

    /**
     * Get days between two dates
     *
     * @param date $sStartDate  Start Date
     * @param date $sEndDate    End Date
     * @return array $days      Between two dates
     */
    public function get_days($sStartDate, $sEndDate)
    {    
        $sStartDate   = date('Y-m-d',strtotime($sStartDate));
        $sEndDate     = date('Y-m-d',strtotime($sEndDate));
        $aDays[]      = $sStartDate;
        $sCurrentDate = $sStartDate;  
       
        while($sCurrentDate < $sEndDate)
        {
            $sCurrentDate = gmdate("Y-m-d", strtotime("+1 day", strtotime($sCurrentDate)));
            $aDays[]      = $sCurrentDate;  
        }
      
        return $aDays;  
    } 

}
