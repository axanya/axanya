<?php

/**
 * Bed Type Controller
 *
 * @package     Makent
 * @subpackage  Controller
 * @category    Bed Type
 * @author      Trioangle Product Team
 * @version     0.9
 * @link        http://trioangle.com
 */

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\DataTables\BedTypeDataTable;
use App\Models\BedType;
use App\Models\Rooms;
use App\Http\Start\Helpers;
use Validator;

class BedTypeController extends Controller
{
    protected $helper;  // Global variable for instance of Helpers

    public function __construct()
    {
        $this->helper = new Helpers;
    }

    /**
     * Load Datatable for Bed Type
     *
     * @param array $dataTable  Instance of BedTypeDataTable
     * @return datatable
     */
    public function index(BedTypeDataTable $dataTable)
    {
        return $dataTable->render('admin.bed_type.view');
    }

    /**
     * Add a New Bed Type
     *
     * @param array $request  Input values
     * @return redirect     to Bed Type view
     */
    public function add(Request $request)
    {
        if(!$_POST)
        {
            return view('admin.bed_type.add');
        }
        else if($request->submit)
        {
            // Add Bed Type Validation Rules
            $rules = array(
                    'name'    => 'required|unique:bed_type',
                    'status'  => 'required'
                    );

            // Add Bed Type Validation Custom Names
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
                $bed_type = new BedType;

			    $bed_type->name        = $request->name;
			    $bed_type->status      = $request->status;

                $bed_type->save();

                $this->helper->flash_message('success', 'Added Successfully'); // Call flash message function

                return redirect('admin/bed_type');
            }
        }
        else
        {
            return redirect('admin/bed_type');
        }
    }

    /**
     * Update Bed Type Details
     *
     * @param array $request    Input values
     * @return redirect     to Bed Type View
     */
    public function update(Request $request)
    {
        if(!$_POST)
        {
			$data['result'] = BedType::find($request->id);

            return view('admin.bed_type.edit', $data);
        }
        else if($request->submit)
        {
            // Edit Bed Type Validation Rules
            $rules = array(
                    'name'    => 'required|unique:bed_type,name,'.$request->id,
                    'status'  => 'required'
                    );

            // Edit Bed Type Validation Custom Fields Name
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
                $bed_type = BedType::find($request->id);

			    $bed_type->name        = $request->name;
			    $bed_type->status      = $request->status;

                $bed_type->save();

                $this->helper->flash_message('success', 'Updated Successfully'); // Call flash message function

                return redirect('admin/bed_type');
            }
        }
        else
        {
            return redirect('admin/bed_type');
        }
    }

    /**
     * Delete Bed Type
     *
     * @param array $request    Input values
     * @return redirect     to Bed Type View
     */
    public function delete(Request $request)
    {
        $count = Rooms::where('bed_type', $request->id)->count();

        if($count > 0)
            $this->helper->flash_message('error', 'Rooms have this Bed Type. So, Delete that Rooms or Change the Rooms Bed Type.'); // Call flash message function
        else {
            BedType::find($request->id)->delete();
            $this->helper->flash_message('success', 'Deleted Successfully'); // Call flash message function
        }
        return redirect('admin/bed_type');
    }
}
