<?php

/**
 * Currency Controller
 *
 * @package     Makent
 * @subpackage  Controller
 * @category    Currency
 * @author      Trioangle Product Team
 * @version     0.9
 * @link        http://trioangle.com
 */

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\DataTables\CurrencyDataTable;
use App\Models\Currency;
use App\Models\RoomsPrice;
use App\Http\Start\Helpers;
use Validator;

class CurrencyController extends Controller
{
    protected $helper;  // Global variable for instance of Helpers

    public function __construct()
    {
        $this->helper = new Helpers;
    }

    /**
     * Load Datatable for Currency
     *
     * @param array $dataTable  Instance of CurrencyDataTable
     * @return datatable
     */
    public function index(CurrencyDataTable $dataTable)
    {
        return $dataTable->render('admin.currency.view');
    }

    /**
     * Add a New Currency
     *
     * @param array $request  Input values
     * @return redirect     to Currency view
     */
    public function add(Request $request)
    {
        if(!$_POST)
        {
            return view('admin.currency.add');
        }
        else if($request->submit)
        {
            $rules = array(
                    'name'   => 'required|unique:currency',
                    'code'   => 'required|unique:currency',
                    'symbol' => 'required',
                    'rate'   => 'required',
                    'status' => 'required'
                    );

            $niceNames = array(
                        'name'   => 'Name',
                        'code'   => 'Code',
                        'symbol' => 'Symbol',
                        'rate'   => 'Rate',
                        'status' => 'Status'
                        );

            $validator = Validator::make($request->all(), $rules);
            $validator->setAttributeNames($niceNames); 

            if ($validator->fails()) 
            {
                return back()->withErrors($validator)->withInput(); // Form calling with Errors and Input values
            }
            else
            {
                $currency = new Currency;

                $currency->name   = $request->name;
                $currency->code   = $request->code;
                $currency->symbol = $request->symbol;
                $currency->rate   = $request->rate;
                $currency->status = $request->status;

                $currency->save();

                $this->helper->flash_message('success', 'Added Successfully'); // Call flash message function

                return redirect('admin/currency');
            }
        }
        else
        {
            return redirect('admin/currency');
        }
    }

    /**
     * Update Currency Details
     *
     * @param array $request    Input values
     * @return redirect     to Currency View
     */
    public function update(Request $request)
    {
        if(!$_POST)
        {
			$data['result'] = Currency::find($request->id);

            return view('admin.currency.edit', $data);
        }
        else if($request->submit)
        {
            $rules = array(
                    'name'   => 'required|unique:currency,name,'.$request->id,
                    'code'   => 'required|unique:currency,code,'.$request->id,
                    'symbol' => 'required',
                    'rate'   => 'required',
                    'status' => 'required'
                    );

            $niceNames = array(
                        'name'   => 'Name',
                        'code'   => 'Code',
                        'symbol' => 'Symbol',
                        'rate'   => 'Rate',
                        'status' => 'Status'
                        );

            $validator = Validator::make($request->all(), $rules);
            $validator->setAttributeNames($niceNames); 

            if ($validator->fails()) 
            {
                return back()->withErrors($validator)->withInput(); // Form calling with Errors and Input values
            }
            else
            {
                $currency = Currency::find($request->id);

			    $currency->name   = $request->name;
                $currency->code   = $request->code;
                $currency->symbol = $request->symbol;
                $currency->rate   = $request->rate;
                $currency->status = $request->status;

                $currency->save();

                $this->helper->flash_message('success', 'Updated Successfully'); // Call flash message function

                return redirect('admin/currency');
            }
        }
        else
        {
            return redirect('admin/currency');
        }
    }

    /**
     * Delete Currency
     *
     * @param array $request    Input values
     * @return redirect     to Currency View
     */
    public function delete(Request $request)
    {
        $currency_code = Currency::find($request->id)->code;

        $count = RoomsPrice::where('currency_code', $currency_code)->count();

        if($count > 0)
            $this->helper->flash_message('error', 'Rooms have this Currency. So, Delete that Rooms or Change that Rooms Currency.'); // Call flash message function
        else {
            Currency::find($request->id)->delete();
            $this->helper->flash_message('success', 'Deleted Successfully'); // Call flash message function
        }
        return redirect('admin/currency');
    }
}
