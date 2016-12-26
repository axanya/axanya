<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\Models\Rooms;
use App\Models\Wishlists;
use App\Models\SavedWishlists;
use App\Models\User;
use Mail;
use Auth;
use App\Http\Start\Helpers;
use App\Http\Controllers\EmailController;

class WishlistController extends Controller
{
    protected $helper; // Global variable for Helpers instance

    public function __construct()
    {
        $this->helper = new Helpers;
    }

    public function wishlist_list(Request $request)
    {
        if(Auth::user()->check()) {
            $result = Wishlists::leftJoin('saved_wishlists', function($join) use($request) {
                                $join->on('saved_wishlists.wishlist_id', '=', 'wishlists.id')->where('saved_wishlists.room_id', '=', $request->id);
                            })->where('wishlists.user_id', Auth::user()->user()->id)->orderBy('wishlists.id','desc')->select(['wishlists.id as id', 'name', 'saved_wishlists.id as saved_id'])->get();

    	   return $result;
        }
        else {
            return 'redirect';
        }
    }

    public function create(Request $request)
    {
        $wishlist = new Wishlists;

        $wishlist->name    = $request->data;
        $wishlist->user_id = Auth::user()->user()->id;

        $wishlist->save();

        $result = Wishlists::leftJoin('saved_wishlists', function($join) use($request) {
                                $join->on('saved_wishlists.wishlist_id', '=', 'wishlists.id')->where('saved_wishlists.room_id', '=', $request->id);
                            })->where('wishlists.user_id', Auth::user()->user()->id)->orderBy('wishlists.id','desc')->select(['wishlists.id as id', 'name', 'saved_wishlists.id as saved_id'])->get();
        
        return json_encode($result);
    }

    public function create_new_wishlist(Request $request)
    {
        $wishlist = new Wishlists;

        $wishlist->name    = $request->name;
        $wishlist->privacy = $request->privacy;
        $wishlist->user_id = Auth::user()->user()->id;

        $wishlist->save();

        $this->helper->flash_message('success', trans('messages.wishlist.created_successfully')); // Call flash message function
        return redirect('wishlists/my');
    }

    public function edit_wishlist(Request $request)
    {
        $wishlist = Wishlists::find($request->id);

        $wishlist->name    = $request->name;
        $wishlist->privacy = $request->privacy;

        $wishlist->save();

        $this->helper->flash_message('success', trans('messages.wishlist.updated_successfully')); // Call flash message function
        return redirect('wishlists/'.$request->id);
    }

    public function delete_wishlist(Request $request)
    {
        $delete = Wishlists::whereId($request->id)->whereUserId(Auth::user()->user()->id);

        if($delete->count()) {
            SavedWishlists::whereWishlistId($request->id)->delete();
            $delete->delete();
            $this->helper->flash_message('success', trans('messages.wishlist.deleted_successfully')); // Call flash message function
            return redirect('wishlists/my');
        }
        else 
            abort('404');
    }

    public function add_note_wishlist(Request $request)
    {
    	SavedWishlists::whereWishlistId($request->id)->whereUserId(Auth::user()->user()->id)->whereRoomId($request->room_id)->update(['note' => $request->note]);
    }

    public function save_wishlist(Request $request)
    {
        if($request->saved_id) {
            SavedWishlists::find($request->saved_id)->delete();
            return 'null';
        }
        else {
            $save_wishlist = new SavedWishlists;

            $save_wishlist->room_id     = $request->data;
            $save_wishlist->wishlist_id = $request->wishlist_id;
            $save_wishlist->user_id     = Auth::user()->user()->id;

            $save_wishlist->save();

            return $save_wishlist->id;
        }
    }

    public function remove_saved_wishlist(Request $request)
    {
        SavedWishlists::whereWishlistId($request->id)->whereRoomId($request->room_id)->delete();
        return SavedWishlists::whereWishlistId($request->id)->count();
    }

    public function my_wishlists(Request $request)
    {
        if(!@$request->id || @Auth::user()->user()->id == $request->id) {
            $data['result'] = Wishlists::with(['saved_wishlists' => function($query){
                $query->with(['rooms']);
            }, 'profile_picture'])->where('user_id', Auth::user()->user()->id)->orderBy('id', 'desc')->get();
            $data['owner'] = 1;
        }
        else {
            $data['result'] = Wishlists::with(['saved_wishlists' => function($query){
                $query->with(['rooms']);
            }, 'profile_picture'])->where('user_id', $request->id)->wherePrivacy(0)->orderBy('id', 'desc')->get();
            $data['owner'] = 0;
        }
        
        if($data['result']->count() == 0)
            abort(404);
        
        $data['count'] = $data['result']->count();

        return view('wishlists.my_wishlists', $data);
    }

