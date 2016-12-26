<?php

/**
 * Amenities Type Controller
 *
 * @package     Makent
 * @subpackage  Controller
 * @category    Amenities Type
 * @author      Trioangle Product Team
 * @version     1.2
 * @link        http://trioangle.com
 */

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\DataTables\AmenitiesTypeDataTable;
use App\Models\AmenitiesType;
use App\Models\Amenities;
use App\Http\Start\Helpers;
use Validator;

class AmenitiesTypeController extends Controller
{
    protected $helper;  // Global variable for instance of Helpers

    public function __construct()
    {
        $this->helper = new Helpers;
    }

    /**
     * Load Datatable for Amenities Type
     *
     * @param array $dataTable  Instance of AmenitiesTypeDataTable
     * @return datatable
     */
    public function index(AmenitiesTypeDataTable $dataTable)
    {
        return $dataTable->render('admin.amenities_type.view');
    }

    /**
     * Add a New Amenities Type
     *
     * @param array $request  Input values
     * @return redirect     to Amenities view
     */
    public function add(Request $request)
    {
        if(!$_POST)
        {
            return view('admin.amenities_type.add');
        }
        else if($request->submit)
        {
            // Add Amenities Type Validation Rules
            $rules = array(
                    'name'    => 'required|unique:amenities_type',
                    'status'  => 'required'
                    );

            // Add Amenities Type Validation Custom Names
            $niceNames = array(
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
                $amenities_type = new AmenitiesType;

			    $amenities_type->name        = $request->name;
			    $amenities_type->description = $request->description;
			    $amenities_type->status      = $request->status;

                $amenities_type->save();

                $this->helper->flash_message('success', 'Added Successfully'); // Call flash message function

                return redirect('admin/amenities_type');
            }
        }
        else
        {
            return redirect('admin/amenities_type');
        }
    }

    /**
     * Update Amenities Type Details
     *
     * @param array $request    Input values
     * @return redirect     to Amenities View
     */
    public function update(Request $request)
    {
        if(!$_POST)
        {
			$data['result'] = AmenitiesType::find($request->id);

            return view('admin.amenities_type.edit', $data);
        }
        else if($request->submit)
        {
            // Edit Amenities Type Validation Rules
            $rules = array(
                    'name'    => 'required|unique:amenities_type,name,'.$request->id,
                    'status'  => 'required'
                    );

            // Edit Amenities Type Validation Custom Fields Name
            $niceNames = array(
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
                $amenities_type = AmenitiesType::find($request->id);

			    $amenities_type->name        = $request->name;
			    $amenities_type->description = $request->description;
			    $amenities_type->status      = $request->status;

                $amenities_type->save();

                $this->helper->flash_message('success', 'Updated Successfully'); // Call flash message function

                return redirect('admin/amenities_type');
            }
        }
        else
        {
            return redirect('admin/amenities_type');
        }
    }

    /**
     * Delete Amenities Type
     *
     * @param array $request    Input values
     * @return redirect     to Amenities View
     */
    public function delete(Request $request)
    {
        $count = Amenities::where('type_id', $request->id)->count();

        if($count > 0)
            $this->helper->flash_message('error', 'Amenities have this type. So, Delete that Amenities or Change that Amenities Type.'); // Call flash message function
        else {
            AmenitiesType::find($request->id)->delete();
            $this->helper->flash_message('success', 'Deleted Successfully'); // Call flash message function
        }
        return redirect('admin/amenities_type');
    }
}
