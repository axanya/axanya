<?php

/**
 * Amenities Controller
 *
 * @package     Makent
 * @subpackage  Controller
 * @category    Amenities
 * @author      Trioangle Product Team
 * @version     1.2
 * @link        http://trioangle.com
 */

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\DataTables\AmenitiesDataTable;
use App\Models\Amenities;
use App\Models\AmenitiesType;
use App\Http\Start\Helpers;
use Validator;

class AmenitiesController extends Controller
{
    protected $helper;  // Global variable for instance of Helpers

    public function __construct()
    {
        $this->helper = new Helpers;
    }

    /**
     * Load Datatable for Amenities
     *
     * @param array $dataTable  Instance of AmenitiesDataTable
     * @return datatable
     */
    public function index(AmenitiesDataTable $dataTable)
    {
        return $dataTable->render('admin.amenities.view');
    }

    /**
     * Add a New Amenities
     *
     * @param array $request  Input values
     * @return redirect     to Admin view
     */
    public function add(Request $request)
    {
        if(!$_POST)
        {
        	$data['types'] = AmenitiesType::active_all();

            return view('admin.amenities.add', $data);
        }
        else if($request->submit)
        {
            // Add Amenities Validation Rules
            $rules = array(
                    'type_id' => 'required',
                    'name'    => 'required|unique:amenities',
                    'status'  => 'required'
                    );

            // Add Amenities Validation Custom Names
            $niceNames = array(
                        'type_id' => 'Type',
                        'name'    => 'Name',
                        'status'  => 'Status'
                        );

            $validator = Validator::make($request->all(), $rules);
            $validator->setAttributeNames($niceNames); 

            if ($validator->fails()) 
            {
                return back()->withErrors($validator)->withInput(); // Form calling with Errors and Input values
            }
            else
            {
                $amenities = new Amenities;

			    $amenities->type_id     = $request->type_id;
			    $amenities->name        = $request->name;
			    $amenities->description = $request->description;
			    $amenities->icon        = $request->icon;
			    $amenities->status      = $request->status;

                $amenities->save();

                $this->helper->flash_message('success', 'Added Successfully'); // Call flash message function

                return redirect('admin/amenities');
            }
        }
        else
        {
            return redirect('admin/amenities');
        }
    }

    /**
     * Update Amenities
     *
     * @param array $request    Input values
     * @return redirect     to Admin View
     */
    public function update(Request $request)
    {
        if(!$_POST)
        {
			$data['types']  = AmenitiesType::active_all();
			$data['result'] = Amenities::find($request->id);

            return view('admin.amenities.edit', $data);
        }
        else if($request->submit)
        {
            // Edit Amenities Validation Rules
            $rules = array(
                    'type_id' => 'required',
                    'name'    => 'required|unique:amenities,name,'.$request->id,
                    'status'  => 'required'
                    );

            // Edit Amenities Validation Custom Fields Name
            $niceNames = array(
                        'type_id' => 'Type',
                        'name'    => 'Name',
                        'status'  => 'Status'
                        );

            $validator = Validator::make($request->all(), $rules);
            $validator->setAttributeNames($niceNames); 

            if ($validator->fails()) 
            {
                return back()->withErrors($validator)->withInput(); // Form calling with Errors and Input values
            }
            else
            {
                $amenities = Amenities::find($request->id);

			    $amenities->type_id     = $request->type_id;
			    $amenities->name        = $request->name;
			    $amenities->description = $request->description;
			    $amenities->icon        = $request->icon;
			    $amenities->status      = $request->status;

                $amenities->save();

                $this->helper->flash_message('success', 'Updated Successfully'); // Call flash message function

                return redirect('admin/amenities');
            }
        }
        else
        {
            return redirect('admin/amenities');
        }
    }

    /**
     * Delete Amenities
     *
     * @param array $request    Input values
     * @return redirect     to Admin View
     */
    public function delete(Request $request)
    {
        Amenities::find($request->id)->delete();

        $this->helper->flash_message('success', 'Deleted Successfully'); // Call flash message function

        return redirect('admin/amenities');
    }
}