    public function wishlist_details(Request $request)
    {
        $check = Wishlists::whereId($request->id)->whereUserId(@Auth::user()->user()->id)->first();

        if($check) {
            $data['result'] = Wishlists::with(['saved_wishlists' => function($query){
                $query->with(['rooms' => function($query){
                $query->with('rooms_address');
            }, 'rooms_photos', 'rooms_price' => function($query){
                $query->with('currency');
            }, 'users', 'profile_picture']);
            }])->where('id', $request->id)->get();

            $data['owner'] = 1;
        }
        else {
            $data['result'] = Wishlists::with(['saved_wishlists' => function($query){
                $query->with(['rooms' => function($query){
                $query->with('rooms_address');
            }, 'rooms_photos', 'rooms_price' => function($query){
                $query->with('currency');
            }, 'users', 'profile_picture']);
            }])->where('id', $request->id)->where('privacy',0)->get();

            if($data['result']->count() == 0)
                abort(404);

            $data['owner'] = 0;
        }
        
        $data['count'] = $data['result']->count();

        return view('wishlists.wishlist_details', $data);
    }

    public function share_email(Request $request, EmailController $email_controller)
    {
        $wishlist_id = $request->id;
        $emails      = $request->email;
        $message     = $request->message;

        $ex_email = explode(',', $emails);
        $results  = User::select('email')->get();

        foreach ($results as $row)
            $result[] = $row->email;

        $result      = explode(',', $request->email);
        $emails      = array_filter(array_map('trim', $result));
        $data['url'] = url().'/';
        $message     = Auth::user()->user()->first_name."'s Wish List Link: ".$data['url'].'wishlists/'.$wishlist_id.' <br><br>'.$message;

        foreach($emails as $email) {
            $user               = User::where('email', $email)->get();
            $data['first_name'] = (@$user[0]->first_name) ? $user[0]->first_name : $email;
            $data['content']    = $message;
            $subject            = Auth::user()->user()->first_name.' shared his Wish List';

            Mail::queue('emails.custom_email', $data, function($message) use($user, $subject, $emails, $email) {
                $message->to((@$user[0]->email) ? $user[0]->email : $email, (@$user[0]->first_name) ? $user[0]->first_name : $email)->subject($subject);
            });
        }

        $this->helper->flash_message('success', trans('messages.wishlist.shared_successfully')); // Call flash message function
        return redirect('wishlists/'.$wishlist_id);
    }

    public function popular(Request $request)
    {
        $data['result'] = Rooms::wherePopular('Yes')->whereStatus('Listed')->get();

        if(!@$request->id || @Auth::user()->user()->id == $request->id) {
            $result = Wishlists::with(['saved_wishlists' => function($query){
                $query->with(['rooms']);
            }, 'profile_picture'])->where('user_id', @Auth::user()->user()->id)->orderBy('id', 'desc')->get();
        }
        else {
            $result = Wishlists::with(['saved_wishlists' => function($query){
                $query->with(['rooms']);
            }, 'profile_picture'])->where('user_id', $request->id)->wherePrivacy(0)->orderBy('id', 'desc')->get();
        }
        
        $data['count'] = $result->count();

        return view('wishlists.popular', $data);
    }

    public function picks(Request $request)
    {
        $data['result'] = Wishlists::with(['saved_wishlists' => function($query){
                $query->with(['rooms']);
            }, 'profile_picture'])->wherePrivacy(0)->wherePick('Yes')->orderBy('id', 'desc')->get();

        if(!@$request->id || @Auth::user()->user()->id == $request->id) {
            $result = Wishlists::with(['saved_wishlists' => function($query){
                $query->with(['rooms']);
            }, 'profile_picture'])->where('user_id', @Auth::user()->user()->id)->orderBy('id', 'desc')->get();
        }
        else {
            $result = Wishlists::with(['saved_wishlists' => function($query){
                $query->with(['rooms']);
            }, 'profile_picture'])->where('user_id', $request->id)->wherePrivacy(0)->orderBy('id', 'desc')->get();
        }
        
        $data['count'] = $result->count();
        
        return view('wishlists.picks', $data);
    }
}
