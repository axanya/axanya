<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Models\PropertyType;
use App\Models\RoomType;
use App\Models\Rooms;
use App\Models\User;
use App\Models\Reviews;
use App\Models\RoomsPhotos;
use App\Models\RoomsAddress;
use App\Models\Amenities;
use App\Models\AmenitiesType;
use App\Models\Calendar;
use App\Models\Currency;
use App\Models\Country;
use App\Models\PayoutPreferences;
use App\Models\RoomsPrice;
use App\Models\RoomsDescription;
use App\Http\Controllers\Controller;
use App\Http\Helper\PaymentHelper;
use App\Http\Start\Helpers;
use Validator;
use DB;
use Auth;

class HomeController extends Controller
{

    protected $payment_helper; // Global variable for Helpers instance


    public function __construct(PaymentHelper $payment)
    {
        $this->payment_helper = $payment;
        $this->helper         = new Helpers;
    }


    public function currency_list(Request $request)
    {

        $currency_details = Currency::where('status', 'Active')->select('code', 'symbol')->get()->toArray();
        foreach ($currency_details as $currency)
        {
            $currency_list[] = ['code' => $currency['code'], 'symbol' => $currency['original_symbol']];
        }
        if ( ! empty($currency_list))
        {

            return response()->json([
                'success_message' => 'Currency Details Listed Successfully...',
                'status_code'     => '1',
                'currency_list'   => $currency_list
            ]);
        }
        else
        {
            return response()->json([
                'success_message' => 'Currency Details Not Found...',
                'status_code'     => '0'
            ]);
        }

    }
}
