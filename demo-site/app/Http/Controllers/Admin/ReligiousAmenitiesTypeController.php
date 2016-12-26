<?php

/**
 * Religious Amenities Type Controller
 *
 * @package     Makent
 * @subpackage  Controller
 * @category    Religious Amenities Type
 * @author      Trioangle Product Team
 * @version     0.9
 * @link        http://trioangle.com
 */

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\DataTables\ReligiousAmenitiesTypeDataTable;
use App\Models\ReligiousAmenitiesType;
use App\Models\ReligiousAmenities;
use App\Http\Start\Helpers;
use Validator;

class ReligiousAmenitiesTypeController extends Controller
{
    protected $helper;  // Global variable for instance of Helpers

    public function __construct()
    {
        $this->helper = new Helpers;
    }

    /**
     * Load Datatable for Religious Amenities Type
     *
     * @param array $dataTable  Instance of ReligiousAmenitiesTypeDataTable
     * @return datatable
     */
    public function index(ReligiousAmenitiesTypeDataTable $dataTable)
    {
        return $dataTable->render('admin.religious_amenities_type.view');
    }

    /**
     * Add a New Religious Amenities Type
     *
     * @param array $request  Input values
     * @return redirect     to Amenities view
     */
    public function add(Request $request)
    {
        if(!$_POST)
        {
            return view('admin.religious_amenities_type.add');
        }
        else if($request->submit)
        {
            // Add Religious Amenities Type Validation Rules
            $rules = array(
                    'name'    => 'required|unique:religious_amenities_type',
                    'status'  => 'required'
                    );

            // Add Religious Amenities Type Validation Custom Names
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
                $religious_amenities_type = new ReligiousAmenitiesType;

			    $religious_amenities_type->name        = $request->name;
			    $religious_amenities_type->description = $request->description;
			    $religious_amenities_type->status      = $request->status;

                $religious_amenities_type->save();

                $this->helper->flash_message('success', 'Added Successfully'); // Call flash message function

                return redirect('admin/religious_amenities_type');
            }
        }
        else
        {
            return redirect('admin/religious_amenities_type');
        }
    }

    /**
     * Update Religious Amenities Type Details
     *
     * @param array $request    Input values
     * @return redirect     to Amenities View
     */
    public function update(Request $request)
    {
        if(!$_POST)
        {
			$data['result'] = ReligiousAmenitiesType::find($request->id);

            return view('admin.religious_amenities_type.edit', $data);
        }
        else if($request->submit)
        {
            // Edit Religious Amenities Type Validation Rules
            $rules = array(
                    'name'    => 'required|unique:religious_amenities_type,name,'.$request->id,
                    'status'  => 'required'
                    );

            // Edit Religious Amenities Type Validation Custom Fields Name
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
                $religious_amenities_type = ReligiousAmenitiesType::find($request->id);

			    $religious_amenities_type->name        = $request->name;
			    $religious_amenities_type->description = $request->description;
			    $religious_amenities_type->status      = $request->status;

                $religious_amenities_type->save();

                $this->helper->flash_message('success', 'Updated Successfully'); // Call flash message function

                return redirect('admin/religious_amenities_type');
            }
        }
        else
        {
            return redirect('admin/religious_amenities_type');
        }
    }

    /**
     * Delete Religious Amenities Type
     *
     * @param array $request    Input values
     * @return redirect     to Amenities View
     */
    public function delete(Request $request)
    {
        $count = ReligiousAmenities::where('type_id', $request->id)->count();

        if($count > 0)
            $this->helper->flash_message('error', 'Religious Amenities have this type. So, Delete that Religious Amenities or Change that Religious Amenities.'); // Call flash message function
        else {
            ReligiousAmenitiesType::find($request->id)->delete();
            $this->helper->flash_message('success', 'Deleted Successfully'); // Call flash message function
        }
        return redirect('admin/religious_amenities_type');
    }
}
