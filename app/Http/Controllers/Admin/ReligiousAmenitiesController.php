<?php

/**
 * ReligiousAmenities Controller
 *
 * @package     Makent
 * @subpackage  Controller
 * @category    ReligiousAmenities
 * @author      Trioangle Product Team
 * @version     0.9
 * @link        http://trioangle.com
 */

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\DataTables\ReligiousAmenitiesDataTable;
use App\Models\ReligiousAmenities;
use App\Models\ReligiousAmenitiesType;
use App\Http\Start\Helpers;
use Validator;

class ReligiousAmenitiesController extends Controller
{
    protected $helper;  // Global variable for instance of Helpers

    public function __construct()
    {
        $this->helper = new Helpers;
    }

    /**
     * Load Datatable for ReligiousAmenities
     *
     * @param array $dataTable  Instance of ReligiousAmenitiesDataTable
     * @return datatable
     */
    public function index(ReligiousAmenitiesDataTable $dataTable)
    {
        return $dataTable->render('admin.religious_amenities.view');
    }

    /**
     * Add a New ReligiousAmenities
     *
     * @param array $request  Input values
     * @return redirect     to Admin view
     */
    public function add(Request $request)
    {
        if(!$_POST)
        {
        	$data['types'] = ReligiousAmenitiesType::active_all();

            return view('admin.religious_amenities.add', $data);
        }
        else if($request->submit)
        {
            // Add ReligiousAmenities Validation Rules
            $rules = array(
                    'type_id' => 'required',
                    'name'    => 'required|unique:amenities',
                    'status'  => 'required'
                    );

            // Add ReligiousAmenities Validation Custom Names
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
                $amenities = new ReligiousAmenities;

			    $amenities->type_id     = $request->type_id;
			    $amenities->name        = $request->name;
			    $amenities->description = $request->description;
			    // $amenities->icon        = $request->icon;
			    $amenities->status      = $request->status;

                $amenities->save();

                $this->helper->flash_message('success', 'Added Successfully'); // Call flash message function

                return redirect('admin/religious_amenities');
            }
        }
        else
        {
            return redirect('admin/religious_amenities');
        }
    }

    /**
     * Update ReligiousAmenities
     *
     * @param array $request    Input values
     * @return redirect     to Admin View
     */
    public function update(Request $request)
    {
        if(!$_POST)
        {
			$data['types']  = ReligiousAmenitiesType::active_all();
			$data['result'] = ReligiousAmenities::find($request->id);

            return view('admin.religious_amenities.edit', $data);
        }
        else if($request->submit)
        {
            // Edit ReligiousAmenities Validation Rules
            $rules = array(
                    'type_id' => 'required',
                    'name'    => 'required|unique:amenities,name,'.$request->id,
                    'status'  => 'required'
                    );

            // Edit ReligiousAmenities Validation Custom Fields Name
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
                $amenities = ReligiousAmenities::find($request->id);

			    $amenities->type_id     = $request->type_id;
			    $amenities->name        = $request->name;
			    $amenities->description = $request->description;
			    // $amenities->icon        = $request->icon;
			    $amenities->status      = $request->status;

                $amenities->save();

                $this->helper->flash_message('success', 'Updated Successfully'); // Call flash message function

                return redirect('admin/religious_amenities');
            }
        }
        else
        {
            return redirect('admin/religious_amenities');
        }
    }

    /**
     * Delete ReligiousAmenities
     *
     * @param array $request    Input values
     * @return redirect     to Admin View
     */
    public function delete(Request $request)
    {
        ReligiousAmenities::find($request->id)->delete();

        $this->helper->flash_message('success', 'Deleted Successfully'); // Call flash message function

        return redirect('admin/religious_amenities');
    }
}
