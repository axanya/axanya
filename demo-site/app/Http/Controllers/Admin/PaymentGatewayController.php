<?php

/**
 * Payment Gateway Controller
 *
 * @package     Makent
 * @subpackage  Controller
 * @category    Payment Gateway
 * @author      Trioangle Product Team
 * @version     1.2
 * @link        http://trioangle.com
 */

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\Models\PaymentGateway;
use App\Http\Start\Helpers;
use Validator;

class PaymentGatewayController extends Controller
{
    protected $helper;  // Global variable for instance of Helpers

    public function __construct()
    {
        $this->helper = new Helpers;
    }

    /**
     * Load View and Update Payment Gateway Data
     *
     * @return redirect     to payment_gateway
     */
    public function index(Request $request)
    {
        if(!$_POST)
        {
            $data['result'] = PaymentGateway::get();

            return view('admin.payment_gateway', $data);
        }
        else if($request->submit)
        {
            // Payment Gateway Validation Rules
            $rules = array(
                    'paypal_username'  => 'required',
                    'paypal_password'  => 'required',
                    'paypal_signature' => 'required'
                    );

            // Payment Gateway Validation Custom Names
            $niceNames = array(
                        'paypal_username'  => 'PayPal Username',
                        'paypal_password'  => 'PayPal Password',
                        'paypal_signature' => 'PayPal Signature'
                        );

            $validator = Validator::make($request->all(), $rules);
            $validator->setAttributeNames($niceNames); 

            if ($validator->fails()) 
            {
                return back()->withErrors($validator)->withInput(); // Form calling with Errors and Input values
            }
            else
            {
                PaymentGateway::where(['name' => 'username', 'site' => 'PayPal'])->update(['value' => $request->paypal_username]);

                PaymentGateway::where(['name' => 'password', 'site' => 'PayPal'])->update(['value' => $request->paypal_password]);

                PaymentGateway::where(['name' => 'signature', 'site' => 'PayPal'])->update(['value' => $request->paypal_signature]);

                PaymentGateway::where(['name' => 'mode', 'site' => 'PayPal'])->update(['value' => $request->paypal_mode]);

                $this->helper->flash_message('success', 'Updated Successfully'); // Call flash message function
            
                return redirect('admin/payment_gateway');
            }
        }
        else
        {
            return redirect('admin/payment_gateway');
        }
    }
}
