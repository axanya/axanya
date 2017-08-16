<?php

/**
 * Room Type Controller
 *
 * @package     Makent
 * @subpackage  Controller
 * @category    Room Type
 * @author      Trioangle Product Team
 * @version     0.9
 * @link        http://trioangle.com
 */

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\DataTables\RoomTypeDataTable;
use App\Models\RoomType;
use App\Models\Rooms;
use App\Http\Start\Helpers;
use Validator;

class RoomTypeController extends Controller
{
    protected $helper;  // Global variable for instance of Helpers

    public function __construct()
    {
        $this->helper = new Helpers;
    }

    /**
     * Load Datatable for Room Type
     *
     * @param array $dataTable  Instance of RoomTypeDataTable
     * @return datatable
     */
    public function index(RoomTypeDataTable $dataTable)
    {
        return $dataTable->render('admin.room_type.view');
    }

    /**
     * Add a New Room Type
     *
     * @param array $request  Input values
     * @return redirect     to Room Type view
     */
    public function add(Request $request)
    {
        if(!$_POST)
        {
            return view('admin.room_type.add');
        }
        else if($request->submit)
        {
            // Add Room Type Validation Rules
            $rules = array(
                    'name'    => 'required|unique:room_type',
                    'name_iw' => 'required',
                    'status'  => 'required'
                    );

            // Add Room Type Validation Custom Names
            $niceNames = array(
                        'name'    => 'English Name',
                        'name_iw' => 'Hebrew Name',
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
                $room_type = new RoomType;

			    $room_type->name        = $request->name;
          $room_type->name_iw     = $request->name_iw;
			    $room_type->description = $request->description;
			    $room_type->status      = $request->status;

                $room_type->save();

                $this->helper->flash_message('success', 'Added Successfully'); // Call flash message function

                return redirect('admin/room_type');
            }
        }
        else
        {
            return redirect('admin/room_type');
        }
    }

    /**
     * Update Room Type Details
     *
     * @param array $request    Input values
     * @return redirect     to Room Type View
     */
    public function update(Request $request)
    {
        if(!$_POST)
        {
			$data['result'] = RoomType::find($request->id);

            return view('admin.room_type.edit', $data);
        }
        else if($request->submit)
        {
            // Edit Room Type Validation Rules
            $rules = array(
                    'name'    => 'required|unique:room_type,name,'.$request->id,
                    'name_iw' => 'required',
                    'status'  => 'required'
                    );

            // Edit Room Type Validation Custom Fields Name
            $niceNames = array(
                        'name'    => 'English Name',
                        'name_iw' => 'Hebrew Name',
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
                $room_type = RoomType::find($request->id);

			    $room_type->name        = $request->name;
          $room_type->name_iw     = $request->name_iw;
			    $room_type->description = $request->description;
			    $room_type->status      = $request->status;

                $room_type->save();

                $this->helper->flash_message('success', 'Updated Successfully'); // Call flash message function

                return redirect('admin/room_type');
            }
        }
        else
        {
            return redirect('admin/room_type');
        }
    }

    /**
     * Delete Room Type
     *
     * @param array $request    Input values
     * @return redirect     to Room Type View
     */
    public function delete(Request $request)
    {
        $count = Rooms::where('room_type', $request->id)->count();

        if($count > 0)
            $this->helper->flash_message('error', 'Rooms have this Room Type. So, Delete that Rooms or Change that Rooms Room Type.'); // Call flash message function
        else {
            RoomType::find($request->id)->delete();
            $this->helper->flash_message('success', 'Deleted Successfully'); // Call flash message function
        }
        return redirect('admin/room_type');
    }
}
