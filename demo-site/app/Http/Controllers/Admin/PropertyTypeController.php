<?php

/**
 * Property Type Controller
 *
 * @package     Makent
 * @subpackage  Controller
 * @category    Property Type
 * @author      Trioangle Product Team
 * @version     1.2
 * @link        http://trioangle.com
 */

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\DataTables\PropertyTypeDataTable;
use App\Models\PropertyType;
use App\Models\Rooms;
use App\Http\Start\Helpers;
use Validator;

class PropertyTypeController extends Controller
{
    protected $helper;  // Global variable for instance of Helpers

    public function __construct()
    {
        $this->helper = new Helpers;
    }

    /**
     * Load Datatable for Property Type
     *
     * @param array $dataTable  Instance of PropertyTypeDataTable
     * @return datatable
     */
    public function index(PropertyTypeDataTable $dataTable)
    {
        return $dataTable->render('admin.property_type.view');
    }

    /**
     * Add a New Property Type
     *
     * @param array $request  Input values
     * @return redirect     to Property Type view
     */
    public function add(Request $request)
    {
        if(!$_POST)
        {
            return view('admin.property_type.add');
        }
        else if($request->submit)
        {
            // Add Property Type Validation Rules
            $rules = array(
                    'name'    => 'required|unique:property_type',
                    'status'  => 'required'
                    );

            // Add Property Type Validation Custom Names
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
                $property_type = new PropertyType;

			    $property_type->name        = $request->name;
			    $property_type->description = $request->description;
			    $property_type->status      = $request->status;

                $property_type->save();

                $this->helper->flash_message('success', 'Added Successfully'); // Call flash message function

                return redirect('admin/property_type');
            }
        }
        else
        {
            return redirect('admin/property_type');
        }
    }

    /**
     * Update Property Type Details
     *
     * @param array $request    Input values
     * @return redirect     to Property Type View
     */
    public function update(Request $request)
    {
        if(!$_POST)
        {
			$data['result'] = PropertyType::find($request->id);

            return view('admin.property_type.edit', $data);
        }
        else if($request->submit)
        {
            // Edit Property Type Validation Rules
            $rules = array(
                    'name'    => 'required|unique:property_type,name,'.$request->id,
                    'status'  => 'required'
                    );

            // Edit Property Type Validation Custom Fields Name
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
                $property_type = PropertyType::find($request->id);

			    $property_type->name        = $request->name;
			    $property_type->description = $request->description;
			    $property_type->status      = $request->status;

                $property_type->save();

                $this->helper->flash_message('success', 'Updated Successfully'); // Call flash message function

                return redirect('admin/property_type');
            }
        }
        else
        {
            return redirect('admin/property_type');
        }
    }

    /**
     * Delete Property Type
     *
     * @param array $request    Input values
     * @return redirect     to Property Type View
     */
    public function delete(Request $request)
    {
        $count = Rooms::where('property_type', $request->id)->count();

        if($count > 0)
            $this->helper->flash_message('error', 'Rooms have this Property Type. So, Delete that Rooms or Change that Rooms Property Type.'); // Call flash message function
        else {
            PropertyType::find($request->id)->delete();
            $this->helper->flash_message('success', 'Deleted Successfully'); // Call flash message function
        }
        return redirect('admin/property_type');
    }
}
